<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $publicKey;
    protected $secretKey;
    protected $encryptionKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->encryptionKey = config('flutterwave.encryptionKey');
        $this->baseUrl = 'https://api.flutterwave.com/v3';
    }

    /**
     * Initiate a Flutterwave Payment
     * 
     * @param array $paymentData
     * @return array
     */
    public function initiatePayment($paymentData)
    {
        try {
            $certPath = file_exists(base_path('cacert.pem')) ? base_path('cacert.pem') : true;

            $response = Http::timeout(15)
                ->withOptions(['verify' => $certPath])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/payments', $paymentData);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'payment_link' => $result['data']['link'] ?? null,
                    'transaction_ref' => $paymentData['tx_ref'],
                    'message' => 'Payment initiated successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Payment initiation failed',
                'error' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave Payment Initiation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while initiating payment',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify Transaction Status
     * 
     * @param int $transactionId
     * @return array
     */
    public function verifyTransaction($transactionId)
    {
        try {
            $certPath = file_exists(base_path('cacert.pem')) ? base_path('cacert.pem') : true;

            $response = Http::timeout(15)
                ->withOptions(['verify' => $certPath])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/transactions/' . $transactionId . '/verify');

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === 'success') {
                $data = $result['data'];

                return [
                    'success' => true,
                    'status' => $data['status'], // successful, failed, pending
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'transaction_ref' => $data['tx_ref'],
                    'flw_ref' => $data['flw_ref'],
                    'customer' => [
                        'email' => $data['customer']['email'] ?? null,
                        'name' => $data['customer']['name'] ?? null,
                    ],
                ];
            }

            return [
                'success' => false,
                'status' => 'failed',
                'message' => $result['message'] ?? 'Verification failed'
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave Transaction Verification Error: ' . $e->getMessage());
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'An error occurred while verifying transaction',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature
     * 
     * @param string $signature
     * @param array $payload
     * @return bool
     */
    public function verifyWebhookSignature($signature, $payload)
    {
        $secretHash = config('flutterwave.secretKey');
        $computedSignature = hash_hmac('sha256', json_encode($payload), $secretHash);

        return hash_equals($computedSignature, $signature);
    }
}

