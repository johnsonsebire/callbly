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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SmsController extends Controller
{
    public function __construct(
        protected SmsProviderInterface $smsProvider,
        protected SmsWithCurrencyService $smsWithCurrency,
        protected CurrencyService $currencyService
    ) {}

    public function dashboard(): View 
    {
        return view('sms.dashboard');
    }

    public function compose(Request $request): View
    {
        $user = Auth::user();
        // Use the getAvailableSenderNames method to include shared sender names from teams
        $senderNames = $user->getAvailableSenderNames()->where('status', 'approved');
        $contactGroups = $user->contactGroups()->withCount('contacts')->get();
        $templates = $user->smsTemplates()->latest()->get();
        // Use getAvailableContacts to include shared contacts from teams
        $totalContactsCount = $user->getAvailableContacts()->count();
        
        $templateContent = null;
        if ($request->has('template')) {
            $template = $user->smsTemplates()->find($request->template);
            if ($template) {
                $templateContent = $template->content;
            }
        }
        
        return view('sms.compose', compact(
            'senderNames', 
            'contactGroups', 
            'templates', 
            'totalContactsCount'
        ))->with('template_content', $templateContent);
    }

    public function campaignDetails($id): View
    {
        $campaign = SmsCampaign::with(['recipients' => function($query) {
            $query->select('id', 'campaign_id', 'phone_number', 'status', 'created_at', 'delivered_at', 'error_message');
        }])->findOrFail($id);

        // Force an update of the campaign metrics before displaying
        $campaign->updateMetrics();
        
        $messageLength = mb_strlen($campaign->message);
        $hasUnicode = preg_match('/[\x{0080}-\x{FFFF}]/u', $campaign->message);
        
        $parts = $this->calculateMessageParts($messageLength, $hasUnicode);
        
        // Get recipients with filtering and pagination
        $recipients = $campaign->recipients()
            ->when(request('status'), fn($query, $status) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get the metrics for display
        $metrics = $this->calculateCampaignMetrics($campaign);
        
        return view('sms.campaign-details', [
            'campaign' => $campaign,
            'recipients' => $recipients,
            'totalRecipients' => $metrics['totalRecipients'],
            'deliveredCount' => $metrics['deliveredCount'],
            'failedCount' => $metrics['failedCount'],
            'pendingCount' => $metrics['pendingCount'],
            'deliveredPercentage' => $metrics['deliveredPercentage'],
            'failedPercentage' => $metrics['failedPercentage'],
            'pendingPercentage' => $metrics['pendingPercentage'],
            'totalCreditsUsed' => $campaign->credits_used ?? ($parts * $metrics['totalRecipients']),
            'parts' => $parts
        ]);
    }

    public function credits(): View
    {
        $user = Auth::user();
        // Use getAvailableSmsCredits method to include shared credits from team owners
        $smsCredits = $user->getAvailableSmsCredits();
        $currency = $user->currency ?? $this->currencyService->getDefaultCurrency();
        
        // Get credit purchase history if available
        $creditPurchases = $user->creditPurchases ?? collect([]);
        
        // Get credit usage history
        $campaigns = $user->smsCampaigns()
            ->with('recipients')
            ->select('id', 'name', 'created_at', 'credits_used')
            ->latest()
            ->take(5)
            ->get();
            
        return view('sms.credits', compact(
            'smsCredits',
            'currency',
            'creditPurchases',
            'campaigns'
        ));
    }

    protected function calculateMessageParts(int $messageLength, bool $hasUnicode): int
    {
        if ($hasUnicode) {
            return $messageLength <= 70 ? 1 : ceil(($messageLength - 70) / 67) + 1;
        }
        return $messageLength <= 160 ? 1 : ceil(($messageLength - 160) / 153) + 1;
    }

    protected function calculateCampaignMetrics(SmsCampaign $campaign): array
    {
        $totalRecipients = $campaign->recipients()->count();
        $deliveredCount = $campaign->recipients()->where('status', 'delivered')->count();
        $failedCount = $campaign->recipients()->where('status', 'failed')->count();
        $pendingCount = $campaign->recipients()->where('status', 'pending')->count();

        return [
            'totalRecipients' => $totalRecipients,
            'deliveredCount' => $deliveredCount,
            'failedCount' => $failedCount,
            'pendingCount' => $pendingCount,
            'deliveredPercentage' => $totalRecipients > 0 ? round(($deliveredCount / $totalRecipients) * 100) : 0,
            'failedPercentage' => $totalRecipients > 0 ? round(($failedCount / $totalRecipients) * 100) : 0,
            'pendingPercentage' => $totalRecipients > 0 ? round(($pendingCount / $totalRecipients) * 100) : 0
        ];
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
     * Replace template variables with actual contact data.
     *
     * @param string $message The message template with variables
     * @param \App\Models\Contact $contact The contact with data to use for replacement
     * @return string The message with variables replaced
     */
    protected function replaceTemplateVariables(string $message, $contact = null): string
    {
        if (!$contact) {
            return $message;
        }
        
        $variables = [
            'name' => $contact->full_name ?? ($contact->first_name . ' ' . $contact->last_name),
            'first_name' => $contact->first_name ?? '',
            'last_name' => $contact->last_name ?? '',
            'dob' => $contact->date_of_birth ? date('d/m/Y', strtotime($contact->date_of_birth)) : '',
            'email' => $contact->email ?? '',
            'phone' => $contact->phone_number ?? '',
            'company' => $contact->company ?? '',
        ];
        
        \Illuminate\Support\Facades\Log::info('Replacing template variables', [
            'original_message' => $message,
            'variables' => $variables
        ]);
        
        // Replace variables in the format {variable_name}
        return preg_replace_callback(
            '/\{([a-z_]+)\}/i',
            function ($matches) use ($variables) {
                $key = strtolower($matches[1]);
                return $variables[$key] ?? $matches[0]; // Return original if variable not found
            },
            $message
        );
    }

    /**
     * Send SMS messages (single or bulk).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function send(Request $request): RedirectResponse
    {
        // Debug incoming request data
        \Illuminate\Support\Facades\Log::info('SMS Send Request', [
            'request_data' => $request->all()
        ]);
        
        $request->validate([
            'message' => 'required|string',
            'sender_name' => 'required|string',
            'recipients_type' => 'required|in:single,group,multiple,file,all,contacts',
        ]);
        
        $user = Auth::user();
        $message = $request->input('message');
        $senderName = $request->input('sender_name');
        $recipientsType = $request->input('recipients_type');
        
        // Fix the scheduled_at date parsing
        $scheduledAt = null;
        if ($request->filled('scheduled_at')) {
            try {
                // Parse the datetime from the form
                $scheduledAt = new \DateTime($request->input('scheduled_at'));
                \Illuminate\Support\Facades\Log::info('Scheduling SMS for: ' . $scheduledAt->format('Y-m-d H:i:s'));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to parse scheduled date', [
                    'input' => $request->input('scheduled_at'),
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->withInput()->withErrors([
                    'scheduled_at' => 'Invalid date format for scheduling'
                ]);
            }
        }
        
        \Illuminate\Support\Facades\Log::info('SMS Processing', [
            'user_id' => $user->id,
            'recipients_type' => $recipientsType,
            'sender_name' => $senderName,
            'message_length' => strlen($message)
        ]);
        
        // Check if the sender name is in the user's available sender names (including team shared)
        $senderNameRecord = $user->getAvailableSenderNames()
            ->where('name', $senderName)
            ->where('status', 'approved')
            ->first();
            
        if (!$senderNameRecord) {
            \Illuminate\Support\Facades\Log::warning('Invalid sender name', [
                'sender_name' => $senderName,
                'user_id' => $user->id
            ]);
            return redirect()->back()->withInput()->withErrors([
                'sender_name' => 'The selected sender name is not valid or not approved.'
            ]);
        }
        
        // Get recipients based on type
        $recipients = [];
        
        try {
            switch ($recipientsType) {
                case 'single':
                    // Check both 'recipient' and 'recipients' fields to be more flexible
                    if ($request->has('recipient') && !empty($request->input('recipient'))) {
                        $recipients[] = $request->input('recipient');
                    } elseif ($request->has('recipients') && !empty($request->input('recipients'))) {
                        // For a single recipient entered in the multiple/manual tab
                        $recipientsText = $request->input('recipients');
                        $parsed = array_filter(array_map('trim', preg_split('/[\s,\n]+/', $recipientsText)));
                        if (count($parsed) === 1) {
                            $recipients = $parsed;
                        } else {
                            $recipients = $parsed;
                            $recipientsType = 'multiple'; // Adjust type if multiple numbers were entered
                        }
                    }
                    
                    // Validate we have at least one recipient
                    if (empty($recipients)) {
                        throw new \Exception('The recipient field is required.');
                    }
                    break;
                    
                case 'multiple':
                    if (!$request->has('recipients') || empty($request->input('recipients'))) {
                        throw new \Exception('The recipients field is required.');
                    }
                    $recipientsText = $request->input('recipients');
                    $recipients = array_filter(array_map('trim', preg_split('/[\s,\n]+/', $recipientsText)));
                    
                    if (empty($recipients)) {
                        throw new \Exception('No valid recipients were found in the input.');
                    }
                    break;
                    
                case 'contacts':
                    $request->validate(['contact_ids' => 'required|array', 'contact_ids.*' => 'exists:contacts,id']);
                    $contactIds = $request->input('contact_ids');
                    
                    // Use getAvailableContacts to get both personal and team-shared contacts
                    $availableContacts = $user->getAvailableContacts();
                    $contactsToUse = $availableContacts->whereIn('id', $contactIds);
                    
                    if ($contactsToUse->isEmpty()) {
                        throw new \Exception('No valid contacts were found.');
                    }
                    
                    foreach ($contactsToUse as $contact) {
                        if (!empty($contact->phone_number)) {
                            $recipients[] = $contact->phone_number;
                        }
                    }
                    break;
                    
                case 'group':
                    $request->validate(['contact_group_ids' => 'required|array', 'contact_group_ids.*' => 'exists:contact_groups,id']);
                    $groupIds = $request->input('contact_group_ids');
                    foreach ($groupIds as $groupId) {
                        $group = $user->contactGroups()->findOrFail($groupId);
                        $phoneNumbers = $group->contacts()->pluck('phone_number')->toArray();
                        $recipients = array_merge($recipients, $phoneNumbers);
                    }
                    break;
                    
                case 'file':
                    $request->validate(['recipients_file' => 'required|file|mimes:csv,txt']);
                    $file = $request->file('recipients_file');
                    $contents = file_get_contents($file->getPathname());
                    $recipients = array_filter(array_map('trim', preg_split('/[\s,\n]+/', $contents)));
                    break;
                    
                case 'all':
                    $request->validate(['send_to_all_contacts' => 'required|accepted']);
                    // Use getAvailableContacts to get both personal and team-shared contacts
                    $recipients = $user->getAvailableContacts()->pluck('phone_number')->toArray();
                    break;
            }
            
            \Illuminate\Support\Facades\Log::info('Recipients collected', [
                'count' => count($recipients),
                'first_few' => array_slice($recipients, 0, 5)
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error collecting recipients', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->withErrors([
                'recipients' => 'Error processing recipients: ' . $e->getMessage()
            ]);
        }
        
        // Ensure we have recipients
        if (empty($recipients)) {
            \Illuminate\Support\Facades\Log::warning('No recipients found', [
                'recipients_type' => $recipientsType
            ]);
            return redirect()->back()->withInput()->withErrors([
                'recipients' => 'No valid recipients were found.'
            ]);
        }
        
        // Calculate message parts and required credits
        $messageLength = mb_strlen($message);
        $hasUnicode = preg_match('/[\x{0080}-\x{FFFF}]/u', $message);
        $parts = $this->calculateMessageParts($messageLength, $hasUnicode);
        $creditsNeeded = $parts * count($recipients);
        
        // Get available SMS credits including shared ones from teams
        $availableSmsCredits = $user->getAvailableSmsCredits();
        
        \Illuminate\Support\Facades\Log::info('Credits calculation', [
            'parts' => $parts,
            'recipients_count' => count($recipients),
            'credits_needed' => $creditsNeeded,
            'user_credits' => $user->sms_credits,
            'available_credits' => $availableSmsCredits
        ]);
        
        // Check if user has enough credits (including shared from teams)
        if ($availableSmsCredits < $creditsNeeded) {
            \Illuminate\Support\Facades\Log::warning('Insufficient credits', [
                'user_id' => $user->id,
                'credits_needed' => $creditsNeeded,
                'user_credits' => $user->sms_credits,
                'available_credits' => $availableSmsCredits
            ]);
            return redirect()->back()->withInput()->withErrors([
                'credits' => "You don't have enough SMS credits. You need {$creditsNeeded} credits but have {$availableSmsCredits}."
            ]);
        }
        
        // Create campaign
        try {
            // Set a default campaign name if none is provided
            $campaignName = $request->input('campaign_name');
            
            // If campaign_name is empty, create a default name using the first part of the message
            if (empty($campaignName)) {
                $messagePreview = Str::limit(strip_tags($message), 30);
                $campaignName = 'SMS Campaign - ' . $messagePreview;
            }
            
            \Illuminate\Support\Facades\Log::info('Creating campaign with name', [
                'campaign_name' => $campaignName
            ]);
            
            $campaign = SmsCampaign::create([
                'user_id' => $user->id,
                'name' => $campaignName,
                'message' => $message,
                'sender_name' => $senderName,
                'status' => 'pending', // Always use pending status, even for scheduled messages
                'recipients_count' => count($recipients),
                'delivered_count' => 0,
                'failed_count' => 0,
                'scheduled_at' => $scheduledAt,
            ]);
            
            \Illuminate\Support\Facades\Log::info('Campaign created', [
                'campaign_id' => $campaign->id,
                'name' => $campaignName,
                'status' => $campaign->status
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating campaign', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->withErrors([
                'error' => 'Error creating campaign: ' . $e->getMessage()
            ]);
        }
        
        try {
            // Create a custom request for the API controller
            $apiRequest = new \Illuminate\Http\Request();
            $apiRequest->replace([
                'recipients' => $recipients,
                'message' => $message,
                'sender_name' => $senderName,
                'name' => $campaign->name,
                'scheduled_at' => $scheduledAt ? $scheduledAt->format('Y-m-d H:i:s') : null,
                'campaign_id' => $campaign->id, // Pass the existing campaign ID
                '_internal_call' => true, // Add flag to prevent double credit deduction
            ]);
            
            // Set the authenticated user for the request
            $apiRequest->setUserResolver(function () use ($user) {
                return $user;
            });
            
            \Illuminate\Support\Facades\Log::info('Calling API controller', [
                'campaign_id' => $campaign->id,
                'recipients_count' => count($recipients)
            ]);
            
            // Use the SmsService to send the messages through API controller
            $apiController = app(\App\Http\Controllers\Api\SmsController::class);
            $apiResponse = $apiController->sendBulk($apiRequest);
            
            $responseData = json_decode($apiResponse->getContent(), true);
            
            \Illuminate\Support\Facades\Log::info('API response received', [
                'success' => $responseData['success'] ?? false,
                'message' => $responseData['message'] ?? null,
                'data' => $responseData['data'] ?? null
            ]);
            
            if ($responseData['success'] ?? false) {
                // Debug log to track credit deduction
                \Illuminate\Support\Facades\Log::info('About to deduct SMS credits in web controller', [
                    'user_id' => $user->id,
                    'campaign_id' => $campaign->id,
                    'credits_needed' => $creditsNeeded
                ]);
                
                // Use our centralized method for credit deduction
                $teamResourceService = app(\App\Services\TeamResourceService::class);
                $creditResult = $teamResourceService->deductSharedSmsCredits($user, $creditsNeeded);
                
                if (!$creditResult['success']) {
                    \Illuminate\Support\Facades\Log::error('Failed to deduct SMS credits', [
                        'user_id' => $user->id,
                        'campaign_id' => $campaign->id,
                        'credits_needed' => $creditsNeeded,
                        'error' => $creditResult['message']
                    ]);
                    
                    // Don't fail the request as SMS is already sent, but log the error
                }
                
                // Update campaign with team credit info if applicable
                if (isset($creditResult['team_id']) && $creditResult['team_id']) {
                    $campaign->update([
                        'team_id' => $creditResult['team_id'],
                        'team_credits_used' => $creditResult['team_credits_used']
                    ]);
                }
                
                \Illuminate\Support\Facades\Log::info('SMS campaign sent successfully', [
                    'campaign_id' => $campaign->id,
                    'personal_credits_used' => $creditResult['personal_credits_used'] ?? 0,
                    'team_credits_used' => $creditResult['team_credits_used'] ?? 0,
                    'user_credits_after' => $user->fresh()->sms_credits
                ]);
                
                // Redirect with success message
                return redirect()->route('sms.campaign-details', $responseData['data']['campaign_id'] ?? $campaign->id)
                    ->with('success', 'Your SMS campaign has been sent successfully.');
            } else {
                // Update campaign status to failed
                $campaign->update([
                    'status' => 'failed'
                ]);
                
                \Illuminate\Support\Facades\Log::error('Failed to send campaign via API', [
                    'campaign_id' => $campaign->id,
                    'error' => $responseData['message'] ?? 'Unknown error'
                ]);
                
                return redirect()->route('sms.campaigns')
                    ->withErrors(['error' => 'Failed to send SMS campaign: ' . ($responseData['message'] ?? 'Unknown error')]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS sending error', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Update campaign status to failed
            $campaign->update([
                'status' => 'failed'
            ]);
            
            return redirect()->route('sms.campaigns')
                ->withErrors(['error' => 'An error occurred while sending your SMS campaign: ' . $e->getMessage()]);
        }
    }

    /**
     * Calculate the number of credits needed for a message.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateCredits(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'recipients_count' => 'required|integer|min:1',
        ]);
        
        $message = $request->input('message');
        $recipientsCount = $request->input('recipients_count');
        
        // Calculate message parts
        $messageLength = mb_strlen($message);
        $hasUnicode = preg_match('/[\x{0080}-\x{FFFF}]/u', $message);
        $parts = $this->calculateMessageParts($messageLength, $hasUnicode);
        
        // Calculate credits needed
        $creditsNeeded = $parts * $recipientsCount;
        
        $user = Auth::user();
        // Use getAvailableSmsCredits to include shared credits from team owners
        $availableCredits = $user->getAvailableSmsCredits();
        
        return response()->json([
            'success' => true,
            'message_length' => $messageLength,
            'parts' => $parts,
            'recipients_count' => $recipientsCount,
            'credits_needed' => $creditsNeeded,
            'user_credits' => $availableCredits,
            'has_enough_credits' => $availableCredits >= $creditsNeeded,
        ]);
    }

    /**
     * Show the SMS campaigns list.
     *
     * @return View
     */
    public function campaigns(): View
    {
        $user = Auth::user();
        $campaigns = $user->smsCampaigns()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('sms.campaigns', [
            'campaigns' => $campaigns
        ]);
    }

    /**
     * Download a campaign report as CSV.
     *
     * @param int $id
     * @return BinaryFileResponse
     */
    public function downloadReport(int $id): BinaryFileResponse
    {
        $campaign = SmsCampaign::where('user_id', Auth::id())->findOrFail($id);
        
        // Force an update of the campaign metrics before creating the report
        $campaign->updateMetrics();
        
        // Generate CSV content
        $filename = 'sms_campaign_' . $id . '_' . date('Y-m-d') . '.csv';
        $tempFile = tempnam(sys_get_temp_dir(), 'sms_report_');
        $file = fopen($tempFile, 'w');
        
        // Add CSV header
        fputcsv($file, ['Phone Number', 'Status', 'Sent Time', 'Delivered Time', 'Error Message']);
        
        // Add recipient data
        $campaign->recipients->each(function ($recipient) use ($file) {
            fputcsv($file, [
                $recipient->phone_number,
                ucfirst($recipient->status),
                $recipient->created_at ? $recipient->created_at->format('Y-m-d H:i:s') : 'N/A',
                $recipient->delivered_at ? $recipient->delivered_at->format('Y-m-d H:i:s') : 'N/A',
                $recipient->error_message ?? 'N/A'
            ]);
        });
        
        fclose($file);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend();
    }

    /**
     * Duplicate an existing campaign.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function duplicateCampaign(int $id): RedirectResponse
    {
        $campaign = SmsCampaign::where('user_id', Auth::id())->findOrFail($id);
        
        $newCampaign = $campaign->replicate();
        $newCampaign->name = 'Copy of ' . $campaign->name;
        $newCampaign->status = 'draft';
        $newCampaign->created_at = now();
        $newCampaign->scheduled_at = null;
        $newCampaign->completed_at = null;
        $newCampaign->delivered_count = 0;
        $newCampaign->failed_count = 0;
        $newCampaign->save();
        
        return redirect()->route('sms.compose')
            ->with('campaign_id', $newCampaign->id)
            ->with('success', 'Campaign duplicated. You can now modify and send it.');
    }

    /**
     * Show the sender names page.
     *
     * @return View
     */
    public function senderNames(): View
    {
        $user = Auth::user();
        // Include both personal and shared sender names from teams
        $senderNames = $user->getAvailableSenderNames()->sortByDesc('created_at');
        
        return view('sms.sender-names', [
            'senderNames' => $senderNames
        ]);
    }

    /**
     * Store a new sender name.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeSenderName(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|min:3|max:11|regex:/^[a-zA-Z0-9]+$/',
        ]);
        
        $user = Auth::user();
        $name = $request->input('name');
        
        // Check if user already has this sender name
        if ($user->senderNames()->where('name', $name)->exists()) {
            return redirect()->back()->withErrors([
                'name' => 'You already have this sender name registered.'
            ]);
        }
        
        // Create the sender name
        $senderName = SenderName::create([
            'user_id' => $user->id,
            'name' => $name,
            'status' => 'pending',
        ]);
        
        return redirect()->route('sms.sender-names')
            ->with('success', 'Sender name submitted for approval. You will be notified once it is approved.');
    }

    /**
     * Process the purchase of SMS credits.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function buyCredits(Request $request): RedirectResponse
    {
        $request->validate([
            'credit_amount' => 'required|integer|min:100',
            'payment_method' => 'required|in:card,bank,ussd',
        ]);
        
        $user = Auth::user();
        $creditAmount = $request->input('credit_amount');
        $paymentMethod = $request->input('payment_method');
        $currency = $user->currency ?? $this->currencyService->getDefaultCurrency();
        
        // Calculate price based on credit amount
        $rate = $creditAmount >= 10000 ? 0.009 : 0.01; // Discounted rate for larger purchases
        $price = $creditAmount * $rate * ($currency->conversion_rate ?? 1);
        
        // Log purchase attempt
        Log::info('SMS Credit Purchase Initiated', [
            'user_id' => $user->id,
            'credits' => $creditAmount,
            'amount' => $price,
            'currency' => $currency->code,
            'payment_method' => $paymentMethod
        ]);
        
        try {
            // Create a payment record
            $paymentReference = 'SMS' . strtoupper(Str::random(8));
            
            // Create credit purchase record
            $purchase = $user->creditPurchases()->create([
                'credits' => $creditAmount,
                'amount' => $price,
                'currency_id' => $currency->id,
                'payment_method' => $paymentMethod,
                'reference' => $paymentReference,
                'status' => 'pending'
            ]);
            
            // Redirect to payment page
            return redirect()->route('payment.initiate', [
                'reference' => $paymentReference,
                'amount' => $price,
                'currency' => $currency->code,
                'description' => "Purchase of {$creditAmount} SMS credits",
                'return_url' => route('sms.credits'),
                'payment_method' => $paymentMethod,
                'purchase_type' => 'sms_credits'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to process SMS credit purchase', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('sms.credits')
                ->withErrors(['error' => 'Failed to process payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the SMS billing tier information.
     *
     * @return \Illuminate\View\View
     */
    public function showBillingTier(): \Illuminate\View\View
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
     * Get template content by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplateContent($id): JsonResponse
    {
        $template = SmsTemplate::where('user_id', Auth::id())->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'content' => $template->content
        ]);
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

}