<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhacenterService
{
    protected $deviceId;
    protected $baseUrl = 'https://app.whacenter.com/api/send';

    public function __construct()
    {
        // Prioritaskan dari Database Setting
        $this->deviceId = \App\Models\Setting::get('whacenter_device_id');
    }

    /**
     * @param string $to Recipient number
     * @param string $message Message content
     * @param int|null $donationId Optional donation ID for logging
     */
    public function sendMessage($to, $message, $donationId = null)
    {
        if (!$this->deviceId) {
            Log::warning('Whacenter: Device ID tidak terkonfigurasi di Settings.');
            return false;
        }

        $logData = [
            'donation_id' => $donationId,
            'type' => 'whatsapp',
            'recipient' => $to,
            'message' => $message,
            'status' => 'failed',
        ];

        try {

            $response = Http::timeout(10)->get($this->baseUrl, [
                'device_id' => $this->deviceId,
                'number' => $to,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                $logData['status'] = 'success';
                $logData['response_data'] = $result;
                \App\Models\NotificationLog::create($logData);

                Log::info("Whacenter: Pesan terkirim ke {$to}", ['response' => $result]);
                return true;
            }

            $logData['error_message'] = $result['message'] ?? 'API merespon error';
            $logData['response_data'] = $result;
            \App\Models\NotificationLog::create($logData);

            Log::error("Whacenter Gagal: API merespon error", [
                'to' => $to,
                'status' => $response->status(),
                'response' => $result
            ]);

            return false;
        } catch (\Exception $e) {
            $logData['error_message'] = $e->getMessage();
            \App\Models\NotificationLog::create($logData);

            Log::error('Whacenter Exception: ' . $e->getMessage(), ['to' => $to]);
            return false;
        }
    }
}
