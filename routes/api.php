<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVeficationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
// email verify
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/email/resend', [EmailVeficationController::class, 'resend']);
    Route::get('/email/verify/{id}/{hash}', [EmailVeficationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
});
// protected auth routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});
// profile
Route::get('/profile', function () {
    return response() -> json(["message" => "welcome"],200);
})->middleware(['auth', 'verified']);

