<?php

return [
    'agora_app_id' => env('APP_ENV') == 'local' ? env('AGORA_APP_ID_TEST') : env('AGORA_APP_ID_LIVE'),
    'agora_certificate_id' => env('APP_ENV') == 'local' ? env('AGORA_CERTIFICATE_ID_TEST') : env('AGORA_CERTIFICATE_ID_LIVE'),
    'agora_privilege_expire_ts' => env('APP_ENV') == 'local' ? env('PRIVILEDGE_EXPIRE_TEST') : env('PRIVILEDGE_EXPIRE_LIVE'),

];
