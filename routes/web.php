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

Route::get('/', 'DaftarProdukController@index')->middleware('optimizeImages');
Route::get('/sms', 'HomeController@sms');

Route::get('copy-produk-alfatih', function () {
    //

    $produk = App\Barang::where('id_warung', 73)->get();
    foreach ($produk as $key) {
        App\Barang::where('nama_barang', $key->nama_barang)->update(['foto' => $key->foto]);
    }
});

Route::get('/tentang-warmart', 'HomeController@index');

Route::get('/cek-deposit', 'PortaPulsaController@cekDeposit');

Route::get('/cek-harga-pulsa/{pilihan}', 'PortaPulsaController@cekHargaPulsa');
Route::get('/cek-status-pulsa/{notrx_id}', 'PortaPulsaController@statusTransaksi');

Route::get('/perbarui-harga-pulsa', 'PortaPulsaController@perbaruiDataHargaPulsa');
Route::get('/callback-portal-pulsa', 'PortaPulsaController@callback');

Route::get('/pencarian_contoh/{search}', function ($search) {
    return App\Barang::search($search)->where('konfirmasi_admin', 1)->get();
});

Route::get('/update_produk', function () {

    $barang = App\Barang::all();
    foreach ($barang as $key) {
        $barang                   = App\Barang::find($key->id);
        $barang->konfirmasi_admin = 1;
        $barang->save();
    }
});

Route::get('/resize-all-file', function () {
    $barang = App\Barang::where('foto', '!=', null);
    foreach ($barang->get() as $barangs) {
        $image_resize = Image::make(public_path('foto_produk/' . $barangs->foto));
        $image_resize->fit(300);
        $image_resize->save(public_path('foto_produk/' . $barangs->foto));
    }
    return $barang->count();
});
Route::get('/resize-file', function () {

    $image_resize = Image::make(public_path('foto_produk/YVV306YQr5ZRCru9IzrUbxmZ0FeRe2TVDqPMmzw5.png'));
    $image_resize->fit(300);
    $image_resize->save(public_path('foto_produk/YVV306YQr5ZRCru9IzrUbxmZ0FeRe2TVDqPMmzw5.png'));
});

Route::get('/dashboard', [
    'middleware' => ['auth', 'optimizeImages'],
    'as'         => 'home.dashboard',
    'uses'       => 'HomeController@dashboard',
]);

//PUNYA DAFTAR PRODUK
Route::get('/daftar-produk', [
    'middleware' => ['auth'],
    'as'         => 'daftar_produk.index',
    'uses'       => 'DaftarProdukController@index',
]);

Route::get('/daftar-produk/{id}', [
    'middleware' => ['auth'],
    'as'         => 'daftar_produk.filter_kategori',
    'uses'       => 'DaftarProdukController@filter_kategori',
]);

Route::get('/daftar-produk/pencarian/search', [
    'as'   => 'daftar_produk.pencarian',
    'uses' => 'DaftarProdukController@pencarian',
]);

//PUNYA DETAIL PRODUK
Route::get('/detail-produk/{id}', [
    'as'   => 'detail-produk.detail_produk',
    'uses' => 'DetailProdukController@detail_produk',
]);

//PUNYA HALAMAN WARUNG
Route::get('/halaman-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'halaman-warung.halaman_warung',
    'uses'       => 'HalamanWarungController@index',
]);

Route::get('/halaman-warung/filter/{id}/{id_warung}/', [
    'middleware' => ['auth'],
    'as'         => 'halaman_warung.filter_kategori',
    'uses'       => 'HalamanWarungController@filter_kategori',
]);

Route::get('/halaman-warung/pencarian/search', [
    'middleware' => ['auth'],
    'as'         => 'halaman_warung.pencarian',
    'uses'       => 'HalamanWarungController@pencarian',
]);

//PUNYA KERANJANG BELANJAAN
Route::get('/keranjang-belanja', [
    'middleware' => ['auth'],
    'as'         => 'keranjang_belanja.daftar_belanja',
    'uses'       => 'KeranjangBelanjaController@daftar_belanja',
]);

Route::get('/keranjang-belanja/tambah-jumlah-produk-keranjang-belanja/{id}', [
    'middleware' => ['auth'],
    'as'         => 'keranjang-belanja.tambah_jumlah_produk_keranjang_belanjaan',
    'uses'       => 'KeranjangBelanjaController@tambah_jumlah_produk_keranjang_belanjaan',
]);

Route::get('/keranjang-belanja/kurang-jumlah-produk-keranjang-belanja/{id}', [
    'middleware' => ['auth'],
    'as'         => 'keranjang-belanja.kurang_jumlah_produk_keranjang_belanjaan',
    'uses'       => 'KeranjangBelanjaController@kurang_jumlah_produk_keranjang_belanjaan',
]);

Route::get('/keranjang-belanja/hapus-produk-keranjang-belanja/{id}', [
    'middleware' => ['auth'],
    'as'         => 'keranjang-belanja.hapus_produk_keranjang_belanjaan',
    'uses'       => 'KeranjangBelanjaController@hapus_produk_keranjang_belanjaan',
]);

Route::get('/keranjang-belanja/tambah-produk-keranjang-belanja/{id}', [
    'middleware' => ['auth'],
    'as'         => 'keranjang-belanja.tambah_produk_keranjang_belanjaan',
    'uses'       => 'KeranjangBelanjaController@tambah_produk_keranjang_belanjaan',
]);

//PUNYA SELESAI PEMESANAN
Route::get('/selesaikan-pemesanan', [
    'middleware' => ['auth'],
    'as'         => 'selesaikan-pemesanan.index',
    'uses'       => 'PemesananController@selesaikanPemesanan',
]);

//PUNYA PROSES SELESAI PEMESANAN
Route::put('proses/selesaikan-pemesanan', [
    'middleware' => ['auth'],
    'as'         => 'selesaikan-pemesanan.proses',
    'uses'       => 'PemesananController@prosesSelesaikanPemesanan',
]);

//PUNYA PESANAN PELANGGAN
Route::get('/pesanan', [
    'middleware' => ['auth'],
    'as'         => 'pesanan.index',
    'uses'       => 'PesananPelangganController@pesananPelanggan',
]);

//PUNYA DETAIL PESANAN PELANGGAN
Route::get('pesanan-detail/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan.detail',
    'uses'       => 'PesananPelangganController@detailPesananPelanggan',
]);

//PUNYA PESANAN WARUNG
Route::get('/pesanan-warung', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.index',
    'uses'       => 'PesananWarungController@pesananWarung',
]);

//PUNYA PESANAN WARUNG
Route::get('detail-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.detail',
    'uses'       => 'PesananWarungController@detailPesananWarung',
]);

//PUNYA KONFIRMASI PESANAN WARUNG
Route::get('konfirmasi-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.konfirmasi',
    'uses'       => 'PesananWarungController@konfirmasiPesananWarung',
]);

//PUNYA SELESAI KONFIRMASI PESANAN WARUNG
Route::post('/selesai-konfirmasi-pesanan-warung}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.selesai_konfirmasi',
    'uses'       => 'PesananWarungController@selesaiKonfirmasiPesananWarung',
]);

//PUNYA BATALKAN KONFIRMASI PESANAN WARUNG
Route::get('batalkan-konfirmasi-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.batalkan_konfirmasi',
    'uses'       => 'PesananWarungController@batalkanKonfirmasiPesananWarung',
]);

//PUNYA BATALKAN PESANAN WARUNG
Route::get('batalkan-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.batalkan',
    'uses'       => 'PesananWarungController@batalkanPesananWarung',
]);

//PUNYA BATALKAN PESANAN WARUNG
Route::get('batalkan-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.batalkan',
    'uses'       => 'PesananWarungController@batalkanPesananWarung',
]);

//PUNYA TAMBAH JUMLAH PRODUK PESANAN WARUNG
Route::get('tambah-produk-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.tambah',
    'uses'       => 'PesananWarungController@tambahProdukPesananWarung',
]);

//PUNYA KURANG JUMLAH PRODUK PESANAN WARUNG
Route::get('kurang-produk-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.kurang',
    'uses'       => 'PesananWarungController@kurangProdukPesananWarung',
]);

//PUNYA EDIT JUMLAH  PESANAN WARUNG
Route::post('/edit-jumlah-pesanan-warung}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.edit_jumlah_pesanan',
    'uses'       => 'PesananWarungController@editJumlahPesanan',
]);

//PUNYA

Route::middleware('optimizeImages')->group(function () {

    Auth::routes();

});

//sarat & ketentuan
Route::get('/syarat-ketentuan', 'Auth\RegisterController@syarat_ketentuan');

//USER WARUNG
Route::get('/register-warung', 'Auth\RegisterController@register_warung')->middleware('optimizeImages');

Route::get('/register-customer', 'Auth\RegisterController@register_customer')->middleware('optimizeImages');

//registrasi lewat link affiliasi
Route::get('/aff/{id}/', function ($id) {

    return view('auth.register_customer', ['komunitas_id' => $id]);
})->middleware('optimizeImages');

Route::get('kirim-kode-verifikasi', 'Auth\RegisterController@kirim_kode_verifikasi')->middleware('optimizeImages');
Route::get('kirim-ulang-kode-verifikasi/{id}', 'Auth\RegisterController@kirim_ulang_kode_verifikasi');
Route::get('lupa-password', 'Auth\RegisterController@lupa_password');

Route::put('/proses-kirim-kode-verifikasi/{nomor_hp}', [
    'as'   => 'user.proses_kirim_kode_verifikasi',
    'uses' => 'Auth\RegisterController@proses_kirim_kode_verifikasi',
]);

Route::post('/proses-lupa-password', [
    'as'   => 'user.proses_lupa_password',
    'uses' => 'Auth\RegisterController@proses_lupa_password',
]);

Route::get('/home', 'HomeController@index_home')->name('home');
Route::get('/dashboard-admin', 'HomeController@dashboard_admin')->middleware(['auth', 'user-must-admin']);
Route::get('/dashboard-warung', 'HomeController@dashboard_warung')->middleware(['auth', 'user-must-warung']);

Route::get('/ubah-password', [
    'middleware' => ['auth'],
    'as'         => 'user.ubah_password',
    'uses'       => 'UbahPasswordController@ubah_password',
]);

Route::put('/proses-ubah-password', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_password',
    'uses'       => 'UbahPasswordController@proses_ubah_password',
]);

//UBAH PASSWORD PELANGGAN
Route::get('/ubah-password-pelanggan', [
    'middleware' => ['auth'],
    'as'         => 'user.ubah_password_pelanggan',
    'uses'       => 'UbahPasswordController@ubah_password_pelanggan',
]);

//PROSES //UBAH PASSWORD PELANGGAN
Route::put('/proses-ubah-password-pelanggan/{id}', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_password_pelanggan',
    'uses'       => 'UbahPasswordController@proses_ubah_password_pelanggan',
]);

//UBAH PROFIL PELANGGAN
Route::get('/ubah-profil-pelanggan', [
    'middleware' => ['auth'],
    'as'         => 'user.ubah_profil_pelanggan',
    'uses'       => 'UbahProfilController@ubah_profil_pelanggan',
]);

// PROSES CARI PROVINSI
Route::get('/cek-wilayah', [
    'middleware' => ['auth'],
    'as'         => 'cek_wilayah',
    'uses'       => 'UbahProfilController@cek_wilayah',
]);

//PROSES UBAH PROFIL PELANGGAN
Route::put('/proses-ubah-profil-pelanggan', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_profil_pelanggan',
    'uses'       => 'UbahProfilController@proses_ubah_profil_pelanggan',
]);

//UBAH PROFIL WARUNG
Route::get('/ubah-profil-user-warung', [
    'middleware' => ['auth'],
    'as'         => 'user.ubah_profil_warung',
    'uses'       => 'UbahProfilController@ubah_profil_user_warung',
]);

//PROSES UBAH PROFIL WARUNG
Route::post('/ubah-profil-user-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_profil_warung',
    'uses'       => 'UbahProfilController@proses_ubah_profil_warung',
]);

//UBAH PROFIL KOMUNITAS
Route::get('/ubah-profil-komunitas', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_profil',
    'uses'       => 'UbahProfilController@proses_ubah_profil',
]);

//PROSES UBAH PROFIL KOMUNITAS
Route::put('/proses-ubah-profil-komunitas', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_profil_komunitas',
    'uses'       => 'UbahProfilController@proses_ubah_profil_komunitas',
]);

//UBAH PROFIL ADMIN
Route::get('/ubah-profil-admin', [
    'middleware' => ['auth'],
    'as'         => 'user.ubah_profil_admin',
    'uses'       => 'UbahProfilController@ubah_profil_admin',
]);

//PROSES UBAH PROFIL ADMIN
Route::put('/proses-ubah-profil-admin', [
    'middleware' => ['auth'],
    'as'         => 'user.proses_ubah_profil_admin',
    'uses'       => 'UbahProfilController@proses_ubah_profil_admin',
]);

//menampilkan data bank
Route::get('/bank/view', 'BankController@view')->middleware('auth');
Route::get('/bank/pencarian', 'BankController@pencarian')->middleware('auth');

//menampilkan data satuan
Route::get('/satuan/view', 'SatuanController@view')->middleware('auth');
Route::get('/satuan/pencarian', 'SatuanController@pencarian')->middleware('auth');

//MENAMPILKAN DATA CUSTOMER
Route::get('/customer/view', 'CustomerController@view')->middleware('auth');
Route::get('/customer/view-detail/{id}', 'CustomerController@view_detail')->middleware('auth');
Route::get('/customer/pencarian', 'CustomerController@pencarian')->middleware('auth');
Route::get('/customer/pilih-komunitas', 'CustomerController@pilih_komunitas')->middleware('auth');

//menampilkan data user
Route::get('/user/view', 'UserController@view')->middleware('auth');
Route::get('/user/pencarian', 'UserController@pencarian')->middleware('auth');
Route::get('/user/otoritas-user', 'UserController@otoritas_user')->middleware('auth');
Route::get('/user/reset', 'UserController@reset_password')->middleware('auth');
Route::get('/user/konfirmasi', 'UserController@konfirmasi')->middleware('auth');
Route::get('/user/no-konfirmasi', 'UserController@no_konfirmasi')->middleware('auth');

//MENAMPILKAN DARA ERROR LOG
Route::get('/error/view', 'ErrorController@view')->middleware('auth');
Route::get('/error/pencarian', 'ErrorController@pencarian')->middleware('auth');

//MENAMPILKAN DATA USER WARUNG
Route::get('/user-warung/view', 'UserWarungController@view')->middleware('auth');
Route::get('/user-warung/pencarian', 'UserWarungController@pencarian')->middleware('auth');
Route::get('user-warung/konfirmasi', 'UserWarungController@konfirmasi')->middleware('auth');
Route::get('user-warung/no-konfirmasi', 'UserWarungController@no_konfirmasi')->middleware('auth');
Route::get('/user-warung/pilih-kelurahan', 'UserWarungController@pilih_kelurahan')->middleware('auth');
Route::get('/user-warung/pilih-warung', 'UserWarungController@pilih_warung')->middleware('auth');

//menampilkan data user
Route::get('/warung/view', 'WarungController@view')->middleware('auth');
Route::get('/warung/pencarian', 'WarungController@pencarian')->middleware('auth');
Route::get('/warung/pilih-kelurahan', 'WarungController@pilih_kelurahan')->middleware('auth');

// KOMUNITAS VUE.JS
Route::get('/komunitas/view', 'KomunitasController@view')->middleware('auth');
Route::get('/komunitas/pencarian', 'KomunitasController@pencarian')->middleware('auth');
Route::get('/komunitas/konfirmasi', 'KomunitasController@konfirmasi')->middleware('auth');
Route::get('/komunitas/no-konfirmasi', 'KomunitasController@no_konfirmasi')->middleware('auth');
Route::get('/komunitas/warung-komunitas', 'KomunitasController@warungKomunitas')->middleware('auth');
Route::get('/komunitas/kelurahan-komunitas', 'KomunitasController@keluarahanKomunitas')->middleware('auth');
Route::get('/komunitas/detail-komunitas/{id}', 'KomunitasController@detail_lihat_komunitas')->middleware('auth');

// KELOMPOK PRODUK VUE.JS
Route::get('/kelompok-produk/view', 'KelompokProdukController@view')->middleware('auth');
Route::get('/kelompok-produk/pencarian', 'KelompokProdukController@pencarian')->middleware('auth');

//PROFIL WARUNG VUE.JS
Route::get('/profil-warung/view', 'WarungProfilController@view')->middleware('auth');
Route::get('/profil-warung/pencarian', 'WarungProfilController@pencarian')->middleware('auth');
Route::get('/profil-warung/pilih-provinsi', 'WarungProfilController@pilih_provinsi')->middleware('auth');
Route::get('/profil-warung/pilih-wilayah/{id}/{type}', 'WarungProfilController@pilih_wilayah')->middleware('auth');
Route::get('/profil-warung/view-detail/{id}', 'WarungProfilController@view_detail')->middleware('auth');

//KATEGORI TRANSAKSI VUE.JS
Route::get('/kategori-transaksi/view', 'KategoriTransaksiController@view')->middleware('auth');
Route::get('/kategori-transaksi/pencarian', 'KategoriTransaksiController@pencarian')->middleware('auth');

//SUPLIER VUE.JS
Route::get('/suplier/view', 'SuplierController@view')->middleware('auth');
Route::get('/suplier/pencarian', 'SuplierController@pencarian')->middleware('auth');

//PRODUK VUE.JS
Route::get('/produk/view', 'BarangController@view')->middleware('auth');
Route::get('/produk/pencarian', 'BarangController@pencarian')->middleware('auth');
Route::get('/produk/pilih-kategori', 'BarangController@pilih_kategori')->middleware('auth');
Route::get('/produk/pilih-satuan', 'BarangController@pilih_satuan')->middleware('auth');
Route::post('/produk/{id}', 'BarangController@update')->middleware('auth');
Route::get('/produk/pilih-produk', 'BarangController@pilihProduk')->middleware('auth');

Route::get('/kas/view', 'KasController@view')->middleware('auth');
Route::get('/kas/pencarian', 'KasController@pencarian')->middleware('auth');
Route::get('/kas/cek-default-kas', 'KasController@cekDefaultKas')->middleware('auth');
Route::get('/kas/cek-kas-warung', 'KasController@cekKasWarung')->middleware('auth');

// ITEM MASUK
Route::get('/item-masuk/view', 'ItemMasukController@view')->middleware('auth');
Route::get('/item-masuk/pencarian', 'ItemMasukController@pencarian')->middleware('auth');
Route::get('/item-masuk/view-tbs-item-masuk', 'ItemMasukController@viewTbsItemMasuk')->middleware('auth');
Route::get('/item-masuk/pencarian-tbs-item-masuk', 'ItemMasukController@pencarianTbsItemMasuk')->middleware('auth');
//KAS KELUAR VUE.JS
Route::get('/kas-keluar/view', 'KasKeluarController@view')->middleware('auth');
Route::get('/kas-keluar/pencarian', 'KasKeluarController@pencarian')->middleware('auth');

//KAS MASUK VUE JS
Route::get('/kas-masuk/view', 'KasMasukController@view')->middleware('auth');
Route::get('/kas-masuk/pencarian', 'KasMasukController@pencarian')->middleware('auth');
Route::get('/kas-masuk/pilih-kas', 'KasMasukController@pilih_kas')->middleware('auth');
Route::get('/kas-masuk/pilih-kategori', 'KasMasukController@pilih_kategori')->middleware('auth');
Route::get('/kas-masuk/cek-kas-terpakai/{id}', 'KasMasukController@cekKasTerpakai')->middleware('auth');

//PESANAN WARUNG VUE.JS
Route::get('/pesanan-warung/view', 'PesananWarungController@view')->middleware('auth');
Route::get('/pesanan-warung/detail-view/{id}', 'PesananWarungController@detailView')->middleware('auth');
Route::get('/pesanan-warung/pencarian', 'PesananWarungController@pencarian')->middleware('auth');
Route::get('/pesanan-warung/detail/{id}', 'PesananWarungController@detailPesanan')->middleware('auth');
Route::post('/edit-jumlah-produk-warung', 'PesananWarungController@editJumlahPesanan')->middleware('auth');

Route::middleware('optimizeImages', 'auth')->group(function () {

    Route::resource('user', 'UserController');
    Route::resource('bank', 'BankController');
    Route::resource('komunitas', 'KomunitasController');
    Route::resource('warung', 'WarungController');
    Route::resource('customer', 'CustomerController');
    Route::resource('otoritas', 'OtoritasController');
    Route::resource('user-warung', 'UserWarungController');
    Route::resource('kas', 'KasController');
    Route::resource('produk', 'BarangController');
    Route::resource('kategori-transaksi', 'KategoriTransaksiController');
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
    Route::resource('kelompok-produk', 'KelompokProdukController');
    Route::resource('profil-warung', 'WarungProfilController');

//BARANG
    //HALAMAN DESKRIPSI
    Route::get('/produk/detail-produk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'produk.detail_produk',
        'uses'       => 'BarangController@detail_produk',
    ]);

    //PROSES UPDATE DESKRIPSI
    Route::put('/update-deskripsi', [
        'middleware' => ['auth'],
        'as'         => 'produk.update_deskripsi_produk',
        'uses'       => 'BarangController@update_deskripsi_produk',
    ]);

    //LIHAT DESKRIPSI
    Route::get('/produk/lihat-deskripsi-produk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'produk.lihat_deskripsi_produk',
        'uses'       => 'BarangController@lihat_deskripsi_produk',
    ]);

    // PEMBELIAN

    // TAMBAH TBS PEMBELIAN
    Route::post('/pembelian/proses-tambah-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.proses_tambah_tbs_pembelian',
        'uses'       => 'PembelianController@proses_tambah_tbs_pembelian',
    ]);

    // EDIT JUMLAH TBS PEMBELIAN
    Route::post('/pembelian/proses-edit-jumlah-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.edit_jumlah_tbs_pembelian',
        'uses'       => 'PembelianController@edit_jumlah_tbs_pembelian',
    ]);

    // EDIT HARGA TBS PEMBELIAN
    Route::post('/pembelian/proses-edit-harga-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.edit_harga_tbs_pembelian',
        'uses'       => 'PembelianController@edit_harga_tbs_pembelian',
    ]);

    // EDIT POTONGAN TBS PEMBELIAN
    Route::post('/pembelian/proses-edit-potongan-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.edit_potongan_tbs_pembelian',
        'uses'       => 'PembelianController@edit_potongan_tbs_pembelian',
    ]);

    // EDIT TAX TBS PEMBELIAN
    Route::post('/pembelian/proses-edit-tax-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.edit_tax_tbs_pembelian',
        'uses'       => 'PembelianController@editTaxTbsPembelian',
    ]);

    // HAPUS TBS PEMBELIAN
    Route::delete('/pembelian/hapus-tbs-pembelian/{id}', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.hapus_tbs_pembelian',
        'uses'       => 'PembelianController@hapus_tbs_pembelian',
    ]);
    Route::post('/pembelian/batal-transaksi-pembelian/', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.batal_transaksi_pembelian',
        'uses'       => 'PembelianController@proses_batal_transaksi_pembelian',
    ]);
    // DATATABEL DETAIL PEMBELIAN
    Route::post('pembelian/detail-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'datatable_detail.pembelian',
        'uses'       => 'PembelianController@datatableDetailPembelian',
    ]);
    // DATATABEL DETAIL FAKTUR PEMBELIAN
    Route::post('pembelian/detail-faktur-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'datatable_detail_faktur_beli',
        'uses'       => 'PembelianController@datatableFakturPembelian',
    ]);

    // PROSES FROM EDIT BELI
    Route::get('/pembelian/proses-form-edit/{id}', [
        'middleware' => ['auth'],
        'as'         => 'pembelian.form_edit_beli',
        'uses'       => 'PembelianController@proses_form_edit',
    ]);
    // END PEMBELIAN

    // EDIT PEMBELIAN
    // TAMBAH TBS PEMBELIAN
    Route::post('/edit/proses-tambah-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.proses_tambah_tbs_pembelian',
        'uses'       => 'EditPembelianController@proses_tambah_tbs_pembelian',
    ]);

    // EDIT JUMLAH TBS PEMBELIAN
    Route::post('/editPembelian/proses-edit-jumlah-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.edit_jumlah_tbs_pembelian',
        'uses'       => 'EditPembelianController@edit_jumlah_tbs_pembelian',
    ]);

    // EDIT HARGA TBS PEMBELIAN
    Route::post('/editPembelian/proses-edit-harga-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.edit_harga_tbs_pembelian',
        'uses'       => 'EditPembelianController@edit_harga_tbs_pembelian',
    ]);
    // EDIT POTONGAN TBS PEMBELIAN
    Route::post('/editPembelian/proses-edit-potongan-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.edit_potongan_tbs_pembelian',
        'uses'       => 'EditPembelianController@edit_potongan_tbs_pembelian',
    ]);

    // EDIT TAX TBS PEMBELIAN
    Route::post('/editPembelian/proses-edit-tax-tbs-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.edit_tax_tbs_pembelian',
        'uses'       => 'EditPembelianController@editTaxTbsPembelian',
    ]);
    // HAPUS TBS PEMBELIAN
    Route::delete('/editPembelian/hapus-tbs-pembelian/{id}', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.hapus_tbs_pembelian',
        'uses'       => 'EditPembelianController@hapus_tbs_pembelian',
    ]);
    // batal
    Route::post('/editPembelian/batal-transaksi-pembelian/', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.batal_transaksi_pembelian',
        'uses'       => 'EditPembelianController@proses_batal_transaksi_pembelian',
    ]);
    // PROSES EDIT PEMBELIAN
    Route::post('/editPembelian/proses-edit-pembelian', [
        'middleware' => ['auth'],
        'as'         => 'editPembelian.prosesEditPembelian',
        'uses'       => 'EditPembelianController@prosesEditPembelian',
    ]);
    // EDIT PEMBELIAN

//ITEM KELUAR
    Route::post('/item-keluar/proses-tambah-tbs-item-keluar', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_tambah_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_tambah_tbs_item_keluar',
    ]);

    Route::post('/item-keluar/proses-hapus-semua-tbs-item-keluar/', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_hapus_semua_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_hapus_semua_tbs_item_keluar',
    ]);

    Route::delete('/item-keluar/proses-hapus-tbs-item-keluar/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_hapus_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_hapus_tbs_item_keluar',
    ]);

    Route::post('/item-masuk/proses-hapus-semua-edit-tbs-item-keluar/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_hapus_semua_edit_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_hapus_semua_edit_tbs_item_keluar',
    ]);

    Route::post('/item-keluar/proses-edit-item-keluar/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_edit_item_keluar',
        'uses'       => 'ItemKeluarController@proses_edit_item_keluar',
    ]);

    Route::get('/item-keluar/proses-form-edit/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_form_edit',
        'uses'       => 'ItemKeluarController@proses_form_edit',
    ]);
    Route::post('/item-keluar/proses-tambah-edit-tbs-item-keluar/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_tambah_edit_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_tambah_edit_tbs_item_keluar',
    ]);
    Route::delete('/item-keluar/proses-hapus-edit-tbs-item-keluar/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.proses_hapus_edit_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_hapus_edit_tbs_item_keluar',
    ]);

    Route::post('/item-keluar/edit-jumlah-tbs-item-keluar', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.edit_jumlah_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_edit_jumlah_tbs_item_keluar',
    ]);

    Route::post('/item-keluar/edit-jumlah-edit-tbs-item-keluar', [
        'middleware' => ['auth'],
        'as'         => 'item-keluar.edit_jumlah_edit_tbs_item_keluar',
        'uses'       => 'ItemKeluarController@proses_edit_jumlah_edit_tbs_item_keluar',
    ]);

//ITEM KELUAR

    Route::post('/cek_total_kas', [
        'middleware' => ['auth'],
        'as'         => 'cek_total_kas',
        'uses'       => 'TransaksikasController@total_kas',
    ]);

//KOMUNITAS

    Route::get('komunitas/konfirmasi/{id}', [
        'middleware' => ['auth', 'role:admin'],
        'as'         => 'komunitas.konfirmasi',
        'uses'       => 'KomunitasController@konfirmasi',
    ]);

    Route::get('komunitas/no_konfirmasi/{id}', [
        'middleware' => ['auth'],
        'as'         => 'komunitas.no_konfirmasi',
        'uses'       => 'KomunitasController@no_konfirmasi',
    ]);

    Route::get('otoritas/permission/{id}', [
        'middleware' => ['auth'],
        'as'         => 'otoritas.permission',
        'uses'       => 'OtoritasController@setting_permission',
    ]);
    Route::put('otoritas/permission/{id}', [
        'middleware' => ['auth'],
        'as'         => 'otoritas.permission.edit',
        'uses'       => 'OtoritasController@proses_setting_permission',
    ]);

//ITEM MASUK
    Route::get('/item-masuk/proses-form-edit/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_form_edit',
        'uses'       => 'ItemMasukController@proses_form_edit',
    ]);

    Route::post('/item-masuk/proses-tambah-tbs-item-masuk', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_tambah_tbs_item_masuk',
        'uses'       => 'ItemMasukController@proses_tambah_tbs_item_masuk',
    ]);

    Route::post('/item-masuk/proses-tambah-edit-tbs-item-masuk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_tambah_edit_tbs_item_masuk',
        'uses'       => 'ItemMasukController@proses_tambah_edit_tbs_item_masuk',
    ]);

    Route::post('/item-masuk/proses-hapus-tbs-item-masuk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_hapus_tbs_item_masuk',
        'uses'       => 'ItemMasukController@proses_hapus_tbs_item_masuk',
    ]);

    Route::post('/item-masuk/proses-hapus-edit-tbs-item-masuk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_hapus_edit_tbs_item_masuk',
        'uses'       => 'ItemMasukController@proses_hapus_edit_tbs_item_masuk',
    ]);

    Route::post('/item-masuk/proses-hapus-semua-tbs-item-masuk/', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_hapus_semua_tbs_item_masuk',
        'uses'       => 'ItemMasukController@proses_hapus_semua_tbs_item_masuk',
    ]);

    Route::post('/item-masuk/proses-hapus-semua-edit-tbs-item-masuk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_hapus_semua_edit_tbs_item_masuk',
        'uses'       => 'ItemMasukController@proses_hapus_semua_edit_tbs_item_masuk',
    ]);

    Route::post('/item-masuk/proses-edit-item-masuk/{id}', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.proses_edit_item_masuk',
        'uses'       => 'ItemMasukController@proses_edit_item_masuk',
    ]);

    Route::post('/item-masuk/edit-jumlah-item-masuk', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.edit_jumlah',
        'uses'       => 'ItemMasukController@proses_edit_jumlah',
    ]);

    Route::post('/item-masuk/edit-jumlah-edit-item-masuk', [
        'middleware' => ['auth'],
        'as'         => 'item-masuk.edit_jumlah_edit',
        'uses'       => 'ItemMasukController@proses_edit_jumlah_edit',
    ]);

});
