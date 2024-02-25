<?php

return [
    'public_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('FLUTTERWAVE_TEST_PUBLIC_KEY') : env('FLUTTERWAVE_LIVE_PUBLIC_KEY'),
    'secret_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('FLUTTERWAVE_TEST_SECRET_KEY') : env('FLUTTERWAVE_LIVE_SECRET_KEY'),
    'encryption_key' => env('THIRD_PARTY_API_MODE') == 'test' ? env('FLUTTERWAVE_TEST_ENCRYPTION_KEY') : env('FLUTTERWAVE_LIVE_ENCRYPTION_KEY'),
];
