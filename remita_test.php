<?php
require 'vendor/autoload.php';

$merchantId = "27768931";
$serviceTypeId = "35126630";
$apiKey = "Q1dHREVNTzEyMzR8Q1dHREVNTw==";
$orderId = "TEST-" . time();
$amount = "60000.00";

$hash = hash('sha512', $merchantId . $serviceTypeId . $orderId . $amount . $apiKey);

$baseUrl = "https://demo.remita.net/remita/exapp/api/v1/send/api";
$url = rtrim($baseUrl, '/') . '/echannelsvc/merchant/api/paymentinit';

echo "Testing URL: $url\n";

$payload = [
    'serviceTypeId' => $serviceTypeId,
    'amount' => $amount,
    'orderId' => $orderId,
    'payerName' => "Test Payer",
    'payerEmail' => "test@example.com",
    'payerPhone' => "08012345678",
    'description' => "Test Payment",
];

$headers = [
    'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash,
    'Content-Type: application/json',
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "\nResponse Code: " . $info['http_code'] . "\n";
echo "Raw Body: $response\n";

if (preg_match('/jsonp\s*\((.*)\)/s', $response, $matches)) {
    $response = trim($matches[1]);
}

echo "Parsed Body: \n";
print_r(json_decode($response, true));

