<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::namespace('Api')->prefix('v1')->middleware('cors')->group(function () {

    // 用戶註冊
    // Route::post('/users', 'UserController@store')->name('users.store');
    // 用戶登入
    // Route::post('/login', 'UserController@login')->name('users.login');

    Route::middleware('api.refresh')->group(function () {

        // 取得用戶列表
        Route::get('/users', 'UserController@index')->name('users.index');
        // 取得用戶資料
        Route::get('/users/{user}', 'UserController@show')->name('users.show');
        // 用戶登出
        Route::get('/logout', 'UserController@logout')->name('users.logout');
        // 更新用戶資料
        Route::put('/users/{user}', 'UserController@update')->name('users.update');

        // 取得訂單列表
        Route::get('/orders', 'OrderController@index')->name('order.index');
        // 取得訂單資料
        Route::get('/orders/{order}', 'OrderController@show')->name('order.show');
        // 建立訂單資料
        Route::post('/orders', 'OrderController@store')->name('order.store');

        // 取得產品列表
        Route::get('/products', 'ProductController@index')->name('product.index');
        // 取得產品資料
        Route::get('/products/{product}', 'ProductController@show')->name('product.show');
        // 建立產品資料
        Route::post('/products', 'ProductController@store')->name('product.store');
    });

    // Line Bot
    Route::post('/linebot/callback', 'LineMessageController@index');
});
