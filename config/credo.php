<?php

return [
    'public_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('CREDO_TEST_PUBLIC_KEY') : env('CREDO_LIVE_PUBLIC_KEY'),
    'secret_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('CREDO_TEST_SECRET_KEY') : env('CREDO_LIVE_SECRET_KEY'),
    'redirect_url' => env('THIRD_PARTY_API_MODE') == 'test' ? env('CREDO_REDIRECT_URL') : env('CREDO_REDIRECT_URL'),
    'base_url' => env('THIRD_PARTY_API_MODE') == 'test' ? env('CREDO_BASE_URL') : env('CREDO_BASE_URL'),
    'payment_url' => env('THIRD_PARTY_API_MODE') == 'test' ? env('CREDO_PAYMENT_URL') : env('CREDO_PAYMENT_URL'),
];
