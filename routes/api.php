<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DoughController;
use App\Http\Controllers\CustomerController;


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

Route::post('/user/add', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

//Product
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{product}/show', [ProductController::class, 'show']);
//Categories
Route::get('/categories', [CategoriesController::class, 'index']);
//Dough
Route::get('/dough', [DoughController::class, 'index']);

Route::middleware(['auth.api'])->group(function () {
    Route::get('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    //user
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{user}/show', [UserController::class, 'show']);
    Route::put('/user/{user}/update', [UserController::class, 'update']);
    Route::delete('/user/{user}/delete', [UserController::class, 'destroy']);

    //Product
    Route::post('/product/store', [ProductController::class, 'store']);
    Route::put('/product/{product}/update', [ProductController::class, 'update']);
    Route::delete('/product/{product}/delete', [ProductController::class, 'destroy']);

    //Categories
    Route::post('/category/store', [CategoriesController::class, 'store']);
    Route::get('/category/{categories}/show', [CategoriesController::class, 'show']);
    Route::put('/category/{categories}/update', [CategoriesController::class, 'update']);
    Route::delete('/category/{categories}/delete', [CategoriesController::class, 'destroy']);

    //Dough
    Route::post('/dough/store', [DoughController::class, 'store']);
    Route::get('/dough/{dough}/show', [DoughController::class, 'show']);
    Route::put('/dough/{dough}/update', [DoughController::class, 'update']);
    Route::delete('/dough/{dough}/delete', [DoughController::class, 'destroy']);

    //customer
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customer/store', [CustomerController::class, 'store']);
    Route::put('/customer/{customer}/update', [CustomerController::class, 'update']);
    Route::delete('/customer/{customer}/delete', [CustomerController::class, 'destroy']);
}
);
