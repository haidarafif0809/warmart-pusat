<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KategoriBarang;
use App\Warung;
use App\Barang;

class DaftarProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->paginate(12);
      $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
      $produk_pagination = $data_produk->links();
      $foto_latar_belakang = "background-image: url('image/background2.jpg');";
      $daftar_produk = $this->listProduk($data_produk);
      $kategori_produk = $this->produkKategori($kategori);
      $nama_kategori = "Temukan Apa Yang Anda Butuhkan";

      return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori]);
    }

    public function produkKategori($kategori){
      $kategori_produk = '';
      foreach ($kategori->get() as $kategori) {
        $jumlah_produk = Barang::where('kategori_barang_id', $kategori->id)->count();
        $kategori_produk .= '
        <li>
          <a href="'.route('daftar_produk.filter_kategori',$kategori->id).'"><i class="material-icons">'.$kategori->kategori_icon.'</i>'.$kategori->nama_kategori_barang.' - '.$jumlah_produk.'</a>
        </li>';
      }
      return $kategori_produk;
    }

    public function listProduk($data_produk){
     $daftar_produk = '';
     foreach ($data_produk as $produks) {

      $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();

      $daftar_produk .= '      
      <div class="col-md-3">
        <div class="card cards card-pricing">
          <div class="card card-product card-plain no-shadow" data-colored-shadow="false">
            <div class="card-image">';

              if ($produks->foto != NULL) {
               $daftar_produk .= '<img src="./foto_produk/'.$produks->foto.'">';
             }
             else{
              $daftar_produk .= '<img src="./image/foto_default.png">';
            }

            $daftar_produk .= '</div>
            <div class="card-content">
              <a href="#">
                <h5 class="card-title">'.$produks->nama.'</h5>
              </a>';

              if ($produks->deskripsi_produk != "") {
                $daftar_produk .= '<p class="description">'.strip_tags(substr($produks->deskripsi_produk, 0, 75)).'..</p>';
              }
              else{
                $daftar_produk .= '<p class="description">Tidak Ada Deskripsi'.' <br> '.'Untuk Produk Ini.</p>';
              }
              $daftar_produk .= '<div class="footer">

              <h5 style="color:red;"><b> '.$produks->rupiah.' </b></h5>
              <a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a><hr>
              <a href="'.url("/keranjang-belanja") .'" class="btn btn-danger btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja"><b> Beli Sekarang </b><i class="material-icons">keyboard_arrow_right</i></a>
            </div>

          </div>
        </div>
      </div>
    </div>';
  }
  return $daftar_produk;
}

public function filter_kategori($id)
{
  $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])
  ->where('kategori_barang_id', $id)->paginate(12);
  $kategori = KategoriBarang::select(['id','nama_kategori_barang','kategori_icon']);
  $foto_latar_belakang = "background-image: url('../image/background2.jpg');";
  $produk_pagination = $data_produk->links();
  $kategori_produk = $this->produkKategori($kategori);
  $data_kategori = $kategori->first();
  $nama_kategori = "KATEGORI : ".$data_kategori->nama_kategori_barang."";


  if ($data_produk->count() > 0) {

    $daftar_produk = "";
    foreach ($data_produk as $produks) {
      $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();
      $daftar_produk .= '
      <div class="col-md-3">
        <div class="card cards card-pricing">
          <div class="card card-product card-plain no-shadow" data-colored-shadow="false">
            <div class="card-image">';

              if ($produks->foto != NULL) {
               $daftar_produk .= '<img src="../foto_produk/'.$produks->foto.'">';
             }
             else{
              $daftar_produk .= '<img src="../image/foto_default.png">';
            }

            $daftar_produk .= '</div>
            <div class="card-content">
              <a href="#">
                <h5 class="card-title">'.$produks->nama.'</h5>
              </a>';
              if ($produks->deskripsi_produk != "") {
                $daftar_produk .= '<p class="description">'.strip_tags(substr($produks->deskripsi_produk, 0, 75)).'..</p>';
              }
              else{
                $daftar_produk .= '<p class="description">Tidak Ada Deskripsi'.' <br> '.'Untuk Produk Ini.</p>';
              }
              $daftar_produk .= '<div class="footer">
              <h5 style="color:red"><b> '.$produks->rupiah.' </b></h5>
              <a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a><hr>
              <a href="#" class="btn btn-danger btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja"><b> Beli Sekarang </b><i class="material-icons">keyboard_arrow_right</i></a>

            </div>
          </div>
        </div>
      </div>
    </div>';
  }
}
else{
  $daftar_produk = 
  '<div class="col-md-3">
  <div class="card card-product card-plain no-shadow" data-colored-shadow="false">
    <div class="card-image">
      <img src="../image/foto_default.png">
    </div>
    <div class="card-content">
      <a href="#">
        <h4 class="card-title">Tidak Ada Produk</h4>
      </a>
    </div>
  </div>
</div>';
}        

return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'id' => $id, 'foto_latar_belakang' => $foto_latar_belakang, 'nama_kategori' => $nama_kategori]);
}

}