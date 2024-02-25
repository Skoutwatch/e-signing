<?php

return [
    'folder' => env('THIRD_PARTY_API_MODE') == 'test' ? env('FILE_UPLOAD_TEST_FOLDER') : env('FILE_UPLOAD_LIVE_FOLDER'),
];
