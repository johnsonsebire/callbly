<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SenderName;
use Illuminate\Http\Request;

class SenderNameApprovalController extends Controller
{
    /**
     * Display a listing of pending sender names.
     */
    public function index()
    {
        $pending = SenderName::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.sender-names.index', compact('pending'));
    }

    /**
     * Update the status of a sender name (approve or reject).
     */
    public function update(Request $request, SenderName $sender_name)
    {
        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $sender_name->status = $data['status'];
        if ($data['status'] === 'approved') {
            $sender_name->approved_at = now();
            $sender_name->rejection_reason = null;
        } else {
            $sender_name->rejection_reason = $data['rejection_reason'] ?? 'No reason provided';
        }
        $sender_name->save();

        return redirect()->route('admin.sender-names.index')
            ->with('success', 'Sender Name ' . ucfirst($data['status']) . ' successfully.');
    }
}