<?php

use App\Http\Middleware\AuthMandatoryMiddleware;
use App\Http\Middleware\POSUserMiddleware;
use twa\cmsv2\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\AuthOptionalMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/api/v1/cms/notifications', [ \twa\cmsv2\Http\Controllers\NotificationController::class, 'list']);
