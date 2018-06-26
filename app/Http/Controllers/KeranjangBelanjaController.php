<?php

namespace App\Http\Controllers;

use App\Barang;
use App\KeranjangBelanja;
use App\SettingAplikasi;
use App\SettingPromo;
use App\SettingPembedaAplikasi;
use Auth;
use DB;
use Jenssegers\Agent\Agent;
use OpenGraph;
use SEOMeta;
use Illuminate\Http\Request;
use Session;

class KeranjangBelanjaController extends Controller
{
    //

    public function daftar_belanja()
    {

        $this->seo();
        $warung_id = $this->getIdWarung();
        $agent = new Agent();
        if(!Session::get('session_id')){
            $session_id    = session()->getId();
        }else{
            $session_id = Session::get('session_id');
        }
        if (Auth::check() == false) {
            $keranjang_belanjaan = KeranjangBelanja::with(['produk'])->where('session_id', $session_id)->Where('warung_id',$warung_id)->orderBy('id_keranjang_belanja','desc')->get();
            $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('session_id',$session_id)->Where('warung_id',$warung_id)->first();
        }else{
            $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id)->orderBy('id_keranjang_belanja','desc')->get();  
            $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id)->first();          
        }

        $cek_belanjaan       = $keranjang_belanjaan->count();

        

        //MEANMPILKAN PRODUK BELANJAAN DAN SUBTUTALNYA
        $produk_belanjaan_dan_subtotal = $this->tampilanProdukKeranjangBelanja($keranjang_belanjaan);
        $subtotal                      = number_format($produk_belanjaan_dan_subtotal['subtotal'], 0, ',', '.');
        $produk_belanjaan              = $produk_belanjaan_dan_subtotal['produk_belanjaan'];
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        return view('layouts.keranjang_belanja', ['keranjang_belanjaan' => $keranjang_belanjaan, 'cek_belanjaan' => $cek_belanjaan, 'agent' => $agent, 'produk_belanjaan' => $produk_belanjaan, 'jumlah_produk' => $jumlah_produk, 'subtotal' => $subtotal, 'setting_aplikasi' => $setting_aplikasi]);

    }

    public function daftar_belanja_collapse() {
        $warung_id = $this->getIdWarung();
        $agent = new Agent();

        if(!Session::get('session_id')){
            $session_id    = session()->getId();
        }else{
            $session_id = Session::get('session_id');
        }
        if (Auth::check() == false) {
            $keranjang_belanjaan = KeranjangBelanja::with(['produk'])->where('session_id', $session_id)->Where('warung_id',$warung_id)->orderBy('id_keranjang_belanja','desc')->get();
            $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('session_id',$session_id)->Where('warung_id',$warung_id)->first();
        }else{
            $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id)->orderBy('id_keranjang_belanja','desc')->get();  
            $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id)->first();          
        }
        $cek_belanjaan       = $keranjang_belanjaan->count();



        //MEANMPILKAN PRODUK BELANJAAN DAN SUBTUTALNYA
        $produk_belanjaan_dan_subtotal = $this->tampilanProdukKeranjangBelanjaCollapse($keranjang_belanjaan);
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        return $produk_belanjaan_dan_subtotal;
    }

    public function daftar_belanja_collapse_mobile() {
        $warung_id = $this->getIdWarung();
        $agent = new Agent();

        if(!Session::get('session_id')){
            $session_id    = session()->getId();
        }else{
            $session_id = Session::get('session_id');
        }
        if (Auth::check() == false) {
            $keranjang_belanjaan = KeranjangBelanja::with(['produk'])->where('session_id', $session_id)->Where('warung_id',$warung_id)->orderBy('id_keranjang_belanja','desc')->get();
            $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('session_id',$session_id)->Where('warung_id',$warung_id)->first();
        }else{
            $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id)->orderBy('id_keranjang_belanja','desc')->get();  
            $jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->where('id_pelanggan', Auth::user()->id)->Where('warung_id',$warung_id)->first();          
        }
        $cek_belanjaan       = $keranjang_belanjaan->count();



        //MEANMPILKAN PRODUK BELANJAAN DAN SUBTUTALNYA
        $produk_belanjaan_dan_subtotal = $this->tampilanProdukKeranjangBelanjaCollapseMobile($keranjang_belanjaan);
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        return $produk_belanjaan_dan_subtotal;
    }

    public function hapus_produk_keranjang_belanjaan(Request $request)
    {
        KeranjangBelanja::destroy($request->id);
    }

    public function tambah_jumlah_produk_keranjang_belanjaan(Request $request)
    {


        $keranjang_belanjaans = KeranjangBelanja::with('produk')->find($request->id); 

        $sisa_stok       = $keranjang_belanjaans->produk->stok - $request->jumlah_produk;
        $respons['sisa_stok'] = floor($keranjang_belanjaans->produk->stok);

        if ($keranjang_belanjaans->produk->hitung_stok == 1) {

            $respons['produk'] = "barang";
            if ($sisa_stok < 0) {
                $respons['respons'] = 0;
            }else{

                $keranjang_belanjaans->update(['jumlah_produk'=>$request->jumlah_produk]);
                $respons['respons'] = 1;
            }

        }else{

            $keranjang_belanjaans->update(['jumlah_produk'=>$request->jumlah_produk]); 
            $respons['produk'] = "jasa";           
            $respons['respons'] = 1;
        }


        return response()->json($respons);

    }

    public function kurang_jumlah_produk_keranjang_belanjaan(Request $request)
    {
        $produk = KeranjangBelanja::find($request->id);
        $produk->update(['jumlah_produk'=>$request->jumlah_produk]);
        return response(200);

    }

    public function tambah_produk_keranjang_belanjaan(Request $request)
    {
        $warung_id = $this->getIdWarung();
        if(!Session::get('session_id')){
            $session_id    = session()->getId();
        }else{
            $session_id = Session::get('session_id');
        }

        $harga_produk = $this->cekHargaProdukPromo($request);
        if (Auth::check() == false) {
            $datakeranjang_belanjaan = KeranjangBelanja::where('session_id', $session_id)->Where('warung_id',$warung_id)->Where('id_produk', $request->id_produk); 
            $jumlah_produk           = $datakeranjang_belanjaan->first();

            if ($datakeranjang_belanjaan->count() == 0) {            
                Session::put('session_id',$session_id);
            }

            if ($datakeranjang_belanjaan->count() > 0) {
                $total_tambah_produk = 0;
                $datakeranjang_belanjaan->update(['jumlah_produk' => $jumlah_produk->jumlah_produk + $request->jumlah_produk]);

            } else {
                $total_tambah_produk = 1;
                $produk = KeranjangBelanja::create(['id_produk' => $request->id_produk, 'session_id' => $session_id, 'jumlah_produk' => $request->jumlah_produk, 'warung_id' => $warung_id,'harga_produk'=>$harga_produk]);
            }

        }else{
            $pelanggan = Auth::user()->id;
            $datakeranjang_belanjaan = KeranjangBelanja::where('id_pelanggan', $pelanggan)->Where('warung_id',$warung_id)->Where('id_produk', $request->id_produk); 
            $jumlah_produk           = $datakeranjang_belanjaan->first();

            if ($datakeranjang_belanjaan->count() == 0) {            
                Session::put('session_id',$session_id);
            }

            if ($datakeranjang_belanjaan->count() > 0) {
             $total_tambah_produk = 0;
             $datakeranjang_belanjaan->update(['jumlah_produk' => $jumlah_produk->jumlah_produk + $request->jumlah_produk]);

         } else {
             $total_tambah_produk = 1;
             $produk = KeranjangBelanja::create(['id_produk' => $request->id_produk, 'id_pelanggan' => $pelanggan, 'jumlah_produk' => $request->jumlah_produk, 'warung_id' => $warung_id,'harga_produk'=>$harga_produk]);
         }
     }

     return  $total_tambah_produk;

 }

 public function fotoProduk($keranjang_belanjaans)
 {
    if ($keranjang_belanjaans->produk->foto != null) {
        $foto_produk = '<img src="foto_produk/' . $keranjang_belanjaans->produk->foto . '">';
    } else {
        $foto_produk = '<img src="image/foto_default.png">';
    }
    return $foto_produk;
}

public function tombolKurangiProduk($keranjang_belanjaans)
{

    $agent = new Agent();
    if ($agent->isMobile()) {

        $tombolKurangiProduk = '<button type="button" class="btn btn-xs kurangProdukMobile" data-id="'.$keranjang_belanjaans->id_keranjang_belanja.'" id="kurangProduk-'.$keranjang_belanjaans->id_keranjang_belanja.'"><i class="material-icons">remove</i> </button>';

    } else {

        $tombolKurangiProduk = '<button type="button" class="btn btn-round btn-info btn-xs kurangProduk" style="background-color: #01573e" data-id="'.$keranjang_belanjaans->id_keranjang_belanja.'" id="kurangProduk-'.$keranjang_belanjaans->id_keranjang_belanja.'"><i class="material-icons">remove</i> </button>';

    }

    return $tombolKurangiProduk;
}

public function tombolTambahiProduk($sisa_stok, $keranjang_belanjaans)
{

    $agent = new Agent();
    if ($agent->isMobile()) {

        $tombolTambahiProduk = '<button type="button" class="btn btn-xs tambahProdukMobile" data-id="'.$keranjang_belanjaans->id_keranjang_belanja.'" id="tambahProduk-'.$keranjang_belanjaans->id_keranjang_belanja.'"><i class="material-icons">add</i> </button>';

    } else {

        $tombolTambahiProduk = '<button type="button" class="btn btn-round btn-info btn-xs tambahProduk" style="background-color: #01573e" data-id="'.$keranjang_belanjaans->id_keranjang_belanja.'" id="tambahProduk-'.$keranjang_belanjaans->id_keranjang_belanja.'"><i class="material-icons">add</i> </button>';

    }

    return $tombolTambahiProduk;
}

public function cardProdukBelanjaanCollapse($keranjang_belanjaans) {
    $subtotal_produk = $keranjang_belanjaans->harga_produk * $keranjang_belanjaans->jumlah_produk;
    $res = '';
    $res = '
    <table class="produkTable">
    <tr>
    <td class="produkName">
    <small> <b> '. ucwords($keranjang_belanjaans->produk->nama_barang) .' </b> </small> 
    <br> 
    <div class="warungName">
    <small> '. $keranjang_belanjaans->produk->warung->name .' </small>
    </div>
    <small class="productCount"> '. $keranjang_belanjaans->jumlah_produk .' x Rp.'. number_format($keranjang_belanjaans->harga_produk, 0, ',', '.') .' </small>
    </td>
    <td class="subtotalProduk"> <small> Rp.'. number_format($subtotal_produk, 0, ',', '.') .' </small> </td>
    </tr>
    </table>
    ';
    return $res;
}

public function cardProdukBelanjaanCollapseMobile($keranjang_belanjaans) {
    $subtotal_produk = $keranjang_belanjaans->harga_produk * $keranjang_belanjaans->jumlah_produk;
    $res = '';
    $res = '
    <table class="produkTableMobile">
    <tr>
    <td class="produkNameMobile">
    <small> <b> '. ucwords($keranjang_belanjaans->produk->nama_barang) .' </b> </small> 
    <br> 
    <div class="warungNameMobile">
    <small> '. $keranjang_belanjaans->produk->warung->name .' </small>
    </div>
    <small class="productCountMobile"> '. $keranjang_belanjaans->jumlah_produk .' x Rp.'. number_format($keranjang_belanjaans->harga_produk, 0, ',', '.') .' </small>
    </td>
    <td class="subtotalProdukMobile"> <small> Rp.'. number_format($subtotal_produk, 0, ',', '.') .' </small> </td>
    </tr>
    </table>
    ';        

    return $res;
}

public function cardProdukBelanjaan($harga_produk, $sisa_stok, $keranjang_belanjaans, $subtotal_produk)
{

    $agent = new Agent();
    if ($agent->isMobile()) {

        $produk_belanjaan = '
        <div class="card" style="margin-bottom: 1px; margin-top: 1px;" id="card-produk-'.$keranjang_belanjaans->id_keranjang_belanja.'">
        <div class="row">
        <div class="col-md-12">

        <div class="row">
        <div class="col-xs-4" style="padding-right:0px">
        <div class="img-container" style="margin:10px;">';
        $produk_belanjaan .= $this->fotoProduk($keranjang_belanjaans);
        $produk_belanjaan .= '</div>
        </div>

        <div class="col-xs-3" style="padding-left:0px; padding-right:0px; padding-top:23px">
        <b><a href="' . url('detail-produk/' . $keranjang_belanjaans->id_produk . '') . '" style="font-size: 12px;">' . $this->namaProduk($keranjang_belanjaans->produk->nama_barang) . '</a></b><br>
        <b style="color:red" id="hargaProdukKeranjangMobile-'.$keranjang_belanjaans->id_keranjang_belanja.'">' . number_format($harga_produk, 0, ',', '.') . '</b>
        <p style="font-size: 10px; margin-top:10px">' . $keranjang_belanjaans->produk->warung->name . '</p>
        </div>

        <div class="col-xs-5" style="padding-left:0px; padding-right:0px">
        <center>
        <div class="btn-group">';
            //tombol kurangi produk
        $produk_belanjaan .= $this->tombolKurangiProduk($keranjang_belanjaans); 
        $produk_belanjaan .= $this->tombolTambahiProduk($sisa_stok, $keranjang_belanjaans); 


        $produk_belanjaan .= '
        </div><br>
        <b id="jumlahProdukKeranjangMobile-'.$keranjang_belanjaans->id_keranjang_belanja.'">' . $keranjang_belanjaans->jumlah_produk . ' </b><br>
        <div class="btn-group  btnMobile-'.$keranjang_belanjaans->id_keranjang_belanja.'" data-nama-produk="' . title_case($keranjang_belanjaans->produk->nama_barang) . '">

        <button id="btnHapusProdukMobile" data-nama="' . title_case($keranjang_belanjaans->produk->nama_barang) . '" data-id="' . $keranjang_belanjaans->id_keranjang_belanja . '" data-subtotal="'.$subtotal_produk.'" class="btn btn-danger btn-xs"><i class="material-icons">delete</i></button>
        </div><br>
        <b align="right" id="subtotalProdukKeranjangMobile-'.$keranjang_belanjaans->id_keranjang_belanja.'" data-subtotal="'.$subtotal_produk.'">' . number_format($subtotal_produk, 0, ',', '.') . '</b>
        </center>
        </div>

        </div>
        </div>
        </div>
        </div>';

    } else {

        $produk_belanjaan = '

        <div class="card-content" style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px;" id="card-produk-'.$keranjang_belanjaans->id_keranjang_belanja.'">
        <div class="row">
        <div class="col-md-12">
        <div class="row">

        <div class="col-md-4">
        <div class="row">
        <div class="col-sm-4">
        <div class="img-container"  style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px; width:75px;">';
        $produk_belanjaan .= $this->fotoProduk($keranjang_belanjaans);
        $produk_belanjaan .= '
        </div>
        </div>
        <div class="col-sm-8">
        <font><b><a href="' . url('detail-produk/' . $keranjang_belanjaans->id_produk . '') . '">' . $this->namaProduk($keranjang_belanjaans->produk->nama_barang) . '</a></b> </font>
        <p><small> ' . $keranjang_belanjaans->produk->warung->name . '</small></p>
        </div>
        </div>
        </div>

        <div class="col-md-2">
        <h6 align="right"><b id="hargaProdukKeranjang-'.$keranjang_belanjaans->id_keranjang_belanja.'">' . number_format($harga_produk, 0, ',', '.') . '</b></h6>
        </div>

        <div class="col-md-3">
        <div class="btn-group btn-'.$keranjang_belanjaans->id_keranjang_belanja.'" data-jumlah-produk="'.$keranjang_belanjaans->jumlah_produk.'" data-nama-produk="' . title_case($keranjang_belanjaans->produk->nama_barang) . '">';

            //tombol kurangi produk
        $produk_belanjaan .= $this->tombolKurangiProduk($keranjang_belanjaans);

        $produk_belanjaan .= ' <button class="btn btn-round btn-info btn-xs" style="background-color: #01573e"><font id="jumlahProdukKeranjang-'.$keranjang_belanjaans->id_keranjang_belanja.'" style="font-size: 11.5px;">' . $keranjang_belanjaans->jumlah_produk . ' </font></button>';
            //tombol tambahi
        $produk_belanjaan .= $this->tombolTambahiProduk($sisa_stok, $keranjang_belanjaans);

        $produk_belanjaan .= '
        </div><br>
        <button id="btnHapusProduk" data-id="' . $keranjang_belanjaans->id_keranjang_belanja . '" data-nama="' . title_case($keranjang_belanjaans->produk->nama_barang) . '" data-subtotal="'.$subtotal_produk.'" class="btn btn-danger btn-xs">Hapus</button>

        </div>


        <div class="col-md-2">
        <b>Rp.</b><h6 align="right"><b id="subtotalProdukKeranjang-'.$keranjang_belanjaans->id_keranjang_belanja.'" data-subtotal="'.$subtotal_produk.'"> ' . number_format($subtotal_produk, 0, ',', '.') . '</b></h6>
        </div>
        </div>
        </div>
        </div>
        </div><hr style="margin-top: 0px; margin-bottom: 0px;">

        ';

    }

    return $produk_belanjaan;
}

public function tampilanProdukKeranjangBelanja($keranjang_belanjaan)
{
    $subtotal         = 0;
    $produk_belanjaan = "";
    foreach ($keranjang_belanjaan as $keranjang_belanjaans) {

        $barang = Barang::select(['id'])->where('id', $keranjang_belanjaans->id_produk);
            //jika barang yang di keranjang ternyata sudah dihapus warung
        if ($barang->count() == 0) {
            KeranjangBelanja::where('id_produk', $keranjang_belanjaans->id_produk)->delete();
        } else {

            $sisa_stok       = $barang->first()->stok - $keranjang_belanjaans->jumlah_produk;

            $subtotal_produk = $keranjang_belanjaans->harga_produk * $keranjang_belanjaans->jumlah_produk;
                //card produk belanjaan
            $produk_belanjaan .= $this->cardProdukBelanjaan($keranjang_belanjaans->harga_produk, $sisa_stok, $keranjang_belanjaans, $subtotal_produk);
            $subtotal += $subtotal_produk;
        }
    }

    return array('produk_belanjaan' => $produk_belanjaan, 'subtotal' => $subtotal);
}

public function tampilanProdukKeranjangBelanjaCollapse($keranjang_belanjaan)
{
    $subtotal         = 0;
    $produk_belanjaan = '';

    if (count($keranjang_belanjaan) == 0) {
        $produk_belanjaan = '
        <div class="produkKosong">
        Anda belum memesan produk apapun.
        </div>
        ';
    } else {
        $produk_belanjaan .= '
        <table class="table">
        <thead>
        <th class="thNamaProduk"> Nama </th>
        <th class="thSubtotal"> Subtotal </th>
        </thead>
        </table>
        <div class="tableList">
        ';


        foreach ($keranjang_belanjaan as $keranjang_belanjaans) {
            $barang = Barang::select(['id'])->where('id', $keranjang_belanjaans->id_produk);
                //jika barang yang di keranjang ternyata sudah dihapus warung
            if ($barang->count() == 0) {
                KeranjangBelanja::where('id_produk', $keranjang_belanjaans->id_produk)->delete();
            } else {

                $sisa_stok       = $barang->first()->stok - $keranjang_belanjaans->jumlah_produk;

                $subtotal += $keranjang_belanjaans->harga_produk * $keranjang_belanjaans->jumlah_produk;
                    //card produk belanjaan
                $produk_belanjaan .= $this->cardProdukBelanjaanCollapse($keranjang_belanjaans);
            }
        }

        $produk_belanjaan .= '
        </div>
        <table class="table">
        <tr>
        <hr>
        <td align="left" style="margin-top: 100px;"> Subtotal : </td>
        <td align="right"> Rp.'. number_format($subtotal, 0, ',', '.') .' </td>
        </tr>
        <tr>
        <td align="left">
        <a class="btn btn-info btn-sm" href="'. url("keranjang-belanja") .'"> Lihat Detail </a>
        </td>
        <td align="right">
        <a class="btn btn-info btn-sm" href="'. url("selesaikan-pemesanan") .'"> Pembayaran </a>
        </td>
        </tr>
        </table>    
        ';
    }
    return $produk_belanjaan;
}

public function tampilanProdukKeranjangBelanjaCollapseMobile($keranjang_belanjaan)
{
    $subtotal         = 0;
    $produk_belanjaan = '';

    if (count($keranjang_belanjaan) == 0) {
        $produk_belanjaan = '
        <div class="produkKosong">
        Anda belum memesan produk apapun.
        </div>
        ';
    } else {
        $produk_belanjaan .= '
        <div class="tableTable">
        <table class="table">
        <thead>
        <th class="thNamaProdukMobile"> Nama </th>
        <th class="thSubtotalMobile"> Subtotal </th>
        </thead>
        </table>
        <div class="tableListMobile">
        ';


        foreach ($keranjang_belanjaan as $keranjang_belanjaans) {
            $barang = Barang::select(['id'])->where('id', $keranjang_belanjaans->id_produk);
                //jika barang yang di keranjang ternyata sudah dihapus warung
            if ($barang->count() == 0) {
                KeranjangBelanja::where('id_produk', $keranjang_belanjaans->id_produk)->delete();
            } else {

                $sisa_stok       = $barang->first()->stok - $keranjang_belanjaans->jumlah_produk;

                $subtotal += $keranjang_belanjaans->harga_produk * $keranjang_belanjaans->jumlah_produk;
                    //card produk belanjaan
                $produk_belanjaan .= $this->cardProdukBelanjaanCollapseMobile($keranjang_belanjaans);
            }
        }

        $produk_belanjaan .= '
        </div>
        <table class="table table-striped">
        <tr>
        <hr>
        <td align="left" style="margin-top: 100px;"> Subtotal : </td>
        <td align="right"> Rp.'. number_format($subtotal, 0, ',', '.') .' </td>
        </tr>
        <tr>
        <td align="left">
        <a class="btn btn-info btn-sm" href="'. url("keranjang-belanja") .'"> Lihat Detail </a>
        </td>
        <td align="right">
        <a class="btn btn-info btn-sm" href="'. url("selesaikan-pemesanan") .'"> Pembayaran </a>
        </td>
        </tr>
        </table>    
        </div>
        ';
    }
    return $produk_belanjaan;
}

public function seo()
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

public function namaProduk($nama)
{

    $agent = new Agent();
    if ($agent->isMobile()) {

        if (strlen(strip_tags($nama)) <= 20) {

            $nama_produk = title_case(strip_tags($nama));
        } else {

            $nama_produk = title_case('' . strip_tags(substr($nama, 0, 21)) . '...');
        }

    } else {

        if (strlen(strip_tags($nama)) <= 33) {

            $nama_produk = title_case(strip_tags($nama));
        } else {

            $nama_produk = title_case('' . strip_tags(substr($nama, 0, 30)) . '...');
        }
    }

    return $nama_produk;
}

public function getIdWarung(){
        //Cek Address Aplikasi yg di Jalankan
    $address_current = url('/');

    $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();

    return $address_app->warung_id;
}

    public  function cekHargaProdukPromo($produk){
        $barang = Barang::select(['id','harga_jual',DB::raw('CURDATE() as tanggal_sekarang')])->where('id', $produk->id_produk);
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
                    $harga_produk    = $barang->first()->harga_jual;
                }
            }
        }else{
            $harga_produk    = $barang->first()->harga_jual;
        }
        return $harga_produk;
    }
}
