<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;



// Accessible For Auth/Non Authenticated users
Route::apiResource('/users', UserController::class);

Route::middleware('guest:sanctum')->group(function () {
    // Accessible For Non Authenticated users
    Route::post('/login', [AuthController::class,'login'])->name('auth.login');
});

Route::middleware('auth:sanctum')->group(function () {
    // Accessible For Authenticated users only
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::apiResource('/products', ProductController::class);
    Route::get('/products/s/{slug}', [ProductController::class,'showBySlug']);
});
