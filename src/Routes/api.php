<?php

use App\Http\Middleware\AuthMandatoryMiddleware;
use App\Http\Middleware\POSUserMiddleware;
use twa\cmsv2\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\AuthOptionalMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;


  
    Route::group(['prefix' => 'api/v1/cms'], function () {
        Route::get('notifications', [App\Http\Controllers\API\NotificationController::class, 'list']);


    });
