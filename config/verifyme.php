<?php

return [
    'api_mode' => env('VERIFY_ME_API_MODE'),
    'public_key' => (env('VERIFY_ME_API_MODE') == 'dev') ? env('VERIFYME_TEST_PUBLIC_KEY') : env('VERIFYME_LIVE_PUBLIC_KEY'),
    'secret_key' => (env('VERIFY_ME_API_MODE') == 'dev') ? env('VERIFYME_TEST_SECRET_KEY') : env('VERIFYME_LIVE_SECRET_KEY'),
];
