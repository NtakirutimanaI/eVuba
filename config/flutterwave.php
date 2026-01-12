<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flutterwave API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure Flutterwave payment gateway credentials and settings
    |
    */

    'publicKey' => env('FLUTTERWAVE_PUBLIC_KEY'),
    'secretKey' => env('FLUTTERWAVE_SECRET_KEY'),
    'encryptionKey' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
    'environment' => env('FLUTTERWAVE_ENVIRONMENT', 'sandbox'), // sandbox or production

    'paymentUrl' => env('FLUTTERWAVE_ENVIRONMENT', 'sandbox') === 'production'
        ? 'https://api.flutterwave.com/v3/payments'
        : 'https://api.flutterwave.com/v3/payments',

    'verificationUrl' => env('FLUTTERWAVE_ENVIRONMENT', 'sandbox') === 'production'
        ? 'https://api.flutterwave.com/v3/transactions'
        : 'https://api.flutterwave.com/v3/transactions',
];
