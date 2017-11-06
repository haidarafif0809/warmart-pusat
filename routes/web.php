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
	'middleware' => ['auth','optimizeImages'],
	'as'=>'home.dashboard',
	'uses' => 'HomeController@dashboard'
]);

Route::get('/daftar-produk',[
	'middleware' => ['auth'],
	'as' => 'daftar_produk.index',
	'uses' => 'DaftarProdukController@index'
]);

Route::get('/daftar-produk/{id}',[
	'middleware' => ['auth'],
	'as' => 'daftar_produk.filter_kategori',
	'uses' => 'DaftarProdukController@filter_kategori'
]);

Route::get('/keranjang-belanja',[ 
	'middleware' => ['auth'],
	'as' => 'keranjang_belanja.daftar_belanja',
	'uses' => 'KeranjangBelanjaController@daftar_belanja'
]);

Route::middleware('optimizeImages')->group(function () {

	Auth::routes();

});

//sarat & ketentuan
Route::get('/syarat-ketentuan','Auth\RegisterController@syarat_ketentuan'); 


//USER WARUNG 
Route::get('/register-warung','Auth\RegisterController@register_warung')->middleware('optimizeImages'); 

Route::get('/register-customer','Auth\RegisterController@register_customer')->middleware('optimizeImages');

//registrasi lewat link affiliasi
Route::get('/aff/{id}/',function($id){


	return view('auth.register_customer',['komunitas_id' => $id]);
})->middleware('optimizeImages'); 


Route::get('kirim-kode-verifikasi','Auth\RegisterController@kirim_kode_verifikasi')->middleware('optimizeImages'); 
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

Route::get('/home', 'HomeController@index_home')->name('home');
Route::get('/dashboard-admin', 'HomeController@dashboard_admin')->middleware(['auth','user-must-admin']);

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

//UBAH PROFIL PELANGGAN
Route::get('/ubah-profil-pelanggan',[
	'middleware' => ['auth'],
	'as' => 'user.ubah_profil_pelanggan',
	'uses' => 'UbahProfilController@ubah_profil_pelanggan'
]);

//PROSES UBAH PROFIL PELANGGAN
Route::put('/proses-ubah-profil-pelanggan',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil_pelanggan',
	'uses' => 'UbahProfilController@proses_ubah_profil_pelanggan'
]);

Route::put('/proses-ubah-profil/{id}',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil',
	'uses' => 'UbahProfilController@proses_ubah_profil'
]);

//menampilkan data bank
Route::get('/bank/view','BankController@view')->middleware('auth');
Route::get('/bank/pencarian','BankController@pencarian')->middleware('auth');


//menampilkan data satuan
Route::get('/satuan/view','SatuanController@view')->middleware('auth');
Route::get('/satuan/pencarian','SatuanController@pencarian')->middleware('auth');


//menampilkan data user
Route::get('/user/view','UserController@view')->middleware('auth');
Route::get('/user/pencarian','UserController@pencarian')->middleware('auth');

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
	Route::resource('error', 'ErrorController');
	Route::resource('item-masuk', 'ItemMasukController');
	Route::resource('item-keluar', 'ItemKeluarController');
	Route::resource('suplier', 'SuplierController');
	Route::resource('satuan', 'SatuanController');
	Route::resource('laporan-persediaan', 'LaporanPersediaanController');
	Route::resource('pembelian', 'PembelianController');

//BARANG
	//HALAMAN DESKRIPSI
	Route::get('/barang/detail-produk/{id}',[
		'middleware' => ['auth'],
		'as' 	=> 'barang.detail_produk',
		'uses'	=> 'BarangController@detail_produk'
	]);

	//PROSES UPDATE DESKRIPSI
	Route::post('/barang/update-deskripsi-produk',[
		'middleware' => ['auth'],
		'as' 	=> 'barang.update_deskripsi_produk',
		'uses'	=> 'BarangController@update_deskripsi_produk'
	]);	

	//LIHAT DESKRIPSI
	Route::get('/barang/lihat-deskripsi-produk/{id}',[
		'middleware' => ['auth'],
		'as' 	=> 'barang.lihat_deskripsi_produk',
		'uses'	=> 'BarangController@lihat_deskripsi_produk'
	]);	

	// PEMBELIAN

	// TAMBAH TBS PEMBELIAN
	Route::post('/pembelian/proses-tambah-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'pembelian.proses_tambah_tbs_pembelian',
		'uses' => 'PembelianController@proses_tambah_tbs_pembelian'
	]);

	// EDIT JUMLAH TBS PEMBELIAN
	Route::post('/pembelian/proses-edit-jumlah-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'pembelian.edit_jumlah_tbs_pembelian',
		'uses' => 'PembelianController@edit_jumlah_tbs_pembelian'
	]);	

	// EDIT HARGA TBS PEMBELIAN
	Route::post('/pembelian/proses-edit-harga-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'pembelian.edit_harga_tbs_pembelian',
		'uses' => 'PembelianController@edit_harga_tbs_pembelian'
	]);	

	// EDIT POTONGAN TBS PEMBELIAN
	Route::post('/pembelian/proses-edit-potongan-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'pembelian.edit_potongan_tbs_pembelian',
		'uses' => 'PembelianController@edit_potongan_tbs_pembelian'
	]);	

		// EDIT TAX TBS PEMBELIAN
	Route::post('/pembelian/proses-edit-tax-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'pembelian.edit_tax_tbs_pembelian',
		'uses' => 'PembelianController@editTaxTbsPembelian'
	]);	

	// HAPUS TBS PEMBELIAN
	Route::delete('/pembelian/hapus-tbs-pembelian/{id}',[
		'middleware' => ['auth'],
		'as' => 'pembelian.hapus_tbs_pembelian',
		'uses' => 'PembelianController@hapus_tbs_pembelian'
	]);
	Route::post('/pembelian/batal-transaksi-pembelian/',[
		'middleware' => ['auth'],
		'as' => 'pembelian.batal_transaksi_pembelian',
		'uses' => 'PembelianController@proses_batal_transaksi_pembelian'
	]);
	 // DATATABEL DETAIL PEMBELIAN 
	Route::post('pembelian/detail-pembelian', [ 
		'middleware' => ['auth'], 
		'as'   => 'datatable_detail.pembelian', 
		'uses' => 'PembelianController@datatableDetailPembelian' 
	]); 
	//PEMBELIAN

//ITEM KELUAR
	Route::post('/item-keluar/proses-tambah-tbs-item-keluar',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_tambah_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_tambah_tbs_item_keluar'
	]);	

	Route::post('/item-keluar/proses-hapus-semua-tbs-item-keluar/',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_hapus_semua_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_hapus_semua_tbs_item_keluar'
	]);

	Route::delete('/item-keluar/proses-hapus-tbs-item-keluar/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_hapus_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_hapus_tbs_item_keluar'
	]);

	Route::post('/item-masuk/proses-hapus-semua-edit-tbs-item-keluar/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_hapus_semua_edit_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_hapus_semua_edit_tbs_item_keluar'
	]);

	Route::post('/item-keluar/proses-edit-item-keluar/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_edit_item_keluar',
		'uses' => 'ItemKeluarController@proses_edit_item_keluar'
	]);

	Route::get('/item-keluar/proses-form-edit/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_form_edit',
		'uses' => 'ItemKeluarController@proses_form_edit'
	]);
	Route::post('/item-keluar/proses-tambah-edit-tbs-item-keluar/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_tambah_edit_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_tambah_edit_tbs_item_keluar'
	]);
	Route::delete('/item-keluar/proses-hapus-edit-tbs-item-keluar/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.proses_hapus_edit_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_hapus_edit_tbs_item_keluar'
	]);

	Route::post('/item-keluar/edit-jumlah-tbs-item-keluar',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.edit_jumlah_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_edit_jumlah_tbs_item_keluar'
	]);

	Route::post('/item-keluar/edit-jumlah-edit-tbs-item-keluar',[
		'middleware' => ['auth'],
		'as' => 'item-keluar.edit_jumlah_edit_tbs_item_keluar',
		'uses' => 'ItemKeluarController@proses_edit_jumlah_edit_tbs_item_keluar'
	]);

//ITEM KELUAR

	Route::post('/cek_total_kas',[ 		
		'middleware' => ['auth'],
		'as' => 'cek_total_kas',
		'uses' => 'TransaksikasController@total_kas'
	]);

//KOMUNITAS
	Route::get('detail_komunitas/{id}',[
		'middleware' => ['auth','role:admin'],
		'as' => 'komunitas.detail',
		'uses' => 'KomunitasController@detail_lihat_komunitas'
	]);

	Route::get('komunitas/konfirmasi/{id}',[
		'middleware' => ['auth','role:admin'],
		'as' => 'komunitas.konfirmasi',
		'uses' => 'KomunitasController@konfirmasi'
	]);

	Route::get('komunitas/no_konfirmasi/{id}',[
		'middleware' => ['auth'],
		'as' => 'komunitas.no_konfirmasi',
		'uses' => 'KomunitasController@no_konfirmasi'
	]);	

//USER WARUNG
	Route::get('user_warung/konfirmasi/{id}',[
		'middleware' => ['auth','role:admin'],
		'as' => 'user_warung.konfirmasi',
		'uses' => 'UserWarungController@konfirmasi'
	]);

	Route::get('user_warung/no_konfirmasi/{id}',[
		'middleware' => ['auth'],
		'as' => 'user_warung.no_konfirmasi',
		'uses' => 'UserWarungController@no_konfirmasi'
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


//ITEM MASUK
	Route::get('/item-masuk/proses-form-edit/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_form_edit',
		'uses' => 'ItemMasukController@proses_form_edit'
	]);

	Route::post('/item-masuk/proses-tambah-tbs-item-masuk',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_tambah_tbs_item_masuk',
		'uses' => 'ItemMasukController@proses_tambah_tbs_item_masuk'
	]);

	Route::post('/item-masuk/proses-tambah-edit-tbs-item-masuk/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_tambah_edit_tbs_item_masuk',
		'uses' => 'ItemMasukController@proses_tambah_edit_tbs_item_masuk'
	]);

	Route::post('/item-masuk/proses-hapus-tbs-item-masuk/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_hapus_tbs_item_masuk',
		'uses' => 'ItemMasukController@proses_hapus_tbs_item_masuk'
	]);

	Route::post('/item-masuk/proses-hapus-edit-tbs-item-masuk/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_hapus_edit_tbs_item_masuk',
		'uses' => 'ItemMasukController@proses_hapus_edit_tbs_item_masuk'
	]);

	Route::post('/item-masuk/proses-hapus-semua-tbs-item-masuk/',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_hapus_semua_tbs_item_masuk',
		'uses' => 'ItemMasukController@proses_hapus_semua_tbs_item_masuk'
	]);

	Route::post('/item-masuk/proses-hapus-semua-edit-tbs-item-masuk/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_hapus_semua_edit_tbs_item_masuk',
		'uses' => 'ItemMasukController@proses_hapus_semua_edit_tbs_item_masuk'
	]);

	Route::post('/item-masuk/proses-edit-item-masuk/{id}',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.proses_edit_item_masuk',
		'uses' => 'ItemMasukController@proses_edit_item_masuk'
	]);

	Route::post('/item-masuk/edit-jumlah-item-masuk',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.edit_jumlah',
		'uses' => 'ItemMasukController@proses_edit_jumlah'
	]);

	Route::post('/item-masuk/edit-jumlah-edit-item-masuk',[
		'middleware' => ['auth'],
		'as' => 'item-masuk.edit_jumlah_edit',
		'uses' => 'ItemMasukController@proses_edit_jumlah_edit'
	]);
});
