<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\Error;
use App\Hpp;
use App\KategoriBarang;
use App\PenjualanPos;
use App\PesananPelanggan;
use App\SettingAplikasi;
use App\TransaksiKas;
use App\SettingPembedaAplikasi;
use App\User;
use App\UserWarung;
use App\Warung;
use Auth;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace', 'toko online', 'belanja', 'lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');

        //MENAMPILKAN PRODUK ACAK
        //Pilih warung yang sudah dikonfirmasi admin
        $data_warung  = User::select(['id_warung'])->where('id_warung', '!=', 'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
        $array_warung = array();
        foreach ($data_warung as $data_warungs) {
            array_push($array_warung, $data_warungs->id_warung);
        }

        $data_produk     = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->where('foto', '!=', 'NULL')->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);
        $kategori_produk = KategoriBarang::select(['id', 'nama_kategori_barang'])->get();
        $daftar_produk   = $this->listProduk($data_produk);

        return view('layouts.landing_page', ['daftar_produk' => $daftar_produk]);

    }

    public function listProduk($data_produk)
    {
        $agent = new Agent();

        $daftar_produk = '';
        foreach ($data_produk as $produks) {

            $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();

            $daftar_produk .= '
            <div class="col-md-3 col-sm-6 col-xs-6 list-produk">
            <div class="card cards card-pricing">
            <a href="' . url("/keranjang-belanja") . '">
            <div class="card-image">';
            if ($produks->foto != null) {
                $daftar_produk .= '<img src="./foto_produk/' . $produks->foto . '">';
            } else {
                $daftar_produk .= '<img src="./image/foto_default.png">';
            }
            $daftar_produk .= '
            </div>
            </a>
            <div class="card-content">
            <div class="footer">
            <p class="flexFont">
            <a href="' . url("/keranjang-belanja") . '" >
            ' . strip_tags(substr($produks->nama, 0, 10)) . '...
            </a></p>
            <p style="color:red; font-size:18px"> ' . $produks->rupiah . ' </p>
            <a class="description"><i class="material-icons">store</i>  ' . strip_tags(substr($warung->name, 0, 10)) . '... </a><br>';

            if ($agent->isMobile()) {
                $daftar_produk .= '<a href="' . url("/keranjang-belanja") . '" class="btn btn-danger btn-block" id="btnBeliSekarang" style="background-color:#01573e"> Beli Sekarang </a>';
            } else {
                $daftar_produk .= '<a href="' . url('/keranjang-belanja/tambah-produk-keranjang-belanja/' . $produks->id . '') . '" id="btnBeliSekarang" class="btn btn-danger btn-block"  style="background-color:#01573e;"> Beli Sekarang </a>';
            }
            $daftar_produk .= '
            </div>
            </div>
            </div>
            </div>';
        }
        return $daftar_produk;
    }

    public function index_home()
    {
        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace', 'toko online', 'belanja', 'lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');

        //MENAMPILKAN PRODUK ACAK
        $data_produk      = Barang::select(['id', 'kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->inRandomOrder()->paginate(4);
        $kategori_produk  = KategoriBarang::select(['id', 'nama_kategori_barang'])->get();
        $daftar_produk    = $this->listProduk($data_produk);
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        if ($setting_aplikasi->tipe_aplikasi == 0) {

            if (Auth::user()->tipe_user == 3) {
                return redirect()->route('daftar_produk.index');
            } else {
                return view('layouts.landing_page', ['daftar_produk' => $daftar_produk]);
            }

        } else {
            return redirect()->intended('/');
        }

    }

    public function dashboard()
    {
        //DASBOARD ADMIN
        $jumlah_komunitas             = User::where('tipe_user', '2')->count();
        $jumlah_customer              = User::where('tipe_user', '3')->count();
        $jumlah_warung                = User::where('tipe_user', '4')->count();
        $jumlah_warung_tervalidasi    = User::where('tipe_user', '4')->where('konfirmasi_admin', '1')->count();
        $jumlah_komunitas_tervalidasi = User::where('tipe_user', '2')->where('konfirmasi_admin', '1')->count();
        $produk                       = Barang::count();
        $error_log                    = Error::count();

        //DASBOARD WARUNG
        $data_warung       = Auth::user()->id_warung;
        $produk_warung     = Barang::where('id_warung', $data_warung)->count();
        $transaksi_kas     = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_kas')])->where('warung_id', $data_warung)->first();
        $jumlah_kas_masuk  = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as total_kas_masuk')])->where('warung_id', $data_warung)->first();
        $jumlah_kas_keluar = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as total_kas_keluar')])->where('warung_id', $data_warung)->first();

        $stok_masuk            = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_item_masuk')])->where('warung_id', $data_warung)->first();
        $stok_keluar           = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as jumlah_item_keluar')])->where('warung_id', $data_warung)->first();
        $nila_masuk            = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->where('jenis_hpp', 1)->where('warung_id', $data_warung)->first();
        $nila_keluar           = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->where('jenis_hpp', 2)->where('warung_id', $data_warung)->first();
        $prose_total_persedian = $nila_masuk->total_masuk - $nila_keluar->total_keluar;
        $total_persedian       = number_format($prose_total_persedian, 0, ',', '.');

        $user        = Auth::user();
        $user_warung = UserWarung::with(['kelurahan'])->find($user->id);

        if ($user->tipe_user == 4) {
            //JIKA FOTO KTP SUDAH DI ISI MAKA AKAN REDIRECT KE HOME
            if ($user_warung->foto_ktp == null or $user_warung->foto_ktp == "") {
                return view('home');
            }
            //JIKA FOTO KTP BELUM DI ISI MAKA AKAN REDIRECT KE UBAH PROFIL
            else {
                return view('home', ['jumlah_komunitas' => $jumlah_komunitas, 'jumlah_customer' => $jumlah_customer, 'jumlah_warung' => $jumlah_warung, 'jumlah_warung_tervalidasi' => $jumlah_warung_tervalidasi, 'jumlah_komunitas_tervalidasi' => $jumlah_komunitas_tervalidasi, 'produk' => $produk, 'error_log' => $error_log, 'produk_warung' => $produk_warung, 'transaksi_kas' => $transaksi_kas, 'jumlah_kas_masuk' => $jumlah_kas_masuk, 'jumlah_kas_keluar' => $jumlah_kas_keluar, 'stok_masuk' => $stok_masuk, 'stok_keluar' => $stok_keluar, 'total_persedian' => $total_persedian]);
            }
        } else {
            return view('home', ['jumlah_komunitas' => $jumlah_komunitas, 'jumlah_customer' => $jumlah_customer, 'jumlah_warung' => $jumlah_warung, 'jumlah_warung_tervalidasi' => $jumlah_warung_tervalidasi, 'jumlah_komunitas_tervalidasi' => $jumlah_komunitas_tervalidasi, 'produk' => $produk, 'error_log' => $error_log, 'produk_warung' => $produk_warung, 'transaksi_kas' => $transaksi_kas, 'jumlah_kas_masuk' => $jumlah_kas_masuk, 'jumlah_kas_keluar' => $jumlah_kas_keluar, 'stok_masuk' => $stok_masuk, 'stok_keluar' => $stok_keluar, 'total_persedian' => $total_persedian]);
        }

    }

    public function dashboard_admin(Request $request)
    {

        $jumlah_komunitas             = User::where('tipe_user', '2')->count();
        $jumlah_customer              = User::where('tipe_user', '3')->count();
        $jumlah_warung                = User::where('tipe_user', '4')->count();
        $jumlah_warung_tervalidasi    = User::where('tipe_user', '4')->where('konfirmasi_admin', '1')->count();
        $jumlah_komunitas_tervalidasi = User::where('tipe_user', '2')->where('konfirmasi_admin', '1')->count();
        $produk                       = Barang::count();
        $error_log                    = Error::count();
        $rata_rata_produk_perwarung   = $produk / $jumlah_warung;
        $pesanan                      = PesananPelanggan::all()->count();
        $pesanan_selesai              = PesananPelanggan::where('konfirmasi_pesanan', 2)->count();
        $setting_aplikasi             = SettingAplikasi::select('tipe_aplikasi')->first();
        $user                         = Auth::user();
        $logo_toko                    = UserWarung::find($user->id);

        $response['komunitas']             = $jumlah_komunitas;
        $response['customer']              = $jumlah_customer;
        $response['warung']                = $jumlah_warung;
        $response['warung_tervalidasi']    = $jumlah_warung_tervalidasi;
        $response['komunitas_tervalidasi'] = $jumlah_komunitas_tervalidasi;
        $response['rata_rata_produk']      = $rata_rata_produk_perwarung;
        $response['produk']                = $produk;
        $response['error_log']             = $error_log;
        $response['pesanan']               = $pesanan;
        $response['pesanan_selesai']       = $pesanan_selesai;
        $response['setting_aplikasi']      = $setting_aplikasi;
        $response['logo_toko']             = $logo_toko;

        return response()->json($response);

    }

    public function tandaPemisahTitik($angka)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function dashboard_warung(Request $request)
    {

        $data_warung       = Auth::user()->id_warung;
        $produk_warung     = Barang::where('id_warung', $data_warung)->count();
        $transaksi_kas     = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_kas')])->where('warung_id', $data_warung)->first();
        $jumlah_kas_masuk  = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as total_kas_masuk')])->where('warung_id', $data_warung)->first();
        $jumlah_kas_keluar = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as total_kas_keluar')])->where('warung_id', $data_warung)->first();

        $stok_masuk            = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_item_masuk')])->where('warung_id', $data_warung)->first();
        $stok_keluar           = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as jumlah_item_keluar')])->where('warung_id', $data_warung)->first();
        $nila_masuk            = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->leftJoin('barangs', 'barangs.id', '=', 'hpps.id_produk')->where('barangs.hitung_stok', 1)->where('hpps.jenis_hpp', 1)->where('hpps.warung_id', Auth::user()->id_warung)->first();
        
        $nila_keluar           = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->leftJoin('barangs', 'barangs.id', '=', 'hpps.id_produk')->where('barangs.hitung_stok', 1)->where('hpps.jenis_hpp', 2)->where('hpps.warung_id', Auth::user()->id_warung)->first();
        $prose_total_persedian = $nila_masuk->total_masuk - $nila_keluar->total_keluar;
        $total_persedian       = $prose_total_persedian;
        $user                  = Auth::user();

        $address_current = url('/');

        $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();
        if ($address_current == $address_app->app_address) {
            $logo_toko = UserWarung::where('tipe_user',4)->where('id_warung', $address_app->warung_id)->orderBy('id', 'asc')->limit(1)->first();
        }else{
            $logo_toko = UserWarung::where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first();
        }
        

        // PENJUALAN BULAN INI
        $startDate = new Carbon('first day of this month');
        $endDate   = new Carbon('last day of this month');

        // TOTAL PENJUALAN
        $penjualan_pos   = DetailPenjualanPos::totalPenjualan($startDate, $endDate)->first()->total;
        $penjualan       = DetailPenjualan::totalPenjualan($startDate, $endDate)->first()->total;
        $total_penjualan = $penjualan_pos + $penjualan;

        // LABA KOTOR PENJUALAN
        $laba_kotor_pos = DetailPenjualanPos::labaKotorPenjualan($startDate, $endDate)->first();
        $transaksi_pos  = "PenjualanPos";
        $sub_hpp_pos    = Hpp::hppPenjualan($startDate, $endDate, $transaksi_pos)->first()->total_hpp;

        $laba_kotor = DetailPenjualan::labaKotorPenjualan($startDate, $endDate)->first();
        $transaksi  = "penjualan";
        $sub_hpp    = Hpp::hppPenjualan($startDate, $endDate, $transaksi)->first()->total_hpp;

        // POTONGAN
        $potongan = PenjualanPos::potonganPenjualan($startDate, $endDate)->first()->potongan;

        $total_laba_kotor_pos = $penjualan_pos - $sub_hpp_pos;
        $total_laba_kotor     = $penjualan - $sub_hpp;
        $subtotal_laba_kotor  = $total_laba_kotor_pos + $total_laba_kotor - $potongan;

        $response['produk_warung']    = $this->tandaPemisahTitik($produk_warung);
        $response['transaksi_kas']    = 'Rp ' . $this->tandaPemisahTitik($transaksi_kas->jumlah_kas);
        $response['kas_masuk']        = 'Rp ' . $this->tandaPemisahTitik($jumlah_kas_masuk->total_kas_masuk);
        $response['kas_keluar']       = 'Rp ' . $this->tandaPemisahTitik($jumlah_kas_keluar->total_kas_keluar);
        $response['stok_masuk']       = 'Rp ' . $this->tandaPemisahTitik($total_penjualan);
        $response['stok_keluar']      = 'Rp ' . $this->tandaPemisahTitik($subtotal_laba_kotor);
        $response['total_persedian']  = 'Rp ' . $this->tandaPemisahTitik($total_persedian);
        $response['konfirmasi_admin'] = Auth::user()->konfirmasi_admin;
        $response['kasir_id']         = Auth::user()->kasir_id;
        $response['logo_toko_2']      = $logo_toko;

        return response()->json($response);

    }

    public function sms()
    {

        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=k9d4p8&passkey=afifmaulana&nohp=081222498686&pesan=isi%20pesan');

        return $result->getBody();
    }

    public function caraMemesan()
    {
        return view('auth.cara_memesan');
    }
}
