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
use Illuminate\Support\Facades\Validator;

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

            // Check if purchase amount qualifies for tier upgrade
            if ($totalCost >= 1500) {
                app(\App\Services\Currency\CurrencyService::class)
                    ->updateUserBillingTier($user, $totalCost);
            }
            
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
    
    /**
     * Get wallet balance for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalance()
    {
        try {
            $user = auth()->user();
            $wallet = $user->wallet;
            
            // Ensure user has a currency, fallback to GHS
            $currency = $user->currency;
            $currencyCode = $currency->code ?? 'GHS';
            $currencySymbol = $currency->symbol ?? '₵';
            
            return response()->json([
                'success' => true,
                'data' => [
                    'balance' => $wallet->balance ?? 0,
                    'currency' => $currencyCode,
                    'formatted_balance' => $currencySymbol . number_format($wallet->balance ?? 0, 2),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get wallet balance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve wallet balance'
            ], 500);
        }
    }
    
    /**
     * Get wallet transactions for API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions(Request $request)
    {
        try {
            $user = auth()->user();
            $wallet = $user->wallet;
            
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 20);
            
            $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                ->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);
            
            return response()->json([
                'success' => true,
                'data' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'last_page' => $transactions->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get wallet transactions: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve wallet transactions'
            ], 500);
        }
    }
    
    /**
     * Initiate wallet topup for API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiateTopup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:5',
            'payment_method' => 'string|in:card,mobile_money,bank_transfer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $user = auth()->user();
            $amount = $request->amount;
            $paymentMethod = $request->payment_method ?? 'card';
            
            // Generate a unique reference
            $reference = 'WAL_' . Str::uuid()->toString();
            
            // Create metadata for the transaction
            $metadata = [
                'user_id' => $user->id,
                'wallet_id' => $user->wallet->id,
                'product_type' => 'wallet_topup',
                'payment_method' => $paymentMethod,
                'timestamp' => now()->timestamp,
            ];
            
            // Initialize payment with Paystack
            $paymentResponse = $this->paymentService->initializePayment(
                $user,
                $amount,
                $metadata,
                route('payment.verify'),
                'Wallet Top-up'
            );
            
            if (!$paymentResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment initialization failed: ' . 
                        ($paymentResponse['message'] ?? 'Unknown error')
                ], 400);
            }
            
            // Create a pending wallet transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'wallet_id' => $user->wallet->id,
                'type' => 'credit',
                'amount' => $amount,
                'reference' => $reference,
                'description' => 'Wallet top-up via ' . ucfirst($paymentMethod),
                'status' => 'pending',
                'metadata' => [
                    'payment_method' => $paymentMethod,
                    'paystack_reference' => $paymentResponse['reference'],
                ]
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'authorization_url' => $paymentResponse['authorization_url'],
                    'reference' => $reference,
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                ],
                'message' => 'Payment initiated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Wallet top-up API failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request'
            ], 500);
        }
    }

    /**
     * Purchase SMS credits via API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiPurchaseSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credits' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $wallet = $user->wallet;

            // Get SMS rate based on user's tier
            $userTier = $user->billing_tier ? strtolower($user->billing_tier->name) : 'basic';
            $smsRate = config("sms.rate.{$userTier}", config('sms.rate.default'));

            // Calculate total cost
            $totalCost = $request->credits * $smsRate;

            // Validate user has sufficient balance
            if ($wallet->balance < $totalCost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. You need ' . 
                        $user->currency->symbol . number_format($totalCost, 2) . 
                        ' to purchase ' . number_format($request->credits) . ' SMS credits.',
                    'data' => [
                        'required_amount' => $totalCost,
                        'wallet_balance' => $wallet->balance,
                        'shortage' => $totalCost - $wallet->balance
                    ]
                ], 400);
            }

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

            // Check if purchase amount qualifies for tier upgrade
            if ($totalCost >= 1500) {
                app(\App\Services\Currency\CurrencyService::class)
                    ->updateUserBillingTier($user, $totalCost);
            }

            DB::commit();

            // Send SMS purchase invoice email
            $user->notify(new SmsPurchaseInvoiceNotification($transaction));

            return response()->json([
                'success' => true,
                'message' => 'Successfully purchased ' . number_format($request->credits) . 
                    ' SMS credits for ' . $user->currency->symbol . number_format($totalCost, 2),
                'data' => [
                    'credits_purchased' => $request->credits,
                    'amount_charged' => $totalCost,
                    'remaining_balance' => $wallet->balance,
                    'total_sms_credits' => $user->sms_credits,
                    'transaction_reference' => $transaction->reference
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SMS purchase failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your purchase. Please try again later.'
            ], 500);
        }
    }

    /**
     * Purchase USSD credits via API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiPurchaseUssd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credits' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $wallet = $user->wallet;

            // Get USSD rate (you can configure this in config/sms.php or another config file)
            $ussdRate = config('sms.rate.ussd', 0.05); // Default USSD rate

            // Calculate total cost
            $totalCost = $request->credits * $ussdRate;

            // Validate user has sufficient balance
            if ($wallet->balance < $totalCost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. You need ' . 
                        $user->currency->symbol . number_format($totalCost, 2) . 
                        ' to purchase ' . number_format($request->credits) . ' USSD credits.',
                    'data' => [
                        'required_amount' => $totalCost,
                        'wallet_balance' => $wallet->balance,
                        'shortage' => $totalCost - $wallet->balance
                    ]
                ], 400);
            }

            DB::beginTransaction();

            // Create wallet transaction record
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $totalCost,
                'reference' => 'USSD_' . Str::uuid()->toString(),
                'description' => 'Purchase of ' . number_format($request->credits) . ' USSD credits',
                'status' => 'completed',
                'metadata' => [
                    'ussd_credits' => $request->credits,
                    'rate' => $ussdRate,
                ]
            ]);

            // Deduct from wallet balance
            $wallet->balance -= $totalCost;
            $wallet->save();

            // Add USSD credits to user (assuming you have a ussd_credits field)
            $user->ussd_credits = ($user->ussd_credits ?? 0) + $request->credits;
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully purchased ' . number_format($request->credits) . 
                    ' USSD credits for ' . $user->currency->symbol . number_format($totalCost, 2),
                'data' => [
                    'credits_purchased' => $request->credits,
                    'amount_charged' => $totalCost,
                    'remaining_balance' => $wallet->balance,
                    'total_ussd_credits' => $user->ussd_credits,
                    'transaction_reference' => $transaction->reference
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('USSD purchase failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your purchase. Please try again later.'
            ], 500);
        }
    }

    /**
     * Purchase call credits via API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiPurchaseCall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credits' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $wallet = $user->wallet;

            // Get call rate (you can configure this in config/sms.php or another config file)
            $callRate = config('sms.rate.call', 0.08); // Default call rate per minute

            // Calculate total cost
            $totalCost = $request->credits * $callRate;

            // Validate user has sufficient balance
            if ($wallet->balance < $totalCost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. You need ' . 
                        $user->currency->symbol . number_format($totalCost, 2) . 
                        ' to purchase ' . number_format($request->credits) . ' call credits.',
                    'data' => [
                        'required_amount' => $totalCost,
                        'wallet_balance' => $wallet->balance,
                        'shortage' => $totalCost - $wallet->balance
                    ]
                ], 400);
            }

            DB::beginTransaction();

            // Create wallet transaction record
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $totalCost,
                'reference' => 'CALL_' . Str::uuid()->toString(),
                'description' => 'Purchase of ' . number_format($request->credits) . ' call credits',
                'status' => 'completed',
                'metadata' => [
                    'call_credits' => $request->credits,
                    'rate' => $callRate,
                ]
            ]);

            // Deduct from wallet balance
            $wallet->balance -= $totalCost;
            $wallet->save();

            // Add call credits to user (assuming you have a call_credits field)
            $user->call_credits = ($user->call_credits ?? 0) + $request->credits;
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully purchased ' . number_format($request->credits) . 
                    ' call credits for ' . $user->currency->symbol . number_format($totalCost, 2),
                'data' => [
                    'credits_purchased' => $request->credits,
                    'amount_charged' => $totalCost,
                    'remaining_balance' => $wallet->balance,
                    'total_call_credits' => $user->call_credits,
                    'transaction_reference' => $transaction->reference
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Call purchase failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your purchase. Please try again later.'
            ], 500);
        }
    }
}
