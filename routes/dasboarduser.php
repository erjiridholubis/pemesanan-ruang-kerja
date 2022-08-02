<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Aroutes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the  middleware group!
|
*/


Route::get('/dashboarduser', 'DashboardUserController@dashboarduser');


