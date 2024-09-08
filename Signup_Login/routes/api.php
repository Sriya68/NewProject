<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PhonePePaymentController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);     
Route::post('/register', [AuthController::class, 'register']);
 
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
 
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/posts', [PostController::class, 'store']);
Route::post('send-otp', [OtpController::class, 'sendOtp']);
Route::post('verify-otp', [OtpController::class, 'verifyOtp']);

Route::post('/create-order', [PhonePePaymentController::class, 'createOrder'])->name('create-order');
Route::post('/verify-payment', [PhonePePaymentController::class, 'verifyPayment'])->name('verify-payment');


Route::middleware('auth:sanctum')->post('change-password', [PasswordController::class, 'changePassword']);
Route::post('forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('reset-password', [PasswordController::class, 'resetPassword']);
