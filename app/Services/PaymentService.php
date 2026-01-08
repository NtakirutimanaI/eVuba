<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        // TODO: specific credential handling will go here
        $this->baseUrl = config('services.momo.base_url');
        $this->apiKey = config('services.momo.api_key');
    }

    /**
     * Initiate a Mobile Money Payment
     * 
     * @param string $phoneNumber
     * @param float $amount
     * @param string $orderId
     * @return array
     */
    public function initiateMomoPayment($phoneNumber, $amount, $orderId)
    {
        // Placeholder for API call
        // Example: POST /payment/request
        /*
        $response = Http::withToken($this->apiKey)->post($this->baseUrl . '/payment', [
            'amount' => $amount,
            'phone' => $phoneNumber,
            'ref' => $orderId,
            'callback_url' => route('api.payment.callback')
        ]);

        return $response->json();
        */

        return [
            'success' => true,
            'transaction_ref' => 'PENDING_' . time(),
            'message' => 'Payment initiation simulated (Waiting for API)'
        ];
    }

    /**
     * Verify Transaction Status
     */
    public function verifyTransaction($ref)
    {
        // Placeholder
        return 'pending';
    }
}
