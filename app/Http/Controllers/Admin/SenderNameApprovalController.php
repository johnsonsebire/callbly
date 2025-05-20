<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SenderName;
use Illuminate\Http\Request;

class SenderNameApprovalController extends Controller
{
    /**
     * Display a listing of sender names with filtering options.
     */
    public function index(Request $request)
    {
        $query = SenderName::with('user');
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Get results with pagination
        $senderNames = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.sender-names.index', compact('senderNames'));
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
    
    /**
     * Delete a sender name.
     */
    public function destroy(SenderName $sender_name)
    {
        $name = $sender_name->name;
        $sender_name->delete();
        
        return redirect()->route('admin.sender-names.index')
            ->with('success', "Sender Name '{$name}' has been deleted successfully.");
    }
}