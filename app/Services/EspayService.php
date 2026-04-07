<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EspayService
{
    protected $merchantCode;
    protected $signatureKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->merchantCode = Setting::get('espay_merchant_code');
        $this->signatureKey = Setting::get('espay_signature_key');
        $this->apiUrl = Setting::get('espay_mode') === 'production'
            ? 'https://api.espay.id/payment/api/'
            : 'https://sandbox-api.espay.id/payment/api/';
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
        
        $signature = hash('sha256', strtoupper($this->merchantCode) . $rqDatetime . $orderId . $this->signatureKey);

        $payload = [
            'rq_uuid' => $rqUuid,
            'rq_datetime' => $rqDatetime,
            'comm_code' => $this->merchantCode,
            'order_id' => $orderId,
            'amount' => (string)$params['amount'],
            'ccy' => 'IDR',
            'comm_fam_id' => $params['comm_fam_id'] ?? '',
            'pay_code' => $params['pay_code'],
            'cust_id' => $params['cust_email'],
            'cust_name' => $params['cust_name'],
            'cust_email' => $params['cust_email'],
            'cust_phone' => $params['cust_phone'],
            'signature' => $signature
        ];

        $response = Http::asForm()->post($this->apiUrl . 'get-va', $payload);

        return $response->json();
    }

    /**
     * Create QRIS (Snap)
     * Documentation: https://docs.espay.id/pembayaran/direct-api/snap/qris/
     */
    public function createQRIS($params)
    {
        $rqUuid = (string) Str::uuid();
        $rqDatetime = now()->format('Y-m-d H:i:s');
        $orderId = $params['order_id'];
        
        $signature = hash('sha256', strtoupper($this->merchantCode) . $rqDatetime . $orderId . $this->signatureKey);

        $payload = [
            'rq_uuid' => $rqUuid,
            'rq_datetime' => $rqDatetime,
            'comm_code' => $this->merchantCode,
            'order_id' => $orderId,
            'amount' => (string)$params['amount'],
            'ccy' => 'IDR',
            'cust_id' => $params['cust_email'],
            'cust_name' => $params['cust_name'],
            'cust_email' => $params['cust_email'],
            'cust_phone' => $params['cust_phone'],
            'signature' => $signature
        ];

        $response = Http::asForm()->post($this->apiUrl . 'snap/qris', $payload);

        return $response->json();
    }

    /**
     * Create E-Wallet (Snap Linkage)
     * Documentation: https://docs.espay.id/pembayaran/direct-api/snap/linkage/
     */
    public function createEwallet($params)
    {
        $rqUuid = (string) Str::uuid();
        $rqDatetime = now()->format('Y-m-d H:i:s');
        $orderId = $params['order_id'];
        
        $signature = hash('sha256', strtoupper($this->merchantCode) . $rqDatetime . $orderId . $this->signatureKey);

        $payload = [
            'rq_uuid' => $rqUuid,
            'rq_datetime' => $rqDatetime,
            'comm_code' => $this->merchantCode,
            'order_id' => $orderId,
            'amount' => (string)$params['amount'],
            'ccy' => 'IDR',
            'pay_code' => $params['pay_code'],
            'cust_id' => $params['cust_email'],
            'cust_name' => $params['cust_name'],
            'cust_email' => $params['cust_email'],
            'cust_phone' => $params['cust_phone'],
            'signature' => $signature
        ];

        $response = Http::asForm()->post($this->apiUrl . 'snap/linkage', $payload);

        return $response->json();
    }
}
