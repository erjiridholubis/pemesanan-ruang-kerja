<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user', 'Api\UserController@index')->name('api.user.all');
Route::get('user/{id}', 'Api\UserController@show')->name('api.user.show');

Route::get('customer', 'Api\CustomerController@index')->name('api.customer.all');
Route::get('customer/{id}', 'Api\CustomerController@show')->name('api.customer.show');

Route::get('facility', 'Api\FacilityController@index')->name('api.facility.all');
Route::get('facility/{id}', 'Api\FacilityController@show')->name('api.facility.show');

Route::get('order', 'Api\OrderController@index')->name('api.order.all');
Route::get('order/{id}', 'Api\OrderController@show')->name('api.order.show');

Route::get('room', 'Api\RoomController@index')->name('api.room.all');
Route::get('room/{id}', 'Api\RoomController@show')->name('api.room.show');
Route::get('room/{id}/json_tags', 'Api\RoomController@jsonTags')->name('api.room.json_tags');

Route::get('payment', 'Api\PaymentController@index')->name('api.payment.all');
Route::get('payment/{id}', 'Api\PaymentController@show')->name('api.payment.show');
