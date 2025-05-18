<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UssdService;
use App\Services\UssdService as UssdServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UssdController extends Controller
{
    protected UssdServiceProvider $ussdServiceProvider;

    public function __construct(UssdServiceProvider $ussdServiceProvider)
    {
        $this->ussdServiceProvider = $ussdServiceProvider;
    }

    /**
     * Create a new USSD service.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:20|unique:ussd_services,shortcode',
            'menu_structure' => 'required|json',
            'callback_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $menuStructure = json_decode($request->menu_structure, true);
            
            // Validate the menu structure format
            if (!$this->validateMenuStructure($menuStructure)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid menu structure format'
                ], 422);
            }

            // Create USSD service
            $ussdService = UssdService::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'shortcode' => $request->shortcode,
                'menu_structure' => $menuStructure,
                'status' => 'pending',
                'callback_url' => $request->callback_url,
            ]);

            // Register the service with provider
            $result = $this->ussdServiceProvider->registerService(
                $ussdService->id,
                $request->shortcode,
                $menuStructure,
                $request->callback_url
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'USSD service created successfully and pending approval',
                    'data' => [
                        'service_id' => $ussdService->id,
                        'reference' => $result['reference'],
                        'status' => $ussdService->status,
                    ]
                ]);
            } else {
                // Delete the USSD service if registration failed
                $ussdService->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create USSD service: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('USSD service creation error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating USSD service'
            ], 500);
        }
    }

    /**
     * Get USSD services for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getServices(Request $request): JsonResponse
    {
        $user = $request->user();
        $services = UssdService::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Get details of a specific USSD service.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getServiceDetails(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $service = UssdService::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    /**
     * Update a USSD service.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'menu_structure' => 'sometimes|required|json',
            'callback_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $service = UssdService::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            $updateData = [];

            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }

            if ($request->has('callback_url')) {
                $updateData['callback_url'] = $request->callback_url;
            }

            if ($request->has('menu_structure')) {
                $menuStructure = json_decode($request->menu_structure, true);
                
                // Validate the menu structure format
                if (!$this->validateMenuStructure($menuStructure)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid menu structure format'
                    ], 422);
                }
                
                $updateData['menu_structure'] = $menuStructure;
                $updateData['status'] = 'pending'; // Require re-approval after menu change
                
                // Update with service provider
                $result = $this->ussdServiceProvider->updateService(
                    $service->id,
                    $service->shortcode,
                    $menuStructure,
                    $request->callback_url ?? $service->callback_url
                );
                
                if (!$result['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to update USSD service with provider: ' . $result['message']
                    ], 500);
                }
            }

            $service->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'USSD service updated successfully',
                'data' => $service
            ]);
        } catch (\Exception $e) {
            Log::error('USSD service update error: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating USSD service'
            ], 500);
        }
    }

    /**
     * Get analytics for a USSD service.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getAnalytics(Request $request, int $id): JsonResponse
    {
        try {
            $user = $request->user();
            $service = UssdService::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            // Get analytics from provider
            $result = $this->ussdServiceProvider->getServiceAnalytics($service->id, $service->shortcode);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve analytics: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('USSD analytics error: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving USSD analytics'
            ], 500);
        }
    }

    /**
     * Delete a USSD service.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $user = $request->user();
            $service = UssdService::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            // Delete with provider
            $result = $this->ussdServiceProvider->deleteService($service->id, $service->shortcode);

            if ($result['success']) {
                $service->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'USSD service deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete USSD service: ' . $result['message']
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('USSD service deletion error: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting USSD service'
            ], 500);
        }
    }

    /**
     * Validate the menu structure format.
     *
     * @param array $menuStructure
     * @return bool
     */
    private function validateMenuStructure(array $menuStructure): bool
    {
        // Basic validation: Check if the required fields exist
        if (!isset($menuStructure['welcome_message'])) {
            return false;
        }

        if (!isset($menuStructure['options']) || !is_array($menuStructure['options'])) {
            return false;
        }

        // Validate each menu option
        foreach ($menuStructure['options'] as $option) {
            if (!isset($option['text']) || !isset($option['action'])) {
                return false;
            }

            // If the action is submenu, validate the submenu structure
            if ($option['action'] === 'submenu' && (!isset($option['submenu']) || !is_array($option['submenu']))) {
                return false;
            }
        }

        return true;
    }
}
