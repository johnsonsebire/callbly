<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['servicePlan:id,name,description', 'virtualNumber:id,number'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['servicePlan:id,name,description', 'virtualNumber:id,number'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Download invoice for an order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadInvoice($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // Check if invoice exists
        $invoicePath = "invoices/{$order->reference_id}.pdf";
        
        if (!Storage::exists($invoicePath)) {
            // Generate invoice if it doesn't exist
            $this->generateInvoice($order);
            
            // Check again if generation was successful
            if (!Storage::exists($invoicePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice could not be generated'
                ], 500);
            }
        }
        
        return Storage::download($invoicePath, "Callbly_Invoice_{$order->reference_id}.pdf");
    }
    
    /**
     * Generate a PDF invoice for an order.
     *
     * @param  \App\Models\Order  $order
     * @return bool
     */
    private function generateInvoice($order)
    {
        try {
            $user = $order->user;
            $servicePlan = $order->servicePlan;
            
            $data = [
                'order' => $order,
                'user' => $user,
                'servicePlan' => $servicePlan,
                'company' => [
                    'name' => 'Callbly',
                    'address' => '123 Business Avenue',
                    'city' => 'Lagos',
                    'country' => 'Nigeria',
                    'phone' => '+234 800 123 4567',
                    'email' => 'billing@callbly.com',
                ],
                'invoiceDate' => $order->created_at->format('Y-m-d'),
                'dueDate' => $order->created_at->addDays(15)->format('Y-m-d'),
            ];
            
            $pdf = app()->make('dompdf.wrapper');
            $pdf->loadView('invoices.order', $data);
            
            $invoicePath = "invoices/{$order->reference_id}.pdf";
            Storage::put($invoicePath, $pdf->output());
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Invoice generation failed: ' . $e->getMessage());
            return false;
        }
    }
}
