<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EspayService
{
    protected $merchantCode;
    protected $signatureKey;
    protected $privateKey;
    protected $baseUrl;
    protected $isProduction;

    public function __construct()
    {
        $this->merchantCode = Setting::get('espay_merchant_code');
        $this->signatureKey = Setting::get('espay_signature_key');
        $this->privateKey = Setting::get('espay_private_key');
        
        $this->isProduction = Setting::get('espay_mode') === 'production';
        $this->baseUrl = $this->isProduction 
            ? 'https://api.espay.id/' 
            : 'https://sandbox-api.espay.id/';
    }

    /**
     * Create Virtual Account (Non-Snap)
     * Documentation: https://docs.espay.id/pembayaran/direct-api/non-snap/virtual-account/
     */
    public function createVA($params)
    {
        $rqUuid = (string) Str::uuid();
        $rqDatetime = now()->format('Y-m-d H:i:s');
        $orderId = $params['order_id'];
        // Espay non-SNAP: amount is integer string (no decimals)
        $amount = (string)(int)$params['amount'];
        $bankCode = $params['pay_code'];
        $ccy = 'IDR';
        $commCode = strtoupper($this->merchantCode);
        
        // Signature Formula: ##signatureKey##rq_uuid##rq_datetime##order_id##amount##ccy##comm_code##SENDINVOICE##
        // Must be UPPERCASE before hashing.
        $stringToSign = "##" . $this->signatureKey . "##" . $rqUuid . "##" . $rqDatetime . "##" . strtoupper($orderId) . "##" . $amount . "##" . $ccy . "##" . $commCode . "##SENDINVOICE##";
        \Illuminate\Support\Facades\Log::info('Espay VA String to Sign:', ['string' => strtoupper($stringToSign)]);
        $signature = hash('sha256', strtoupper($stringToSign));

        $payload = [
            'rq_uuid' => $rqUuid,
            'rq_datetime' => $rqDatetime,
            'comm_code' => $this->merchantCode,
            'order_id' => $orderId,
            'amount' => $amount,
            'ccy' => $ccy,
            'remark1' => $params['cust_phone'],
            'remark2' => $params['cust_name'],
            'remark3' => $params['cust_email'],
            'update' => 'N',
            'bank_code' => $bankCode,
            'va_expired' => 1440, // 24 hours
            'signature' => $signature
        ];

        $url = $this->baseUrl . 'rest/merchantpg/sendinvoice';

        \Illuminate\Support\Facades\Log::info('Espay VA Request:', ['url' => $url, 'payload' => $payload]);
        $response = Http::asForm()->post($url, $payload);
        
        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('Espay VA Response:', $result ?? ['raw' => $response->body()]);

        if (!$result) {
            return [
                'rq_ret_code' => '999',
                'rq_ret_msg' => 'Empty response from Espay'
            ];
        }

        // Normalize: Espay non-SNAP uses numeric error_code (0 = success)
        $errorCode = $result['error_code'] ?? $result['rq_ret_code'] ?? '999';
        $isSuccess = ($errorCode == 0 || $errorCode === '0' || $errorCode === '0000');

        return [
            'rq_ret_code' => $isSuccess ? '0000' : (string)$errorCode,
            'rq_ret_msg' => $result['error_message'] ?? ($result['rq_ret_msg'] ?? 'Unknown error'),
            'va_number' => $result['va_number'] ?? null,
            'pay_code' => $result['va_number'] ?? null,
        ];
    }

    /**
     * Create QRIS (Snap)
     * Documentation: https://docs.espay.id/pembayaran/direct-api/snap/qris/
     */
    public function createQRIS($params)
    {
        // Espay QRIS menggunakan non-SNAP (sendinvoice) seperti VA, bukan BI-SNAP
        // Referensi: https://docs.espay.id
        $rqUuid = (string) Str::uuid();
        $rqDatetime = now()->format('Y-m-d H:i:s');
        $orderId = $params['order_id'];
        $amount = (string)(int)$params['amount'];
        $ccy = 'IDR';
        $commCode = strtoupper($this->merchantCode);

        $stringToSign = "##" . $this->signatureKey . "##" . $rqUuid . "##" . $rqDatetime . "##" . strtoupper($orderId) . "##" . $amount . "##" . $ccy . "##" . $commCode . "##SENDINVOICE##";
        \Illuminate\Support\Facades\Log::info('Espay QRIS String to Sign:', ['string' => strtoupper($stringToSign)]);
        $signature = hash('sha256', strtoupper($stringToSign));

        $payload = [
            'rq_uuid' => $rqUuid,
            'rq_datetime' => $rqDatetime,
            'comm_code' => $this->merchantCode,
            'order_id' => $orderId,
            'amount' => $amount,
            'ccy' => $ccy,
            'remark1' => $params['cust_phone'],
            'remark2' => $params['cust_name'],
            'remark3' => $params['cust_email'],
            'update' => 'N',
            'bank_code' => 'QRIS',
            'va_expired' => 1440,
            'signature' => $signature
        ];

        $url = $this->baseUrl . 'rest/merchantpg/sendinvoice';

        \Illuminate\Support\Facades\Log::info('Espay QRIS Request:', ['url' => $url, 'payload' => $payload]);
        $response = Http::asForm()->post($url, $payload);

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('Espay QRIS Response:', $result ?? ['raw' => $response->body()]);

        if (!$result) {
            return ['rq_ret_code' => '999', 'rq_ret_msg' => 'Empty response from Espay QRIS'];
        }

        $errorCode = $result['error_code'] ?? $result['rq_ret_code'] ?? '999';
        $isSuccess = ($errorCode == 0 || $errorCode === '0' || $errorCode === '0000');

        if ($isSuccess) {
            $qrData = $result['qr_string'] ?? $result['qr_content'] ?? $result['va_number'] ?? null;
            return [
                'rq_ret_code' => '0000',
                'rq_ret_msg' => 'Success',
                'qr_data' => $qrData,
                'pay_code' => $qrData,
            ];
        }

        return [
            'rq_ret_code' => (string)$errorCode,
            'rq_ret_msg' => $result['error_message'] ?? ($result['rq_ret_msg'] ?? 'Unknown QRIS error')
        ];
    }

    /**
     * Create E-Wallet (Snap Linkage)
     * Documentation: https://docs.espay.id/pembayaran/direct-api/snap/linkage/
     */
    public function createEwallet($params)
    {
        // For simplicity, using QRIS logic as placeholder until exact Linkage URL is used
        // But the user specifically wants Linkage.
        // Linkage typically needs registration first.
        return [
            'rq_ret_code' => '99',
            'rq_ret_msg' => 'E-Wallet Linkage requires extra setup. Use QRIS or VA for now.'
        ];
    }

    // Method ini tidak lagi digunakan (QRIS sekarang pakai non-SNAP)
    // Tetap ada untuk referensi / keperluan masa depan
    protected function generateSnapSignature($method, $path, $body, $timestamp)
    {
        if (!$this->privateKey) {
            \Illuminate\Support\Facades\Log::warning('Espay: MISSING_PRIVATE_KEY for SNAP signature');
            return 'MISSING_PRIVATE_KEY';
        }

        $hashedBody = strtolower(hash('sha256', json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)));
        $stringToSign = $method . ':' . $path . ':' . ':' . $hashedBody . ':' . $timestamp;

        $binarySignature = '';
        $result = openssl_sign($stringToSign, $binarySignature, $this->privateKey, OPENSSL_ALGO_SHA256);

        if (!$result) {
            \Illuminate\Support\Facades\Log::error('Espay: openssl_sign failed. Key may be invalid.', [
                'openssl_error' => openssl_error_string()
            ]);
            return 'SIGN_FAILED';
        }

        return base64_encode($binarySignature);
    }
}
