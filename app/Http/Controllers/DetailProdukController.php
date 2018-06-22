<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Http\Controllers\DaftarProdukController;
use App\KeranjangBelanja;
use App\SettingAplikasi;
use App\SettingPembedaAplikasi;
use App\SettingPromo;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;
use OpenGraph;
use SEOMeta;
use Session;


class DetailProdukController extends Controller
{
    //
    public function detail_produk($id)
    {

        $this->seoDetailProduk();
        $barang               = Barang::find($id);

        $cek_harga = $this->cekHargaProdukPromo($id);
        if ($cek_harga == ""){
            $harga_produk = $barang->harga_jual;
        }else{
            $harga_produk = $cek_harga;
        }


        $array_warung         = DaftarProdukController::dataWarungTervalidasi();
        $daftar_produk_sama   = $this->produkSekategori($barang, $array_warung);
        $daftar_produk_warung = $this->produkSewarung($barang, $array_warung);

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

        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $sisa_stok_keluar = DaftarProdukController::cekStokProduk($barang);
        return view('layouts.detail_produk', ['id' => $id, 'barang' => $barang, 'cek_belanjaan' => $cek_belanjaan, 'daftar_produk_sama' => $daftar_produk_sama, 'daftar_produk_warung' => $daftar_produk_warung, 'cek_produk' => $sisa_stok_keluar,'setting_aplikasi'=>$setting_aplikasi,'harga_produk'=>$harga_produk]);

    }

        public function cekHargaProdukPromo($id){
        $barang = Barang::select(['id','harga_jual',DB::raw('CURDATE() as tanggal_sekarang')])->where('id', $id);
        $data_tanggal_promo = SettingPromo::settingPromoTanggal($barang->first());
        if ($data_tanggal_promo->count() > 0) {
            $dari_tanggal = $data_tanggal_promo->first()->dari_tanggal;
            $sampai_tanggal = $data_tanggal_promo->first()->sampai_tanggal;

            $data_harga_coret = SettingPromo::settingPromoData($barang->first(),$dari_tanggal,$sampai_tanggal);
        }
        else{
            $dari_tanggal = '0000-00-00';
            $sampai_tanggal = '0000-00-00';

            $data_harga_coret = SettingPromo::settingPromoData($barang->first(),$dari_tanggal,$sampai_tanggal);
        }
                //Mencari hari sekarang
        $tgl= substr($barang->first()->tanggal_sekarang,8,2);
        $bln= substr($barang->first()->tanggal_sekarang,5,2);
        $thn= substr($barang->first()->tanggal_sekarang,0,4);

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
        if ($data_harga_coret->count() > 0 ) {
            foreach ($data_harga_coret->get() as $data) {
                if ($hari == $data->name) {
                    $harga_produk    = $data->harga_coret;
                    break;
                }else{
                    $harga_produk    = "";
                }
            }
        }else{
            $harga_produk    = "";
        }
        return $harga_produk;
    }

    public function dataWarungTervalidasi()
    {
        $data_warung  = User::select(['id_warung'])->where('id_warung', '!=', 'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
        $array_warung = array();
        foreach ($data_warung as $data_warungs) {
            array_push($array_warung, $data_warungs->id_warung);
        }

        return $array_warung;

    }

    public function produkSewarung($barang, $array_warung)
    {
        $data_produk          = Barang::where('foto', '!=', 'NULL')->where('id_warung', $barang->id_warung)->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);
        $daftar_produk_warung = DaftarProdukController::daftarProduk($data_produk);
        return $daftar_produk_warung;
    }

    public function produkSekategori($barang, $array_warung)
    {

        //Cek Address Aplikasi yg di Jalankan
        $address_current = url('/');

        $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();

        if ($address_current == $address_app->app_address) {
            $data_produk        = Barang::where('foto', '!=', 'NULL')->where('kategori_barang_id', $barang->kategori_barang_id)->where('id_warung', $address_app->warung_id)->inRandomOrder()->paginate(4);
        }else{
            $data_produk        = Barang::where('foto', '!=', 'NULL')->where('kategori_barang_id', $barang->kategori_barang_id)->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);    
        }
        
        $daftar_produk_sama = DaftarProdukController::daftarProduk($data_produk);
        return $daftar_produk_sama;
    }
    public function seoDetailProduk()
    {
        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace', 'toko online', 'belanja', 'lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');

    }

}
