<?php

return [
    'url' => env('PAYSTACK_PAYMENT_URL'),
    'public_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('PAYSTACK_TEST_PUBLIC_KEY') : env('PAYSTACK_LIVE_PUBLIC_KEY'),
    'secret_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('PAYSTACK_TEST_SECRET_KEY') : env('PAYSTACK_LIVE_SECRET_KEY'),
];
