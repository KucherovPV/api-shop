<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('products','App\Http\Controllers\Product\ProductController@product');
Route::post('product','App\Http\Controllers\Product\ProductController@productSave');
Route::patch('product/{id}','App\Http\Controllers\Product\ProductController@productEdit');
Route::delete('product/{id}','App\Http\Controllers\Product\ProductController@productDelete');

Route::post('signup','App\Http\Controllers\Auth\RegisterController@register');
Route::post('login','App\Http\Controllers\Auth\LoginController@login');
Route::get('logout','App\Http\Controllers\Auth\LogoutController@logout');

Route::post('cart/{id}','App\Http\Controllers\Cart\CartController@addToCart');
Route::delete('cart/{id}','App\Http\Controllers\Cart\CartController@removeFromCart');
Route::get('cart','App\Http\Controllers\Cart\CartController@ShowCart');

Route::post('order','App\Http\Controllers\Order\OrderController@createOrder');
Route::get('order','App\Http\Controllers\Order\OrderController@showOrders');





