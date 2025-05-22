<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }
    
    /**
     * Register a device for push notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'device_name' => 'required|string',
            'device_token' => 'required|string',
            'platform' => 'required|string|in:ios,android',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = auth()->user();
        
        // Update or create device record
        $device = UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_id' => $request->device_id,
            ],
            [
                'device_name' => $request->device_name,
                'device_token' => $request->device_token,
                'platform' => $request->platform,
                'notification_enabled' => true,
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Device registered successfully',
            'data' => [
                'device' => $device
            ]
        ]);
    }
    
    /**
     * Update notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'settings' => 'required|array',
            'settings.promotional' => 'boolean',
            'settings.transactional' => 'boolean',
            'settings.account' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = auth()->user();
        
        // Find the device
        $device = UserDevice::where('user_id', $user->id)
            ->where('device_id', $request->device_id)
            ->first();
            
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }
        
        // Update notification settings
        $device->notification_settings = $request->settings;
        $device->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully',
            'data' => [
                'settings' => $device->notification_settings
            ]
        ]);
    }
    
    /**
     * Mark notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:user_notifications,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = auth()->user();
        
        // Mark notifications as read
        UserNotification::where('user_id', $user->id)
            ->whereIn('id', $request->notification_ids)
            ->update(['read_at' => now()]);
            
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }
}
