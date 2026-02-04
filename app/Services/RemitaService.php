<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RemitaService
{
    protected $merchantId;
    protected $serviceTypeId;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('services.remita.merchant_id');
        $this->serviceTypeId = config('services.remita.service_type_id');
        $this->apiKey = config('services.remita.api_key');
        $this->baseUrl = config('services.remita.base_url');
    }

    public function initializePayment($application)
    {
        $orderId = $application->application_ref;
        $amount = $application->amount;
        $hash = hash('sha512', $this->merchantId . $this->serviceTypeId . $orderId . $amount . $this->apiKey);

        // Standard Remita RRR Generation Endpoint
        $url = rtrim($this->baseUrl, '/') . '/echannelsvc/merchant/api/paymentinit';

        $payload = [
            'serviceTypeId' => $this->serviceTypeId,
            'amount' => $amount,
            'orderId' => $orderId,
            'payerName' => trim($application->surname . ' ' . $application->first_name . ' ' . ($application->other_name ?? '')),
            'payerEmail' => $application->email,
            'payerPhone' => $application->phone,
            'description' => 'Payment for ' . $application->course->course_name,
        ];

        $headers = [
            'Authorization' => 'remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $hash,
            'Content-Type' => 'application/json',
        ];

        Log::info('Remita Init Payload:', $payload);

        try {
            $response = Http::withHeaders($headers)->post($url, $payload);
            $rawBody = $response->body();
            Log::info('Remita Init Raw Response:', ['body' => $rawBody]);

            // Handle JSONP wrapper if present: jsonp ({"...": "..."})
            if (preg_match('/jsonp\s*\((.*)\)/s', $rawBody, $matches)) {
                $rawBody = trim($matches[1]);
            }

            $body = json_decode($rawBody, true);

            if ($response->successful() || (isset($body['statuscode']) && $body['statuscode'] == '025')) {
                if (isset($body['RRR']) && $body['RRR'] != null) {
                    return $body;
                }
            }
            
            Log::error('Remita Init Failed:', ['body' => $body]);
            return null;
        } catch (\Exception $e) {
            Log::error('Remita Init Error: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyPayment($rrr)
    {
        $hash = hash('sha512', $rrr . $this->apiKey . $this->merchantId);
        $url = rtrim($this->baseUrl, '/') . "/echannelsvc/" . $this->merchantId . "/" . $rrr . "/" . $hash . "/status.reg";

        // Adjust based on typical verification URL
        // Endpoint: /echannelsvc/{merchantId}/{rrr}/{apiHash}/status.reg

        try {
            $response = Http::get($url, [
                'Authorization' => 'remitaConsumerKey=' . $this->merchantId . ',remitaConsumerToken=' . $hash
            ]);
            
            Log::info('Remita Verify Response:', $response->json());
            
            return $response->json();
        } catch (\Exception $e) {
             Log::error('Remita Verify Error: ' . $e->getMessage());
             return null;
        }
    }
}
