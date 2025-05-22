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
        $senderNames = $user->senderNames()->where('status', 'approved')->get();
        $contactGroups = $user->contactGroups()->withCount('contacts')->get();
        $templates = $user->smsTemplates()->latest()->get();
        $totalContactsCount = $user->contacts()->count();
        
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

        $messageLength = mb_strlen($campaign->message);
        $hasUnicode = preg_match('/[\x{0080}-\x{FFFF}]/u', $campaign->message);
        
        $parts = $this->calculateMessageParts($messageLength, $hasUnicode);

        $metrics = $this->calculateCampaignMetrics($campaign);
        $totalCreditsUsed = $parts * $metrics['totalRecipients'];

        $campaign->update([
            'recipients_count' => $metrics['totalRecipients'],
            'delivered_count' => $metrics['deliveredCount'],
            'failed_count' => $metrics['failedCount'],
            'credits_used' => $totalCreditsUsed
        ]);

        $recipients = $campaign->recipients()
            ->when(request('status'), fn($query, $status) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

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
            'totalCreditsUsed' => $totalCreditsUsed,
            'parts' => $parts
        ]);
    }

    public function credits(): View
    {
        $user = Auth::user();
        $smsCredits = $user->smsCredits ?? 0;
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

    protected function replaceTemplateVariables(string $message, array $variables): string
    {
        return str_replace(
            array_map(fn($key) => '{'.$key.'}', array_keys($variables)),
            array_values($variables),
            $message
        );
    }
}