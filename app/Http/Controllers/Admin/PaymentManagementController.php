<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Payment\PaymentWithCurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentManagementController extends Controller
{
    protected PaymentWithCurrencyService $paymentService;

    public function __construct(PaymentWithCurrencyService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display the payment management dashboard
     */
    public function index(Request $request)
    {
        // Get pending transactions that might need manual verification
        $pendingTransactions = WalletTransaction::with(['user', 'wallet'])
            ->where('status', 'pending')
            ->whereNotNull('metadata->paystack_reference')
            ->when($request->search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('reference', 'like', "%{$search}%")
                ->orWhere('metadata->paystack_reference', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get completed transactions for recent activity
        $recentCompletedTransactions = WalletTransaction::with(['user', 'wallet'])
            ->where('status', 'completed')
            ->whereNotNull('metadata->paystack_reference')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Get statistics
        $stats = [
            'pending_count' => WalletTransaction::where('status', 'pending')->count(),
            'completed_today' => WalletTransaction::where('status', 'completed')
                ->whereDate('updated_at', today())->count(),
            'total_amount_pending' => WalletTransaction::where('status', 'pending')->sum('amount'),
            'total_amount_completed_today' => WalletTransaction::where('status', 'completed')
                ->whereDate('updated_at', today())->sum('amount'),
        ];

        return view('admin.payment-management.index', compact(
            'pendingTransactions', 
            'recentCompletedTransactions', 
            'stats'
        ));
    }

    /**
     * Manual payment confirmation for a specific transaction
     */
    public function confirmPayment(Request $request, $transactionId)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:verify_and_confirm,mark_failed',
            'admin_note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $transaction = WalletTransaction::with(['user', 'wallet'])->findOrFail($transactionId);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'This transaction has already been processed.');
        }

        $admin = Auth::user();
        
        try {
            if ($request->action === 'verify_and_confirm') {
                return $this->verifyAndConfirmPayment($transaction, $admin, $request->admin_note);
            } else {
                return $this->markPaymentFailed($transaction, $admin, $request->admin_note);
            }
        } catch (\Exception $e) {
            Log::error('Admin payment confirmation failed', [
                'transaction_id' => $transaction->id,
                'admin_id' => $admin->id,
                'action' => $request->action,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while processing the payment: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment with Paystack and confirm if successful
     */
    private function verifyAndConfirmPayment(WalletTransaction $transaction, User $admin, ?string $adminNote)
    {
        $paystackReference = $transaction->metadata['paystack_reference'] ?? null;
        
        if (!$paystackReference) {
            return back()->with('error', 'No Paystack reference found for this transaction.');
        }

        try {
            // Verify the payment with Paystack
            $verificationResponse = $this->paymentService->verifyPayment($paystackReference, $transaction->user);
            
            if ($verificationResponse['success'] && 
                isset($verificationResponse['data']['status']) && 
                $verificationResponse['data']['status'] === 'success') {
                
                // Payment is confirmed, process it
                $this->processConfirmedPayment($transaction, $verificationResponse, $admin, $adminNote);
                
                Log::info('Admin manually confirmed payment', [
                    'transaction_id' => $transaction->id,
                    'admin_id' => $admin->id,
                    'user_id' => $transaction->user_id,
                    'amount' => $transaction->amount,
                    'paystack_reference' => $paystackReference,
                ]);

                return back()->with('success', 
                    "Payment confirmed successfully! {$transaction->user->name}'s wallet has been updated.");
                
            } else {
                return back()->with('error', 
                    'Payment verification with Paystack failed. The payment may not have been completed successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Paystack verification failed during admin confirmation', [
                'transaction_id' => $transaction->id,
                'paystack_reference' => $paystackReference,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 
                'Unable to verify payment with Paystack: ' . $e->getMessage());
        }
    }

    /**
     * Mark payment as failed
     */
    private function markPaymentFailed(WalletTransaction $transaction, User $admin, ?string $adminNote)
    {
        DB::transaction(function () use ($transaction, $admin, $adminNote) {
            $transaction->update([
                'status' => 'failed',
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'admin_action' => 'marked_failed',
                    'admin_id' => $admin->id,
                    'admin_note' => $adminNote,
                    'admin_action_at' => now()->toIso8601String(),
                ])
            ]);
        });

        Log::info('Admin marked payment as failed', [
            'transaction_id' => $transaction->id,
            'admin_id' => $admin->id,
            'user_id' => $transaction->user_id,
            'admin_note' => $adminNote,
        ]);

        return back()->with('success', 'Transaction has been marked as failed.');
    }

    /**
     * Process confirmed payment and update user account
     */
    private function processConfirmedPayment(WalletTransaction $transaction, array $verificationResponse, User $admin, ?string $adminNote)
    {
        $paystackTransaction = $verificationResponse['data'];
        $metadata = $paystackTransaction['metadata'] ?? [];
        $amount = $paystackTransaction['amount'] / 100; // Convert from kobo to actual amount
        
        DB::transaction(function () use ($transaction, $paystackTransaction, $amount, $admin, $adminNote) {
            // Update wallet balance
            $wallet = $transaction->user->wallet;
            $wallet->balance += $amount;
            $wallet->save();
            
            // Update transaction status and metadata
            $transaction->update([
                'status' => 'completed',
                'amount' => $amount, // Ensure amount is correct
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'paystack_transaction_id' => $paystackTransaction['id'],
                    'paid_at' => now()->toIso8601String(),
                    'admin_confirmed' => true,
                    'admin_id' => $admin->id,
                    'admin_note' => $adminNote,
                    'admin_confirmation_at' => now()->toIso8601String(),
                ])
            ]);
        });
    }

    /**
     * Show details for a specific transaction
     */
    public function show($transactionId)
    {
        $transaction = WalletTransaction::with(['user', 'wallet'])
            ->findOrFail($transactionId);

        return view('admin.payment-management.show', compact('transaction'));
    }

    /**
     * Bulk action on multiple transactions
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_ids' => 'required|array|min:1',
            'transaction_ids.*' => 'integer|exists:wallet_transactions,id',
            'bulk_action' => 'required|in:verify_and_confirm,mark_failed',
            'bulk_admin_note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $admin = Auth::user();
        $transactionIds = $request->transaction_ids;
        $action = $request->bulk_action;
        $adminNote = $request->bulk_admin_note;

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($transactionIds as $transactionId) {
            try {
                $transaction = WalletTransaction::with(['user', 'wallet'])->find($transactionId);
                
                if (!$transaction || $transaction->status !== 'pending') {
                    $errorCount++;
                    $errors[] = "Transaction ID {$transactionId}: Already processed or not found";
                    continue;
                }

                if ($action === 'verify_and_confirm') {
                    $this->verifyAndConfirmPayment($transaction, $admin, $adminNote);
                } else {
                    $this->markPaymentFailed($transaction, $admin, $adminNote);
                }
                
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Transaction ID {$transactionId}: " . $e->getMessage();
            }
        }

        $message = "Processed {$successCount} transactions successfully.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} failed.";
        }

        if (!empty($errors)) {
            session()->flash('bulk_errors', $errors);
        }

        return back()->with('success', $message);
    }
}
