<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PincodeController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Users API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {

    Route::post("register", [AuthController::class, "register"]);
    Route::post("verify-registration-otp", [AuthController::class, "verifyRegistrationOTP"]);
    Route::post("resend-registration-otp", [AuthController::class, "resendRegistrationOTP"]);
    Route::post("login-otp-send", [AuthController::class, "loginOTPSend"]);
    Route::post("verify-login-otp", [AuthController::class, "verifyLoginOTP"]);
    Route::post("forgot-password", [AuthController::class, "forgotPassword"]);
    Route::post("verify-forgot-password-otp", [AuthController::class, "verifyForgotPasswordOTP"]);
    Route::post("update-password", [AuthController::class, "updatePassword"]);

    // Login Using Email
    Route::post("login-using-email", [AuthController::class, "loginUsingEmail"]);

    Route::group(['middleware' => ['jwt']], function () {
        Route::get("get-authenticate-user", [AuthController::class, "getAuthenticateUser"]);
        Route::post("user/aadhar-kyc-save", [UserController::class, "userAadharKycSave"]);
        Route::post("user/pan-kyc-save", [UserController::class, "userPanKycSave"]);


        // User Profile Routes
        Route::post("update-profile",[UserController::class,"updateProfile"]);
        Route::post("send-mobile-otp",[UserController::class,"sendMobileOTP"]);
        Route::post("send-email-otp",[UserController::class,"sendEmailOTP"]);
        Route::post("verify-old-email-otp-and-send-new-mail-otp",[UserController::class,"verifyEmailOTPAndSendNewMailOTP"]);
        Route::post("verify-old-mobile-otp-and-send-new-mobile-otp",[UserController::class,"verifyMobileOTPAndSendNewMobileOTP"]);
        Route::post("verify-new-email-otp-and-update-mail",[UserController::class,"verifyNewMailOTP"]);
        Route::post("verify-new-mobile-otp-and-update-mobile",[UserController::class,"verifyNewMobileOTP"]);


        // Update FCM Token API
        Route::post('update-fcm-token',[UserController::class,"updateFCMToken"]);

        Route::post('logout', [AuthController::class, "logout"]);
    });


    // Pincodes APIs
    Route::get("get-states", [PincodeController::class, "getStates"]);
    Route::post("get-district", [PincodeController::class, "getDistrict"]);
    Route::post("get-pincode", [PincodeController::class, "getPincode"]);
    Route::get('states-districts-pincodes', [PincodeController::class, 'getStatesDistrictsPincodes']);
});
