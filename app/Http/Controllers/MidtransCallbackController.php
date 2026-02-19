<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $data = $request->all();

            // Log payload for auditing
            Log::info("Midtrans Callback Received", ['payload' => $data]);

            // Data extraction
            $orderId = $data['order_id'] ?? null;
            $statusCode = $data['status_code'] ?? null;
            $grossAmount = $data['gross_amount'] ?? null;
            $signatureKey = $data['signature_key'] ?? null;
            $transactionStatus = $data['transaction_status'] ?? null;
            $paymentType = $data['payment_type'] ?? null;
            $fraudStatus = $data['fraud_status'] ?? null;

            if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
                return response()->json(['message' => 'Invalid payload'], 400);
            }

            // Signature Validation
            $serverKey = Setting::get('midtrans_server_key');
            $localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $localSignature) {
                Log::error("Midtrans Callback: Signature Mismatch", [
                    'order_id' => $orderId,
                    'received' => $signatureKey,
                    'expected' => $localSignature
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Find donation
            $donation = Donation::where('transaction_id', $orderId)->first();

            if (!$donation) {
                // If it's a test notification from Midtrans dashboard, it might have a fake order_id
                if (str_contains($orderId, 'test') || str_contains($orderId, 'G509')) {
                    return response()->json(['message' => 'Success (Test Notification)']);
                }

                Log::warning("Midtrans Callback: Donation not found for order_id={$orderId}");
                return response()->json(['message' => 'Donation not found'], 404);
            }

            // Handle Status
            DB::transaction(function () use ($donation, $transactionStatus, $paymentType, $fraudStatus) {
                if ($donation->status === 'success') {
                    return;
                }

                if ($transactionStatus == 'capture') {
                    if ($paymentType == 'credit_card') {
                        if ($fraudStatus == 'challenge') {
                            $donation->update(['status' => 'pending']);
                        } else {
                            $this->markAsSuccess($donation, $paymentType);
                        }
                    }
                } else if ($transactionStatus == 'settlement') {
                    $this->markAsSuccess($donation, $paymentType);
                } else if ($transactionStatus == 'pending') {
                    $donation->update(['status' => 'pending']);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    $donation->update(['status' => 'failed']);

                    // Kirim Notifikasi Gagal/Batal
                    \App\Models\NotificationTemplate::sendStatusNotification($donation, 'donation-rejected');
                }
            });

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all()
            ]);
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function markAsSuccess($donation, $paymentType = null)
    {
        if ($donation->status !== 'success') {
            $merchantFee = 0;
            $amount = $donation->amount;

            // Fee Calculation
            if (in_array($paymentType, ['bank_transfer'])) {
                $merchantFee = 4000;
            } elseif ($paymentType === 'shopeepay') {
                $merchantFee = $amount * 0.02;
            } elseif ($paymentType === 'gopay') {
                $merchantFee = $amount * 0.02;
            } elseif ($paymentType === 'qris') {
                $merchantFee = $amount * 0.007;
            } elseif ($paymentType === 'dana') {
                $merchantFee = $amount * 0.015;
            }

            $donation->update([
                'status' => 'success',
                'paid_at' => now(),
                'merchant_fee' => $merchantFee,
            ]);

            if ($donation->campaign_id) {
                DB::table('campaigns')
                    ->where('id', $donation->campaign_id)
                    ->increment('collected_amount', $donation->amount);
            }

            // Kirim Notifikasi Berhasil
            \App\Models\NotificationTemplate::sendStatusNotification($donation, 'donation-confirmed');
        }
    }
}
