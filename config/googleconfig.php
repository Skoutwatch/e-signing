<?php

return [
    'google_api' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'project_id' => 'zeta-bonfire-394811',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_uris' => [
            'http://localhost',
        ],
        'javascript_origins' => [
            'https://www.example.com',
        ],
    ],

    'test_auth_code' => env('GOOGLE_TEST_AUTH_CODE'),

    'test_access_code' => env('GOOGLE_TEST_ACCESS_CODE'),

];
