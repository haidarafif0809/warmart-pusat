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
      $kategori_produk = KategoriBarang::select(['id','nama_kategori_barang'])->get();
      $produk_pagination = $data_produk->links();
      $daftar_produk = $this->listProduk($data_produk);
      return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination ]);
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

                    <h5 style="color:red"> '.$produks->rupiah.' </h5>
                    <a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a><hr>
                    <a href="#" class="btn btn-danger btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja">Beli Sekarang <i class="material-icons">keyboard_arrow_right</i></a>
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
  $kategori_produk = KategoriBarang::select(['id','nama_kategori_barang'])->get();

  $produk_pagination = $data_produk->links();

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
                    </a>
                    <p class="description">
                        Impeccably tailored in Italy from lightweight navy wool.
                    </p>
                    <div class="footer">
                        <h5 style="color:red"> '.$produks->rupiah.' </h5>
                        <a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a><hr>
                        <a href="#" class="btn btn-danger btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja">Beli Sekarang <i class="material-icons">keyboard_arrow_right</i></a>

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

return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination, 'id' => $id]);
}

}