<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\KategoriBarang;
use App\Warung;
use App\Barang;
use App\User;
use App\KeranjangBelanja; 
use Auth;
use App\Hpp;
use DB;
use Intervention\Image\ImageManagerStatic as Image;

class DaftarProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
      $cek_belanjaan = $keranjang_belanjaan->count();  
        //Pilih warung yang sudah dikonfirmasi admin
      $data_warung = User::select(['id_warung'])->where('id_warung', '!=' ,'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
      $array_warung = array();
      foreach ($data_warung as $data_warungs) {
        array_push($array_warung, $data_warungs->id_warung);
      }

        //PILIH DATA PRODUK

      $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung','konfirmasi_admin'])
      ->inRandomOrder()
      ->whereIn('id_warung', $array_warung)->paginate(12);

        //PILIH DATA KATEGORI PRODUK
      $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
        //PERINTAH PAGINATION
      $produk_pagination = $data_produk->links();
        //FOTO HEADER
      $foto_latar_belakang = "background-image: url('image/background2.jpg');";
        //FOTO WARMART
      $logo_warmart = "assets/img/examples/warmart_logo.png";
        //TAMPIL DAFTAR PRODUK
      $daftar_produk = $this->daftarProduk($data_produk);
        //TAMPIL KATEGORI
      $kategori_produk = $this->produkKategori($kategori);
      $nama_kategori = "Temukan Apa Yang Anda Butuhkan";
        //TAMPILAN MOBILE
      $agent = new Agent();

      return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart]);
    }

    public function produkKategori($kategori){
        //Pilih warung yang sudah dikonfirmasi admin
      $data_warung = User::select(['id_warung'])->where('id_warung', '!=' ,'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
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
        <a href="'.route('daftar_produk.filter_kategori',$kategori->id).'" style="color:white"><i class="material-icons">'.$kategori->kategori_icon.'</i>'.$kategori->nama_kategori_barang.' - '.$jumlah_produk.'</a>
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
        <a href="'.route('daftar_produk.filter_kategori',$kategori->id).'"><i class="material-icons">'.$kategori->kategori_icon.'</i>'.$kategori->nama_kategori_barang.' - '.$jumlah_produk.'</a>
        </li>';
      }
      $kategori_produk .= '
      </ul>
      </li>';

      return $kategori_produk;
    }



    public function filter_kategori($id)
    {
      $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
      $cek_belanjaan = $keranjang_belanjaan->count(); 
    //Pilih warung yang sudah dikonfirmasi admin
      $data_warung = User::select(['id_warung'])->where('id_warung', '!=' ,'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
      $array_warung = array();
      foreach ($data_warung as $data_warungs) {
        array_push($array_warung, $data_warungs->id_warung);
      }

  //PILIH PRODUK
      $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung','konfirmasi_admin'])
      ->where('kategori_barang_id', $id)->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(12);

      
  //FOTO HEADER
      $foto_latar_belakang = "background-image: url('../image/background2.jpg');";
  //FOTO WARMART
      $logo_warmart = "../assets/img/examples/warmart_logo.png";
  //PAGINATION DAFTAR PRODUK
      $produk_pagination = $data_produk->links();
      //PILIH KATEGORI
      $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon'])->where('id',$id);
      $kategori_produk = $this->produkKategori($kategori);
      $data_kategori = $kategori->first();
      $nama_kategori = "KATEGORI : ".$data_kategori->nama_kategori_barang."";

  //TAMPILAN VIA HP
      $agent = new Agent();

      $daftar_produk = $this->daftarProduk($data_produk);      

      return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'id' => $id, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart]);
    }

    public function pencarian(Request $request){

      $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
      $cek_belanjaan = $keranjang_belanjaan->count(); 

  //PILIH PRODUK
      $data_produk = Barang::search($request->search)->paginate(12);
  //PILIH KATEGORI
      $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
  //FOTO HEADER
      $foto_latar_belakang = "background-image: url('".asset('/image/background2.jpg')."');";
  //FOTO WARMART
      $logo_warmart = "".asset('/assets/img/examples/warmart_logo.png')."";
  //PAGINATION DAFTAR PRODUK
      $produk_pagination = $data_produk->links();
  //MENAMPILKAN KATEGORI
      $kategori_produk = $this->produkKategori($kategori);
      $data_kategori = $kategori->first();
      $nama_kategori = 'Hasil Pencarian : "'.$request->search.'"';
  //TAMPILAN VIA HP
      $agent = new Agent();

      $daftar_produk = $this->daftarProduk($data_produk);

      return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori, 'agent' => $agent,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart]);
    }

    public function cekStokProduk($produks){
      $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->where('id_produk',$produks->id)->count(); 
      //jika belum ada belanjaan
      if ($keranjang_belanjaan == 0) {
       $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $produks->id)->where('warung_id', $produks->id_warung)->first();
       $cek_produk = $stok->stok_produk; 
     }
     elseif($produks->hitung_stok == 1){
      //jika produk tersebut jasa
      $cek_produk = 1;
    } 
    elseif($keranjang_belanjaan > 0){
      //jika sudah ada belanjaan
      $cek_produk = KeranjangBelanja::where('id_pelanggan',Auth::user()->id)->where('id_produk',$produks->id)->first(); 
      $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $cek_produk->id_produk)->where('warung_id', $produks->id_warung)->first();
      $cek_produk = $stok->stok_produk - $cek_produk->jumlah_produk; 

    }

    return $cek_produk;
  }


  public function tombolBeli($cek_produk,$produks){
    $agent = new Agent();
    if ($agent->isMobile()) {
                  //JIKA USER LOGIN BUKAN PELANGGAN MAKA TIDAK BISA PESAN PRODUK
      if(Auth::user()->tipe_user == 3){
       if ($cek_produk == 0) {
         $tombol_beli = '<a style="background-color:#01573e" class="btn btn-round" rel="tooltip" title="Stok Tidak Ada"><b style="font-size:18px"> Beli </b><i class="fa fa-chevron-right" aria-hidden="true" disabled="" ></i></a>';  
       }else{
         $tombol_beli = '<a href="'.url("/keranjang-belanja") .'" style="background-color:#01573e" class="btn btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja" id="btnBeliSekarang"><b style="font-size:18px"> Beli </b><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';            
       }
     }
     else{
      $tombol_beli = '<button type="button" style="background-color:#01573e" class="btn btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja" id="btnBeli"><b style="font-size:18px"> Beli </b><i class="fa fa-chevron-right" aria-hidden="true"></i></button>';
    }

  }
  else{
                  //JIKA USER LOGIN BUKAN PELANGGAN MAKA TIDAK BISA PESAN PRODUK
    if(Auth::user()->tipe_user == 3){
      if ($cek_produk == 0) {
        $tombol_beli = '<a style="background-color:#01573e" class="btn btn-round" rel="tooltip" title="Stok Tidak Ada" disabled="" ><b style="font-size:18px"> Beli Sekarang </b><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
      }else{
       $tombol_beli = '<a href="'. url('/keranjang-belanja/tambah-produk-keranjang-belanja/'.$produks->id.''). '" id="btnBeliSekarang" style="background-color:#01573e" class="btn btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja"><b style="font-size:18px"> Beli Sekarang </b><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
     }
   }
   else{
    $tombol_beli = '<button type="button" style="background-color:#01573e" class="btn btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja" id="btnBeli"><b style="font-size:18px"> Beli Sekarang</b><i class="fa fa-chevron-right" aria-hidden="true"></i></button>';
  }  

}
return $tombol_beli; 
}

public function tidakAdaProduk(){
  return   '<div class="col-md-3">
  <div class="card card-product card-plain no-shadow" data-colored-shadow="false">
  <div class="card-image">
  <img src="'.asset('image/foto_default.png').'">
  </div>
  <div class="card-content">
  <a href="#">
  <h4 >Tidak Ada Produk</h4>
  </a>
  </div>
  </div>
  </div>'; 
}

public function namaProduk($produks){
  if (strlen(strip_tags($produks->nama)) <= 33) {
    $nama_produk = ''.strip_tags(substr($produks->nama, 0, 60)).'...<br>';
  }
  else{
    $nama_produk = ''.strip_tags(substr($produks->nama, 0, 60)).'...';                
  }

  return $nama_produk;
}

public function namaWarung($warung){

  return '<a class="description"><i class="material-icons">store</i>  '.strip_tags(substr($warung->name, 0, 10)).'... </a>';

}

public function fotoProduk($produks){
 if ($produks->foto != NULL) {
  $this->resizeProduk($produks);
  $foto_produk = '<img src="'.asset('foto_produk/'.$produks->foto.'').'">';
}
else{
  $foto_produk = '<img src="'.asset('image/foto_default.png').'">';
}
return $foto_produk;
}

public function cardProduk($produks){
  $card_produk = "";
  if ($produks->konfirmasi_admin != 0) {
    $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();
    $cek_produk = $this->cekStokProduk($produks);
    $card_produk .= '      
    <div class="col-md-3 col-sm-6 col-xs-6 list-produk">
    <div class="card cards card-pricing">
    <a href="'.url("/keranjang-belanja") .'">
    <div class="card-image">';
    $card_produk .= $this->fotoProduk($produks);
    $card_produk .= '
    </div>
    </a>
    <div class="card-content">
    <div class="footer">  
    <a href="'.url("/keranjang-belanja") .'" class="card-title">';
    $card_produk .= $this->namaProduk($produks);
    $card_produk .= '</a><br>             
    <b style="color:red; font-size:18px"> '.$produks->rupiah.' </b><br>';
    $card_produk .= $this->namaWarung($warung).'<br>';
      //tombol beli
    $card_produk .= $this->tombolBeli($cek_produk,$produks);
    $card_produk .= '
    </div>
    </div>
    </div>
    </div>';
  }
  return $card_produk;
}

public function daftarProduk($data_produk){
 if ($data_produk->count() > 0) {
  $daftar_produk = "";
  foreach ($data_produk as $produks) {
    $daftar_produk .= $this->cardProduk($produks);
  }
  if ($daftar_produk == "") {
   $daftar_produk = $this->tidakAdaProduk();
 }
}
else {
  $daftar_produk = $this->tidakAdaProduk()."asdasda";
}
return $daftar_produk;
}

public function resizeProduk($produks){
  $foto_produk =  Image::make(asset('foto_produk/'.$produks->foto));
  $height_foto = $foto_produk->height();
  $width_foto = $foto_produk->width();
  if ($height_foto != 300 || $width_foto != 300 ) {
    $foto_produk->fit(300);
    $foto_produk->save(public_path('foto_produk/' .$filename));
  }
}


}