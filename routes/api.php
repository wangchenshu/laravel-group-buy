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
    Route::get('/orders', 'OrderController@index')->name('order.index');
    Route::get('/products', 'ProductController@index')->name('product.index');

    // Line Bot
    Route::post('/linebot/callback', 'LineMessageController@index');
});
