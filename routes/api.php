<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {

    Route::apiResource('users', UserController::class);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('chart', [DashboardController::class, 'chart']);

    Route::get('user', [UserController::class, 'user']);
    Route::put('user-update', [UserController::class, 'user_update']);
    Route::patch('user-password', [UserController::class, 'user_password']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('products', ProductController::class);
    Route::post('upload-image', [ImageController::class, 'upload']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::get('orders-export', [OrderController::class, 'export']);
    Route::get('permissions', [PermissionController::class, 'index']);
});
