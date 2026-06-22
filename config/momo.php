<?php

return [
    'base_url'         => env('MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
    'environment'      => env('MOMO_ENV', 'sandbox'), // 'sandbox' or 'mtnuganda' (or other prod target)
    'subscription_key' => env('MOMO_SUBSCRIPTION_KEY'),
    'api_user'         => env('MOMO_API_USER'),
    'api_key'          => env('MOMO_API_KEY'),
    'callback_host'    => env('MOMO_CALLBACK_HOST'),

    // Shared secret MTN will NOT send back to us — used to protect our own
    // callback route via a query-string token, since MoMo webhooks carry no
    // signature we can verify. See PaymentController::momoCallback().
    'callback_secret'  => env('MOMO_CALLBACK_SECRET'),

    'currency' => env('MOMO_CURRENCY', 'EUR'), // sandbox ONLY accepts EUR; production uses UGX
];