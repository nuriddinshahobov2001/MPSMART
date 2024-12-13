<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlansUserController;
use App\Http\Controllers\SubscribePlansController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-code', [AuthController::class, 'verifyCode']);

Route::middleware('auth:api')->group(function () {

    Route::prefix('/subscribe-plans')->group(function () {
        Route::get('/', [SubscribePlansController::class, 'index']);
        Route::get('/{id}', [SubscribePlansController::class, 'show']);
        Route::post('/', [SubscribePlansController::class, 'store']);
        Route::patch('/{id}', [SubscribePlansController::class, 'update']);
        Route::delete('/{id}', [SubscribePlansController::class, 'destroy']);
    });

    Route::prefix('/users-plan')->group(function () {
        Route::get('/', [PlansUserController::class, 'index']);
        Route::get('/{id}', [PlansUserController::class, 'show']);
        Route::post('/', [PlansUserController::class, 'store']);
        Route::patch('/{id}', [PlansUserController::class, 'update']);
        Route::delete('/{id}', [PlansUserController::class, 'destroy']);
    });

});
