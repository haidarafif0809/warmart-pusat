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
    $cek_belanjaan = KeranjangBelanja::jumlahBelanja(); 
      //Pilih warung yang sudah dikonfirmasi admin
    $array_warung = DaftarProdukController::dataWarungTervalidasi();
    $data_produk = Barang::inRandomOrder()
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
    $warungs_data = Warung::select(['name','id'])->where('id', $id)->first();
    $nama_warung = 'Produk';

        //TAMPIL DAFTAR PRODUK
    if ($data_produk->count() > 0) {
      $daftar_produk = DaftarProdukController::daftarProduk($data_produk);
    }
    else{
      $daftar_produk = HalamanWarungController::tidakAdaProdukWarung();
    }

        //TAMPIL KATEGORI
    $kategori_produk = HalamanWarungController::produkKategori($kategori,$id);
    $nama_kategori = "Warung : ".$warungs_data->name;


    return view('layouts.halaman_warung', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart,'list_warung'=>$list_warung,'id'=>$id,'nama_warung'=>$nama_warung]);
  }


  public static function filter_kategori($id,$id_warung)
  {
    $cek_belanjaan = KeranjangBelanja::jumlahBelanja(); 
    $array_warung = DaftarProdukController::dataWarungTervalidasi();
  		//PILIH PRODUK
    $data_produk = Barang::where('kategori_barang_id', $id)->whereIn('id_warung',  $array_warung)->inRandomOrder()->paginate(12);

      //TAMPIL LIST WARUNG
    $list_warung = HalamanWarungController::cardWarung($id_warung);
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

        //TAMPIL DAFTAR PRODUK
    if ($data_produk->count() > 0) {
      $daftar_produk = DaftarProdukController::daftarProduk($data_produk);
    }
    else{
      $daftar_produk = HalamanWarungController::tidakAdaProdukKategori();
    }


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

    $cek_belanjaan = KeranjangBelanja::jumlahBelanja(); 
  	//PILIH PRODUK
    $data_produk = Barang::search($request->search)->paginate(12);

      //TAMPIL LIST WARUNG
    $list_warung = HalamanWarungController::cardWarung($request->id_warung);

  	//PILIH KATEGORI
    $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
    $data_kategori = $kategori->first();
  //FOTO HEADER
    $foto_latar_belakang = "background-image: url('".asset('/image/background2.jpg')."');";
  //FOTO WARMART
    $logo_warmart = asset('/assets/img/examples/warmart_logo.png');
  //PAGINATION DAFTAR PRODUK
    $produk_pagination = $data_produk->links();
  //MENAMPILKAN KATEGORI
    $kategori_produk = HalamanWarungController::produkKategori($kategori,$request->id_warung);

    $nama_kategori = 'Hasil Pencarian : "'.$request->search.'" <a href="'.url("/halaman-warung/".$request->id_warung) .'"> <i class="material-icons" style="color:red" >highlight_off</i> </a>';

    //TAMPIL NAMA WARUNG
    $data_warung = Warung::select(['name','id'])->where('id', $request->id_warung)->first();

    $daftar_produk = DaftarProdukController::daftarProduk($data_produk,$request->id_warung);

    return view('layouts.halaman_warung', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori,'cek_belanjaan'=>$cek_belanjaan,'logo_warmart'=>$logo_warmart,'list_warung'=>$list_warung,'id'=>$request->id_warung]);
  }

  public static function cardWarung($id_warungs){
    $warung = Warung::select(['name','id','wilayah','alamat','no_telpon'])->where('id', $id_warungs)->first();
    $jumlah_produk_warung = Barang::where('id_warung',$id_warungs)->count();
    $agent = new Agent();
    if ($agent->isMobile()) {
     $card_warung = '';
     $card_warung .= '
     <div class="card card-raised card-form-horizontal"> 
     <div class="card-content"> 
     <div class="col-md-2 col-sm-6 col-xs-6 nav-pills-icons"> 
     <p class="text-center"><i  class="material-icons">store</i>  </p>
     <p class="text-center">';$card_warung .= DaftarProdukController::warungNama($warung); 
     $card_warung .= '</p> 
     </div> 
     <div class="col-md-2 col-sm-6 col-xs-6"> 
     <p class="text-center"><i  class="material-icons">place</i> </p>  
     <p class="text-center">';$card_warung .= DaftarProdukController::alamatWarung($warung); 
     $card_warung .= '</p> 
     </div> 
     <div class="col-md-2 col-sm-6 col-xs-6"> 
     <p class="text-center"> <i  class="material-icons">call</i>  </p>
     <p class="text-center">';$card_warung .= HalamanWarungController::telponWarung($warung); 
     $card_warung .= '</p> 
     </div>  
     <div class="col-md-2 col-sm-6 col-xs-6"> 
     <p class="text-center"><i  class="material-icons">offline_pin</i> </p>
     <p class="text-center">';$card_warung .= HalamanWarungController::produkWarung($jumlah_produk_warung); 
     $card_warung .= '</p> 
     </div>  
     </div> 
     </div>'; 
   }
   else{
    $card_warung = '';
    $card_warung .= '
    <div class="profile-tabs">
    <div class="nav-align-center">
    <ul class="nav nav-pills nav-pills-icons" role="tablist">
    <li >
    <a role="tab" >
    <i class="material-icons">store</i>';$card_warung .= DaftarProdukController::warungNama($warung);
    $card_warung .= '
    </a>
    </li>
    <li>
    <a role="tab" >
    <i class="material-icons">place</i>';$card_warung .= DaftarProdukController::alamatWarung($warung);
    $card_warung .= '
    </a>
    </li> 
    <li>
    <a role="tab" >
    <i class="material-icons">call</i>';$card_warung .= HalamanWarungController::telponWarung($warung);
    $card_warung .= '
    </a>
    </li>
    <li>
    <a role="tab" >
    <i class="material-icons">offline_pin</i>';$card_warung .= HalamanWarungController::produkWarung($jumlah_produk_warung);
    $card_warung .= '
    </a>
    </li>          
    </ul>
    </div>
    </div>';
  }

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
  $produk_warung = ''.strip_tags($jumlah_produk_warung).' Produk';
}
else{
  $agent = new Agent();
  if ($agent->isMobile()) {
    $produk_warung = ''.strip_tags(substr($jumlah_produk_warung, 0, 35)).'... Produk'; 
  }
  else {
    $produk_warung = ''.strip_tags(substr($jumlah_produk_warung, 0, 60)).'... Produk'; 
  }
}
return $produk_warung;
}

public static function tidakAdaProdukWarung(){
  $produk_kosong ="";
  $produk_kosong .='
  <div class="col-md-12 col-s,-12 col-xs-12">
  <div class="card" data-colored-shadow="false" style="background-color:#f7f7f7">
  <div class="card-content">';
  $agent = new Agent();
  if ($agent->isMobile()) {
    $produk_kosong .='<h6 class="text-center" style="margin:0px">Maaf Warung yang anda pilih tidak ada produk yang tersedia.</h6>
    <p class="text-center">Silakan pilih warung lain</p>';
  }
  else{
    $produk_kosong .='<h3 class="title text-center" style="margin:0px">Maaf Warung yang anda pilih tidak ada produk yang tersedia.</h3>
    <h5 class="text-center" style="margin:0px">Silakan pilih warung lain</h5>';
  }        
  $produk_kosong .='</div>
  </div>
  </div>'; 

  return $produk_kosong;
}

public static function tidakAdaProdukKategori(){
  $produk_kosong ="";
  $produk_kosong .='
  <div class="col-md-12 col-s,-12 col-xs-12">
  <div class="card" data-colored-shadow="false" style="background-color:#f7f7f7">
  <div class="card-content">';
  $agent = new Agent();
  if ($agent->isMobile()) {
    $produk_kosong .='<h6 class="text-center" style="margin:0px">Maaf Kategori yang anda pilih tidak ada produk yang tersedia.</h6>
    <p class="text-center">Silakan pilih kategori lain</p>';
  }
  else{
    $produk_kosong .='<h3 class="title text-center" style="margin:0px">Maaf Kategori yang anda pilih tidak ada produk yang tersedia.</h3>
    <h5 class="text-center" style="margin:0px">Silakan pilih kategori lain</h5>';
  }        
  $produk_kosong .='</div>
  </div>
  </div>'; 

  return $produk_kosong;
}

}
