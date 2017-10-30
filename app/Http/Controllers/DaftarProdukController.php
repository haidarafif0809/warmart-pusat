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
        $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->paginate(4);
        $kategori_produk = KategoriBarang::select(['id','nama_kategori_barang'])->get();

        $daftar_produk = "";
        $produk_pagination = $data_produk->links();
        foreach ($data_produk as $produks) {

            $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();

            $daftar_produk .= '<div class="col-md-3">
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
                   <h5 class="card-title">'.$produks->nama_barang.'</h5>
               </a>
               <p class="description">
                Impeccably tailored in Italy from lightweight navy wool.
            </p>
            <div class="footer">
               <div class="price-container">
                <span class="price">'.$produks->rupiah.'</span>
            </div>

            <button class="btn btn-rose btn-simple btn-fab btn-fab-mini btn-round pull-right btn-wishlist" data-id="'.$produks->id.'" rel="tooltip" title="Tambah Ke Wishlist" data-placement="left" data-toogle="0">
               <i class="material-icons" id="icon-'.$produks->id.'" data-toogle="0"><span id="icon_wishlist-'.$produks->id.'">favorite_border</span></i>
           </button>

           <button class="btn btn-rose btn-simple btn-fab btn-fab-mini btn-round pull-right" rel="tooltip" title="Tambah Ke Keranjang Belanja" data-placement="left">
               <i class="material-icons">add_shopping_cart</i>
           </button>

       </div>

   </div>

   <a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a>
</div>
</div>';
}

return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination ]);
}

public function filter_kategori($id)
{
    $data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])
    ->where('kategori_barang_id', $id)->paginate(4);
    $kategori_produk = KategoriBarang::select(['id','nama_kategori_barang'])->get();

    $produk_pagination = $data_produk->links();

    if ($data_produk->count() > 0) {

        $daftar_produk = "";
        foreach ($data_produk as $produks) {
            $warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();
            $daftar_produk .= '<div class="col-md-3">
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
                   <h5 class="card-title">'.$produks->nama_barang.'</h5>
               </a>
               <p class="description">
                Impeccably tailored in Italy from lightweight navy wool.
            </p>
            <div class="footer">
               <div class="price-container">
                <span class="price"> Rp. '.number_format($produks->harga_jual,0,',','.').'</span>
            </div>

            <button class="btn btn-rose btn-simple btn-fab btn-fab-mini btn-round pull-right btn-wishlist" data-id="'.$produks->id.'" rel="tooltip" title="Tambah Ke Wishlist" data-placement="left" data-toogle="0">
               <i class="material-icons" id="icon-'.$produks->id.'" data-toogle="0"><span id="icon_wishlist-'.$produks->id.'">favorite_border</span></i>
           </button>

           <button class="btn btn-rose btn-simple btn-fab btn-fab-mini btn-round pull-right" rel="tooltip" title="Tambah Ke Keranjang Belanja" data-placement="left">
               <i class="material-icons">add_shopping_cart</i>
           </button>

       </div>
   </div>
   <a class="description"><i class="material-icons">store</i>  '.$warung->name.' </a>
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

return view('layouts.daftar_produk', ['kategori_produk' => $kategori_produk, 'daftar_produk' => $daftar_produk, 'produk_pagination' => $produk_pagination]);
}

}