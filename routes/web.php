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

Route::get('/provinsi-destinasi-pengiriman', [ 
    'middleware' => ['auth'], 
    'uses'       => 'PemesananController@dataProvinsi', 
]); 

Route::get('/kota-destinasi-pengiriman', [ 
    'middleware' => ['auth'], 
    'uses'       => 'PemesananController@dataKota', 
]); 

Route::get('/hitung-ongkir', [ 
    'middleware' => ['auth'], 
    'uses'       => 'PemesananController@hitungOngkir', 
]); 

//BATAL PESANAN PELANGGAN
Route::get('batal-pesanan-pelanggan/{id}', 'PesananPelangganController@batalPesananPelanggan')->middleware('auth');

//LANJUTKAN PESANAN PELANGGAN
Route::get('lanjut-pesanan-pelanggan/{id}', 'PesananPelangganController@lanjutPesananPelanggan')->middleware('auth');

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

//PUNYA BATALKAN KONFIRMASI PESANAN WARUNG
Route::get('batalkan-konfirmasi-pesanan-warung/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pesanan-warung.batalkan_konfirmasi',
    'uses'       => 'PesananWarungController@batalkanKonfirmasiPesananWarung',
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
Route::get('/syarat-ketentuan-topos', 'Auth\RegisterController@syarat_ketentuan_topos');
Route::get('/pendaftaran-topos/{id}', 'PendaftarToposController@pendaftaranTopos')->middleware('optimizeImages');
Route::post('/proses-daftar-topos', 'PendaftarToposController@prosesDaftarTopos');
Route::get('/kirim-bukti-pembayaran/{id}', 'PendaftarToposController@kirimBuktiPembayaran')->middleware('optimizeImages');
//USER WARUNG
Route::get('/register-warung', 'Auth\RegisterController@register_warung')->middleware('optimizeImages');

Route::get('/register-customer', 'Auth\RegisterController@register_customer')->middleware('optimizeImages');
Route::get('/register-customer', 'Auth\RegisterController@register_customer')->middleware('optimizeImages');

//registrasi lewat link affiliasi
Route::get('/aff/{id}/', function ($id) {

    return view('auth.register_customer', ['komunitas_id' => $id]);
})->middleware('optimizeImages');

Route::get('kirim-kode-verifikasi', 'Auth\RegisterController@kirim_kode_verifikasi')->middleware('optimizeImages');
Route::get('kirim-ulang-kode-verifikasi/{id}', 'Auth\RegisterController@kirim_ulang_kode_verifikasi');
Route::get('lupa-password', 'Auth\RegisterController@lupa_password');

Route::put('/proses-kirim-bukti-pembayaran/{id}', [
    'as'   => 'pendaftar_topos.proses_kirim_bukti_pembayaran',
    'uses' => 'PendaftarToposController@prosesKirimBuktiPembayaran',
]);
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
Route::get('/user/pilih-pelanggan', 'UserController@pilihPelanggan')->middleware('auth');

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

// daftar topos
Route::get('/daftar-topos/data-warung', 'PendaftarToposController@dataWarung')->middleware('auth');
Route::get('/daftar-topos/data-bank', 'PendaftarToposController@dataBank')->middleware('auth');
Route::post('/daftar-topos/kirim-bukti-pembayaran/{id}', 'PendaftarToposController@update')->middleware('auth');
Route::get('/daftar-topos/view', 'PendaftarToposController@view')->middleware('auth');
Route::get('/daftar-topos/pencarian', 'PendaftarToposController@pencarian')->middleware('auth');
Route::get('/daftar-topos/konfirmasi/{id}', 'PendaftarToposController@konfirmasi')->middleware('auth');
Route::get('/daftar-topos/view-detail-user-topos/{id}', 'PendaftarToposController@viewDetailUserTopos')->middleware('auth');
Route::get('/daftar-topos/pencarian-detail-user-topos/{id}', 'PendaftarToposController@pencarianDetailUserTopos')->middleware('auth');
Route::get('/daftar-topos/cek-sisa-demo', 'PendaftarToposController@cekSisaDemo')->middleware('auth');

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
Route::get('/produk/pilih-agent', 'BarangController@data_agent')->middleware('auth');

Route::get('/kas/view', 'KasController@view')->middleware('auth');
Route::get('/kas/pencarian', 'KasController@pencarian')->middleware('auth');
Route::get('/kas/cek-default-kas', 'KasController@cekDefaultKas')->middleware('auth');
Route::get('/kas/cek-kas-warung', 'KasController@cekKasWarung')->middleware('auth');

// ITEM MASUK
Route::get('/item-masuk/view', 'ItemMasukController@view')->middleware('auth');
Route::get('/item-masuk/pencarian', 'ItemMasukController@pencarian')->middleware('auth');
Route::get('/item-masuk/view-tbs-item-masuk', 'ItemMasukController@viewTbsItemMasuk')->middleware('auth');
Route::get('/item-masuk/pencarian-tbs-item-masuk', 'ItemMasukController@pencarianTbsItemMasuk')->middleware('auth');
Route::get('/item-masuk/view-edit-tbs-item-masuk/{id}', 'ItemMasukController@viewEditTbsItemMasuk')->middleware('auth');
Route::get('/item-masuk/pencarian-edit-tbs-item-masuk/{id}', 'ItemMasukController@pencarianEditTbsItemMasuk')->middleware('auth');
Route::get('/item-masuk/ambil-faktur-item-masuk/{id}', 'ItemMasukController@ambilFakturItemMasuk')->middleware('auth');
Route::get('/item-masuk/detail-item-masuk/{id}', 'ItemMasukController@detailItemMasuk')->middleware('auth');
Route::get('/item-masuk/pencarian-detail-item-masuk/{id}', 'ItemMasukController@pencarianDetailItemMasuk')->middleware('auth');

//KAS KELUAR VUE.JS
Route::get('/kas-keluar/view', 'KasKeluarController@view')->middleware('auth');
Route::get('/kas-keluar/pencarian', 'KasKeluarController@pencarian')->middleware('auth');
Route::get('/kas-keluar/pilih-kas', 'KasKeluarController@pilih_kas')->middleware('auth');
Route::get('/kas-keluar/pilih-kategori', 'KasKeluarController@pilih_kategori')->middleware('auth');

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
Route::get('/konfirmasi-pesanan-warung/{id}', 'PesananWarungController@konfirmasiPesananWarung')->middleware('auth');
Route::get('/batalkan-konfirmasi-pesanan-warung/{id}', 'PesananWarungController@batalkanKonfirmasiPesananWarung')->middleware('auth');
Route::get('/batalkan-pesanan-warung/{id}', 'PesananWarungController@batalkanPesananWarung')->middleware('auth');
Route::post('/selesai-konfirmasi-pesanan-warung', 'PesananWarungController@selesaiKonfirmasiPesananWarung')->middleware('auth');

//PEMBELIAN  VUE JS
Route::get('/pembelian/view', 'PembelianController@view')->middleware('auth');
Route::get('/pembelian/pencarian', 'PembelianController@pencarian')->middleware('auth');
Route::get('/pembelian/view-tbs-pembelian', 'PembelianController@viewTbsPembelian')->middleware('auth');
Route::get('/pembelian/pencarian-tbs-pembelian', 'PembelianController@pencarianTbsPembelian')->middleware('auth');
Route::get('/pembelian/view-edit-tbs-pembelian/{id}', 'PembelianController@viewEditTbsPembelian')->middleware('auth');
Route::get('/pembelian/pencarian-edit-tbs-pembelian/{id}', 'PembelianController@pencarianEditTbsPembelian')->middleware('auth');
Route::get('/pembelian/ambil-faktur-pembelian/{id}', 'PembelianController@ambilFakturPembelian')->middleware('auth');

Route::get('/pembelian/view-detail-pembelian/{id}', 'PembelianController@viewDetailPembelian')->middleware('auth');
Route::get('/pembelian/pencarian-detail-pembelian/{id}', 'PembelianController@pencarianDetailPembelian')->middleware('auth');

Route::get('/pembelian/pilih-suplier', 'PembelianController@pilih_suplier')->middleware('auth');
Route::get('/pembelian/cek-tbs-pembelian', 'PembelianController@cekTbsPembelian')->middleware('auth');
Route::get('/pembelian/proses-tambah-tbs-pembelian', 'PembelianController@proses_tambah_tbs_pembelian')->middleware('auth');
Route::get('/pembelian/proses-edit-jumlah-tbs-pembelian', 'PembelianController@edit_jumlah_tbs_pembelian')->middleware('auth');
Route::get('/pembelian/proses-edit-harga-tbs-pembelian', 'PembelianController@edit_harga_tbs_pembelian')->middleware('auth');
Route::get('/pembelian/proses-edit-potongan-tbs-pembelian', 'PembelianController@edit_potongan_tbs_pembelian')->middleware('auth');
Route::get('/pembelian/cek-persen-potongan-pembelian', 'PembelianController@cek_persen_potongan_pembelian')->middleware('auth');
Route::get('/pembelian/cek-persen-tax-pembelian', 'PembelianController@cek_persen_potongan_pembelian')->middleware('auth');
Route::get('/pembelian/proses-edit-tax-tbs-pembelian', 'PembelianController@editTaxTbsPembelian')->middleware('auth');
Route::get('/pembelian/cek-total-kas-pembelian', 'PembelianController@total_kas')->middleware('auth');
Route::get('/pembelian/detail-view', 'PembelianController@detailView')->middleware('auth');
Route::get('/pembelian/cetak-besar-pembelian/{id}', 'PembelianController@cetakBesar')->middleware('auth');

//Edit Pembelian
Route::get('/edit-pembelian/proses-tambah-tbs-pembelian', 'EditPembelianController@proses_tambah_tbs_pembelian')->middleware('auth');
Route::get('/edit-pembelian/proses-edit-jumlah-tbs-pembelian', 'EditPembelianController@edit_jumlah_tbs_pembelian')->middleware('auth');
Route::get('/edit-pembelian/proses-edit-harga-tbs-pembelian', 'EditPembelianController@edit_harga_tbs_pembelian')->middleware('auth');
Route::get('/edit-pembelian/proses-edit-potongan-tbs-pembelian', 'EditPembelianController@edit_potongan_tbs_pembelian')->middleware('auth');
Route::get('/edit-pembelian/cek-persen-potongan-pembelian', 'EditPembelianController@cek_persen_potongan_pembelian')->middleware('auth');
Route::get('/edit-pembelian/cek-persen-tax-pembelian', 'EditPembelianController@cek_persen_potongan_pembelian')->middleware('auth');
Route::get('/edit-pembelian/proses-edit-tax-tbs-pembelian', 'EditPembelianController@editTaxTbsPembelian')->middleware('auth');
Route::get('/edit-pembelian/cek-total-kas-pembelian', 'EditPembelianController@total_kas')->middleware('auth');
Route::get('/edit-pembelian/cek-data-tbs-pembelian/{id}', 'EditPembelianController@cekDataPembelian')->middleware('auth');

// PEMBELIAN

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

// HAPUS TBS PEMBELIAN
Route::delete('/edit-pembelian/hapus-tbs-pembelian/{id}', [
    'middleware' => ['auth'],
    'as'         => 'editPembelian.hapus_tbs_pembelian',
    'uses'       => 'EditPembelianController@hapus_tbs_pembelian',
]);
// batal
Route::post('/edit-pembelian/batal-transaksi-pembelian/', [
    'middleware' => ['auth'],
    'as'         => 'editPembelian.batal_transaksi_pembelian',
    'uses'       => 'EditPembelianController@proses_batal_transaksi_pembelian',
]);
// PROSES EDIT PEMBELIAN
Route::post('/edit-pembelian/proses-edit-pembelian', [
    'middleware' => ['auth'],
    'as'         => 'editPembelian.prosesEditPembelian',
    'uses'       => 'EditPembelianController@prosesEditPembelian',
]);
// EDIT PEMBELIAN

// ITEM MASUK
Route::get('/item-keluar/view', 'ItemKeluarController@view')->middleware('auth');
Route::get('/item-keluar/pencarian', 'ItemKeluarController@pencarian')->middleware('auth');
Route::get('/item-keluar/view-tbs-item-keluar', 'ItemKeluarController@viewTbsItemKeluar')->middleware('auth');
Route::get('/item-keluar/pencarian-tbs-item-keluar', 'ItemKeluarController@pencarianTbsItemKeluar')->middleware('auth');
Route::get('/item-keluar/ambil-faktur-item-keluar/{id}', 'ItemKeluarController@ambilFakturItemKeluar')->middleware('auth');
Route::get('/item-keluar/detail-item-keluar/{id}', 'ItemKeluarController@detailItemKeluar')->middleware('auth');
Route::get('/item-keluar/pencarian-detail-item-keluar/{id}', 'ItemKeluarController@pencarianDetailItemKeluar')->middleware('auth');
Route::get('/item-keluar/view-edit-tbs-item-keluar/{id}', 'ItemKeluarController@viewEditTbsItemKeluar')->middleware('auth');
Route::get('/item-keluar/pencarian-edit-tbs-item-keluar/{id}', 'ItemKeluarController@pencarianEditTbsItemKeluar')->middleware('auth');

// LAPORAN PERSEDIAAN
Route::get('/laporan-persediaan/view', 'LaporanPersediaanController@view')->middleware('auth');
Route::get('/laporan-persediaan/pencarian', 'LaporanPersediaanController@pencarian')->middleware('auth');

//KAS MUTASI VUE.JS
Route::get('/kas-mutasi/view', 'KasMutasiController@view')->middleware('auth');
Route::get('/kas-mutasi/pencarian', 'KasMutasiController@pencarian')->middleware('auth');
Route::get('/kas-mutasi/pilih-kas', 'KasMutasiController@pilih_kas')->middleware('auth');

// PENJUALAN VUE.JS

Route::get('/penjualan/view', 'PenjualanController@view')->middleware('auth');
Route::get('/penjualan/pencarian', 'PenjualanController@pencarian')->middleware('auth');
Route::get('/penjualan/view-online', 'PenjualanController@viewOnline')->middleware('auth');
Route::get('/penjualan/pencarian-online', 'PenjualanController@pencarianOnline')->middleware('auth');
Route::get('/penjualan/view-detail-penjualan/{id}', 'PenjualanController@viewDetailPenjualan')->middleware('auth');
Route::get('/penjualan/pencarian-detail-penjualan/{id}', 'PenjualanController@pencarianDetailPenjualan')->middleware('auth');
Route::get('/penjualan/view-detail-penjualan-online/{id}', 'PenjualanController@viewDetailPenjualanOnline')->middleware('auth');
Route::get('/penjualan/pencarian-detail-penjualan-online/{id}', 'PenjualanController@pencarianDetailPenjualanOnline')->middleware('auth');
Route::get('/penjualan/view-edit-tbs-penjualan/{id}', 'PenjualanController@viewEditTbsPenjualan')->middleware('auth');
Route::get('/penjualan/pencarian-edit-tbs-penjualan/{id}', 'PenjualanController@pencarianEditTbsPenjualan')->middleware('auth');
Route::get('/penjualan/view-tbs-penjualan', 'PenjualanController@viewTbsPenjualan')->middleware('auth');
Route::get('/penjualan/pencarian-tbs-penjualan', 'PenjualanController@pencarianTbsPenjualan')->middleware('auth');
Route::get('/penjualan/cek-data-tbs-penjualan/{id}', 'PenjualanController@cekDataTbsPenjualan')->middleware('auth');
Route::get('/penjualan/pilih-pelanggan', 'PenjualanController@pilihPelanggan')->middleware('auth');
Route::get('/penjualan/pilih-kas', 'PenjualanController@pilih_kas')->middleware('auth');
Route::get('/penjualan/cetak-besar-penjualan/{id}', 'PenjualanController@cetakBesar')->middleware('auth');
Route::get('/penjualan/cetak-kecil-penjualan/{id}', 'PenjualanController@cetakKecil')->middleware('auth');
Route::post('/penjualan/proses-setting-penjualan-pos', 'PenjualanController@settingPenjualanPos')->middleware('auth');
Route::get('/penjualan/cek-setting-penjualan-pos', 'PenjualanController@cekSettingPenjualanPos')->middleware('auth');
Route::get('/penjualan/subtotal-tbs-penjualan', 'PenjualanController@cekSubtotalTbsPenjualan')->middleware('auth');

// LABA KOTOR VUE.JS
Route::post('/laporan-laba-kotor/view', 'LaporanLabaKotorController@prosesLaporanLabaKotor')->middleware('auth');
Route::post('/laporan-laba-kotor/pencarian', 'LaporanLabaKotorController@pencarian')->middleware('auth');
Route::post('/laporan-laba-kotor/view-pesanan', 'LaporanLabaKotorController@prosesLaporanLabaKotorPesanan')->middleware('auth');
Route::post('/laporan-laba-kotor/pencarian-pesanan', 'LaporanLabaKotorController@pencarianPesanan')->middleware('auth');
Route::get('/laporan-laba-kotor/pilih-pelanggan', 'LaporanLabaKotorController@pilihPelanggan')->middleware('auth');
Route::post('/laporan-laba-kotor/subtotal-laba-kotor', 'LaporanLabaKotorController@subtotalLabaKotor')->middleware('auth');
Route::post('/laporan-laba-kotor/subtotal-laba-kotor-pesanan', 'LaporanLabaKotorController@subtotalLabaKotorPesanan')->middleware('auth');
Route::get('/laporan-laba-kotor/download-excel-laba-kotor/{dari_tanggal}/{sampai_tanggal}/{pelanggan}', 'LaporanLabaKotorController@downloadExcel')->middleware('auth');
Route::get('/laporan-laba-kotor/cetak-laporan/{dari_tanggal}/{sampai_tanggal}/{pelanggan}', 'LaporanLabaKotorController@cetakLaporan')->middleware('auth');
Route::post('/laporan-laba-kotor/total-akhir-laba-kotor', 'LaporanLabaKotorController@totalAkhirLabaKotor')->middleware('auth');

// LABA KOTOR PRODUK VUE.JS
Route::post('/laporan-laba-kotor-produk/view', 'LaporanLabaKotorProdukController@prosesLaporanLabaKotorProduk')->middleware('auth');
Route::post('/laporan-laba-kotor-produk/pencarian', 'LaporanLabaKotorProdukController@pencarian')->middleware('auth');
Route::post('/laporan-laba-kotor-produk/view-pesanan', 'LaporanLabaKotorProdukController@prosesLaporanLabaKotorProdukPesanan')->middleware('auth');
Route::post('/laporan-laba-kotor-produk/pencarian-pesanan', 'LaporanLabaKotorProdukController@pencarianPesanan')->middleware('auth');
Route::get('/laporan-laba-kotor-produk/pilih-produk', 'LaporanLabaKotorProdukController@pilihProduk')->middleware('auth');
Route::post('/laporan-laba-kotor-produk/subtotal-laba-kotor-produk', 'LaporanLabaKotorProdukController@subtotalLabaKotorProduk')->middleware('auth');
Route::post('/laporan-laba-kotor-produk/subtotal-laba-kotor-produk-pesanan', 'LaporanLabaKotorProdukController@subtotalLabaKotorProdukPesanan')->middleware('auth');
Route::get('/laporan-laba-kotor-produk/download-excel-laba-kotor/{dari_tanggal}/{sampai_tanggal}/{produk}', 'LaporanLabaKotorProdukController@downloadExcel')->middleware('auth');
Route::get('/laporan-laba-kotor-produk/cetak-laporan/{dari_tanggal}/{sampai_tanggal}/{produk}', 'LaporanLabaKotorProdukController@cetakLaporan')->middleware('auth');
Route::post('/laporan-laba-kotor-produk/total-akhir-laba-kotor-produk', 'LaporanLabaKotorProdukController@totalAkhirLabaKotorProduk')->middleware('auth');

// LAPORAN MUTASI STOK VUE.JS
Route::post('/laporan-mutasi-stok/view', 'LaporanMutasiStokController@prosesLaporanMutasiStok')->middleware('auth');
Route::post('/laporan-mutasi-stok/pencarian', 'LaporanMutasiStokController@pencarian')->middleware('auth');
Route::post('/laporan-mutasi-stok/subtotal-mutasi-stok', 'LaporanMutasiStokController@subtotalMutasiStok')->middleware('auth');
Route::get('/laporan-mutasi-stok/download-excel-mutasi-stok/{dari_tanggal}/{sampai_tanggal}', 'LaporanMutasiStokController@downloadExcel')->middleware('auth');
Route::get('/laporan-mutasi-stok/cetak-laporan/{dari_tanggal}/{sampai_tanggal}', 'LaporanMutasiStokController@cetakLaporan')->middleware('auth');

// LAPORAN PEMEBELIAN /PRODUK VUE.JS
Route::post('/laporan-pembelian-produk/view', 'LaporanPembelianProdukController@prosesLaporanPembelianProduk')->middleware('auth');
Route::post('/laporan-pembelian-produk/pencarian', 'LaporanPembelianProdukController@pencarian')->middleware('auth');
Route::post('/laporan-pembelian-produk/subtotal-pembelian-produk', 'LaporanPembelianProdukController@subtotalPembelianProduk')->middleware('auth');
Route::get('/laporan-pembelian-produk/pilih-produk', 'LaporanPembelianProdukController@dataProduk')->middleware('auth');
Route::get('/laporan-pembelian-produk/pilih-supplier', 'LaporanPembelianProdukController@dataSupplier')->middleware('auth');
Route::get('/laporan-pembelian-produk/download-excel-pembelian-produk/{dari_tanggal}/{sampai_tanggal}/{produk}/{suplier}', 'LaporanPembelianProdukController@downloadExcel')->middleware('auth');
Route::get('/laporan-pembelian-produk/cetak-laporan/{dari_tanggal}/{sampai_tanggal}/{produk}/{suplier}', 'LaporanPembelianProdukController@cetakLaporan')->middleware('auth');

// LAPORAN PENJUALAN /PRODUK VUE.JS
Route::post('/laporan-penjualan-pos-produk/view', 'LaporanPenjualanPosProdukController@prosesLaporanPenjualanPosProduk')->middleware('auth');
Route::post('/laporan-penjualan-pos-produk/pencarian', 'LaporanPenjualanPosProdukController@pencarian')->middleware('auth');
Route::post('/laporan-penjualan-pos-produk/total-penjualan-pos-produk', 'LaporanPenjualanPosProdukController@totalPenjualanPosProduk')->middleware('auth');
Route::get('/laporan-penjualan-pos-produk/pilih-produk', 'LaporanPenjualanPosProdukController@dataProduk')->middleware('auth');
Route::get('/laporan-penjualan-pos-produk/pilih-pelanggan', 'LaporanPenjualanPosProdukController@dataPelanggan')->middleware('auth');
Route::get('/laporan-penjualan-pos-produk/download-excel-penjualan-produk/{dari_tanggal}/{sampai_tanggal}/{produk}/{suplier}', 'LaporanPenjualanPosProdukController@downloadExcel')->middleware('auth');
Route::get('/laporan-penjualan-pos-produk/cetak-laporan/{dari_tanggal}/{sampai_tanggal}/{produk}/{suplier}', 'LaporanPenjualanPosProdukController@cetakLaporan')->middleware('auth');

// LAPORAN KARTU STOK VUE.JS
Route::post('/laporan-kartu-stok/view', 'LaporanKartuStokController@prosesLaporanKartuStok')->middleware('auth');
Route::post('/laporan-kartu-stok/pencarian', 'LaporanKartuStokController@pencarian')->middleware('auth');
Route::get('/laporan-kartu-stok/pilih-produk', 'LaporanKartuStokController@dataProduk')->middleware('auth');
Route::post('/laporan-kartu-stok/total-saldo-awal', 'LaporanKartuStokController@totalSaldoAwal')->middleware('auth');
Route::post('/laporan-kartu-stok/total-saldo-akhir', 'LaporanKartuStokController@totalSaldoAkhir')->middleware('auth');
Route::get('/laporan-kartu-stok/download-excel-kartu-stok/{dari_tanggal}/{sampai_tanggal}/{produk}', 'LaporanKartuStokController@downloadExcel')->middleware('auth');
Route::get('/laporan-kartu-stok/cetak-laporan/{dari_tanggal}/{sampai_tanggal}/{produk}', 'LaporanKartuStokController@cetakLaporan')->middleware('auth');

////PEMBAYARAN Hutang
Route::get('/pembayaran-hutang/view', 'PembayaranHutangController@view')->middleware('auth');
Route::get('/pembayaran-hutang/pencarian', 'PembayaranHutangController@pencarian')->middleware('auth');
Route::get('/pembayaran-hutang/view-tbs-pembayaran-hutang', 'PembayaranHutangController@viewTbsPembayaranHutang')->middleware('auth');
Route::get('/pembayaran-hutang/pencarian-tbs-pembayaran-hutang', 'PembayaranHutangController@pencarianTbsPembayaranHutang')->middleware('auth');
Route::get('/pembayaran-hutang/pilih-suplier', 'PembayaranHutangController@pilihSuplier')->middleware('auth');
Route::get('/pembayaran-hutang/data-suplier-hutang/{id}', 'PembayaranHutangController@dataSupplierHutang')->middleware('auth');
Route::get('/pembayaran-hutang/pencarian-suplier-hutang/{id}', 'PembayaranHutangController@pencarianDataSupplierHutang')->middleware('auth');
Route::post('/pembayaran-hutang/proses-tambah-tbs-pembayaran-hutang', 'PembayaranHutangController@prosesTbsPembayaranHutang')->middleware('auth');
Route::get('/pembayaran-hutang/pilih-kas', 'PembayaranHutangController@dataKas')->middleware('auth');
Route::get('/pembayaran-hutang/view-detail-pembayaran-hutang/{id}', 'PembayaranHutangController@viewDetail')->middleware('auth');
Route::get('/pembayaran-hutang/pencarian-detail-pembayaran-hutang/{id}', 'PembayaranHutangController@pencarianDetail')->middleware('auth');
Route::get('/pembayaran-hutang/subtotal-tbs-pembayaran-hutang/{jenis_tbs}', 'PembayaranHutangController@cekSubtotalTbsPembayaranHutang')->middleware('auth');
Route::get('/pembayaran-hutang/cek-supplier-double', 'PembayaranHutangController@cekSupplierDouble')->middleware('auth');
Route::get('/pembayaran-hutang/cek-supplier-double-edit', 'PembayaranHutangController@cekSupplierDoubleEdit')->middleware('auth');
Route::get('/pembayaran-hutang/cek-total-kas', 'PembayaranHutangController@total_kas')->middleware('auth');

Route::delete('/pembayaran-hutang/proses-hapus-tbs-pembayaran-hutang/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pembayaran-hutang.proses_hapus_tbs_pembayaran_hutang',
    'uses'       => 'PembayaranHutangController@prosesHapusTbsPembayaranHutang',
]);

Route::post('/pembayaran-hutang/edit-jumlah-tbs-pembayaran-hutang', [
    'middleware' => ['auth'],
    'as'         => 'pembayaran-hutang.edit_jumlah_tbs_pembayaran_hutang',
    'uses'       => 'PembayaranHutangController@prosesEditTbsPembayaranHutang',
]);

Route::post('/pembayaran-hutang/batal-transaksi-pembayaran-hutang/', [
    'middleware' => ['auth'],
    'as'         => 'pembayaran-hutang.batal_transaksi_pembayaran_hutang',
    'uses'       => 'PembayaranHutangController@proses_batal_transaksi_pembayaran_hutang',
]);

//PEMBAYARAN HUTANG EDIT
Route::get('/pembayaran-hutang/view-edit-tbs-pembayaran-hutang/{id}', 'PembayaranHutangController@viewTbsEdit')->middleware('auth');
Route::get('/pembayaran-hutang/pencarian-edit-tbs-pembayaran-hutang/{id}', 'PembayaranHutangController@pencarianTbsEdit')->middleware('auth');
Route::post('/pembayaran-hutang/proses-tambah-tbs-edit-pembayaran-hutang/{id}', 'PembayaranHutangController@prosesTbsEditPembayaranHutang')->middleware('auth');
Route::delete('/pembayaran-hutang/proses-hapus-tbs-edit-pembayaran-hutang/{id}', 'PembayaranHutangController@prosesHapusEditTbsPembayaranHutang')->middleware('auth');
Route::get('/pembayaran-hutang/cek-data-tbs-pembayan-hutang/{id}', 'PembayaranHutangController@cekDataTbsPembayaranHutang')->middleware('auth');

Route::post('/pembayaran-hutang/edit-jumlah-tbs-edit-pembayaran-hutang', [
    'middleware' => ['auth'],
    'as'         => 'pembayaran-hutang.edit_jumlah_tbs_pembayaran_hutang',
    'uses'       => 'PembayaranHutangController@prosesUpdateTbsEditPembayaranHutang',
]);

Route::post('/pembayaran-hutang/proses-batal-edit-pembayaran-hutang/{id}', [
    'middleware' => ['auth'],
    'as'         => 'pembayaran-hutang.proses_batal_pembayaran_hutang',
    'uses'       => 'PembayaranHutangController@prosesBatalEditPembayaranHutang',
]);

// PEMBAYARAN PIUTANG VUE.JS
Route::get('/pembayaran-piutang/view', 'PembayaranPiutangController@view')->middleware('auth');
Route::get('/pembayaran-piutang/pencarian', 'PembayaranPiutangController@pencarian')->middleware('auth');
Route::get('/pembayaran-piutang/pilih-penjualan-piutang', 'PembayaranPiutangController@dataPiutang')->middleware('auth');
Route::get('/pembayaran-piutang/pilih-kas', 'PembayaranPiutangController@dataKas')->middleware('auth');
Route::get('/pembayaran-piutang/data-penjualan-piutang/{id}', 'PembayaranPiutangController@getDataFakturPiutang')->middleware('auth');
Route::post('/pembayaran-piutang/proses-tambah-tbs-pembayaran-piutang', 'PembayaranPiutangController@prosesTbsPembayaranPiutang')->middleware('auth');
Route::get('/pembayaran-piutang/view-tbs-pembayaran-piutang', 'PembayaranPiutangController@viewTbs')->middleware('auth');
Route::get('/pembayaran-piutang/pencarian-tbs-pembayaran-piutang', 'PembayaranPiutangController@pencarianTbs')->middleware('auth');
Route::get('/pembayaran-piutang/view-detail-pembayaran-piutang/{id}', 'PembayaranPiutangController@viewDetail')->middleware('auth');
Route::get('/pembayaran-piutang/pencarian-detail-pembayaran-piutang/{id}', 'PembayaranPiutangController@pencarianDetail')->middleware('auth');

//PEMBAYARAN PIUTANG EDIT
Route::get('/pembayaran-piutang/view-edit-tbs-pembayaran-piutang/{id}', 'PembayaranPiutangController@viewTbsEdit')->middleware('auth');
Route::get('/pembayaran-piutang/pencarian-edit-tbs-pembayaran-piutang/{id}', 'PembayaranPiutangController@pencarianTbsEdit')->middleware('auth');
Route::post('/pembayaran-piutang/proses-tambah-edit-tbs-pembayaran-piutang/{id}', 'PembayaranPiutangController@prosesEditTbsPembayaranPiutang')->middleware('auth');
Route::delete('/pembayaran-piutang/proses-hapus-edit-tbs-pembayaran-piutang/{id}', 'PembayaranPiutangController@prosesHapusEditTbsPenjualan')->middleware('auth');

//LAPORAN KAS DETAIL
Route::get('/laporan-kas/pilih-kas', 'LaporanKasController@dataKas')->middleware('auth');
Route::post('/laporan-kas/view', 'LaporanKasController@prosesLaporanKasDetail')->middleware('auth');
Route::post('/laporan-kas/pencarian', 'LaporanKasController@pencarianLaporanKasDetail')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-detail-masuk', 'LaporanKasController@subtotalLaporanKasDetailMasuk')->middleware('auth');

Route::post('/laporan-kas/view-keluar', 'LaporanKasController@prosesLaporanKasKeluarDetail')->middleware('auth');
Route::post('/laporan-kas/pencarian-keluar', 'LaporanKasController@pencarianLaporanKasKeluarDetail')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-detail-keluar', 'LaporanKasController@subtotalLaporanKasDetailKeluar')->middleware('auth');

Route::post('/laporan-kas/view-mutasi-masuk', 'LaporanKasController@prosesLaporanKasMutasiMasukDetail')->middleware('auth');
Route::post('/laporan-kas/pencarian-mutasi-masuk', 'LaporanKasController@pencarianLaporanKasMutasiMasukDetail')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-detail-mutasi-masuk', 'LaporanKasController@subtotalLaporanKasDetailMutasiMasuk')->middleware('auth');

Route::post('/laporan-kas/view-mutasi-keluar', 'LaporanKasController@prosesLaporanKasMutasiKeluarDetail')->middleware('auth');
Route::post('/laporan-kas/pencarian-mutasi-keluar', 'LaporanKasController@pencarianLaporanKasMutasiKeluarDetail')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-detail-mutasi-keluar', 'LaporanKasController@subtotalLaporanKasDetailMutasiKeluar')->middleware('auth');

Route::post('/laporan-kas/subtotal-laporan-kas-detail', 'LaporanKasController@subtotalLaporanKasDetail')->middleware('auth');

Route::post('/laporan-kas/view-rekap', 'LaporanKasController@prosesLaporanKasRekap')->middleware('auth');
Route::post('/laporan-kas/pencarian-rekap', 'LaporanKasController@pencarianLaporanKasRekap')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-rekap-masuk', 'LaporanKasController@subtotalLaporanKasRekapMasuk')->middleware('auth');

Route::post('/laporan-kas/view-keluar-rekap', 'LaporanKasController@prosesLaporanKasKeluarRekap')->middleware('auth');
Route::post('/laporan-kas/pencarian-keluar-rekap', 'LaporanKasController@pencarianLaporanKasKeluarRekap')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-rekap-keluar', 'LaporanKasController@subtotalLaporanKasRekapKeluar')->middleware('auth');

Route::post('/laporan-kas/view-mutasi-masuk-rekap', 'LaporanKasController@prosesLaporanKasMutasiMasukRekap')->middleware('auth');
Route::post('/laporan-kas/pencarian-mutasi-masuk-rekap', 'LaporanKasController@pencarianLaporanKasMutasiMasukRekap')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-rekap-mutasi-masuk', 'LaporanKasController@subtotalLaporanKasRekapMutasiMasuk')->middleware('auth');

Route::post('/laporan-kas/view-mutasi-keluar-rekap', 'LaporanKasController@prosesLaporanKasMutasiKeluarRekap')->middleware('auth');
Route::post('/laporan-kas/pencarian-mutasi-keluar-rekap', 'LaporanKasController@pencarianLaporanKasMutasiKeluarRekap')->middleware('auth');
Route::post('/laporan-kas/subtotal-laporan-kas-rekap-mutasi-keluar', 'LaporanKasController@subtotalLaporanKasRekapMutasiKeluar')->middleware('auth');

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
    Route::resource('kas-keluar', 'KasKeluarController');
    Route::resource('kas-mutasi', 'KasMutasiController');
    Route::resource('error', 'ErrorController');
    Route::resource('item-masuk', 'ItemMasukController');
    Route::resource('item-keluar', 'ItemKeluarController');
    Route::resource('suplier', 'SuplierController');
    Route::resource('satuan', 'SatuanController');
    Route::resource('laporan-persediaan', 'LaporanPersediaanController');
    Route::resource('laporan-laba-kotor', 'LaporanLabaKotorController');
    Route::resource('laporan-laba-kotor-produk', 'LaporanLabaKotorProdukController');
    Route::resource('laporan-mutasi-stok', 'LaporanMutasiStokController');
    Route::resource('laporan-pembelian-produk', 'LaporanPembelianProdukController');
    Route::resource('laporan-kas', 'LaporanKasController');
    Route::resource('pembelian', 'PembelianController');
    Route::resource('kelompok-produk', 'KelompokProdukController');
    Route::resource('profil-warung', 'WarungProfilController');
    Route::resource('penjualan', 'PenjualanController');
    Route::resource('daftar-topos', 'PendaftarToposController');
    Route::resource('laporan-kartu-stok', 'LaporanKartuStokController');
    Route::resource('pembayaran-hutang', 'PembayaranHutangController');
    Route::resource('pembayaran-piutang', 'PembayaranPiutangController');
    Route::resource('setting-footer', 'SettingFooterController');

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

    Route::post('/item-keluar/proses-hapus-semua-edit-tbs-item-keluar', [
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
    Route::post('/item-keluar/proses-tambah-edit-tbs-item-keluar', [
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

    Route::post('/item-masuk/proses-tambah-edit-tbs-item-masuk', [
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

    Route::post('/item-masuk/proses-hapus-semua-edit-tbs-item-masuk', [
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

    // penjualan
    Route::post('/penjualan/proses-tambah-tbs-penjualan', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.proses_tambah_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesTambahTbsPenjualan',
    ]);

    Route::post('/penjualan/edit-jumlah-tbs-penjualan', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.edit_jumlah_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesEditJumlahTbsPenjualan',
    ]);

    Route::post('/penjualan/edit-potongan-tbs-penjualan', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.edit_potongan_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesEditPotonganTbsPenjualan',
    ]);

    Route::delete('/penjualan/proses-hapus-tbs-penjualan/{id}', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.proses_hapus_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesHapusTbsPenjualan',
    ]);

    Route::post('/penjualan/proses-batal-penjualan/', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.proses_batal_penjualan',
        'uses'       => 'PenjualanController@proses_batal_penjualan',
    ]);

// edit penjualan
    Route::post('/penjualan/proses-tambah-edit-tbs-penjualan/{id}', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.proses_tambah_edit_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesTambahEditTbsPenjualan',
    ]);

    Route::post('/penjualan/edit-jumlah-edit-tbs-penjualan', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.edit_jumlah_edit_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesEditJumlahEditTbsPenjualan',
    ]);

    Route::post('/penjualan/edit-potongan-edit-tbs-penjualan', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.edit_potongan_edit_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesEditPotonganEditTbsPenjualan',
    ]);

    Route::delete('/penjualan/proses-hapus-edit-tbs-penjualan/{id}', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.proses_hapus_edit_tbs_penjualan',
        'uses'       => 'PenjualanController@prosesHapusEditTbsPenjualan',
    ]);

    Route::post('/penjualan/proses-batal-edit-penjualan/{id}', [
        'middleware' => ['auth'],
        'as'         => 'penjualan.proses_batal_edit_penjualan',
        'uses'       => 'PenjualanController@proses_batal_edit_penjualan',
    ]);

    //PEMBAYARAN PIUTANG
    Route::delete('/pembayaran-piutang/proses-hapus-tbs-pembayaran-piutang/{id}', [
        'middleware' => ['auth'],
        'as'         => 'pembayaran-piutang.proses_hapus_tbs_pembayaran_piutang',
        'uses'       => 'PembayaranPiutangController@prosesHapusTbsPembayaranPiutang',
    ]);

    Route::post('pembayaran-piutang/edit-jumlah-tbs-pembayaran-piutang', [
        'middleware' => ['auth'],
        'as'         => 'pembayaran-piutang.edit_jumlah_tbs_pembayaran_piutang',
        'uses'       => 'PembayaranPiutangController@prosesEditPotonganTbsPembayaranPiutang',
    ]);

    Route::post('/pembayaran-piutang/proses-batal-pembayaran-piutang/', [
        'middleware' => ['auth'],
        'as'         => 'pembayaran-piutang.proses_batal_pembayaran_piutang',
        'uses'       => 'PembayaranPiutangController@prosesBatalPembayaranPiutang',
    ]);

    //EDIT PEMBAYARAN PIUTANG
    Route::post('pembayaran-piutang/edit-jumlah-edit-tbs-pembayaran-piutang', [
        'middleware' => ['auth'],
        'as'         => 'pembayaran-piutang.edit_jumlah_edit_tbs_pembayaran_piutang',
        'uses'       => 'PembayaranPiutangController@updateEditTbsPembayaranPiutang',
    ]);

    Route::post('/pembayaran-piutang/proses-batal-edit-pembayaran-piutang/{id}', [
        'middleware' => ['auth'],
        'as'         => 'pembayaran-piutang.proses_batal_pembayaran_piutang',
        'uses'       => 'PembayaranPiutangController@prosesBatalEditPembayaranPiutang',
    ]);

});
