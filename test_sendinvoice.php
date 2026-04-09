<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = new App\Services\EspayService();
$params = [
    'order_id' => 'TEST-' . time(),
    'amount' => 100000,
    'pay_code' => '014', // We know the user DB now outputs 014 directly
    'cust_phone' => '081234567890',
    'cust_name' => 'Tester',
    'cust_email' => 'test@example.com'
];
$resp = $service->createVA($params);
print_r($resp);
