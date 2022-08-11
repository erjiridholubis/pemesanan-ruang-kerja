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

  Route::group(['prefix' => 'customers'], function () {
      Route::get('/', 'Admin\CustomerController@index')->name('admin.customer');
      Route::get('/trash', 'Admin\CustomerController@trash')->name('admin.customer.trash');
      Route::get('/restore', 'Admin\CustomerController@restore')->name('admin.customer.restore');
    
      Route::get('/{id}/restore', 'Admin\CustomerController@restoreCustomer')->name('admin.customer.restore.id');
      Route::get('/{id}/delete', 'Admin\CustomerController@delete')->name('admin.customer.delete');
      Route::get('/{id}/delete/permanent', 'Admin\CustomerController@deletePermanent')->name('admin.customer.delete.permanent');
    
      Route::post('/store', 'Admin\CustomerController@store')->name('admin.customer.store');
      Route::put('/{id}/update', 'Admin\CustomerController@update')->name('admin.customer.update');
  });

  Route::group(['prefix' => 'facilities'], function () {
      Route::get('/', 'Admin\FacilityController@index')->name('admin.facility');
      Route::get('/trash', 'Admin\FacilityController@trash')->name('admin.facility.trash');
      Route::get('/restore', 'Admin\FacilityController@restore')->name('admin.facility.restore');
    
      Route::get('/{id}/restore', 'Admin\FacilityController@restoreFacility')->name('admin.facility.restore.id');
      Route::get('/{id}/delete', 'Admin\FacilityController@delete')->name('admin.facility.delete');
      Route::get('/{id}/delete/permanent', 'Admin\FacilityController@deletePermanent')->name('admin.facility.delete.permanent');
    
      Route::post('/store', 'Admin\FacilityController@store')->name('admin.facility.store');
      Route::put('/{id}/update', 'Admin\FacilityController@update')->name('admin.facility.update');
  });

  Route::group(['prefix' => 'types'], function () {
      Route::get('/', 'Admin\TypeController@index')->name('admin.type');
      Route::get('/trash', 'Admin\TypeController@trash')->name('admin.type.trash');
      Route::get('/restore', 'Admin\TypeController@restore')->name('admin.type.restore');
    
      Route::get('/{id}/restore', 'Admin\TypeController@restoreType')->name('admin.type.restore.id');
      Route::get('/{id}/delete', 'Admin\TypeController@delete')->name('admin.type.delete');
      Route::get('/{id}/delete/permanent', 'Admin\TypeController@deletePermanent')->name('admin.type.delete.permanent');
    
      Route::post('/store', 'Admin\TypeController@store')->name('admin.type.store');
      Route::put('/{id}/update', 'Admin\TypeController@update')->name('admin.type.update');
  });

  Route::group(['prefix' => 'orders'], function () {
      Route::get('/', 'Admin\OrderController@index')->name('admin.order');
      Route::get('/trash', 'Admin\OrderController@trash')->name('admin.order.trash');
      Route::get('/restore', 'Admin\OrderController@restore')->name('admin.order.restore');
    
      Route::get('/{id}/restore', 'Admin\OrderController@restoreOrder')->name('admin.order.restore.id');
      Route::get('/{id}/delete', 'Admin\OrderController@delete')->name('admin.order.delete');
      Route::get('/{id}/delete/permanent', 'Admin\OrderController@deletePermanent')->name('admin.order.delete.permanent');
    
      Route::post('/store', 'Admin\OrderController@store')->name('admin.order.store');
      Route::put('/{id}/update', 'Admin\OrderController@update')->name('admin.order.update');
  });

  Route::group(['prefix' => 'rooms'], function () {
      Route::get('/', 'Admin\RoomController@index')->name('admin.room');
      Route::get('/trash', 'Admin\RoomController@trash')->name('admin.room.trash');
      Route::get('/restore', 'Admin\RoomController@restore')->name('admin.room.restore');
      
      Route::get('/{id}/restore', 'Admin\RoomController@restoreRoom')->name('admin.room.restore.id');
      Route::get('/{id}/delete', 'Admin\RoomController@delete')->name('admin.room.delete');
      Route::get('/{id}/delete/permanent', 'Admin\RoomController@deletePermanent')->name('admin.room.delete.permanent');
      Route::get('/{id}/json_tags', 'Admin\RoomController@jsonTags')->name('admin.room.json_tags');
    
      Route::post('/store', 'Admin\RoomController@store')->name('admin.room.store');
      Route::put('/{id}/update', 'Admin\RoomController@update')->name('admin.room.update');
  });

  Route::group(['prefix' => 'payments'], function () {
      Route::get('/', 'Admin\PaymentController@index')->name('admin.payment');
      Route::get('/paid', 'Admin\PaymentController@paid')->name('admin.payment.paid');
      Route::get('/pending', 'Admin\PaymentController@pending')->name('admin.payment.pending');
      Route::get('/cancelled', 'Admin\PaymentController@cancel')->name('admin.payment.cancel');
      Route::get('/trash', 'Admin\PaymentController@trash')->name('admin.payment.trash');
      Route::get('/restore', 'Admin\PaymentController@restore')->name('admin.payment.restore');
    
      Route::get('/{id}/restore', 'Admin\PaymentController@restorePayment')->name('admin.payment.restore.id');
      Route::get('/{id}/delete', 'Admin\PaymentController@delete')->name('admin.payment.delete');
      Route::get('/{id}/delete/permanent', 'Admin\PaymentController@deletePermanent')->name('admin.payment.delete.permanent');
      Route::get('/{id}/approve', 'Admin\PaymentController@approve')->name('admin.payment.approve');
      Route::get('/{id}/deceline', 'Admin\PaymentController@approve')->name('admin.payment.deceline');
    
      Route::post('/store', 'Admin\PaymentController@store')->name('admin.payment.store');
      Route::put('/{id}/update', 'Admin\PaymentController@update')->name('admin.payment.update');
  });

  Route::group(['prefix' => 'profile-web'], function () {
    Route::get('/', 'Admin\ProfileController@index')->name('admin.profile');
    Route::get('/edit', 'Admin\ProfileController@index')->name('admin.profile.edit');
    Route::put('/update', 'Admin\ProfileController@update')->name('admin.profile.update');
  });

});