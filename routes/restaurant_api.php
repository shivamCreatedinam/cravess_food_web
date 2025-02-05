<?php

use App\Http\Controllers\API\RestaurantCategoriesController;
use App\Http\Controllers\Restaurant\AuthController;
use App\Http\Controllers\Restaurant\HomeController;
use App\Http\Controllers\Restaurant\KycController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Restaurant API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post("register", [AuthController::class, "register"]);
Route::post("verify-registration-otp", [AuthController::class, "verifyRegistrationOTP"]);
Route::post("resend-registration-otp", [AuthController::class, "resendRegistrationOTP"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("check-restaurant-account", [AuthController::class, "checkRestaurantAccount"]);

Route::group(['middleware' => ['jwt']], function () {
    Route::post('aadhar-pan-card-update',[KycController::class,"aadharPanCardUpdate"]);
    Route::post('gst-update',[KycController::class,"gstUpdate"]);
    Route::post('fssai-details-update',[KycController::class,"fssaiDetailsUpdate"]);
    Route::post('resto-details-update',[HomeController::class,"restoDetailsUpdate"]);
    Route::post('resto-images-upload',[HomeController::class,"restoImagesUpdate"]);
    Route::post('logout', [AuthController::class, "logout"]);
});

Route::get('get-categories',[RestaurantCategoriesController::class,'getCategories']);
Route::get('get-subcategory/{cat_id}',[RestaurantCategoriesController::class,'getSubCategories']);
