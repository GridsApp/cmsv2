<?php

use App\Http\Controllers\EntityController;
use Illuminate\Support\Facades\Route;



Route::get('/login',function(){ return view("CMSView::pages.login"); })->name('login');

// Route::prefix('payment')->group(function () {
//     Route::get('/initialize/{payment_attempt_id}', [App\Http\Controllers\API\PaymentController::class, 'initialize'])->name('payment.initialize');
//     Route::get('/callback/{payment_attempt_id}', [App\Http\Controllers\API\PaymentController::class, 'callback'])->name('payment.callback');
//     Route::get('/response', function(){  echo request()->input('type');  })->name('payment.response');
  
// });

Route::group(['middleware' => \twa\cmsv2\Http\Middleware\CmsAuthMiddleware::class ] , function(){
   
   
    Route::post('/logout' , function(){
        session(['cms_user' => null]);
        return redirect("/");
    })->name('logout');

    Route::get('/',function(){ return view("CMSView::pages.dashboard"); })->name('dashboard');
    // Route::get('/settings',function(){ return view("pages.settings"); })->name('settings');
    Route::get('/settings',[twa\cmsv2\Http\Controllers\SettingsController::class,'render'])->name('settings');
    Route::get('/run-migrate', [twa\cmsv2\Http\Controllers\EntityController::class, 'migrate']);
    Route::get('/{slug}', [twa\cmsv2\Http\Controllers\EntityController::class , 'render'])->name('entity');
    Route::get('/{slug}/create',[twa\cmsv2\Http\Controllers\EntityController::class , 'create'])->name('entity.create');
    Route::get('/{slug}/update/{id}',[twa\cmsv2\Http\Controllers\EntityController::class , 'update'])->name('entity.update');

});







