<?php
$ch = curl_init('https://inisiatifkebaikan.org/api/espay/inquiry');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['order_id' => 'TESTING123']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$info = curl_getinfo($ch);
echo "HTTP Status: " . $info['http_code'] . "\n";
echo "Response: " . $res . "\n";
