<?php

use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix'=>'v1'], function(){
    Route::group(['prefix'=>'auth', 'middleware'=>'auth:sanctum'], function(){
        Route::get('user', [UserController::class, 'getUser'])->name('user');
        Route::get('user-detail', [UserController::class, 'getUserDetail'])->name('user.detail');
        Route::get('sliders', [SliderController::class, 'getAll'])->name('sliders');
        Route::get('notices', [NoticeController::class, 'getAll'])->name('notices');
    });
    Route::post('register-mobile', [RegisterController::class, 'registerMobile'])->name('register.mobile');
    Route::post('verify-mobile', [RegisterController::class, 'numberVerify'])->name('verify.mobile');
    Route::post('update-user', [RegisterController::class, 'additionalUserDetail'])->name('update.user');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetOtp'])->name('reset.otp');
    Route::post('forgot-password-verify', [ForgotPasswordController::class, 'resetVerify'])->name('reset.verify');
    Route::post('change-password', [ForgotPasswordController::class, 'store'])->name('store.password');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('categories', [CategoryController::class, 'getAll']);
    Route::get('services', [ServiceController::class, 'getPaginated']);
});