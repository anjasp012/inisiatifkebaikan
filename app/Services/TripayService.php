<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class TripayService
{
    protected $merchantCode;
    protected $apiKey;
    protected $privateKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->merchantCode = Setting::get('tripay_merchant_code');
        $this->apiKey = Setting::get('tripay_api_key');
        $this->privateKey = Setting::get('tripay_private_key');
        $this->apiUrl = Setting::get('tripay_mode') === 'production'
            ? 'https://tripay.co.id/api/'
            : 'https://tripay.co.id/api-sandbox/';
    }

    public function requestTransaction($params)
    {
        $payload = [
            'method'         => $params['method'],
            'merchant_ref'   => $params['merchant_ref'],
            'amount'         => (int) $params['amount'],
            'customer_name'  => $params['customer_name'],
            'customer_email' => $params['customer_email'],
            'customer_phone' => $params['customer_phone'],
            'order_items'    => [
                [
                    'sku'      => 'DONASI',
                    'name'     => $params['campaign_name'],
                    'price'    => (int) $params['amount'],
                    'quantity' => 1,
                ]
            ],
            'return_url'   => route('donation.instruction', $params['merchant_ref']),
            'callback_url' => url('/api/tripay/callback'),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $this->merchantCode . $params['merchant_ref'] . $params['amount'], $this->privateKey)
        ];

        $response = Http::withToken($this->apiKey)
            ->post($this->apiUrl . 'transaction/create', $payload);

        return $response->json();
    }
}
