<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\SchedulingPage;
use App\Models\EventType;
use App\Models\MeetingBooking;
use App\Services\Meeting\GoogleMeetService;
use App\Services\Meeting\MeetingNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PublicBookingController extends Controller
{
    protected GoogleMeetService $googleMeetService;
    protected MeetingNotificationService $notificationService;

    public function __construct(
        GoogleMeetService $googleMeetService,
        MeetingNotificationService $notificationService
    ) {
        $this->googleMeetService = $googleMeetService;
        $this->notificationService = $notificationService;
    }

    /**
     * Show company profile page
     */
    public function showCompany(string $brandName): View
    {
        $profile = CompanyProfile::active()
            ->where('brand_name', $brandName)
            ->with(['schedulingPages' => function($query) {
                $query->active();
            }])
            ->firstOrFail();

        return view('public.company-profile', compact('profile'));
    }

    /**
     * Show scheduling page
     */
    public function showSchedulingPage(string $brandName, string $slug): View
    {
        $profile = CompanyProfile::active()
            ->where('brand_name', $brandName)
            ->firstOrFail();

        $schedulingPage = $profile->schedulingPages()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $eventTypes = $schedulingPage->eventTypes();

        return view('public.scheduling-page', compact('profile', 'schedulingPage', 'eventTypes'));
    }

    /**
     * Show booking form for specific event type
     */
    public function showBookingForm(string $brandName, string $slug, int $eventTypeId): View
    {
        $profile = CompanyProfile::active()
            ->where('brand_name', $brandName)
            ->firstOrFail();

        $schedulingPage = $profile->schedulingPages()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $eventType = EventType::active()
            ->where('id', $eventTypeId)
            ->where('user_id', $profile->user_id)
            ->firstOrFail();

        // Check if event type is available on this scheduling page
        if (!in_array($eventType->id, $schedulingPage->event_type_ids ?? [])) {
            abort(404);
        }

        return view('public.booking-form', compact('profile', 'schedulingPage', 'eventType'));
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'event_type_id' => 'required|exists:event_types,id',
            'timezone' => 'required|string'
        ]);

        $eventType = EventType::findOrFail($request->event_type_id);
        $user = $eventType->user;
        $date = Carbon::parse($request->date);
        $timezone = $request->timezone;

        // Check if event type has reached daily limit
        if ($eventType->hasReachedDailyLimit()) {
            return response()->json([
                'success' => false,
                'message' => 'Daily booking limit reached for this event type',
                'slots' => []
            ]);
        }

        // Get business hours for the day
        $dayOfWeek = strtolower($date->format('l'));
        $businessHours = $user->companyProfile->business_hours ?? CompanyProfile::getDefaultBusinessHours();
        
        if (!isset($businessHours[$dayOfWeek]) || !$businessHours[$dayOfWeek]['enabled']) {
            return response()->json([
                'success' => true,
                'message' => 'No availability on this day',
                'slots' => []
            ]);
        }

        $dayHours = $businessHours[$dayOfWeek];
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $dayHours['start'], $user->companyProfile->timezone);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $dayHours['end'], $user->companyProfile->timezone);

        // Generate time slots
        $slots = [];
        $slotDuration = $eventType->duration_minutes;
        $bufferTime = $eventType->buffer_before_minutes + $eventType->buffer_after_minutes;
        
        $currentSlot = $startTime->copy();
        
        while ($currentSlot->copy()->addMinutes($slotDuration)->lte($endTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);
            
            // Check if slot conflicts with existing bookings
            $hasConflict = MeetingBooking::where('user_id', $user->id)
                ->where('status', '!=', 'cancelled')
                ->where(function($query) use ($currentSlot, $slotEnd, $bufferTime) {
                    $bufferStart = $currentSlot->copy()->subMinutes($bufferTime);
                    $bufferEnd = $slotEnd->copy()->addMinutes($bufferTime);
                    
                    $query->whereBetween('scheduled_at', [$bufferStart, $bufferEnd])
                          ->orWhereBetween('scheduled_end_at', [$bufferStart, $bufferEnd])
                          ->orWhere(function($q) use ($bufferStart, $bufferEnd) {
                              $q->where('scheduled_at', '<=', $bufferStart)
                                ->where('scheduled_end_at', '>=', $bufferEnd);
                          });
                })
                ->exists();

            if (!$hasConflict && $currentSlot->isFuture()) {
                $slots[] = [
                    'time' => $currentSlot->setTimezone($timezone)->format('H:i'),
                    'datetime' => $currentSlot->toISOString(),
                    'formatted' => $currentSlot->setTimezone($timezone)->format('g:i A')
                ];
            }

            $currentSlot->addMinutes($slotDuration);
        }

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }

    /**
     * Create a new booking
     */
    public function createBooking(Request $request): RedirectResponse
    {
        $request->validate([
            'event_type_id' => 'required|exists:event_types,id',
            'scheduling_page_id' => 'required|exists:scheduling_pages,id',
            'booker_name' => 'required|string|max:255',
            'booker_email' => 'required|email|max:255',
            'booker_phone' => 'nullable|string|max:20',
            'scheduled_at' => 'required|date|after:now',
            'timezone' => 'required|string',
            'custom_responses' => 'nullable|array'
        ]);

        $eventType = EventType::findOrFail($request->event_type_id);
        $schedulingPage = SchedulingPage::findOrFail($request->scheduling_page_id);

        // Verify the event type belongs to the scheduling page
        if (!in_array($eventType->id, $schedulingPage->event_type_ids ?? [])) {
            return back()->withErrors(['error' => 'Invalid event type for this scheduling page.']);
        }

        try {
            $booking = DB::transaction(function () use ($request, $eventType, $schedulingPage) {
                $scheduledAt = Carbon::parse($request->scheduled_at);
                $scheduledEndAt = $scheduledAt->copy()->addMinutes($eventType->duration_minutes);

                // Create the booking
                $booking = MeetingBooking::create([
                    'user_id' => $eventType->user_id,
                    'event_type_id' => $eventType->id,
                    'scheduling_page_id' => $schedulingPage->id,
                    'booker_name' => $request->booker_name,
                    'booker_email' => $request->booker_email,
                    'booker_phone' => $request->booker_phone,
                    'custom_responses' => $request->custom_responses,
                    'scheduled_at' => $scheduledAt,
                    'scheduled_end_at' => $scheduledEndAt,
                    'timezone' => $request->timezone,
                    'booking_reference' => MeetingBooking::generateBookingReference(),
                ]);

                // Create Google Meet link
                $meetResult = $this->googleMeetService->createMeetingEvent($booking);
                if ($meetResult) {
                    $booking->update([
                        'google_meet_link' => $meetResult['meet_link'],
                        'google_event_id' => $meetResult['event_id'],
                        'google_calendar_data' => $meetResult['event_data'],
                    ]);
                }

                // Create notifications
                $this->notificationService->createBookingNotifications($booking);

                return $booking;
            });

            // Send confirmation notifications immediately
            $this->notificationService->sendConfirmationNotifications($booking);

            return redirect()->route('public.booking.confirmation', $booking->booking_reference)
                ->with('success', 'Your meeting has been booked successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to create booking', [
                'event_type_id' => $request->event_type_id,
                'booker_email' => $request->booker_email,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }

    /**
     * Show booking confirmation page
     */
    public function showConfirmation(string $bookingReference): View
    {
        $booking = MeetingBooking::where('booking_reference', $bookingReference)
            ->with(['eventType', 'user.companyProfile'])
            ->firstOrFail();

        return view('public.booking-confirmation', compact('booking'));
    }

    /**
     * Show booking management page
     */
    public function manageBooking(string $bookingReference): View
    {
        $booking = MeetingBooking::where('booking_reference', $bookingReference)
            ->with(['eventType', 'user.companyProfile'])
            ->firstOrFail();

        return view('public.manage-booking', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Request $request, string $bookingReference): RedirectResponse
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);

        $booking = MeetingBooking::where('booking_reference', $bookingReference)
            ->firstOrFail();

        if (!$booking->canBeCancelled()) {
            return back()->withErrors(['error' => 'This booking cannot be cancelled.']);
        }

        try {
            DB::transaction(function () use ($booking, $request) {
                // Cancel the booking
                $booking->cancel($request->cancellation_reason);

                // Cancel Google Calendar event
                $this->googleMeetService->cancelMeetingEvent($booking);

                // Send cancellation notifications
                $this->notificationService->createCancellationNotifications($booking);
            });

            return redirect()->route('public.booking.manage', $booking->booking_reference)
                ->with('success', 'Your booking has been cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to cancel booking', [
                'booking_reference' => $bookingReference,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Failed to cancel booking. Please try again.']);
        }
    }

    /**
     * Show reschedule form
     */
    public function showRescheduleForm(string $bookingReference): View
    {
        $booking = MeetingBooking::where('booking_reference', $bookingReference)
            ->with(['eventType', 'user.companyProfile'])
            ->firstOrFail();

        if (!$booking->canBeRescheduled()) {
            abort(404);
        }

        return view('public.reschedule-booking', compact('booking'));
    }

    /**
     * Process booking reschedule
     */
    public function rescheduleBooking(Request $request, string $bookingReference): RedirectResponse
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'timezone' => 'required|string'
        ]);

        $booking = MeetingBooking::where('booking_reference', $bookingReference)
            ->firstOrFail();

        if (!$booking->canBeRescheduled()) {
            return back()->withErrors(['error' => 'This booking cannot be rescheduled.']);
        }

        try {
            DB::transaction(function () use ($booking, $request) {
                $oldScheduledAt = $booking->scheduled_at;
                $scheduledAt = Carbon::parse($request->scheduled_at);
                $scheduledEndAt = $scheduledAt->copy()->addMinutes($booking->eventType->duration_minutes);

                // Update booking times
                $booking->update([
                    'scheduled_at' => $scheduledAt,
                    'scheduled_end_at' => $scheduledEndAt,
                    'timezone' => $request->timezone,
                    'reschedule_history' => array_merge($booking->reschedule_history ?? [], [
                        [
                            'old_time' => $oldScheduledAt,
                            'new_time' => $scheduledAt,
                            'rescheduled_at' => now(),
                        ]
                    ])
                ]);

                // Update Google Calendar event
                $this->googleMeetService->updateMeetingEvent($booking);

                // Send reschedule notifications
                $this->notificationService->createRescheduleNotifications($booking);
            });

            return redirect()->route('public.booking.manage', $booking->booking_reference)
                ->with('success', 'Your booking has been rescheduled successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to reschedule booking', [
                'booking_reference' => $bookingReference,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Failed to reschedule booking. Please try again.']);
        }
    }
}