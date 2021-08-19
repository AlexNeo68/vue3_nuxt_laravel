<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::put('user-update', [AuthController::class, 'user_update']);
    Route::patch('user-password', [AuthController::class, 'user_password']);
});


Route::group([
    'middleware' => ['auth:api', 'scope:admin'],
    'prefix' => 'admin',
    'namespace' => 'App\Http\Controllers\Admin'
], function () {

    Route::apiResource('users', UserController::class);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('chart', [DashboardController::class, 'chart']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('products', ProductController::class);
    Route::post('upload-image', [ImageController::class, 'upload']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::get('orders-export', [OrderController::class, 'export']);
    Route::get('permissions', [PermissionController::class, 'index']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'influencer',
    'namespace' => 'App\Http\Controllers\Influencer'
], function () {
    Route::get('products', [\App\Http\Controllers\Influencer\ProductController::class, 'index']);
    Route::post('links', [\App\Http\Controllers\Influencer\LinkController::class, 'store']);
});

Route::group([
    'prefix' => 'checkout',
    'namespace' => 'App\Http\Controllers\Checkout'
], function () {
    Route::get('links/{link:code}', [\App\Http\Controllers\Checkout\LinkController::class, 'show']);
    Route::post('orders', [\App\Http\Controllers\Checkout\OrderController::class, 'store']);
    Route::post('orders/confirm', [\App\Http\Controllers\Checkout\OrderController::class, 'confirm']);
});
