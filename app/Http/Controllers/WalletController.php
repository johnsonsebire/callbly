<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\SmsPurchaseInvoiceNotification;
use App\Services\Payment\PaymentWithCurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    protected PaymentWithCurrencyService $paymentService;

    public function __construct(PaymentWithCurrencyService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display the wallet dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('wallet.index', compact('wallet', 'transactions', 'user'));
    }
    
    /**
     * Show the wallet top-up form.
     *
     * @return \Illuminate\View\View
     */
    public function showTopupForm()
    {
        $user = Auth::user();
        
        return view('wallet.topup', [
            'user' => $user
        ]);
    }
    
    /**
     * Process the wallet top-up.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5',
            'payment_method' => 'required|in:card,mobile_money,bank_transfer',
        ]);
        
        $user = Auth::user();
        
        try {
            // Generate a unique reference
            $reference = 'WAL_' . Str::uuid()->toString();
            
            // Create metadata for the transaction
            $metadata = [
                'user_id' => $user->id,
                'wallet_id' => $user->wallet->id,
                'product_type' => 'wallet_topup',
                'payment_method' => $request->payment_method,
                'timestamp' => now()->timestamp,
            ];
            
            // Initialize payment with Paystack
            $paymentResponse = $this->paymentService->initializePayment(
                $user,
                $request->amount,
                $metadata,
                route('payment.verify'),
                'Wallet Top-up'
            );
            
            if (!$paymentResponse['success']) {
                return back()->withErrors(['error' => 'Payment initialization failed: ' . 
                    ($paymentResponse['message'] ?? 'Unknown error')]);
            }
            
            // Create a pending wallet transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'wallet_id' => $user->wallet->id,
                'type' => 'credit',
                'amount' => $request->amount,
                'reference' => $reference,
                'description' => 'Wallet top-up via ' . ucfirst($request->payment_method),
                'status' => 'pending',
                'metadata' => [
                    'payment_method' => $request->payment_method,
                    'paystack_reference' => $paymentResponse['reference'],
                ]
            ]);
            
            // Store payment reference in session
            session(['payment_reference' => $paymentResponse['reference']]);
            
            // Redirect to Paystack payment page
            return redirect($paymentResponse['authorization_url']);
            
        } catch (\Exception $e) {
            Log::error('Wallet top-up failed: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'An error occurred while processing your payment. Please try again later.']);
        }
    }
    
    /**
     * Show the SMS purchase form.
     *
     * @return \Illuminate\View\View
     */
    public function showPurchaseSmsForm()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        // Get SMS rate based on user's tier
        $userTier = $user->billing_tier ? strtolower($user->billing_tier->name) : 'basic';
        
        // Get tier-specific rate or default to basic tier rate (0.035)
        $smsRate = config("sms.rate.{$userTier}", config('sms.rate.default'));
        
        // Calculate how many SMS credits the user can purchase with their wallet balance
        $estimatedCredits = floor($wallet->balance / $smsRate);
        
        return view('wallet.purchase-sms', [
            'user' => $user,
            'wallet' => $wallet,
            'smsRate' => $smsRate,
            'estimatedCredits' => $estimatedCredits
        ]);
    }
    
    /**
     * Process the SMS credits purchase.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPurchaseSms(Request $request)
    {
        $request->validate([
            'credits' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;
        
        // Get SMS rate based on user's tier
        $userTier = $user->billing_tier ? strtolower($user->billing_tier->name) : 'basic';
        
        // Get tier-specific rate or default to basic tier rate (0.035)
        $smsRate = config("sms.rate.{$userTier}", config('sms.rate.default'));
        
        // Calculate total cost
        $totalCost = $request->credits * $smsRate;
        
        // Validate user has sufficient balance
        if ($wallet->balance < $totalCost) {
            return back()->withErrors([
                'error' => 'Insufficient wallet balance. You need ' . 
                    $user->currency->symbol . number_format($totalCost, 2) . 
                    ' to purchase ' . number_format($request->credits) . ' SMS credits.'
            ]);
        }
        
        try {
            DB::beginTransaction();
            
            // Create wallet transaction record
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $totalCost,
                'reference' => 'SMS_' . Str::uuid()->toString(),
                'description' => 'Purchase of ' . number_format($request->credits) . ' SMS credits',
                'status' => 'completed',
                'metadata' => [
                    'sms_credits' => $request->credits,
                    'rate' => $smsRate,
                    'tier' => $userTier,
                ]
            ]);
            
            // Deduct from wallet balance
            $wallet->balance -= $totalCost;
            $wallet->save();
            
            // Add SMS credits to user
            $user->sms_credits += $request->credits;
            $user->save();
            
            DB::commit();
            
            // Send SMS purchase invoice email
            $user->notify(new SmsPurchaseInvoiceNotification($transaction));
            
            return redirect()->route('wallet.index')
                ->with('success', 'Successfully purchased ' . number_format($request->credits) . 
                    ' SMS credits for ' . $user->currency->symbol . number_format($totalCost, 2));
                    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SMS purchase failed: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'An error occurred while processing your purchase. Please try again later.'
            ]);
        }
    }
}
