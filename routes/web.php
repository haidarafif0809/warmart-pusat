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

Route::get('/','HomeController@index')->middleware('optimizeImages');
Route::get('/sms','HomeController@sms');

Route::get('/dashboard',[
	'middleware' => ['auth'],
	'as'=>'home.dashboard',
	'uses' => 'HomeController@dashboard'
]);





Auth::routes();

//USER WARUNG 
Route::get('/register-warung','Auth\RegisterController@register_warung'); 

Route::get('/register-customer','Auth\RegisterController@register_customer');

//registrasi lewat link affiliasi
Route::get('/aff/{id}/',function($id){


	return view('auth.register_customer',['komunitas_id' => $id]);
}); 


Route::get('kirim-kode-verifikasi','Auth\RegisterController@kirim_kode_verifikasi'); 
Route::get('kirim-ulang-kode-verifikasi/{id}','Auth\RegisterController@kirim_ulang_kode_verifikasi');
Route::get('lupa-password','Auth\RegisterController@lupa_password');

Route::put('/proses-kirim-kode-verifikasi/{nomor_hp}',[ 
	'as' => 'user.proses_kirim_kode_verifikasi',
	'uses' => 'Auth\RegisterController@proses_kirim_kode_verifikasi'
	]);

Route::post('/proses-lupa-password',[ 
	'as' => 'user.proses_lupa_password',
	'uses' => 'Auth\RegisterController@proses_lupa_password'
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

Route::get('/ubah-profil',[
	'middleware' => ['auth'],
	'as' => 'user.ubah_profil',
	'uses' => 'UbahProfilController@ubah_profil'
	]);

Route::put('/proses-ubah-profil/{id}',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil',
	'uses' => 'UbahProfilController@proses_ubah_profil'
	]);

Route::middleware('optimizeImages','auth')->group(function () {

	Route::resource('user', 'UserController');
	Route::resource('bank', 'BankController');
	Route::resource('komunitas', 'KomunitasController'); 
	Route::resource('warung', 'WarungController');
	Route::resource('customer', 'CustomerController');
	Route::resource('otoritas', 'OtoritasController'); 
	Route::resource('user_warung', 'UserWarungController'); 	
	Route::resource('kas', 'KasController'); 	
	Route::resource('barang', 'BarangController'); 
	Route::resource('kategori_transaksi', 'KategoriTransaksiController'); 
	Route::resource('kas_masuk', 'KasMasukController');
	Route::resource('kas_keluar', 'KasKeluarController');	
	Route::resource('kas_mutasi', 'KasMutasiController');

	Route::post('/cek_total_kas',[ 		
	'middleware' => ['auth'],
	'as' => 'cek_total_kas',
	'uses' => 'TransaksikasController@total_kas'
	]);

	Route::get('detail_komunitas/{id}',[
	'middleware' => ['auth','role:admin'],
	'as' => 'komunitas.detail',
	'uses' => 'KomunitasController@detail_lihat_komunitas'
	]);

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
