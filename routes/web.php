<?php

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

Route::get('/','HomeController@index');
Route::get('/sms','HomeController@sms');




Auth::routes();

 
Route::get('/register-customer','Auth\RegisterController@register_customer'); 
Route::get('kirim-kode-verifikasi','Auth\RegisterController@kirim_kode_verifikasi');
Route::get('proses-kirim-kode-verifikasi','Auth\RegisterController@proses_kirim_kode_verifikasi');  
Route::get('kirim-ulang-kode-verifikasi/{id}','Auth\RegisterController@kirim_ulang_kode_verifikasi');

Route::put('/proses-kirim-kode-verifikasi/{nomor_hp}',[ 
	'as' => 'user.proses_kirim_kode_verifikasi',
	'uses' => 'Auth\RegisterController@proses_kirim_kode_verifikasi'
	]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/ubah-password',[
	'middleware' => ['auth'],
	'as' => 'user.ubah_password',
	'uses' => 'UbahPasswordController@ubah_password'
	]);

Route::put('/proses-ubah-password/{id}',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_password',
	'uses' => 'UbahPasswordController@proses_ubah_password'
	]);

Route::group(['middleware' =>'auth'], function(){

	Route::resource('user', 'UserController');
	Route::resource('bank', 'BankController');
	Route::resource('komunitas', 'KomunitasController'); 
	Route::resource('warung', 'WarungController');
	Route::resource('customer', 'CustomerController');
	Route::resource('otoritas', 'OtoritasController'); 


	Route::get('user/konfirmasi/{id}',[
	'middleware' => ['auth','role:admin'],
	'as' => 'user.konfirmasi',
	'uses' => 'UserController@konfirmasi'
	]);

	Route::get('user/reset/{id}',[
	'middleware' => ['auth','role:admin'],
	'as' => 'user.reset',
	'uses' => 'UserController@reset_password'
	]);

	Route::get('user/no_konfirmasi/{id}',[
	'middleware' => ['auth'],
	'as' => 'user.no_konfirmasi',
	'uses' => 'UserController@no_konfirmasi'
	]);	

	Route::get('otoritas/permission/{id}',[
	'middleware' => ['auth'],
	'as' => 'otoritas.permission',
	'uses' => 'OtoritasController@setting_permission'
	]);
	Route::put('otoritas/permission/{id}',[
	'middleware' => ['auth'],
	'as' => 'otoritas.permission.edit',
	'uses' => 'OtoritasController@proses_setting_permission'
	]);

});
