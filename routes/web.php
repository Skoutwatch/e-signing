<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/relematic', function () {
    // return phpinfo();
});

Route::get('/info', function () {
    // phpinfo();
});

Route::get('/sync', function () {
    Artisan::call('sync:filesystem');
});

Route::get('/cron', function () {
    Artisan::call('document:check-participants');

    return 'ok';
});

Route::get('/payments', function () {
    Artisan::call('recurring:payments');

    return 'ok';
});

Route::get('/partner-transactions', function () {
    Artisan::call('check:first-transaction-monthly');

    return 'ok';
});

/*Route::prefix('demo')
    ->group(function () {
        Route::get('sub', [\App\Http\Controllers\DemoController::class, 'subs']);
        Route::get('ip', [\App\Http\Controllers\DemoController::class, 'initPs']);
        Route::get('cb', [\App\Http\Controllers\DemoController::class, 'cb']);
        Route::get('ref', [\App\Http\Controllers\DemoController::class, 'ref']);
    });*/
