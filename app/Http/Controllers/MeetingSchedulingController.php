<?php

namespace App\Http\Controllers;

use App\Models\MeetingSchedulingSubscription;
use App\Models\CompanyProfile;
use App\Models\EventType;
use App\Models\SchedulingPage;
use App\Models\MeetingBooking;
use App\Services\Meeting\GoogleMeetService;
use App\Services\Meeting\MeetingNotificationService;
use App\Services\Payment\PaymentWithCurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class MeetingSchedulingController extends Controller
{
    protected GoogleMeetService $googleMeetService;
    protected MeetingNotificationService $notificationService;
    protected PaymentWithCurrencyService $paymentService;

    public function __construct(
        GoogleMeetService $googleMeetService,
        MeetingNotificationService $notificationService,
        PaymentWithCurrencyService $paymentService
    ) {
        $this->googleMeetService = $googleMeetService;
        $this->notificationService = $notificationService;
        $this->paymentService = $paymentService;
    }

    /**
     * Show the meeting scheduling dashboard
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return view('meeting-scheduling.subscribe');
        }

        // Get statistics
        $totalBookings = $user->meetingBookings()->count();
        $upcomingBookings = $user->meetingBookings()->upcoming()->count();
        $todayBookings = $user->meetingBookings()->today()->count();
        $completedBookings = $user->meetingBookings()->where('status', 'completed')->count();

        // Get recent bookings
        $recentBookings = $user->meetingBookings()
            ->with(['eventType', 'schedulingPage'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming meetings
        $upcomingMeetings = $user->meetingBookings()
            ->with(['eventType'])
            ->upcoming()
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        return view('meeting-scheduling.dashboard', compact(
            'totalBookings',
            'upcomingBookings', 
            'todayBookings',
            'completedBookings',
            'recentBookings',
            'upcomingMeetings'
        ));
    }

    /**
     * Show subscription page
     */
    public function subscribe(): View
    {
        $user = Auth::user();
        
        if ($user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.dashboard');
        }

        return view('meeting-scheduling.subscribe');
    }

    /**
     * Process subscription payment
     */
    public function processSubscription(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => 'required|in:card,bank,ussd',
        ]);

        $user = Auth::user();
        $amount = 500.00; // GHS 500 subscription fee
        $currency = $user->currency;

        try {
            // Create metadata for the transaction
            $metadata = [
                'user_id' => $user->id,
                'product_type' => 'meeting_scheduling_subscription',
                'timestamp' => now()->timestamp,
            ];

            // Initialize payment
            $paymentResponse = $this->paymentService->initializePayment(
                $amount,
                $user->email,
                $metadata,
                route('meeting-scheduling.payment.verify'),
                'Meeting Scheduling Service Subscription'
            );

            if (!$paymentResponse['success']) {
                return back()->withErrors(['error' => $paymentResponse['message']]);
            }

            // Store payment reference in session
            session(['meeting_payment_reference' => $paymentResponse['reference']]);

            // Redirect to payment page
            return redirect($paymentResponse['authorization_url']);

        } catch (\Exception $e) {
            Log::error('Meeting scheduling subscription payment failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Payment initialization failed. Please try again.']);
        }
    }

    /**
     * Verify subscription payment
     */
    public function verifyPayment(Request $request): RedirectResponse
    {
        $reference = $request->get('reference') ?? session('meeting_payment_reference');
        
        if (!$reference) {
            return redirect()->route('meeting-scheduling.subscribe')
                ->withErrors(['error' => 'Payment reference not found']);
        }

        try {
            $verification = $this->paymentService->verifyPayment($reference);
            
            if (!$verification['success']) {
                return redirect()->route('meeting-scheduling.subscribe')
                    ->withErrors(['error' => $verification['message']]);
            }

            $transaction = $verification['data'];
            $user = Auth::user();

            // Create subscription record
            DB::transaction(function () use ($user, $transaction) {
                MeetingSchedulingSubscription::create([
                    'user_id' => $user->id,
                    'status' => 'active',
                    'amount_paid' => 500.00,
                    'payment_reference' => $transaction['reference'],
                    'payment_details' => $transaction,
                    'subscribed_at' => now(),
                ]);
            });

            return redirect()->route('meeting-scheduling.dashboard')
                ->with('success', 'Meeting Scheduling Service activated successfully!');

        } catch (\Exception $e) {
            Log::error('Meeting scheduling payment verification failed', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('meeting-scheduling.subscribe')
                ->withErrors(['error' => 'Payment verification failed']);
        }
    }

    /**
     * Show company profile setup
     */
    public function setupProfile(): View
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.subscribe');
        }

        $profile = $user->companyProfile;
        
        return view('meeting-scheduling.setup-profile', compact('profile'));
    }

    /**
     * Store or update company profile
     */
    public function storeProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.subscribe');
        }

        $request->validate([
            'brand_name' => 'required|string|max:50|alpha_dash|unique:company_profiles,brand_name,' . ($user->companyProfile?->id ?? 'NULL'),
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
            'website' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:500',
            'timezone' => 'required|string',
            'business_hours' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request, $user) {
                $data = $request->only([
                    'brand_name', 'company_name', 'description', 'website',
                    'phone', 'email', 'address', 'timezone', 'business_hours'
                ]);

                // Handle logo upload
                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('company-logos', 'public');
                    $data['logo_path'] = $logoPath;
                }

                if ($user->companyProfile) {
                    $user->companyProfile->update($data);
                } else {
                    $user->companyProfile()->create($data);
                }
            });

            return redirect()->route('meeting-scheduling.event-types.index')
                ->with('success', 'Company profile saved successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to save company profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Failed to save profile. Please try again.']);
        }
    }

    /**
     * Show event types list
     */
    public function eventTypes(): View
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.subscribe');
        }

        $eventTypes = $user->eventTypes()->orderBy('created_at', 'desc')->get();
        
        return view('meeting-scheduling.event-types.index', compact('eventTypes'));
    }

    /**
     * Show create event type form
     */
    public function createEventType(): View
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.subscribe');
        }

        return view('meeting-scheduling.event-types.create');
    }

    /**
     * Store new event type
     */
    public function storeEventType(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.subscribe');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'buffer_before_minutes' => 'nullable|integer|min:0|max:60',
            'buffer_after_minutes' => 'nullable|integer|min:0|max:60',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'price' => 'nullable|numeric|min:0',
            'max_bookings_per_day' => 'nullable|integer|min:1',
            'max_bookings_per_week' => 'nullable|integer|min:1',
            'requires_confirmation' => 'boolean',
            'meeting_location' => 'required|in:google_meet,phone,in_person,custom',
            'location_details' => 'nullable|string|max:500',
            'custom_questions' => 'nullable|json',
        ]);

        try {
            $eventType = $user->eventTypes()->create($request->all());

            return redirect()->route('meeting-scheduling.event-types.index')
                ->with('success', 'Event type created successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to create event type', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Failed to create event type. Please try again.']);
        }
    }

    /**
     * Show bookings list
     */
    public function bookings(Request $request): View
    {
        $user = Auth::user();
        
        if (!$user->hasMeetingSchedulingAccess()) {
            return redirect()->route('meeting-scheduling.subscribe');
        }

        $query = $user->meetingBookings()->with(['eventType', 'schedulingPage']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type_id', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        $eventTypes = $user->eventTypes()->active()->get();

        return view('meeting-scheduling.bookings.index', compact('bookings', 'eventTypes'));
    }

    /**
     * Show single booking details
     */
    public function showBooking(MeetingBooking $booking): View
    {
        $user = Auth::user();
        
        if ($booking->user_id !== $user->id) {
            abort(404);
        }

        $booking->load(['eventType', 'schedulingPage', 'notifications']);

        return view('meeting-scheduling.bookings.show', compact('booking'));
    }

    /**
     * Google Calendar integration
     */
    public function connectGoogle(): RedirectResponse
    {
        $user = Auth::user();
        $authUrl = $this->googleMeetService->getAuthUrl($user);
        
        return redirect($authUrl);
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback(Request $request): RedirectResponse
    {
        $code = $request->get('code');
        $state = json_decode($request->get('state'), true);
        
        if (!$code || !isset($state['user_id'])) {
            return redirect()->route('meeting-scheduling.dashboard')
                ->withErrors(['error' => 'Google authorization failed']);
        }

        $user = Auth::user();
        
        if ($this->googleMeetService->handleCallback($code, $user)) {
            return redirect()->route('meeting-scheduling.dashboard')
                ->with('success', 'Google Calendar connected successfully!');
        } else {
            return redirect()->route('meeting-scheduling.dashboard')
                ->withErrors(['error' => 'Failed to connect Google Calendar']);
        }
    }
}