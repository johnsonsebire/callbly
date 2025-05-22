<?php

namespace App\Http\Controllers;

use App\Mail\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    /**
     * Send a new support request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
        ]);
        
        $user = Auth::user();
        
        try {
            // Create support request data
            $supportData = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'priority' => $validated['priority'],
                'submitted_at' => now(),
            ];
            
            // Send email to support
            Mail::to('support@callbly.com')->send(new SupportRequest($supportData));
            
            // Log the support request
            Log::info('Support request submitted', $supportData);
            
            return response()->json([
                'success' => true,
                'message' => 'Your support request has been sent successfully.',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send support request', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send support request. Please try again later.',
            ], 500);
        }
    }
}