<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth routes
Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/auth/forgot-password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [\App\Http\Controllers\AuthController::class, 'resetPassword']);
