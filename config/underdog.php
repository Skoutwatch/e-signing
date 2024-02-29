<?php

return [
    'env' => env('UNDERDOG_API_MODE'),
    'key' => (env('UNDERDOG_API_KEY')),
    'url' =>env('UNDERDOG_URL'),
    'project_id' => (env('UNDERDOG_PROJECT_ID')),
    'receiver_address' => (env('UNDERDOG_RECEIVER_ADDRESS')),
];