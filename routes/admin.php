<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::group(['namespace' => 'Api\Admin', 'middleware' => 'isAdmin'], function () {
        Route::resource('/users', 'DashboardController');
        Route::resource('/user-requests', 'RequestsController');
        Route::resource('/subscriptions', 'UserSubscriptionsController');
        Route::resource('/notaries', 'NotaryUsersController');
    });
});
