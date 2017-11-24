<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\KategoriBarang;
use App\Warung;
use App\Kelurahan;
use App\Barang;
use App\User;
use App\KeranjangBelanja; 
use Auth;
use App\Hpp;
use DB;
use App\Http\Controllers\DaftarProdukController;
use App\Http\Controllers\HalamanWarungController;
use Intervention\Image\ImageManagerStatic as Image;

class HalamanWarungController extends Controller
{
    //

        public function index($id)
    {
      $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
      $cek_belanjaan = $keranjang_belanjaan->count();  
        //Pilih warung yang sudah dikonfirmasi admin
      $data_warung = User::select(['id_warung'])->where('id_warung',$id)->where('konfirmasi_admin', 1)->get();
      $array_warung = array();
      foreach ($data_warung as $data_warungs) {
        array_push($array_warung, $data_warungs->id_warung);
      }
        //PILIH DATA PRODUK

      $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung','konfirmasi_admin','satuan_id'])
      ->inRandomOrder()
      ->whereIn('id_warung', $array_warung)->paginate(12);

        //PILIH DATA KATEGORI PRODUK
      $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
        //PERINTAH PAGINATION
      $produk_pagination = $data_produk->links();
        //FOTO HEADER
      $foto_latar_belakang = "background-image: url('".asset('/image/background2.jpg')."');";
        //FOTO WARMART
      $logo_warmart = asset("assets/img/examples/warmart_logo.png");

     //TAMPIL LIST WARUNG
      $list_warung = HalamanWarungController::cardWarung($id);

          //TAMPIL NAMA WARUNG
    	 $data_warung = Warung::select(['name','id'])->where('id', $id)->first();
     	$nama_warung = 'Produk';

        //TAMPIL DAFTAR PRODUK
      $daftar_produk = DaftarProdukController::daftarProduk($data_produk);
        //TAMPIL KATEGORI
      $kategori_produk = HalamanWarungController::produkKategori($kategori,$id);
      $nama_kategori = "Warung : ".$data_warung->name;

        //TAMPILAN MOBILE
      $agent = new Agent();

      return view('layouts.halaman_warung', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart,'list_warung'=>$list_warung,'id'=>$id,'nama_warung'=>$nama_warung]);
    }


        public static function filter_kategori($id,$id_warung)
    {
      $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
      $cek_belanjaan = $keranjang_belanjaan->count(); 
    //Pilih warung yang sudah dikonfirmasi admin
      $data_warung = User::select(['id_warung'])->where('id_warung',$id_warung)->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
      $array_warung = array();
      foreach ($data_warung as $data_warungs) {
        array_push($array_warung, $data_warungs->id_warung);
      }

  		//PILIH PRODUK
      $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung','konfirmasi_admin','satuan_id'])
      ->where('kategori_barang_id', $id)->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(12);

      
      //TAMPIL LIST WARUNG
      $list_warung = HalamanWarungController::cardWarung($id_warung);

         //TAMPIL NAMA WARUNG
     	$data_warung = Warung::select(['name','id'])->where('id', $id_warung)->first();
     	$nama_warung = 'Produk';

  		//FOTO HEADER
      $foto_latar_belakang = "background-image: url('".asset('image/background2.jpg')."');";
  		//FOTO WARMART
      $logo_warmart = asset("assets/img/examples/warmart_logo.png");
  		//PAGINATION DAFTAR PRODUK
      $produk_pagination = $data_produk->links();
      //PILIH KATEGORI
      $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon'])->where('id',$id);
      $kategori_produk = HalamanWarungController::produkKategori($kategori,$id_warung);
      $data_kategori = $kategori->first();
      $nama_kategori = "KATEGORI : ".$data_kategori->nama_kategori_barang."";

  		//TAMPILAN VIA HP
      $agent = new Agent();

      $daftar_produk = DaftarProdukController::daftarProduk($data_produk);      

      return view('layouts.halaman_warung', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'id' => $id, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart,'list_warung'=>$list_warung,'id'=>$id_warung,'nama_warung'=>$nama_warung]);
    }


       public static function produkKategori($kategori,$id){
        //Pilih warung yang sudah dikonfirmasi admin
      $data_warung = User::select(['id_warung'])->where('id_warung', $id)->where('konfirmasi_admin', 1)->get();
      $array_warung = array();
      foreach ($data_warung as $data_warungs) {
        array_push($array_warung, $data_warungs->id_warung);
      }
        //MEANMPILKAN KATEGORI PRODUK
      $kategori_produk = '';
      foreach ($kategori->paginate(4) as $kategori) {
        $jumlah_produk = Barang::where('kategori_barang_id', $kategori->id)->whereIn('id_warung', $array_warung)->count();
        $kategori_produk .= '
        <li>
        <a href="'.url('halaman-warung/filter/'.$kategori->id.'/'.$id).'" style="color:white"><i class="material-icons">'.$kategori->kategori_icon.'</i>'.$kategori->nama_kategori_barang.' - '.$jumlah_produk.'</a>
        </li>';
      }
      $kategori_produk .= '
      <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white"><i class="material-icons">list</i> Lain - Lain <b class="caret"></b></a>
      <ul class="dropdown-menu dropdown-with-icons">';
      foreach ($kategori->get() as $kategori) {
        $jumlah_produk = Barang::where('kategori_barang_id', $kategori->id)->whereIn('id_warung', $array_warung)->count();
        $kategori_produk .= '
        <li>
        <a href="'.url('halaman-warung/filter/'.$kategori->id.'/'.$id).'"><i class="material-icons">'.$kategori->kategori_icon.'</i>'.$kategori->nama_kategori_barang.' - '.$jumlah_produk.'</a>
        </li>';
      }
      $kategori_produk .= '
      </ul>
      </li>';

      return $kategori_produk;
    }


        public static function pencarian(Request $request){

     $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
     $cek_belanjaan = $keranjang_belanjaan->count(); 

  	//PILIH PRODUK
     $data_produk = Barang::search($request->search)->where('id_warung',$request->id_warung)->paginate(12);

      //TAMPIL LIST WARUNG
      $list_warung = HalamanWarungController::cardWarung($request->id_warung);

  	//PILIH KATEGORI
     $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
  //FOTO HEADER
     $foto_latar_belakang = "background-image: url('".asset('/image/background2.jpg')."');";
  //FOTO WARMART
     $logo_warmart = asset('/assets/img/examples/warmart_logo.png');
  //PAGINATION DAFTAR PRODUK
     $produk_pagination = $data_produk->links();
  //MENAMPILKAN KATEGORI
     $kategori_produk = HalamanWarungController::produkKategori($kategori,$request->id_warung);
     $data_kategori = $kategori->first();
     $nama_kategori = 'Hasil Pencarian : "'.$request->search.'" <a href="'.url("/halaman-warung/".$request->id_warung) .'"> <i class="material-icons" style="color:red" >highlight_off</i> </a>';

    //TAMPIL NAMA WARUNG
     $data_warung = Warung::select(['name','id'])->where('id', $request->id_warung)->first();
     $nama_warung = 'Produk';

  //TAMPILAN VIA HP
     $agent = new Agent();

     $daftar_produk = DaftarProdukController::daftarProduk($data_produk);

     return view('layouts.halaman_warung', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart,'list_warung'=>$list_warung,'id'=>$request->id_warung,'nama_warung'=>$nama_warung]);
   }

    public static function cardWarung($id_warungs){
    $warung = Warung::select(['name','id','wilayah','alamat','no_telpon'])->where('id', $id_warungs)->first();
    $jumlah_produk_warung = Barang::where('id_warung',$id_warungs)->count();

    $card_warung = '';
    $card_warung .= '<div class="card card-raised card-form-horizontal">
            <div class="card-content">
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-6">
                    <i class="material-icons">store</i> <b>Warung</b>
                    <p>';$card_warung .= DaftarProdukController::warungNama($warung);
                    $card_warung .= '</p>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">
                    <i class="material-icons">place</i>  <b>Lokasi</b>
                    <p>';$card_warung .= DaftarProdukController::alamatWarung($warung);
                    $card_warung .= '</p>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-6">
                    <i class="material-icons">call</i> <b>Telpon</b>
                    <p>';$card_warung .= HalamanWarungController::telponWarung($warung);
                    $card_warung .= '</p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                    <i class="material-icons">offline_pin</i> <b>Produk</b>
                    <p>';$card_warung .= HalamanWarungController::produkWarung($jumlah_produk_warung);
                    $card_warung .= '</p>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                    <h6>
                    <a href="'.url('daftar-produk').'" style="background-color:#01573e; position: relative" class="btn btn-block tombolBeli" id="btnKunjungi"> Kembali </a>
                    </h6>
                    </div>
                </div>
            </div>
        </div>';

	return $card_warung;
    }

    public static function telponWarung($warungs){
  	if (strlen(strip_tags($warungs->no_telpon)) <= 15) {
    $telpon_warung = ''.strip_tags($warungs->no_telpon);
  	}
  	else{
    $agent = new Agent();
    if ($agent->isMobile()) {
      $telpon_warung = ''.strip_tags(substr($warungs->no_telpon, 0, 35)).'...'; 
    }
    else {
      $telpon_warung = ''.strip_tags(substr($warungs->no_telpon, 0, 60)).'...'; 
    }
  }
  return $telpon_warung;
}

	public static function produkWarung($jumlah_produk_warung){
  	if (strlen(strip_tags($jumlah_produk_warung)) <= 15) {
    $produk_warung = ''.strip_tags($jumlah_produk_warung).' Item';
  	}
  	else{
    $agent = new Agent();
    if ($agent->isMobile()) {
      $produk_warung = ''.strip_tags(substr($jumlah_produk_warung, 0, 35)).'... Item'; 
    }
    else {
      $produk_warung = ''.strip_tags(substr($jumlah_produk_warung, 0, 60)).'... Item'; 
    }
  }
  return $produk_warung;
}

}
