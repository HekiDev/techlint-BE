<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IPAddressController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('ip-address')->group(function () {
        Route::get('/', [IPAddressController::class, 'index']);
        Route::post('/store', [IPAddressController::class, 'store']);
        Route::post('/{ipAddress}/update', [IPAddressController::class, 'update']);
        Route::delete('/{ipAddress}/delete', [IPAddressController::class, 'destroy']);
    });
});
