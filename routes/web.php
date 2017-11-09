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
Route::get('/resize-all-file',function(){
	$barang =  App\Barang::where('foto','<>',null)->get();
	foreach ($barang as $barangs) {
		$image_resize = Image::make(public_path('foto_produk/' .$barangs->foto));              
		$image_resize->fit(300);
		$image_resize->save(public_path('foto_produk/' .$barangs->foto));
	}
	return $barang;
});

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

Route::get('/daftar-produk/pencarian/search',[
	'middleware' => ['auth'],
	'as' => 'daftar_produk.pencarian',
	'uses' => 'DaftarProdukController@pencarian'
	]);

//PUNYA KERANJANG BELANJAAN
Route::get('/keranjang-belanja',[ 
	'middleware' => ['auth'],
	'as' => 'keranjang_belanja.daftar_belanja',
	'uses' => 'KeranjangBelanjaController@daftar_belanja'
	]);

Route::get('/keranjang-belanja/tambah-jumlah-produk-keranjang-belanja/{id}',[
	'middleware' => ['auth'],
	'as' => 'keranjang-belanja.tambah_jumlah_produk_keranjang_belanjaan',
	'uses' => 'KeranjangBelanjaController@tambah_jumlah_produk_keranjang_belanjaan'
	]);

Route::get('/keranjang-belanja/kurang-jumlah-produk-keranjang-belanja/{id}',[
	'middleware' => ['auth'],
	'as' => 'keranjang-belanja.kurang_jumlah_produk_keranjang_belanjaan',
	'uses' => 'KeranjangBelanjaController@kurang_jumlah_produk_keranjang_belanjaan'
	]);

Route::get('/keranjang-belanja/hapus-produk-keranjang-belanja/{id}',[
	'middleware' => ['auth'],
	'as' => 'keranjang-belanja.hapus_produk_keranjang_belanjaan',
	'uses' => 'KeranjangBelanjaController@hapus_produk_keranjang_belanjaan'
	]);

Route::get('/keranjang-belanja/tambah-produk-keranjang-belanja/{id}',[
	'middleware' => ['auth'],
	'as' => 'keranjang-belanja.tambah_produk_keranjang_belanjaan',
	'uses' => 'KeranjangBelanjaController@tambah_produk_keranjang_belanjaan'
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

//UBAH PASSWORD PELANGGAN
Route::get('/ubah-password-pelanggan',[
	'middleware' => ['auth'],
	'as' => 'user.ubah_password_pelanggan',
	'uses' => 'UbahPasswordController@ubah_password_pelanggan'
	]);

//PROSES //UBAH PASSWORD PELANGGAN
Route::put('/proses-ubah-password-pelanggan/{id}',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_password_pelanggan',
	'uses' => 'UbahPasswordController@proses_ubah_password_pelanggan'
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

//UBAH PROFIL WARUNG
Route::get('/ubah-profil-warung',[
	'middleware' => ['auth'],
	'as' => 'user.ubah_profil_warung',
	'uses' => 'UbahProfilController@ubah_profil_warung'
]);

//PROSES UBAH PROFIL WARUNG
Route::put('/proses-ubah-profil-warung',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil_warung',
	'uses' => 'UbahProfilController@proses_ubah_profil_warung'
]);

//UBAH PROFIL KOMUNITAS
Route::get('/ubah-profil-komunitas',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil',
	'uses' => 'UbahProfilController@proses_ubah_profil'
	]);

//PROSES UBAH PROFIL KOMUNITAS
Route::put('/proses-ubah-profil-komunitas',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil_komunitas',
	'uses' => 'UbahProfilController@proses_ubah_profil_komunitas'
]);

//UBAH PROFIL ADMIN
Route::get('/ubah-profil-admin',[
	'middleware' => ['auth'],
	'as' => 'user.ubah_profil_admin',
	'uses' => 'UbahProfilController@ubah_profil_admin'
]);

//PROSES UBAH PROFIL ADMIN
Route::put('/proses-ubah-profil-admin',[
	'middleware' => ['auth'],
	'as' => 'user.proses_ubah_profil_admin',
	'uses' => 'UbahProfilController@proses_ubah_profil_admin'
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
Route::get('/user/selectize','UserController@selectize')->middleware('auth');
Route::get('/user/reset','UserController@reset_password')->middleware('auth');
Route::get('/user/konfirmasi','UserController@konfirmasi')->middleware('auth');
Route::get('/user/no-konfirmasi','UserController@no_konfirmasi')->middleware('auth');


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
	 // DATATABEL DETAIL FAKTUR PEMBELIAN 
	Route::post('pembelian/detail-faktur-pembelian', [ 
		'middleware' => ['auth'], 
		'as'   => 'datatable_detail_faktur_beli', 
		'uses' => 'PembelianController@datatableFakturPembelian' 
	]); 

	// PROSES FROM EDIT BELI
	Route::get('/pembelian/proses-form-edit/{id}',[
		'middleware' => ['auth'],
		'as' => 'pembelian.form_edit_beli',
		'uses' => 'PembelianController@proses_form_edit'
		]);
	// END PEMBELIAN

	// EDIT PEMBELIAN	
	// TAMBAH TBS PEMBELIAN
	Route::post('/edit/proses-tambah-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.proses_tambah_tbs_pembelian',
		'uses' => 'EditPembelianController@proses_tambah_tbs_pembelian'
		]);

	// EDIT JUMLAH TBS PEMBELIAN
	Route::post('/editPembelian/proses-edit-jumlah-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.edit_jumlah_tbs_pembelian',
		'uses' => 'EditPembelianController@edit_jumlah_tbs_pembelian'
		]);	

	// EDIT HARGA TBS PEMBELIAN
	Route::post('/editPembelian/proses-edit-harga-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.edit_harga_tbs_pembelian',
		'uses' => 'EditPembelianController@edit_harga_tbs_pembelian'
		]);	
	// EDIT POTONGAN TBS PEMBELIAN
	Route::post('/editPembelian/proses-edit-potongan-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.edit_potongan_tbs_pembelian',
		'uses' => 'EditPembelianController@edit_potongan_tbs_pembelian'
		]);	

		// EDIT TAX TBS PEMBELIAN
	Route::post('/editPembelian/proses-edit-tax-tbs-pembelian',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.edit_tax_tbs_pembelian',
		'uses' => 'EditPembelianController@editTaxTbsPembelian'
		]);	
	// HAPUS TBS PEMBELIAN
	Route::delete('/editPembelian/hapus-tbs-pembelian/{id}',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.hapus_tbs_pembelian',
		'uses' => 'EditPembelianController@hapus_tbs_pembelian'
		]);
	// batal
	Route::post('/editPembelian/batal-transaksi-pembelian/',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.batal_transaksi_pembelian',
		'uses' => 'EditPembelianController@proses_batal_transaksi_pembelian'
		]);
	// PROSES EDIT PEMBELIAN 
	Route::post('/editPembelian/proses-edit-pembelian',[
		'middleware' => ['auth'],
		'as' => 'editPembelian.prosesEditPembelian',
		'uses' => 'EditPembelianController@prosesEditPembelian'
		]);
	// EDIT PEMBELIAN

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
