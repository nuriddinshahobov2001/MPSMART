<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlansUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUsersController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreUsersController;
use App\Http\Controllers\SubscribePlansController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-code', [AuthController::class, 'verifyCode']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('/subscribe-plans', SubscribePlansController::class, ['only' => ['index', 'store', 'show', 'update']]);
    Route::apiResource('/users-plan', PlansUserController::class);
    Route::apiResource('/users', UsersController::class, ['only' => ['index']]);
    Route::apiResource('/role-users', RoleUsersController::class, ['only' => ['index', 'store', 'update', 'show']]);
    Route::apiResource('/stores', StoreController::class);
    Route::apiResource('/store-users', StoreUsersController::class);
});


Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::apiResource('/stores', StoreController::class);
    Route::apiResource('/store-users', StoreUsersController::class);
});
