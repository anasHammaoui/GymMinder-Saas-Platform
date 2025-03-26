<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVeficationController;
use App\Http\Controllers\ForgetPassController;
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
// reset password
Route::middleware('guest') -> group(function(){
    Route::post('auth/login/forgot-password',[ForgetPassController::class, 'forgetPassword'])-> name("password.reset"); //send email
Route::get('auth/login/forgot-password/{token}',function ($token){
    return response() -> json(["token"=>$token],200);
})-> name("password.reset"); //show form  with the token
Route::post('auth/login/reset-password',[ForgetPassController::class, 'updatePassword'])-> name("password.update"); //update password

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

