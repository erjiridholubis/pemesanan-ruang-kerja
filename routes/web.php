<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/home', 'Admin\HomeController@index')->name('admin.home');
Route::get('/', function () {
    return redirect()->route('admin.home');
});

Route::group(['prefix' => 'admin'], function () {
  Auth::routes(['register'=> false]);
  
  Route::get('/login/process', 'Auth\ProcessController@index')->name('admin.login.process');
  Route::get('/', function () {
      if (Auth::user()) {
          return redirect()->route('admin.home');
      } else {
          return redirect()->route('login');
      }
  });

  Route::group(['prefix' => 'users'], function () {
      Route::get('/', 'Admin\UserController@index')->name('admin.user');
      Route::get('/trash', 'Admin\UserController@trash')->name('admin.user.trash');
      Route::get('/restore', 'Admin\UserController@restore')->name('admin.user.restore');
    
      Route::get('/{id}/restore', 'Admin\UserController@restoreUser')->name('admin.user.restore.id');
      Route::get('/{id}/delete', 'Admin\UserController@delete')->name('admin.user.delete');
      Route::get('/{id}/delete/permanent', 'Admin\UserController@deletePermanent')->name('admin.user.delete.permanent');
      Route::get('/{id}/active', 'Admin\UserController@active')->name('admin.user.active');
      Route::get('/{id}/nonactive', 'Admin\UserController@active')->name('admin.user.nonactive');
    
      Route::post('/store', 'Admin\UserController@store')->name('admin.user.store');
      Route::put('/{id}/update', 'Admin\UserController@update')->name('admin.user.update');
  });

  Route::group(['prefix' => 'profile-web'], function () {
    Route::get('/', 'Admin\ProfileController@index')->name('admin.profile');
    Route::get('/edit', 'Admin\ProfileController@index')->name('admin.profile.edit');
    Route::put('/update', 'Admin\ProfileController@update')->name('admin.profile.update');
  });

});