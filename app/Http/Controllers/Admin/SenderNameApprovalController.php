<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SenderName;
use App\Models\User;
use App\Services\SenderNameWhitelistPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SenderNameApprovalController extends Controller
{
    /**
     * Display a listing of sender names for approval
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = SenderName::with('user')->orderBy('created_at', 'desc');
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $senderNames = $query->paginate(15)
            ->withQueryString();
            
        // Get all users for the "Add Sender Name for User" feature
        $users = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'super-admin');
            })
            ->orderBy('name')
            ->get();
            
        return view('admin.sender-names.index', compact('senderNames', 'users'));
    }
    
    /**
     * Update the sender name status (approve/reject) or edit the sender name
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $senderName = SenderName::findOrFail($id);
        
        // Check if we're in edit mode (updating the sender name itself)
        if ($request->has('edit_mode')) {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:11',
                    'alpha_num',
                    Rule::unique('sender_names', 'name')
                        ->where(function ($query) use ($request, $senderName) {
                            return $query->where('user_id', $senderName->user_id);
                        })
                        ->ignore($senderName->id),
                ],
            ]);
            
            try {
                // Update the sender name preserving the case as entered by user
                $senderName->update([
                    'name' => $validated['name'], // Preserve case exactly as entered
                ]);
                
                Log::info('Sender name edited', [
                    'admin_id' => Auth::id(),
                    'sender_name_id' => $senderName->id,
                    'user_id' => $senderName->user_id,
                    'old_name' => $senderName->getOriginal('name'),
                    'new_name' => $validated['name'],
                ]);
                
                return redirect()->route('admin.sender-names.index')
                    ->with('success', "Sender name has been updated successfully to '{$validated['name']}'.");
            } catch (\Exception $e) {
                Log::error('Error editing sender name', [
                    'error' => $e->getMessage(),
                    'sender_name_id' => $id,
                ]);
                
                return back()->with('error', 'An error occurred while updating the sender name.');
            }
        }
        
        // Regular status update (approve/reject)
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'rejection_reason' => 'required_if:status,rejected',
        ]);
        
        try {
            if ($validated['status'] === 'approved') {
                $senderName->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => Auth::id(),
                ]);
                
                Log::info('Sender name approved', [
                    'admin_id' => Auth::id(),
                    'sender_name_id' => $senderName->id,
                    'user_id' => $senderName->user_id,
                ]);
                
                return redirect()->route('admin.sender-names.index')
                    ->with('success', "Sender name '{$senderName->name}' has been approved successfully.");
                    
            } else {
                $senderName->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                ]);
                
                Log::info('Sender name rejected', [
                    'admin_id' => Auth::id(),
                    'sender_name_id' => $senderName->id,
                    'user_id' => $senderName->user_id,
                    'reason' => $validated['rejection_reason'],
                ]);
                
                return redirect()->route('admin.sender-names.index')
                    ->with('success', "Sender name '{$senderName->name}' has been rejected.");
            }
        } catch (\Exception $e) {
            Log::error('Error updating sender name', [
                'error' => $e->getMessage(),
                'sender_name_id' => $id,
            ]);
            
            return back()->with('error', 'An error occurred while updating the sender name.');
        }
    }
    
    /**
     * Remove a sender name
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $senderName = SenderName::findOrFail($id);
        
        try {
            $name = $senderName->name;
            $senderName->delete();
            
            Log::info('Sender name deleted', [
                'admin_id' => Auth::id(),
                'sender_name_id' => $id,
                'user_id' => $senderName->user_id,
            ]);
            
            return redirect()->route('admin.sender-names.index')
                ->with('success', "Sender name '{$name}' has been deleted successfully.");
                
        } catch (\Exception $e) {
            Log::error('Error deleting sender name', [
                'error' => $e->getMessage(),
                'sender_name_id' => $id,
            ]);
            
            return back()->with('error', 'An error occurred while deleting the sender name.');
        }
    }
    
    /**
     * Create a new sender name on behalf of a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => [
                'required',
                'string',
                'max:11',
                'alpha_num',
                Rule::unique('sender_names', 'name')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                }),
            ],
            'auto_approve' => 'sometimes',
        ]);
        
        try {
            $user = User::findOrFail($validated['user_id']);
            
            $senderName = new SenderName();
            $senderName->name = $validated['name']; // Preserve case exactly as entered
            $senderName->user_id = $user->id;
            
            // Auto-approve the sender name if selected
            if ($request->has('auto_approve')) {
                $senderName->status = 'approved';
                $senderName->approved_at = now();
                $senderName->approved_by = Auth::id();
            } else {
                $senderName->status = 'pending';
            }
            
            $senderName->save();
            
            Log::info('Sender name created for user by admin', [
                'admin_id' => Auth::id(),
                'sender_name' => $senderName->name,
                'user_id' => $user->id,
                'auto_approved' => $request->has('auto_approve'),
            ]);
            
            return redirect()->route('admin.sender-names.index')
                ->with('success', "Sender name '{$senderName->name}' has been created for {$user->name}" . 
                ($request->has('auto_approve') ? " and automatically approved" : ""));
                
        } catch (\Exception $e) {
            Log::error('Error creating sender name for user', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id ?? null,
                'name' => $request->name ?? null,
            ]);
            
            return back()->with('error', 'An error occurred while creating the sender name: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Download PDF whitelist request for a sender name
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadWhitelistPdf($id)
    {
        $senderName = SenderName::with('user')->findOrFail($id);
        
        try {
            $pdfService = new SenderNameWhitelistPdfService();
            return $pdfService->downloadWhitelistRequestPdf($senderName);
            
        } catch (\Exception $e) {
            Log::error('Error generating PDF for sender name', [
                'error' => $e->getMessage(),
                'sender_name_id' => $id,
                'admin_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'An error occurred while generating the PDF document.');
        }
    }
    
    /**
     * Send whitelist request email for a sender name
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendWhitelistRequest($id)
    {
        $senderName = SenderName::with('user')->findOrFail($id);
        
        try {
            $pdfService = new SenderNameWhitelistPdfService();
            $sent = $pdfService->sendWhitelistRequestIfEnabled($senderName);
            
            if ($sent) {
                Log::info('Whitelist request email sent manually', [
                    'sender_name_id' => $id,
                    'admin_id' => Auth::id(),
                ]);
                
                return back()->with('success', "Whitelist request email sent successfully for sender name '{$senderName->sender_name}'.");
            } else {
                return back()->with('error', 'Auto-send is not enabled or email addresses are not configured. Please check system settings.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error sending whitelist request email', [
                'error' => $e->getMessage(),
                'sender_name_id' => $id,
                'admin_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'An error occurred while sending the whitelist request email.');
        }
    }
    
    /**
     * Preview the PDF whitelist request in browser
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function previewWhitelistPdf($id)
    {
        $senderName = SenderName::with('user')->findOrFail($id);
        
        try {
            $pdfService = new SenderNameWhitelistPdfService();
            $data = $pdfService->preparePdfData($senderName);
            
            return view('pdfs.sender-name-whitelist-request', $data);
            
        } catch (\Exception $e) {
            Log::error('Error previewing PDF for sender name', [
                'error' => $e->getMessage(),
                'sender_name_id' => $id,
                'admin_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'An error occurred while previewing the PDF document.');
        }
    }
}