<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\CustomerAuth\CustomerAuthController;
use Modules\Authentication\Http\Controllers\CustomerAuth\ForgotPassword;
use Modules\Authentication\Http\Controllers\CustomerController;
use Modules\Authentication\Http\Controllers\UserAuth\UserAuthController;
use Modules\Authentication\Http\Controllers\UserController;
use Modules\Authentication\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    ################## User Auth ##################

    Route::group([
        'prefix' => '/auth',
    ], function () {
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::group(['middleware' => 'auth:user'], function () {
            Route::get('/user-Permissions', [UserAuthController::class, 'userPermissions']);
            Route::get('/user-Role', [UserAuthController::class, 'userRole']);
            Route::get('/profile-details', [UserAuthController::class, 'getProfileDetails']);
            Route::post('/logout', [UserAuthController::class, 'logout']);
            Route::post('/change-password', [UserAuthController::class, 'changePassword']);
        });
    });

    ################## User ##################
    Route::group([
        'middleware' => 'auth:user'
    ], function () {
        Route::resource('users', UserController::class)->except(['create', 'edit']);
    });

    ################## Employee ##################
    Route::group([
        'middleware' => 'auth:user'
    ], function () {
        Route::get('employees/permissions', [EmployeeController::class, 'getEmployeePermissions']);
        Route::resource('employees', EmployeeController::class)->except(['create', 'edit']);
    });


    ################## Customer Auth ##################

    Route::group([
        'prefix' => '/customer/auth',
    ], function () {
        //login-register
        Route::post('/login', [CustomerAuthController::class, 'login']);
        Route::post('/site-login', [CustomerAuthController::class, 'login2']);
        Route::post('/register', [CustomerAuthController::class, 'register']);
        Route::post('/site-register', [CustomerAuthController::class, 'SiteRegister']);
        Route::post('/check-identity', [CustomerAuthController::class, 'check_identity']);

        //forgot-password
        Route::post('/forgot-password', [ForgotPassword::class, 'reset_password_request']);
        Route::post('/verify-identity', [ForgotPassword::class, 'otp_verification_submit']);
        Route::post('/reset-password', [ForgotPassword::class, 'reset_password_submit']);

        //logout-changePassword
        Route::group(['middleware' => 'auth:customer'], function () {
            Route::post('/logout', [CustomerAuthController::class, 'logout']);
            Route::post('/change-password', [CustomerAuthController::class, 'changePassword']);
            Route::post('/save-fcm-token', [CustomerAuthController::class, 'saveFcmToken']);
            Route::put('/edit-profile', [CustomerController::class, 'update']);
        });
    });

    ################## Customer ##################
    Route::group([
        'middleware' => 'auth:user'
    ], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::put('customer/{customer}/change-status', [CustomerController::class, 'updateStatus']);
        Route::resource('customers', CustomerController::class)->except(['create', 'edit','update']);
    });
});
