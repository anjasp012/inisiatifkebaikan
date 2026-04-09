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
        Log::info('Espay Inquiry Callback (SNAP):', $request->all());

        // Get Order ID from virtualAccountNo (SNAP format)
        $orderId = $request->input('virtualAccountNo');
        $donation = Donation::with('campaign')->where('transaction_id', $orderId)->first();

        if (!$donation) {
            return response()->json([
                'responseCode' => '4042400',
                'responseMessage' => 'Order Not Found'
            ]);
        }

        $amount = number_format($donation->amount, 2, '.', '');
        $campaignTitle = $donation->campaign ? $donation->campaign->title : 'Donasi';

        return response()->json([
            'responseCode' => '2002400',
            'responseMessage' => 'Success',
            'virtualAccountData' => [
                'partnerServiceId' => 'SGWINISIATIFKEBAIKAN', // Should be dynamic
                'customerNo' => $donation->phone ?? '08000000000',
                'virtualAccountNo' => $orderId,
                'virtualAccountName' => $donation->name ?? 'Donatur Inisiatif',
                'totalAmount' => [
                    'value' => $amount,
                    'currency' => 'IDR'
                ],
                'billDetails' => [
                    [
                        'billDescription' => [
                            'english' => 'Donation for ' . $campaignTitle,
                            'indonesia' => 'Donasi untuk ' . $campaignTitle
                        ]
                    ]
                ],
                'inquiryRequestId' => $request->input('inquiryRequestId') ?? uniqid()
            ]
        ]);
    }

    /**
     * Handle Payment Notification Callback from Espay
     */
    public function payment(Request $request)
    {
        Log::info('Espay Payment Callback (SNAP):', $request->all());

        $orderId = $request->input('virtualAccountNo') ?? $request->input('order_id');
        $donation = Donation::where('transaction_id', $orderId)->first();

        if ($donation) {
            $donation->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            Log::info("Donation {$orderId} successfully updated via SNAP Callback.");
            
            return response()->json([
                'responseCode' => '2002500', // Success code for payment notification
                'responseMessage' => 'Success'
            ]);
        }

        return response()->json([
            'responseCode' => '4042500',
            'responseMessage' => 'Order Not Found'
        ]);
    }
}
