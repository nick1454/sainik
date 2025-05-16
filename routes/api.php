<?php

use Illuminate\Support\Facades\Route;

Route::withoutMiddleware([App\Http\Middleware\ApiMiddleware::class])->group(function(){
    Route::post('/login', ['App\Http\Controllers\AuthController', 'apiLogin']);
});

Route::middleware(['api'])->group(function () {
    Route::post('/auth-user', ['App\Http\Controllers\AuthController', 'authUser']);
    Route::post('/payment/add', ['App\Http\Controllers\Api\FeeController', 'addPayment']);
    Route::post('/student/list', ['App\Http\Controllers\Api\StudentController', 'students']);
    Route::post('/payment/list', ['App\Http\Controllers\Api\AppDashBoardController','getPaymentList']);
    Route::post('/dasboard/tiles', ['App\Http\Controllers\Api\AppDashBoardController','dashboardTiles']);
});