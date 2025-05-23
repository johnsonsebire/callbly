<?php

namespace App\Http\Controllers;

use App\Http\Requests\VirtualNumber\ConfigureNumberRequest;
use App\Http\Requests\VirtualNumber\PurchaseNumberRequest;
use App\Models\VirtualNumber;
use App\Models\ServicePlan;
use App\Services\VirtualNumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VirtualNumberController extends Controller
{
    /**
     * The virtual number service instance.
     */
    protected VirtualNumberService $virtualNumberService;

    /**
     * Create a new controller instance.
     *
     * @param VirtualNumberService $virtualNumberService
     */
    public function __construct(VirtualNumberService $virtualNumberService)
    {
        $this->virtualNumberService = $virtualNumberService;
    }

    /**
     * Show the virtual numbers dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        $activeNumbers = VirtualNumber::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();
        
        $expiringNumbers = VirtualNumber::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(7))
            ->count();

        $virtualNumbers = VirtualNumber::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('virtual-numbers.index', compact('activeNumbers', 'expiringNumbers', 'virtualNumbers'));
    }

    /**
     * Show the browse virtual numbers page.
     */
    public function browse(): View
    {
        $user = auth()->user();
        $availableNumbers = VirtualNumber::where('status', 'available')
            ->orderBy('monthly_fee', 'asc')
            ->paginate(12);
        
        // Fetch active virtual number plans using defined scopes
        $plans = ServicePlan::ofType('virtual_number')
            ->active()
            ->get();
        
        return view('virtual-numbers.browse', compact('availableNumbers', 'plans'));
    }

    /**
     * Show user's virtual numbers.
     */
    public function myNumbers(): View
    {
        $user = auth()->user();
        $numbers = VirtualNumber::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('virtual-numbers.my-numbers', compact('numbers'));
    }

    /**
     * Get available virtual numbers.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAvailableNumbers(Request $request): JsonResponse
    {
        $country = $request->input('country');
        $type = $request->input('type');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $result = $this->virtualNumberService->getAvailableNumbers($country, $type, $page, $perPage);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }

    /**
     * Purchase a virtual number.
     *
     * @param PurchaseNumberRequest $request
     * @return JsonResponse
     */
    public function purchaseNumber(PurchaseNumberRequest $request): JsonResponse
    {
        $user = Auth::user();
        $number = $request->input('number');
        $options = [
            'forward_to' => $request->input('forward_to'),
            'forward_sms' => $request->boolean('forward_sms'),
            'forward_voice' => $request->boolean('forward_voice'),
            'callback_url' => $request->input('callback_url'),
        ];

        $result = $this->virtualNumberService->purchaseNumber($number, $options);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);
        }

        // Create a record in the database
        VirtualNumber::create([
            'user_id' => $user->id,
            'number' => $number,
            'provider_number_id' => $result['data']['id'] ?? null,
            'status' => 'active',
            'configuration' => json_encode($options),
            'country_code' => $request->input('country_code'),
            'number_type' => $request->input('number_type', 'local'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Virtual number purchased successfully',
            'data' => $result['data'],
        ], 201);
    }

    /**
     * Configure a virtual number.
     *
     * @param ConfigureNumberRequest $request
     * @param VirtualNumber $virtualNumber
     * @return JsonResponse
     */
    public function configureNumber(ConfigureNumberRequest $request, VirtualNumber $virtualNumber): JsonResponse
    {
        // Check if the virtual number belongs to the authenticated user
        if ($virtualNumber->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to configure this number',
            ], 403);
        }

        $config = $request->validated();
        
        $result = $this->virtualNumberService->configureNumber($virtualNumber->provider_number_id, $config);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);
        }

        // Update the configuration in the database
        $virtualNumber->update([
            'configuration' => json_encode($config),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Number configured successfully',
            'data' => $result['data'],
        ]);
    }

    /**
     * Release a virtual number.
     *
     * @param VirtualNumber $virtualNumber
     * @return JsonResponse
     */
    public function releaseNumber(VirtualNumber $virtualNumber): JsonResponse
    {
        // Check if the virtual number belongs to the authenticated user
        if ($virtualNumber->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to release this number',
            ], 403);
        }

        $result = $this->virtualNumberService->releaseNumber($virtualNumber->provider_number_id);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);
        }

        // Update the status in the database
        $virtualNumber->update(['status' => 'released']);

        return response()->json([
            'success' => true,
            'message' => 'Number released successfully',
        ]);
    }

    /**
     * Get usage logs for a virtual number.
     *
     * @param Request $request
     * @param VirtualNumber $virtualNumber
     * @return JsonResponse
     */
    public function getUsageLogs(Request $request, VirtualNumber $virtualNumber): JsonResponse
    {
        // Check if the virtual number belongs to the authenticated user
        if ($virtualNumber->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access this number',
            ], 403);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $result = $this->virtualNumberService->getNumberUsageLogs(
            $virtualNumber->provider_number_id,
            $startDate,
            $endDate,
            $page,
            $perPage
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }

    /**
     * Get virtual numbers for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserNumbers(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 15);
        
        $numbers = VirtualNumber::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $numbers,
        ]);
    }

    /**
     * Show the usage statistics for a virtual number.
     *
     * @param VirtualNumber $virtualNumber
     * @return \Illuminate\View\View
     */
    public function showUsage(VirtualNumber $virtualNumber): View
    {
        if ($virtualNumber->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to view this number\'s usage');
        }
        
        // Get usage statistics for the last 30 days by default
        $startDate = now()->subDays(30);
        $endDate = now();
        
        $usageStats = $this->virtualNumberService->getNumberUsageLogs(
            $virtualNumber->provider_number_id,
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );
        
        return view('virtual-numbers.usage', [
            'number' => $virtualNumber,
            'usageStats' => $usageStats['data'] ?? [],
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}