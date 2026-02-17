<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Setting;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = Setting::get('midtrans_server_key');
        Config::$isProduction = Setting::get('midtrans_mode') === 'production';
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapLink($params)
    {
        try {
            return Snap::createTransaction($params)->redirect_url;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSnapToken($params)
    {
        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function charge($params)
    {
        try {
            return \Midtrans\CoreApi::charge($params);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Charge Error: ' . $e->getMessage());
            return null;
        }
    }
}
