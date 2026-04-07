<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EspayCallbackController extends Controller
{
    /**
     * Handle Inquiry Callback from Espay
     * Espay calls this to verify if the Order ID is valid and get the amount.
     */
    public function inquiry(Request $request)
    {
        Log::info('Espay Inquiry Callback:', $request->all());

        $orderId = $request->input('order_id');
        $donation = Donation::where('transaction_id', $orderId)->first();

        if (!$donation) {
            return response()->json([
                'rq_uuid' => $request->input('rq_uuid'),
                'rs_datetime' => now()->format('Y-m-d H:i:s'),
                'error_code' => '0001',
                'error_message' => 'Order ID not found'
            ]);
        }

        // Return the valid donation info to Espay
        return response('res_code=0000&res_msg=Success&order_id=' . $orderId . '&amount=' . (int)$donation->amount . '&ccy=IDR&description=Donasi ' . $donation->campaign->title);
    }

    /**
     * Handle Payment Notification Callback from Espay
     * Espay calls this when the customer has successfully paid.
     */
    public function payment(Request $request)
    {
        Log::info('Espay Payment Callback:', $request->all());

        // Note: For production, you should verify the signature here
        
        $orderId = $request->input('order_id');
        $donation = Donation::where('transaction_id', $orderId)->first();

        if ($donation) {
            $donation->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            Log::info("Donation {$orderId} successfully updated to success via Espay.");
            
            // Trigger any notifications if needed
            // event(new \App\Events\DonationPaid($donation));

            return response('res_code=0000&res_msg=Success');
        }

        return response('res_code=0001&res_msg=Order ID not found');
    }
}
