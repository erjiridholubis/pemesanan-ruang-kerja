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
