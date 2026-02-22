<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Donation;
use App\Models\Campaign;

class TripayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Ambil signature dari header
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');

        // 2. Ambil raw body content
        $json = $request->getContent();

        // 3. Ambil private key dari database settings
        $privateKey = \App\Models\Setting::get('tripay_private_key');

        // 4. Generate signature lokal untuk validasi
        $signature = hash_hmac('sha256', $json, $privateKey);

        // 5. Validasi signature
        if ($signature !== $callbackSignature) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid signature - Security Check Failed',
            ], 403); // Forbidden
        }

        // 6. Decode JSON data
        $data = json_decode($json);

        // 7. Ambil event type (misal: payment_status)
        $event = $request->server('HTTP_X_CALLBACK_EVENT');

        if ($event == 'payment_status') {
            $merchantRef = $data->merchant_ref; // ID Transaksi kita (MAN-XXXX)
            $status = strtoupper((string) $data->status); // PAID, EXPIRED, FAILED, REFUND

            \Illuminate\Support\Facades\Log::info("Tripay Callback Received: {$merchantRef}", [
                'status' => $status,
                'payload' => $data
            ]);

            // Cari donasi berdasarkan reference
            $donation = Donation::where('transaction_id', $merchantRef)->first();

            if (!$donation) {
                return Response::json([
                    'success' => false,
                    'message' => 'Donation data not found',
                ], 404);
            }

            // Handle jika status sudah success sebelumnya, jangan diproses lagi
            if ($donation->status == 'success') {
                return Response::json([
                    'success' => true,
                    'message' => 'Payment already processed',
                ]);
            }

            switch ($status) {
                case 'PAID':
                    $donation->update([
                        'status'       => 'success',
                        'paid_at'      => now(),
                        'merchant_fee' => $data->total_fee ?? ($data->fee_merchant ?? 0),
                        'payment_data' => json_encode($data)
                    ]);

                    // Tambah amount ke campaign collected_amount
                    $campaign = Campaign::find($donation->campaign_id);
                    if ($campaign) {
                        $campaign->increment('collected_amount', $donation->amount);
                    }

                    // Kirim Notifikasi Berhasil
                    \App\Models\NotificationTemplate::sendStatusNotification($donation, 'donation-confirmed');
                    break;

                case 'EXPIRED':
                    $donation->update([
                        'status' => 'failed',
                        'payment_data' => json_encode($data)
                    ]);

                    // Kirim Notifikasi Gagal/Batal
                    \App\Models\NotificationTemplate::sendStatusNotification($donation, 'donation-rejected');
                    break;
                case 'FAILED':
                    $donation->update([
                        'status' => 'failed',
                        'payment_data' => json_encode($data)
                    ]);

                    // Kirim Notifikasi Gagal/Batal
                    \App\Models\NotificationTemplate::sendStatusNotification($donation, 'donation-rejected');
                    break;
                case 'REFUND':
                    $donation->update([
                        'status' => 'failed',
                        'payment_data' => json_encode($data)
                    ]);

                    // Kirim Notifikasi Gagal/Batal
                    \App\Models\NotificationTemplate::sendStatusNotification($donation, 'donation-rejected');
                    break;
            }

            return Response::json(['success' => true]);
        }

        return Response::json(['success' => false, 'message' => 'Unrecognized event']);
    }
}
