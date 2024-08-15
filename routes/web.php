<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin Panel Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get("/", [AuthController::class, "loginView"])->name("login");
Route::post("login", [AuthController::class, "loginPost"])->name("loginPost");

Route::get("header", [HomeController::class, "printHeaders"]);


Route::group(['prefix' => 'admin','middleware' => 'auth:web'], function () {

    Route::get('/dashboard', [HomeController::class, "dashboard"])->name("dashboard");

    Route::group(['prefix' => 'user'], function () {
        Route::get('/user-list', [UserController::class, "userList"])->name("admin_user_list");
        Route::get('/user-view/{user_id}', [UserController::class, "userView"])->name("admin_user_view");
        Route::get('/user-edit/{user_id}', [UserController::class, "userEdit"])->name("admin_user_edit");
        Route::post('/user-update', [UserController::class, "userUpdate"])->name("admin_user_update");
    });

    Route::group(['prefix' => 'resto'], function () {
        Route::get('/pending-list', [RestaurantController::class, "pending_list"])->name("resto_pending_list");
        Route::get('/list', [RestaurantController::class, "index"])->name("resto_list");
        Route::get('/resto-view-pending/{user_id}', [RestaurantController::class, "restoViewPending"])->name("resto_view_pending");
        Route::get('/resto-view/{user_id}', [RestaurantController::class, "restoView"])->name("resto_view");
        Route::get('/resto-edit/{user_id}', [RestaurantController::class, "restoEdit"])->name("resto_edit");
        Route::post('/user-update', [UserController::class, "userUpdate"])->name("admin_user_update");
        Route::post('/user-status-update', [UserController::class, "userStatusUpdate"])->name("admin_user_status_update");
        Route::post('/resto-approve', [RestaurantController::class, "restoApprove"])->name("resto_approve");
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/add', [ProductCategoryController::class, "addForm"])->name("category_add");
        Route::post('/add-post', [ProductCategoryController::class, "categoryStore"])->name("category_store");
        Route::get('/list', [ProductCategoryController::class, "index"])->name("category_list");
        Route::get('/edit/{category_id}', [ProductCategoryController::class, "EditPage"])->name("cat_edit_form");
        Route::post('/update', [ProductCategoryController::class, "Update"])->name("cat_update");
        Route::get('/delete/{category_id}', [ProductCategoryController::class, "delete"])->name("cat_delete");
    });

    Route::group(['prefix' => 'sub-category'], function () {
        Route::get('/add', [ProductSubCategoryController::class, "addForm"])->name("subcategory_add");
        Route::post('/add-post', [ProductSubCategoryController::class, "categoryStore"])->name("subcategory_store");
        Route::get('/list', [ProductSubCategoryController::class, "index"])->name("subcategory_list");
        Route::get('/edit/{sub_cat_id}', [ProductSubCategoryController::class, "EditPage"])->name("subcat_edit_form");
        Route::post('/update', [ProductSubCategoryController::class, "Update"])->name("subcat_update");
        Route::get('/delete/{sub_cat_id}', [ProductSubCategoryController::class, "delete"])->name("subcat_delete");
    });

    Route::get("logout", [AuthController::class, "logout"])->name("admin_logout");
});
