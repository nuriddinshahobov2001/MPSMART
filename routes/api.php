<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlansUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUsersController;
use App\Http\Controllers\SubscribePlansController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-code', [AuthController::class, 'verifyCode']);

Route::middleware(['auth:api', 'role:super-admin'])->group(function () {

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

    Route::prefix('/users')->group(function () {
        Route::get('/', [UsersController::class, 'index']);
    });

    Route::prefix('/role')->group(function() {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::post('/', [RoleController::class, 'store']);
        Route::patch('/{id}', [RoleController::class, 'update']);
    });


    Route::prefix('/role-users')->group(function() {
        Route::get('/', [RoleUsersController::class, 'index']);
        Route::get('/{id}', [RoleUsersController::class, 'show']);
        Route::post('/', [RoleUsersController::class, 'store']);
        Route::patch('/{id}', [RoleUsersController::class, 'update']);
    });

});

