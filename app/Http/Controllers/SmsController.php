<?php

namespace App\Http\Controllers;

use App\Contracts\SmsProviderInterface;
use App\Jobs\SendBulkSmsJob;
use App\Jobs\SendSingleSmsJob;
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
use Illuminate\Support\Facades\DB;
class SmsController extends Controller
{
    public function __construct(
        protected SmsProviderInterface $smsProvider,
        protected SmsWithCurrencyService $smsWithCurrency,
        protected CurrencyService $currencyService
    ) {}

    public function dashboard(): View 
    {
        $user = Auth::user();

        // Get delivery statistics
        $totalSent = $user->smsCampaigns()->sum('recipients_count');
        $deliveredCount = $user->smsCampaigns()->sum('delivered_count');
        $deliveryRate = $totalSent > 0 ? round(($deliveredCount / $totalSent) * 100, 1) : 0;

        // Get today's statistics
        $todayStats = $user->smsCampaigns()
            ->whereDate('created_at', now()->toDateString())
            ->selectRaw('
                COUNT(*) as count,
                SUM(recipients_count) as total_recipients,
                SUM(delivered_count) as delivered_count,
                SUM(credits_used) as credits_used
            ')
            ->first();

        $todayCount = $todayStats->total_recipients ?? 0;
        $todaySuccessRate = $todayStats->total_recipients > 0 
            ? round(($todayStats->delivered_count / $todayStats->total_recipients) * 100, 1) 
            : 0;
        $todayCreditsUsed = $todayStats->credits_used ?? 0;

        // Get active campaigns count
        $activeCampaigns = $user->smsCampaigns()
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        // Get approved sender IDs count
        $approvedSenderIds = $user->getAvailableSenderNames()
            ->where('status', 'approved')
            ->count();

        // Get recent campaigns
        $campaigns = $user->smsCampaigns()
            ->with('recipients')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent templates
        $templates = $user->smsTemplates()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('sms.dashboard', compact(
            'deliveryRate',
            'deliveredCount',
            'totalSent',
            'todayCount',
            'todaySuccessRate',
            'todayCreditsUsed',
            'activeCampaigns',
            'approvedSenderIds',
            'campaigns',
            'templates'
        ));
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
        $hasUnicode = $this->hasUnicodeCharacters($campaign->message);
        
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
            return $messageLength <= 70 ? 1 : ceil($messageLength / 67);
        }
        return $messageLength <= 160 ? 1 : ceil($messageLength / 153);
    }

    /**
     * Check if message contains characters that require Unicode SMS encoding
     * More precise detection that excludes common punctuation
     */
    protected function hasUnicodeCharacters(string $message): bool
    {
        // GSM 7-bit character set + common punctuation that should use regular SMS
        // This pattern matches characters that are NOT in the extended GSM character set
        return preg_match('/[^\x{0000}-\x{007F}\x{00A0}-\x{00FF}\x{2010}-\x{2019}\x{201C}-\x{201D}\x{2026}\x{20AC}]/u', $message) === 1;
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
        // === DEBUGGING: START OF FLOW ===
        \Illuminate\Support\Facades\Log::info('=== SMS SEND REQUEST START ===', [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'user_id' => auth()->id(),
            'raw_scheduled_at' => $request->input('scheduled_at'),
            'all_inputs' => $request->all(),
            'request_method' => $request->method(),
            'request_url' => $request->url()
        ]);
        
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
                // Parse the datetime from the form using Carbon
                $scheduledAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('scheduled_at'));
                \Illuminate\Support\Facades\Log::info('=== SCHEDULING ANALYSIS ===', [
                    'parsed_scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                    'current_time' => now()->format('Y-m-d H:i:s'),
                    'is_future' => $scheduledAt->isAfter(now()),
                    'diff_in_seconds' => $scheduledAt->diffInSeconds(now()),
                    'timezone' => $scheduledAt->timezone,
                    'raw_input' => $request->input('scheduled_at')
                ]);
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
        
        // Calculate message pages and required credits
        $messageLength = mb_strlen($message);
        $hasUnicode = $this->hasUnicodeCharacters($message);
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
            // Deduct credits before queuing the job
            \Illuminate\Support\Facades\Log::info('About to deduct SMS credits before queuing job', [
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'credits_needed' => $creditsNeeded,
                'user_credits_before' => $user->sms_credits
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
                
                return redirect()->back()->withInput()->withErrors([
                    'credits' => 'Failed to deduct SMS credits: ' . $creditResult['message']
                ]);
            }
            
            // Update campaign with team credit info if applicable
            if (isset($creditResult['team_id']) && $creditResult['team_id']) {
                $campaign->update([
                    'team_id' => $creditResult['team_id'],
                    'team_credits_used' => $creditResult['team_credits_used'],
                    'credits_used' => $creditsNeeded
                ]);
            } else {
                $campaign->update([
                    'credits_used' => $creditsNeeded
                ]);
            }
            
            // Dispatch the appropriate job based on recipients count and schedule
            if (count($recipients) === 1 && $recipientsType === 'single') {
                // Single SMS job
                $job = new \App\Jobs\SendSingleSmsJob(
                    $campaign->id,
                    $recipients[0],
                    $message,
                    $senderName
                );
                
                // Apply delay if message is scheduled
                if ($scheduledAt && $scheduledAt->isAfter(now())) {
                    $delay = now()->diffInSeconds($scheduledAt);
                    
                    \Illuminate\Support\Facades\Log::info('=== APPLYING DELAY TO SINGLE SMS JOB ===', [
                        'campaign_id' => $campaign->id,
                        'recipient' => $recipients[0],
                        'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                        'current_time' => now()->format('Y-m-d H:i:s'),
                        'delay_seconds' => $delay,
                        'job_class' => get_class($job)
                    ]);
                    
                    $job->delay($delay);
                    
                    \Illuminate\Support\Facades\Log::info('=== DELAY APPLIED - DISPATCHING JOB ===', [
                        'job_delayed' => true,
                        'delay_seconds' => $delay
                    ]);
                    
                    dispatch($job);
                    
                    \Illuminate\Support\Facades\Log::info('Single SMS job scheduled', [
                        'campaign_id' => $campaign->id,
                        'recipient' => $recipients[0],
                        'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                        'delay_seconds' => $delay
                    ]);
                    
                    $successMessage = 'Your SMS has been scheduled for ' . $scheduledAt->format('M d, Y H:i:s') . '. You can track its progress on the campaign details page.';
                } else {
                    \Illuminate\Support\Facades\Log::info('=== NO DELAY NEEDED - IMMEDIATE DISPATCH ===', [
                        'campaign_id' => $campaign->id,
                        'scheduled_at' => $scheduledAt ? $scheduledAt->format('Y-m-d H:i:s') : 'null',
                        'is_after_now' => $scheduledAt ? $scheduledAt->isAfter(now()) : false
                    ]);
                    
                    dispatch($job);
                    
                    \Illuminate\Support\Facades\Log::info('Single SMS job dispatched immediately', [
                        'campaign_id' => $campaign->id,
                        'recipient' => $recipients[0]
                    ]);
                    
                    $successMessage = 'Your SMS is being sent. You can track its progress on the campaign details page.';
                }
            } else {
                // Bulk SMS job
                $job = new \App\Jobs\SendBulkSmsJob(
                    $campaign->id,
                    $recipients,
                    $message,
                    $senderName
                );
                
                // Apply delay if message is scheduled
                if ($scheduledAt && $scheduledAt->isAfter(now())) {
                    $delay = now()->diffInSeconds($scheduledAt);
                    
                    \Illuminate\Support\Facades\Log::info('=== APPLYING DELAY TO BULK SMS JOB ===', [
                        'campaign_id' => $campaign->id,
                        'recipients_count' => count($recipients),
                        'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                        'current_time' => now()->format('Y-m-d H:i:s'),
                        'delay_seconds' => $delay,
                        'job_class' => get_class($job)
                    ]);
                    
                    $job->delay($delay);
                    
                    \Illuminate\Support\Facades\Log::info('=== DELAY APPLIED - DISPATCHING BULK JOB ===', [
                        'job_delayed' => true,
                        'delay_seconds' => $delay
                    ]);
                    
                    dispatch($job);
                    
                    \Illuminate\Support\Facades\Log::info('Bulk SMS job scheduled', [
                        'campaign_id' => $campaign->id,
                        'recipients_count' => count($recipients),
                        'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                        'delay_seconds' => $delay
                    ]);
                    
                    $successMessage = 'Your SMS campaign has been scheduled for ' . $scheduledAt->format('M d, Y H:i:s') . '. You can track its progress on the campaign details page.';
                } else {
                    \Illuminate\Support\Facades\Log::info('=== NO DELAY NEEDED - IMMEDIATE BULK DISPATCH ===', [
                        'campaign_id' => $campaign->id,
                        'scheduled_at' => $scheduledAt ? $scheduledAt->format('Y-m-d H:i:s') : 'null',
                        'is_after_now' => $scheduledAt ? $scheduledAt->isAfter(now()) : false
                    ]);
                    
                    dispatch($job);
                    
                    \Illuminate\Support\Facades\Log::info('Bulk SMS job dispatched immediately', [
                        'campaign_id' => $campaign->id,
                        'recipients_count' => count($recipients)
                    ]);
                    
                    $successMessage = 'Your SMS campaign is being processed in the background. You can track its progress on the campaign details page.';
                }
            }
            
            // Refresh user to get the latest credit balance
            $user->refresh();
            
            \Illuminate\Support\Facades\Log::info('SMS job dispatched successfully', [
                'campaign_id' => $campaign->id,
                'personal_credits_used' => $creditResult['personal_credits_used'] ?? 0,
                'team_credits_used' => $creditResult['team_credits_used'] ?? 0,
                'user_credits_before' => $creditResult['initial_credits'] ?? 'unknown',
                'user_credits_after' => $user->sms_credits
            ]);
            
            // Redirect with success message
            return redirect()->route('sms.campaign-details', $campaign->id)
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS job dispatch error', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Update campaign status to failed
            $campaign->update([
                'status' => 'failed'
            ]);
            
            return redirect()->route('sms.campaigns')
                ->withErrors(['error' => 'An error occurred while processing your SMS campaign: ' . $e->getMessage()]);
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
        
        // Calculate message pages
        $messageLength = mb_strlen($message);
        $hasUnicode = $this->hasUnicodeCharacters($message);
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

    /**
     * Get campaign status for real-time updates (AJAX endpoint).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getCampaignStatus(int $id): JsonResponse
    {
        $campaign = SmsCampaign::where('user_id', Auth::id())->findOrFail($id);
        
        // Force an update of the campaign metrics before returning
        $campaign->updateMetrics();
        
        // Get the metrics for display
        $metrics = $this->calculateCampaignMetrics($campaign);
        
        // Calculate message pages for cost estimation
        $messageLength = mb_strlen($campaign->message);
        $hasUnicode = $this->hasUnicodeCharacters($campaign->message);
        $parts = $this->calculateMessageParts($messageLength, $hasUnicode);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'status' => $campaign->status,
                'recipients_count' => $campaign->recipients_count,
                'delivered_count' => $campaign->delivered_count,
                'failed_count' => $campaign->failed_count,
                'pending_count' => $metrics['pendingCount'],
                'delivered_percentage' => $metrics['deliveredPercentage'],
                'failed_percentage' => $metrics['failedPercentage'],
                'pending_percentage' => $metrics['pendingPercentage'],
                'credits_used' => $campaign->credits_used ?? ($parts * $campaign->recipients_count),
                'created_at' => $campaign->created_at->format('M d, Y H:i:s'),
                'started_at' => $campaign->started_at?->format('M d, Y H:i:s'),
                'completed_at' => $campaign->completed_at?->format('M d, Y H:i:s'),
                'is_processing' => in_array($campaign->status, ['pending', 'processing']),
                'success_rate' => $campaign->getSuccessRate(),
            ]
        ]);
    }

    /**
     * Get queue status for monitoring (AJAX endpoint).
     *
     * @return JsonResponse
     */
    public function getQueueStatus(): JsonResponse
    {
        try {
            // Get queue statistics from the database
            $pendingJobs = DB::table('jobs')->where('queue', 'sms')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            // Get recent processing campaigns
            $processingCampaigns = SmsCampaign::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'processing'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id', 'name', 'status', 'recipients_count', 'delivered_count', 'created_at']);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pending_jobs' => $pendingJobs,
                    'failed_jobs' => $failedJobs,
                    'processing_campaigns' => $processingCampaigns->map(function ($campaign) {
                        return [
                            'id' => $campaign->id,
                            'name' => $campaign->name,
                            'status' => $campaign->status,
                            'progress' => $campaign->recipients_count > 0 
                                ? round(($campaign->delivered_count / $campaign->recipients_count) * 100, 1)
                                : 0,
                            'created_at' => $campaign->created_at->diffForHumans(),
                        ];
                    }),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get queue status: ' . $e->getMessage()
            ], 500);
        }
    }

}