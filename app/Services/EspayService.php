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
        $amount = (string)$params['amount'];
        $bankCode = $params['pay_code'];
        $ccy = 'IDR';
        
        // Correct Signature Formula: ##signatureKey##rq_uuid##rq_datetime##order_id##amount##ccy##comm_code##SENDINVOICE##
        // Must be UPPERCASE before hashing.
        $stringToSign = "##" . $this->signatureKey . "##" . $rqUuid . "##" . $rqDatetime . "##" . $orderId . "##" . $amount . "##" . $ccy . "##" . strtoupper($this->merchantCode) . "##SENDINVOICE##";
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

        // Normalize response keys to match frontend expectations
        return [
            'rq_ret_code' => $result['error_code'] ?? ($result['rq_ret_code'] ?? '999'),
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
        $timestamp = now()->toIso8601String();
        $externalId = (string) Str::uuid();
        
        $body = [
            'partnerReferenceNo' => $params['order_id'],
            'merchantId' => $this->merchantCode,
            'amount' => [
                'value' => number_format($params['amount'], 2, '.', ''),
                'currency' => 'IDR'
            ],
            'additionalInfo' => [
                'productCode' => 'QRIS'
            ],
            'validityPeriod' => now()->addDay()->toIso8601String()
        ];

        // RSA Signature for SNAP
        $signature = $this->generateSnapSignature('POST', '/api/v1.0/qr/qr-mpm-generate', $body, $timestamp);

        $url = $this->baseUrl . 'api/v1.0/qr/qr-mpm-generate';

        \Illuminate\Support\Facades\Log::info('Espay QRIS Request:', ['url' => $url, 'body' => $body]);

        $response = Http::withHeaders([
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'X-PARTNER-ID' => $this->merchantCode,
            'X-EXTERNAL-ID' => $externalId,
            'CHANNEL-ID' => 'ESPAY',
            'Content-Type' => 'application/json'
        ])->post($url, $body);

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('Espay QRIS Response:', $result ?? ['raw' => $response->body()]);

        if (isset($result['responseCode']) && $result['responseCode'] === '2004700') {
            return [
                'rq_ret_code' => '0000',
                'rq_ret_msg' => 'Success',
                'qr_data' => $result['qrContent'] ?? ($result['qrImage'] ?? null),
            ];
        }

        return [
            'rq_ret_code' => $result['responseCode'] ?? '999',
            'rq_ret_msg' => $result['responseMessage'] ?? 'Unknown SNAP error'
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

    protected function generateSnapSignature($method, $path, $body, $timestamp)
    {
        if (!$this->privateKey) {
            return 'MISSING_PRIVATE_KEY';
        }

        // BI SNAP Standard String to Sign for Transactions
        // HTTPMethod + ":" + EndpointUrl + ":" + AccessToken + ":" + Lowercase(HexEncode(SHA-256(RequestBody))) + ":" + Timestamp
        
        $hashedBody = strtolower(hash('sha256', json_encode($body, JSON_UNESCAPED_SLASHES)));
        $stringToSign = $method . ':' . $path . '::' . $hashedBody . ':' . $timestamp;

        $binarySignature = '';
        openssl_sign($stringToSign, $binarySignature, $this->privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($binarySignature);
    }
}
