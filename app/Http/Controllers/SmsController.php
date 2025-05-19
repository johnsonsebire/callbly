<?php

namespace App\Http\Controllers;

use App\Contracts\SmsProviderInterface;
use App\Models\SmsCampaign;
use App\Models\SenderName;
use App\Models\User;
use App\Services\Sms\SmsWithCurrencyService;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Inertia\Inertia;

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
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render('Sms/Dashboard');
    }
    
    /**
     * Show the form to compose a new SMS.
     *
     * @return \Inertia\Response
     */
    public function compose()
    {
        return Inertia::render('Sms/Compose');
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
     * @return \Inertia\Response
     */
    public function campaigns()
    {
        return Inertia::render('Sms/Campaigns');
    }
    
    /**
     * Show details of a specific SMS campaign.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function campaignDetails($id)
    {
        return Inertia::render('Sms/CampaignDetails', ['id' => $id]);
    }
    
    /**
     * Show sender names management page.
     *
     * @return \Inertia\Response
     */
    public function senderNames()
    {
        return Inertia::render('Sms/SenderNames');
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
     * @return \Inertia\Response
     */
    public function credits()
    {
        return Inertia::render('Sms/Credits');
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
     * @return \Inertia\Response
     */
    public function showBillingTier()
    {
        return Inertia::render('Sms/BillingTier');
    }
}