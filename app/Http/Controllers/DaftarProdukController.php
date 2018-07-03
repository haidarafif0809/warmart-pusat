<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use App\Http\Controllers\DaftarProdukController;
use App\KategoriBarang;
use App\KeranjangBelanja;
use App\SettingAplikasi;
use App\TemaWarna;
use App\User;
use App\Warung;
use App\SettingPromo;
use App\SettingPembedaAplikasi;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Jenssegers\Agent\Agent;
use Session;


class DaftarProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //Cek Address Aplikasi yg di Jalankan
        $address_current = url('/');

        $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();
    // return url('/');
        $warung_id = $address_app->warung_id;

        if(!Session::get('session_id')){
            $session_id    = session()->getId();
        }else{
            $session_id = Session::get('session_id');
        }
        if (Auth::check() == false) {
            $keranjang_belanjaan = KeranjangBelanja::where('session_id', $session_id)->Where('warung_id',$warung_id);
            if ($keranjang_belanjaan->count() > 0) {
                $warung_yang_dipesan = $keranjang_belanjaan->first()->produk->id_warung;
            }
            $cek_belanjaan = $keranjang_belanjaan->count();
        } else {
            $keranjang_belanjaan = KeranjangBelanja::where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id);
            if ($keranjang_belanjaan->count() > 0) {
                $warung_yang_dipesan = $keranjang_belanjaan->first()->produk->id_warung;
            }
            $cek_belanjaan = $keranjang_belanjaan->count();
        }

    //TAMPILAN MOBILE
        $agent = new Agent();

    //Pilih warung yang sudah dikonfirmasi admin
        $array_warung = DaftarProdukController::dataWarungTervalidasi();


        if (isset($warung_yang_dipesan)) {

            $data_produk = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung', 'konfirmasi_admin', 'satuan_id', 'hitung_stok', 'status_aktif'])
            ->inRandomOrder()
            ->where('id_warung', $warung_yang_dipesan)->where('status_aktif', 1)->paginate(24);
        } else {
            if ($address_app->app_address == $address_current) {

                $data_produk  = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung', 'konfirmasi_admin', 'satuan_id', 'hitung_stok', 'status_aktif'])
                ->inRandomOrder()
                ->where('id_warung', $address_app->warung_id)->where('status_aktif', 1)->paginate(24);

            }else{

                $data_produk  = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung', 'konfirmasi_admin', 'satuan_id', 'hitung_stok', 'status_aktif'])
                ->inRandomOrder()
                ->whereIn('id_warung', $array_warung)->where('status_aktif', 1)->paginate(24);

            }
        }

        if ($address_app->app_address == $address_current) {
            $cek_baner = SettingPromo::where('status',1)->where('id_warung', $address_app->warung_id);
        }
        else{
            $cek_baner = SettingPromo::where('status',1)->whereIn('id_warung',$array_warung);
        }

        if ($cek_baner->count() > 0) {
            $baner_promo_active = $cek_baner->first();
            $baner_promo = SettingPromo::where('status',1)->where('baner_promo','!=',$baner_promo_active->baner_promo);
        }
        else{
            $baner_promo_active = 0;
            $baner_promo = 0;
        }


        //PILIH DATA WARUNG
        $warung_data = Warung::select(['id', 'name', 'alamat', 'wilayah', 'no_telpon'])
        ->inRandomOrder()->get();

        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        //SETTING TEMA
        $tema = TemaWarna::select('header_tema', 'kode_tema')->where('default_tema', 1)->where('warung_id', $warung_id)->first();

        //PILIH DATA KATEGORI PRODUK
        $kategori = KategoriBarang::select(['id', 'nama_kategori_barang', 'kategori_icon'])->where('warung_id', $this->idWarung());
        //PERINTAH PAGINATION
        $produk_pagination = $data_produk->links();
        //FOTO HEADER
        $foto_latar_belakang = "background-image: url('image/background2.jpg');";
        //FOTO WARMART
        $logo_warmart = "assets/img/examples/warmart_logo.png";
        //TAMPIL DAFTAR PRODUK
        $daftar_produk = DaftarProdukController::daftarProduk($data_produk);
        $daftar_warung = DaftarProdukController::daftarWarung($warung_data);
        //TAMPIL KATEGORI
        $kategori_produk = DaftarProdukController::produkKategori($kategori);
        $nama_kategori   = "";

        return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'daftar_warung' => $daftar_warung, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent, 'cek_belanjaan' => $cek_belanjaan, 'logo_warmart' => $logo_warmart, 'setting_aplikasi' => $setting_aplikasi,'baner_promo'=>$baner_promo,'baner_promo_active'=>$baner_promo_active,'cek_baner'=>$cek_baner, 'tema' => $tema]);
    }

    public static function idWarung() {
        $id_warung = SettingPembedaAplikasi::select('warung_id')->where('app_address', url('/'))->first();
        return $id_warung->warung_id;
    }

    public static function produkKategori($kategori)
    {
        //Pilih warung yang sudah dikonfirmasi admin
        $array_warung = DaftarProdukController::dataWarungTervalidasi();

        //MEANMPILKAN KATEGORI PRODUK
        $kategori_produk = '';
        $kategori_produk .= '
        <li class="dropdown ">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white"><i class="material-icons">list</i> PILIH KATEGORI <b class="caret"></b></a>
            <ul class="dropdown-menu dropdown-with-icons scrollable-menu">
                <li>
                    <a href="'.route('daftar_produk.index').'">
                        SEMUA KATEGORI </a>
                    </li>';
                    foreach ($kategori->get() as $kategori) {

                    //Cek Address Aplikasi yg di Jalankan
                        $address_current = url('/');
                    // ambil warung_id berdasarkan link aplikasi
                        $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();
                        if ($address_app->app_address == $address_current) {
                            $jumlah_produk = Barang::where('kategori_barang_id', $kategori->id)->where('id_warung', $address_app->warung_id)->count();
                        }
                        else{
                            $jumlah_produk = Barang::where('kategori_barang_id', $kategori->id)->whereIn('id_warung', $array_warung)->count();
                        }

                        $kategori_produk .= '
                        <li>

                            <a href="' . route('daftar_produk.filter_kategori', $kategori->id) . '"><i class="material-icons">' . $kategori->kategori_icon . '</i>' . $kategori->nama_kategori_barang . ' - ' . $jumlah_produk . '</a>
                        </li>';
                    }
                    $kategori_produk .= '
                </ul>
            </li>';

            return $kategori_produk;
        }

        public static function filter_kategori($id)
        {

        //Cek Address Aplikasi yg di Jalankan
            $address_current = url('/');
        // ambil warung_id berdasarkan link aplikasi
            $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();

            if(!Session::get('session_id')){
                $session_id    = session()->getId();
            }else{
                $session_id = Session::get('session_id');
            }

            if (Auth::check() == false) {
                $keranjang_belanjaan = KeranjangBelanja::where('session_id', $session_id);
                if ($keranjang_belanjaan->count() > 0) {
                    $warung_yang_dipesan = $keranjang_belanjaan->first()->produk->id_warung;
                }
                $cek_belanjaan = $keranjang_belanjaan->count();
            } else {
                $keranjang_belanjaan = KeranjangBelanja::where('id_pelanggan', Auth::user()->id);
                if ($keranjang_belanjaan->count() > 0) {
                    $warung_yang_dipesan = $keranjang_belanjaan->first()->produk->id_warung;
                }
                $cek_belanjaan = $keranjang_belanjaan->count();
            }


            if (isset($warung_yang_dipesan)) {
                $data_produk = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung', 'konfirmasi_admin', 'satuan_id', 'hitung_stok', 'status_aktif'])
                ->where('kategori_barang_id', $id)->where('id_warung', $warung_yang_dipesan)->where('status_aktif', 1)->inRandomOrder()->paginate(24);
            } else {
            //Pilih warung yang sudah dikonfirmasi admin
                $array_warung = DaftarProdukController::dataWarungTervalidasi();

                if ($address_app->app_address == $address_current) {

            //PILIH PRODUK
                    $data_produk = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung', 'konfirmasi_admin', 'satuan_id', 'hitung_stok', 'status_aktif'])
                    ->where('kategori_barang_id', $id)->where('id_warung', $address_app->warung_id)->where('status_aktif', 1)->inRandomOrder()->paginate(24);

                }else{

            //PILIH PRODUK
                    $data_produk = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung', 'konfirmasi_admin', 'satuan_id', 'hitung_stok', 'status_aktif'])
                    ->where('kategori_barang_id', $id)->whereIn('id_warung', $array_warung)->where('status_aktif', 1)->inRandomOrder()->paginate(24);

                }

            }


            if ($address_app->app_address == $address_current) {
                $cek_baner = SettingPromo::where('status',1)->where('id_warung', $address_app->warung_id);
            }
            else{
                $cek_baner = SettingPromo::where('status',1)->whereIn('id_warung',$array_warung);
            }

            if ($cek_baner->count() > 0) {
                $baner_promo_active = $cek_baner->first();
                $baner_promo = SettingPromo::where('status',1)->where('baner_promo','!=',$baner_promo_active->baner_promo);
            }
            else{
                $baner_promo_active = 0;
                $baner_promo = 0;
            }

        //PILIH DATA WARUNG
            $warung_data = Warung::select(['id', 'name', 'alamat', 'wilayah', 'no_telpon'])
            ->inRandomOrder()->get();

        //SETTING APLIKASI
            $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        //SETTING TEMA
            $tema = TemaWarna::select('header_tema', 'kode_tema')->where('default_tema', 1)->where('warung_id', $address_app->warung_id)->first();

        //FOTO HEADER
            $foto_latar_belakang = "background-image: url('../image/background2.jpg');";
        //FOTO WARMART
            $logo_warmart = "../assets/img/examples/warmart_logo.png";
        //PAGINATION DAFTAR PRODUK
            $produk_pagination = $data_produk->links();
        //PILIH KATEGORI
            $kategori        = KategoriBarang::select(['id', 'nama_kategori_barang', 'kategori_icon'])->where('warung_id', $this->idWarung())->where('id', $id);
            $kategori_semua  = KategoriBarang::select(['id', 'nama_kategori_barang', 'kategori_icon'])->where('warung_id', $this->idWarung())->where('id', '!=', $id);
            $kategori_produk = DaftarProdukController::produkKategori($kategori_semua);
            $daftar_warung   = DaftarProdukController::daftarWarung($warung_data);
            $data_kategori   = $kategori->first();
            $nama_kategori   = "KATEGORI : " . $data_kategori->nama_kategori_barang . "";

        //TAMPILAN VIA HP
            $agent = new Agent();

            $daftar_produk = DaftarProdukController::daftarProduk($data_produk);
            $daftar_warung = DaftarProdukController::daftarWarung($warung_data);

            return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_warung' => $daftar_warung, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'id' => $id, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent, 'cek_belanjaan' => $cek_belanjaan, 'logo_warmart' => $logo_warmart, 'setting_aplikasi' => $setting_aplikasi,'baner_promo'=>$baner_promo,'baner_promo_active'=>$baner_promo_active,'cek_baner'=>$cek_baner, 'tema' => $tema]);
        }

        public static function pencarian(Request $request)
        {

            if (Auth::check()) {
                $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->get();
                if ($keranjang_belanjaan->count() > 0) {
                    $warung_yang_dipesan = $keranjang_belanjaan->first()->produk->id_warung;
                }
                $cek_belanjaan = $keranjang_belanjaan->count();

            } else {
                $cek_belanjaan = 0;
            }

        //Cek Address Aplikasi yg di Jalankan
            $address_current = url('/');
        // ambil warung_id berdasarkan link aplikasi
            $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();

        //PILIH PRODUK
            $data_produk = Barang::search($request->search)->paginate(24);
        //PILIH DATA WARUNG
            $warung_data = Warung::search($request->search)->get();
        //PILIH KATEGORI
            $kategori = KategoriBarang::select(['id', 'nama_kategori_barang', 'kategori_icon'])->where('warung_id', $this->idWarung());
        //FOTO HEADER
            $foto_latar_belakang = "background-image: url('" . asset('/image/background2.jpg') . "');";
        //FOTO WARMART
            $logo_warmart = asset('/assets/img/examples/warmart_logo.png');
        //PAGINATION DAFTAR PRODUK
            $produk_pagination = $data_produk->links();
        //MENAMPILKAN KATEGORI
            $kategori_produk = DaftarProdukController::produkKategori($kategori);
            $data_kategori   = $kategori->first();
            $nama_kategori   = 'Hasil Pencarian : "' . $request->search . '" <a href="' . url("/daftar-produk") . '"> <i class="material-icons" style="color:red" >highlight_off</i> </a>';
        //TAMPILAN VIA HP
            $agent = new Agent();
            if (isset($warung_yang_dipesan)) {
                $daftar_produk = DaftarProdukController::daftarProduk($data_produk, $warung_yang_dipesan);
            } else {
                $daftar_produk = DaftarProdukController::daftarProduk($data_produk);
            }

            $array_warung = DaftarProdukController::dataWarungTervalidasi();

            $cek_baner = SettingPromo::where('status',1)->where('id_warung',$array_warung);
            if ($cek_baner->count() > 0) {
                $baner_promo_active = $cek_baner->first();
                $baner_promo = SettingPromo::where('status',1)->where('baner_promo','!=',$baner_promo_active->baner_promo);
            }
            else{
                $baner_promo_active = 0;
                $baner_promo = 0;
            }


            $daftar_warung = DaftarProdukController::daftarWarung($warung_data);
        //SETTING APLIKASI
            $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        //SETTING TEMA
            $tema = TemaWarna::select('header_tema', 'kode_tema')->where('default_tema', 1)->where('warung_id', $address_app->warung_id)->first();

            return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_warung' => $daftar_warung, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent, 'cek_belanjaan' => $cek_belanjaan, 'logo_warmart' => $logo_warmart, 'setting_aplikasi' => $setting_aplikasi,'baner_promo'=>$baner_promo,'baner_promo_active'=>$baner_promo_active,'cek_baner'=>$cek_baner, 'tema' => $tema]);
        }

        public static function cekStokProduk($produks)
        {
            if (Auth::check()) {
                $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->where('id_produk', $produks->id)->count();
            } else {
                $keranjang_belanjaan = 0;
            }

        //jika belum ada belanjaan
            if ($keranjang_belanjaan == 0) {
            //jika barang di hitung stok
                if ($produks->hitung_stok == 1) {
                    $stok       = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $produks->id)->where('warung_id', $produks->id_warung)->first();
                    $cek_produk = $stok->stok_produk;
                }
            //jika tidak hitung stok
                else {
                    $cek_produk = 1;
                }

            } elseif ($keranjang_belanjaan > 0) {
            //jika sudah ada belanjaan
                if ($produks->hitung_stok == 1) {
                    $cek_produk = KeranjangBelanja::where('id_pelanggan', Auth::user()->id)->where('id_produk', $produks->id)->first();
                    $stok       = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $cek_produk->id_produk)->where('warung_id', $produks->id_warung)->first();
                    $cek_produk = $stok->stok_produk - $cek_produk->jumlah_produk;
                } else {

                    $cek_produk = 1;
                }

            }

            return $cek_produk;
        }

        public static function tombolBeli($cek_produk, $produks)
        {
            $pelanggan = 3;
            if (Auth::check() && Auth::user()->tipe_user == $pelanggan) {
                if ($cek_produk < 1) {
                    $tombol_beli = '<a disabled="true" class="btn btn-block tombolBeli buttonColor" rel="tooltip" title="Stok Tidak Ada"> Beli Sekarang </a>';
                } else {
            // $tombol_beli = '<a href="' . url('/keranjang-belanja/tambah-produk-keranjang-belanja/' . $produks->id . '') . '" id="btnBeliSekarang" class="btn btn-block tombolBeli buttonColor" > Beli Sekarang </a>';
                    $tombol_beli = '<button id="" class="btn btn-block tombolBeli buttonColor" data-id-produk='.$produks->id.' data-nama-produk="'.$produks->NamaProduk.'"> Beli Sekarang </button>';
                }
            } elseif (Auth::check() && Auth::user()->tipe_user != $pelanggan) {
             $tombol_beli = '<a  disabled="true" class="btn btn-block tombolBeli buttonColor" rel="tooltip" title="Masuk Sebagai Pelanggan Untuk Beli" > Beli Sekarang </a>';
         } else {
            if ($cek_produk < 1) {
                $tombol_beli = '<a disabled="true" class="btn btn-block tombolBeli buttonColor" rel="tooltip" title="Stok Tidak Ada" > Beli Sekarang </a>';
            } else {
               $tombol_beli = '<button id="" class="btn btn-block tombolBeli buttonColor" data-id-produk='.$produks->id.' data-nama-produk="'.$produks->NamaProduk.'"> Beli Sekarang </button>';
        // $tombol_beli = '<a href="' . url('/keranjang-belanja/tambah-produk-keranjang-belanja/' . $produks->id . '') . '" id="btnBeliSekarang" class="btn btn-block tombolBeli buttonColor" > Beli Sekarang </a>';
           }
       }

       return $tombol_beli;
   }

   public function tomboBeliDisable($tool_tip)
   {
    $tombol_beli = '<button type="button" class="btn btn-block tombolBeli buttonColor" id="btnBeli" disabled="" rel="tooltip" title="' . $tool_tip . '" > Beli Sekarang  </button>';
}

public static function tidakAdaProduk()
{
    $produk_kosong = "";
    $produk_kosong .= '
    <div class="col-md-12 col-s,-12 col-xs-12">
        <div class="card" data-colored-shadow="false" style="background-color:#f7f7f7">
            <div class="card-content">';
                $agent = new Agent();
                if ($agent->isMobile()) {
                    $produk_kosong .= '<h6 class="text-center" style="margin:0px">Oops... "Produk" Tidak Dapat Ditemukan.</h6>
                    <p class="text-center">Silakan menggunakan kata kunci lain.</p>';
                } else {
                    $produk_kosong .= '<h3 class="title text-center" style="margin:0px">Oops... Hasil Pencarian "Produk" Tidak Dapat Ditemukan.</h3>
                    <h5 class="text-center" style="margin:0px">Silakan melakukan pencarian kembali dengan menggunakan kata kunci lain.</h5>';
                }
                $produk_kosong .= '</div>
            </div>
        </div>';

        return $produk_kosong;
    }

    public static function namaProduk($produks)
    {
        if (strlen(strip_tags($produks->nama)) <= 33) {

            $nama_produk = '' . strip_tags($produks->nama);
        } else {
            $agent = new Agent();
            if ($agent->isMobile()) {
                $nama_produk = '' . strip_tags(substr($produks->nama, 0, 35)) . '...';
            } else {
                $nama_produk = '' . strip_tags(substr($produks->nama, 0, 60)) . '...';
            }

        }
        return $nama_produk;
    }

    public static function namaWarung($warung)
    {

        if (strlen($warung->name) > 25) {
            # code...
            $namaWarung = '<a href="' . url("/halaman-warung/" . $warung->id . "") . '" class="description"> <i class="material-icons">store</i> ' . strip_tags(substr($warung->name, 0, 25)) . '... </a>';
        } else {
            $namaWarung = '<a href="' . url("/halaman-warung/" . $warung->id . "") . '" class="description"> <i class="material-icons">store</i> ' . strip_tags($warung->name) . ' </a>';
        }
        return $namaWarung;

    }

    public static function fotoProduk($produks)
    {
        if ($produks->foto != null && file_exists(public_path() . '/foto_produk/' . $produks->foto)) {

            DaftarProdukController::resizeProduk($produks);

            $foto_produk = '<img id="foto' . $produks->id . '" alt="' . $produks->nama . '" data-src="' . asset('foto_produk/' . $produks->foto . '') . '">';
        } else {
            $foto_produk = '<img src="' . asset('image/foto_default.png') . '">';
        }
        return $foto_produk;
    }

    public static function cardProduk($produks)
    {
        $card_produk = "";
        if ($produks->konfirmasi_admin != 0) {
            if ($produks->status_aktif != 0) {

                $warung     = Warung::select(['name', 'id',DB::raw('CURDATE() as tanggal_sekarang'),DB::raw('DATE_FORMAT(NOW(), "%H") as jam_sekarang')])->where('id', $produks->id_warung)->first();
                $cek_produk = DaftarProdukController::cekStokProduk($produks);

                $data_tanggal_promo = SettingPromo::settingPromoTanggal($produks);
                if ($data_tanggal_promo->count() > 0) {
                    $dari_tanggal = $data_tanggal_promo->first()->dari_tanggal;
                    $sampai_tanggal = $data_tanggal_promo->first()->sampai_tanggal;

                    $data_harga_coret = SettingPromo::settingPromoData($produks,$dari_tanggal,$sampai_tanggal);
                }
                else{
                    $dari_tanggal = '0000-00-00';
                    $sampai_tanggal = '0000-00-00';

                    $data_harga_coret = SettingPromo::settingPromoData($produks,$dari_tanggal,$sampai_tanggal);
                }
                //Mencari hari sekarang
                $tgl= substr($warung->tanggal_sekarang,8,2);
                $bln= substr($warung->tanggal_sekarang,5,2);
                $thn= substr($warung->tanggal_sekarang,0,4);

                $info= date('w', mktime(0,0,0,$bln,$tgl,$thn));
                if ($info == 0) {
                    $hari = "minggu";
                }elseif($info == 1){
                    $hari = "senin";
                }elseif($info == 2){
                    $hari = "selasa";
                }elseif($info == 3){
                    $hari = "rabu";
                }elseif($info == 4){
                    $hari = "kamis";
                }elseif($info == 5){
                    $hari = "jumat";
                }elseif($info == 6){
                    $hari = "sabtu";
                }
                //Mencari hari sekarang

                $jam = $warung->jam_sekarang.":00";

                $card_produk .= '
                <div class="col-md-2 col-sm-6 col-xs-6 list-produk " style=" margin-bottom:10px;">
                    <div class="card cards card-pricing">
                        <a href="' . url("/detail-produk/" . $produks->id . "") . '">
                            <div class="card-image">';
                                $card_produk .= DaftarProdukController::fotoProduk($produks);
                                $card_produk .= '
                            </div>
                        </a>
                        <div class="card-content">
                            <div class="footer">
                                <p class=" flexFont">';

                                    $card_produk .= '<a href="' . url("/detail-produk/" . $produks->id . "") . '" >';
                                    $card_produk .= DaftarProdukController::namaProduk($produks);
                                    $card_produk .= '</a></p>';

            //Menampilkan filter promo 
                                    if ($data_harga_coret->count() > 0 ) {
                                        $card_produk .=  
                                        '<p style="color:#d21f30;" class="flexFont" >
                                        <span id="coret" class="class_coret" >
                                            ' . $produks->rupiah . ' / ' . $produks->satuan->nama_satuan.'</span>
                                            <br><span style="color:#d21f30;" id="id_promo">';
                                            foreach ($data_harga_coret->get() as $data) {
                                                if ($hari == $data->name) {
                                                    $card_produk .= 'Rp ' . number_format($data->harga_coret, 0, ',', '.') . ' / ' . $produks->satuan->nama_satuan;
                                                }
                                                else{
                                                    $card_produk .= '';
                                                }
                                            }
                                            $card_produk .= '</span></p>';          
                                        }else{
                                            $card_produk .=  
                                            '<p style="color:#d21f30;" class="flexFont"> 
                                            ' . $produks->rupiah . ' / ' . $produks->satuan->nama_satuan . '</p>';
                                        }
            //Menampilkan filter promo 


                                        $card_produk .= '<p class="flexFont">' . DaftarProdukController::namaWarung($warung) . '</p>';
                //tombol beli
                                        $card_produk .= DaftarProdukController::tombolBeli($cek_produk, $produks);
                                        $card_produk .= '
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                }
                return $card_produk;
            }

            public static function daftarProduk($data_produk, $warung_yang_dipesan = "")
            {
                if ($data_produk->count() > 0) {
                    $daftar_produk = "";
                    foreach ($data_produk as $produks) {
                     if ($warung_yang_dipesan == "" or $warung_yang_dipesan == $produks->id_warung) {
                       $daftar_produk .= DaftarProdukController::cardProduk($produks);
                   }
               }
               if ($daftar_produk == "") {
                $daftar_produk = DaftarProdukController::tidakAdaProduk();
            }
        } else {
            $daftar_produk = DaftarProdukController::tidakAdaProduk();
        }

        return $daftar_produk;
    }

    public static function dataWarungTervalidasi()
    {
        $data_warung  = User::select(['id_warung'])->where('id_warung', '!=', 'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
        $array_warung = array();
        foreach ($data_warung as $data_warungs) {
            array_push($array_warung, $data_warungs->id_warung);
        }

        return $array_warung;

    }

    public static function resizeProduk($produks)
    {
        $foto_produk = Image::make(asset('foto_produk/' . $produks->foto));
        $height_foto = $foto_produk->height();
        $width_foto  = $foto_produk->width();
        if ($height_foto != 300 || $width_foto != 300) {
            $foto_produk->fit(300);
            $foto_produk->save(public_path('foto_produk/' . $filename));
        }
    }

//WARUNG//WARUNG//WARUNG//WARUNG

    public static function daftarWarung($warung_data)
    {
        if ($warung_data->count() > 0) {
            $daftar_warung = "";
            $perulangan    = 0;
            foreach ($warung_data as $warungs) {
                $daftar_warung .= DaftarProdukController::cardWarung($warungs);

                $perulangan++;
                if ($perulangan == 4) {
                    break;
                }

            }
        } else {
            $daftar_warung = DaftarProdukController::tidakAdaWarung();
        }
        return $daftar_warung;
    }

    public static function cardWarung($warungs)
    {
        $card_warung = "";
        $card_warung .= '
        <div class="col-md-3 col-sm-6 col-xs-6 list-produk " style=" margin-bottom:10px;">
            <div class="card cards card-pricing" style="text-align: left;">
                <div class="card-content">
                    <div class="footer">
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <p class="">
                                    <i class="material-icons">store</i>
                                </p>
                                <p class="">
                                    <i class="material-icons">place</i>
                                </p>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <p class="">
                                    <a href="halaman-warung/' . $warungs->id . '" >';
                                        $card_warung .= DaftarProdukController::warungNama($warungs);
                                        $card_warung .= '
                                    </a>
                                </p>
                                <p class="">
                                    <a href="halaman-warung/' . $warungs->id . '">';
                                        $card_warung .= DaftarProdukController::alamatWarung($warungs);
                                        $card_warung .= '
                                    </a>
                                </p>
                            </div>
                        </div>
                        <p class="btnWarung">';
        //tombol kunjungi
                            $card_warung .= DaftarProdukController::tombolKunjungi($warungs);
                            $card_warung .= '
                        </p>
                    </div>
                </div>
            </div>
        </div>';
        return $card_warung;
    }

    public static function alamatWarung($warungs)
    {
        if (strlen(strip_tags($warungs->alamat)) <= 33) {
            $nama_produk = '' . strip_tags($warungs->alamat);
        } else {
            $agent = new Agent();
            if ($agent->isMobile()) {
                $nama_produk = '' . strip_tags(substr($warungs->alamat, 0, 35)) . '...';
            } else {
                $nama_produk = '' . strip_tags(substr($warungs->alamat, 0, 60)) . '...';
            }

        }
        return $nama_produk;
    }

    public static function tombolKunjungi($warungs)
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $tombol_kunjungi = '<a href="' . asset("halaman-warung/" . $warungs->id . "") . '" style="background-color:#01573e; position: relative" class="btn btn-block tombolBeli buttonColor" id="btnKunjungi"> Kunjungi Warung </a>';
        } else {
            $tombol_kunjungi = '<a href="' . asset("halaman-warung/" . $warungs->id . "") . '" id="btnKunjungi" style="background-color:#01573e; position: relative" class="btn btn-block tombolBeli buttonColor"> Kunjungi Warung </a>';
        }
        return $tombol_kunjungi;
    }

//NAMA WARUNG UNTUK DAFTAR WARUNG
    public static function warungNama($warung)
    {

        if (strlen($warung->name) > 25) {
            $namaWarung = '' . strip_tags(substr($warung->name, 0, 25)) . '...';
        } else {
            $namaWarung = '' . strip_tags($warung->name) . '';
        }
        return $namaWarung;

    }

    public static function tidakAdaWarung()
    {
        $warung_kosong = "";
        $warung_kosong .= '
        <div class="col-md-12 col-s,-12 col-xs-12">
            <div class="card" data-colored-shadow="false" style="background-color:#f7f7f7">
                <div class="card-content">';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $warung_kosong .= '<h6 class="text-center" style="margin:0px">Oops... "Warung" Tidak Dapat Ditemukan.</h6>
                        <p class="text-center">Silakan menggunakan kata kunci lain.</p>';
                    } else {
                        $warung_kosong .= '<h3 class="title text-center" style="margin:0px">Oops... Hasil Pencarian "Warung" Tidak Dapat Ditemukan.</h3>
                        <h5 class="text-center" style="margin:0px">Silakan melakukan pencarian kembali dengan menggunakan kata kunci lain.</h5>';
                    }
                    $warung_kosong .= '</div>
                </div>
            </div>';

            return $warung_kosong;
        }

    }
