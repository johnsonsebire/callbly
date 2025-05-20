<?php

namespace App\Http\Controllers;

use App\Contracts\SmsProviderInterface;
use App\Models\SmsCampaign;
use App\Models\SenderName;
use App\Models\User;
use App\Models\SmsTemplate;
use App\Services\Sms\SmsWithCurrencyService;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SmsController extends Controller
{
    protected SmsProviderInterface $smsProvider;
    protected SmsWithCurrencyService $smsWithCurrency;
    protected CurrencyService $currencyService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        SmsProviderInterface $smsProvider, 
        SmsWithCurrencyService $smsWithCurrency,
        CurrencyService $currencyService
    ) {
        $this->smsProvider = $smsProvider;
        $this->smsWithCurrency = $smsWithCurrency;
        $this->currencyService = $currencyService;
    }

    /**
     * Display the SMS dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('sms.dashboard');
    }
    
    /**
     * Show the form to compose a new SMS.
     *
     * @return \Illuminate\View\View
     */
    public function compose()
    {
        $senderNames = Auth::user()->senderNames()->where('status', 'approved')->get();
        return view('sms.compose', compact('senderNames'));
    }
    
    /**
     * Send an SMS message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required|string',
            'message' => 'required|string|max:918', // Allow for multi-part SMS
            'sender_id' => 'required|string|exists:sender_names,name',
        ]);
        
        $user = Auth::user();
        $recipients = array_filter(explode(',', str_replace(["\r\n", "\n", " "], ",", $request->recipients)));
        $message = $request->message;
        $senderId = $request->sender_id;
        
        // Create campaign record
        $campaign = new SmsCampaign();
        $campaign->user_id = $user->id;
        $campaign->name = Str::limit($message, 30);
        $campaign->message = $message;
        $campaign->sender_name = $senderId;
        $campaign->recipients_count = count($recipients);
        $campaign->status = 'processing';
        $campaign->save();
        
        try {
            // Use our new currency-aware SMS service
            $response = $this->smsWithCurrency->sendSms(
                $user, 
                $recipients, 
                $message, 
                $senderId,
                $campaign->id
            );
            
            // Update campaign with response
            $campaign->status = $response['success'] ? 'sent' : 'failed';
            $campaign->provider_response = json_encode($response);
            
            if (isset($response['result'])) {
                $campaign->provider_batch_id = $response['result']['batch_id'] ?? null;
            }
            
            if (isset($response['cost_details'])) {
                $campaign->credits_used = $response['cost_details']['total_credits'] ?? null;
            }
            
            $campaign->save();
            
            if ($response['success']) {
                return redirect()->route('sms.campaigns')->with('success', 'SMS campaign has been sent successfully.');
            } else {
                return redirect()->route('sms.compose')->with('error', 'Failed to send SMS: ' . ($response['message'] ?? 'Unknown error'));
            }
            
        } catch (\Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage(), [
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'recipients_count' => count($recipients),
            ]);
            
            $campaign->status = 'failed';
            $campaign->save();
            
            return redirect()->route('sms.compose')->with('error', 'An error occurred while sending your SMS: ' . $e->getMessage());
        }
    }
    
    /**
     * Show all SMS campaigns for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function campaigns()
    {
        $campaigns = Auth::user()->smsCampaigns()->latest()->paginate(10);
        return view('sms.campaigns', compact('campaigns'));
    }
    
    /**
     * Show details of a specific SMS campaign.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function campaignDetails($id)
    {
        $campaign = SmsCampaign::where('user_id', Auth::id())
            ->findOrFail($id);
            
        // Get recipients with optional status filter
        $query = $campaign->recipients();
        
        if (request()->has('status') && request('status')) {
            $query->where('status', request('status'));
        }
        
        $recipients = $query->latest()->paginate(15);
            
        return view('sms.campaign-details', compact('campaign', 'recipients'));
    }
    
    /**
     * Show sender names management page.
     *
     * @return \Illuminate\View\View
     */
    public function senderNames()
    {
        $senderNames = Auth::user()->senderNames()->latest()->get();
        return view('sms.sender-names', compact('senderNames'));
    }
    
    /**
     * Store a new sender name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSenderName(Request $request)
    {
        $maxLength = config('sms.sender_name.max_length', 11);
        $minLength = config('sms.sender_name.min_length', 3);
        
        $request->validate([
            'name' => "required|string|min:{$minLength}|max:{$maxLength}|alpha_num|unique:sender_names,name,NULL,id,user_id," . Auth::id(),
        ]);
        
        $senderName = new SenderName();
        $senderName->user_id = Auth::id();
        $senderName->name = strtoupper($request->name);
        $senderName->status = config('sms.sender_name.require_approval', true) ? 'pending' : 'approved';
        $senderName->save();
        
        return redirect()->route('sms.sender-names')->with('success', 'Sender name has been submitted successfully.');
    }
    
    /**
     * Show credit balance page.
     *
     * @return \Illuminate\View\View
     */
    public function credits()
    {
        $user = Auth::user();
        $billingTier = $user->billingTier;
        return view('sms.credits', compact('user', 'billingTier'));
    }
    
    /**
     * Calculate credits needed for a message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateCredits(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'recipients' => 'required|string',
        ]);
        
        $user = Auth::user();
        $message = $request->message;
        $recipients = array_filter(explode(',', str_replace(["\r\n", "\n", " "], ",", $request->recipients)));
        $recipientCount = count($recipients);
        
        $smsCount = $this->smsWithCurrency->calculateSmsPartsCount($message);
        $totalCredits = $this->smsWithCurrency->calculateCreditsNeeded($message, $recipientCount);
        
        // Calculate cost using the user's billing tier and currency
        $costDetails = $this->smsWithCurrency->calculateSmsCost($user, $smsCount, $recipientCount);
        
        return response()->json([
            'credits_needed' => $totalCredits,
            'recipient_count' => $recipientCount,
            'sms_parts' => $smsCount,
            'cost' => $costDetails['user_cost'],
            'formatted_cost' => $costDetails['formatted_cost'],
            'rate' => $costDetails['sms_rate'],
            'billing_tier' => $costDetails['billing_tier'],
            'currency' => $user->currency->code,
            'currency_symbol' => $user->currency->symbol
        ]);
    }
    
    /**
     * Show user's current SMS rate and billing tier
     * 
     * @return \Illuminate\View\View
     */
    public function showBillingTier()
    {
        $user = Auth::user();
        $currentTier = $user->billingTier;
        $userCurrency = $user->currency;
        
        // Format SMS rate in user's currency
        $smsRate = $user->getSmsRate();
        $formattedSmsRate = $userCurrency->symbol . number_format($smsRate, 3);
        
        // Get all billing tiers to display in the table
        $allTiers = \App\Models\BillingTier::orderBy('price_per_sms', 'desc')->get();
        
        return view('sms.billing-tier', compact(
            'user', 
            'currentTier', 
            'userCurrency', 
            'formattedSmsRate', 
            'allTiers'
        ));
    }
    
    /**
     * Show the SMS messages page.
     *
     * @return \Illuminate\View\View
     */
    public function messages()
    {
        $user = Auth::user();
        $messages = $user->smsCampaigns()
            ->with(['recipients' => function ($query) {
                $query->latest();
            }])
            ->latest()
            ->paginate(15);
            
        return view('sms.messages', compact('messages'));
    }

    /**
     * Show SMS templates list.
     *
     * @return \Illuminate\View\View
     */
    public function templates()
    {
        $user = Auth::user();
        $templates = $user->smsTemplates()->latest()->paginate(10);
        return view('sms.templates.index', compact('templates'));
    }

    /**
     * Show form to create a new SMS template.
     *
     * @return \Illuminate\View\View
     */
    public function createTemplate()
    {
        return view('sms.templates.create');
    }

    /**
     * Store a new SMS template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:918',
            'description' => 'nullable|string|max:1000',
        ]);

        $template = new SmsTemplate();
        $template->user_id = Auth::id();
        $template->name = $request->name;
        $template->content = $request->content;
        $template->description = $request->description;
        $template->save();

        return redirect()->route('sms.templates')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Show form to edit an SMS template.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function editTemplate($id)
    {
        $template = SmsTemplate::where('user_id', Auth::id())->findOrFail($id);
        return view('sms.templates.edit', compact('template'));
    }

    /**
     * Update an SMS template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTemplate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:918',
            'description' => 'nullable|string|max:1000',
        ]);

        $template = SmsTemplate::where('user_id', Auth::id())->findOrFail($id);
        $template->name = $request->name;
        $template->content = $request->content;
        $template->description = $request->description;
        $template->save();

        return redirect()->route('sms.templates')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Delete an SMS template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTemplate($id)
    {
        $template = SmsTemplate::where('user_id', Auth::id())->findOrFail($id);
        $template->delete();

        return redirect()->route('sms.templates')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Download a report for a specific SMS campaign.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadReport($id)
    {
        // Get the campaign and make sure it belongs to the authenticated user
        $campaign = SmsCampaign::where('user_id', Auth::id())->findOrFail($id);
        
        // Get all recipients for this campaign
        $recipients = $campaign->recipients()->get();
        
        // Create CSV data
        $csvData = [
            ['Campaign ID', 'Campaign Name', 'Sender Name', 'Phone Number', 'Status', 'Sent Time', 'Delivered Time', 'Error Message']
        ];
        
        foreach ($recipients as $recipient) {
            $csvData[] = [
                $campaign->id,
                $campaign->name,
                $campaign->sender_name,
                $recipient->phone_number,
                ucfirst($recipient->status),
                $recipient->created_at ? $recipient->created_at->format('Y-m-d H:i:s') : 'N/A',
                $recipient->delivered_at ? $recipient->delivered_at->format('Y-m-d H:i:s') : 'N/A',
                $recipient->error_message ?? 'N/A'
            ];
        }
        
        // Create a temporary file
        $fileName = 'sms-campaign-' . $campaign->id . '-' . date('Y-m-d') . '.csv';
        $tempFile = tempnam(sys_get_temp_dir(), 'sms_report_');
        $file = fopen($tempFile, 'w');
        
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        
        // Return the file for download
        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }
    
    /**
     * Duplicate an existing SMS campaign.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicateCampaign($id)
    {
        // Get the original campaign
        $original = SmsCampaign::where('user_id', Auth::id())->findOrFail($id);
        
        // Create a duplicate campaign
        $duplicate = $original->replicate();
        $duplicate->name = $original->name . ' (Copy)';
        $duplicate->status = 'draft';
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->provider_batch_id = null;
        $duplicate->provider_response = null;
        $duplicate->save();
        
        return redirect()->route('sms.campaigns')
            ->with('success', 'Campaign duplicated successfully. You can now edit and send the duplicated campaign.');
    }
}