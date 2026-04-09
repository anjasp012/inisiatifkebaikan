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
            // Error format: 1;Error Message;;;;;;
            return response('1;Order ID not found;;;;;;');
        }

        // Return the valid donation info to Espay
        // Format: error_code;error_message;order_id;amount;ccy;description;trx_date
        $trxDate = date('d/m/Y H:i:s');
        $desc = 'Donasi ' . $donation->campaign->title;
        $amount = (string)(int)$donation->amount;
        
        $res = "0;Success;{$orderId};{$amount};IDR;{$desc};{$trxDate}";
        return response($res);
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
            
            // Format: error_code;error_message;order_id;amount;ccy;description;trx_date
            $trxDate = date('d/m/Y H:i:s');
            $desc = 'Donasi ' . $donation->campaign->title;
            $amount = (string)(int)$donation->amount;

            return response("0;Success;{$orderId};{$amount};IDR;{$desc};{$trxDate}");
        }

        return response('1;Order ID not found');
    }
}
