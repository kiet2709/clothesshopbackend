<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClothController;
use App\Http\Controllers\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::get('todo', [TodoController::class, 'getTodo']);

Route::controller(UserController::class)->prefix('users')->group(function (){
    Route::get('/', 'getAllUsers')->middleware('is_admin');
    Route::get('/current', 'getCurrentUser');
    Route::get('/{id}', 'getUserById')->middleware('is_admin');
    Route::patch('/current', 'updateCurrentProfile');
    Route::post('/current/upload-image', 'uploadImage');
    Route::get('/current/images', 'getImage');
    Route::get('/current/orders', 'getCurrentOrder');
});

Route::controller(BrandController::class)->prefix('brands')->group(function (){
    Route::post('/', 'addBrand')->middleware('is_admin');
    Route::get('/', 'getAllBrands');
    Route::get('/{id}', 'getBrandById');
    Route::patch('/{id}', 'updateBrand')->middleware('is_admin');
    Route::get('/{id}/clothes', 'getClothByBrandId');
});

Route::controller(SizeController::class)->prefix('sizes')->group(function (){
    Route::get('/', 'getAllSizes');
    Route::get('/{id}', 'getSizeById');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function (){
    Route::get('/', 'getAllCategories');
    Route::get('/{id}', 'getCategoryById');
    Route::post('/', 'addCategory')->middleware('is_admin');
    Route::patch('/{id}', 'updateCategory')->middleware('is_admin');
    Route::post('/{id}/upload-image', 'uploadImage')->middleware('is_admin');
    Route::get('/{id}/images', 'getImage');
    Route::get('/{id}/clothes', 'getClothByCategoryId');
});

Route::controller(ClothController::class)->prefix('clothes')->group(function (){
    Route::get('/', 'getAllClothes');
    Route::get('/{id}', 'getClothById');
    Route::post('/', 'addCloth')->middleware('is_admin');
    Route::patch('/{id}', 'updateCloth')->middleware('is_admin');
    Route::post('/{id}/upload-image', 'uploadImage')->middleware('is_admin');
    Route::get('/{id}/images', 'getImage');
});

Route::controller(CartController::class)->prefix('carts')->group(function (){
    Route::get('/current', 'getCurrentCart');
    Route::delete('/', 'deleteCartItem');
    Route::post('/', 'addCart');
    Route::patch('/', 'updateCart');
});

Route::controller(OrderController::class)->prefix('orders')->group(function (){
    Route::get('/', 'getAllOrders')->middleware('is_admin');
    Route::get('/{id}', 'getOrderById')->middleware('is_admin');
    Route::post('/', 'submit')->middleware('is_admin');
    Route::patch('/{id}', 'updateStatus')->middleware('is_admin');
});

Route::controller(BannerController::class)->prefix('banners')->group(function (){
    Route::get('/', 'getAllImageId');
    Route::get('/{id}', 'getImage');
    Route::post('/', 'uploadImage')->middleware('is_admin');
});

