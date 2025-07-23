<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display pending and failed payment transactions
     */
    public function index(Request $request)
    {
        $query = WalletTransaction::with(['user', 'wallet'])
            ->whereIn('status', ['pending', 'failed'])
            ->where('type', 'credit')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['pending', 'failed'])) {
            $query->where('status', $request->status);
        }

        // Search by user email or reference
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%")
                               ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        return view('admin.payments.index', compact('transactions'));
    }

    /**
     * Show details of a specific transaction
     */
    public function show($id)
    {
        $transaction = WalletTransaction::with(['user', 'wallet'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('transaction'));
    }

    /**
     * Manually confirm a payment transaction
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'confirmation_note' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $transaction = WalletTransaction::findOrFail($id);

            // Check if transaction is eligible for confirmation
            if (!in_array($transaction->status, ['pending', 'failed'])) {
                return redirect()->back()->with('error', 'Transaction cannot be confirmed. Current status: ' . $transaction->status);
            }

            if ($transaction->type !== 'credit') {
                return redirect()->back()->with('error', 'Only credit transactions can be manually confirmed.');
            }

            // Update transaction status
            $transaction->status = 'completed';
            
            // Add admin confirmation metadata
            $metadata = $transaction->metadata ?? [];
            $metadata['admin_confirmed'] = true;
            $metadata['admin_id'] = Auth::id();
            $metadata['admin_confirmation_date'] = now()->toISOString();
            $metadata['confirmation_note'] = $request->confirmation_note;
            $transaction->metadata = $metadata;
            
            $transaction->save();

            // Update wallet balance
            $wallet = $transaction->wallet;
            $wallet->balance += $transaction->amount;
            $wallet->save();

            // Log the admin action
            Log::info('Admin payment confirmation', [
                'admin_id' => Auth::id(),
                'admin_email' => Auth::user()->email,
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'reference' => $transaction->reference,
                'confirmation_note' => $request->confirmation_note
            ]);

            DB::commit();

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment confirmed successfully. Wallet balance updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin payment confirmation failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Failed to confirm payment: ' . $e->getMessage());
        }
    }

    /**
     * Reject a payment transaction
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $transaction = WalletTransaction::findOrFail($id);

            // Check if transaction is eligible for rejection
            if (!in_array($transaction->status, ['pending', 'failed'])) {
                return redirect()->back()->with('error', 'Transaction cannot be rejected. Current status: ' . $transaction->status);
            }

            // Update transaction status
            $transaction->status = 'failed';
            
            // Add admin rejection metadata
            $metadata = $transaction->metadata ?? [];
            $metadata['admin_rejected'] = true;
            $metadata['admin_id'] = Auth::id();
            $metadata['admin_rejection_date'] = now()->toISOString();
            $metadata['rejection_reason'] = $request->rejection_reason;
            $transaction->metadata = $metadata;
            
            $transaction->save();

            // Log the admin action
            Log::info('Admin payment rejection', [
                'admin_id' => Auth::id(),
                'admin_email' => Auth::user()->email,
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'reference' => $transaction->reference,
                'rejection_reason' => $request->rejection_reason
            ]);

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment rejected successfully.');

        } catch (\Exception $e) {
            Log::error('Admin payment rejection failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Failed to reject payment: ' . $e->getMessage());
        }
    }

    /**
     * Get payment statistics for dashboard
     */
    public function stats()
    {
        $stats = [
            'pending_count' => WalletTransaction::where('status', 'pending')
                ->where('type', 'credit')
                ->count(),
            'pending_amount' => WalletTransaction::where('status', 'pending')
                ->where('type', 'credit')
                ->sum('amount'),
            'failed_count' => WalletTransaction::where('status', 'failed')
                ->where('type', 'credit')
                ->count(),
            'today_confirmed' => WalletTransaction::whereDate('created_at', today())
                ->where('status', 'completed')
                ->where('type', 'credit')
                ->whereJsonContains('metadata->admin_confirmed', true)
                ->count(),
        ];

        return response()->json($stats);
    }
}
