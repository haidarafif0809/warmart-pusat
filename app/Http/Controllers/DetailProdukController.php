<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;  
use SEOMeta;
use OpenGraph;
use Twitter;
use App\Barang; 
use App\KeranjangBelanja; 
use App\KategoriBarang; 
use App\Warung; 
use App\User; 
use Jenssegers\Agent\Agent;
use Auth;
use DB;

class DetailProdukController extends Controller
{
    // 
	public function listProduk($data_produk){
		$agent = new Agent();

		$daftar_produk = '';
		foreach ($data_produk as $produks) {

			$warung = Warung::select(['name'])->where('id', $produks->id_warung)->first();

			$daftar_produk .= '      
			<div class="col-md-3 col-sm-6 col-xs-6 list-produk">
			<div class="card cards card-pricing">
			<a href="'.url("/keranjang-belanja") .'">
			<div class="card-image">';
			if ($produks->foto != NULL) {
				$daftar_produk .= '<img src="../foto_produk/'.$produks->foto.'">';
			}
			else{
				$daftar_produk .= '<img src="../image/foto_default.png">';
			}
			$daftar_produk .= '
			</div>
			</a>
			<div class="card-content">
			<div class="footer">     
			<a href="'. url('detail-produk/'.$produks->id.''). '" class="card-title">
			'.strip_tags(substr($produks->nama, 0, 10)).'...
			</a><br>
			<b style="color:red; font-size:18px"> '.$produks->rupiah.' </b><br>
			<a class="description"><i class="material-icons">store</i>  '.strip_tags(substr($warung->name, 0, 10)).'... </a><br>';

			if ($agent->isMobile()) {
				$daftar_produk .= '<a href="'. url('/keranjang-belanja/tambah-produk-keranjang-belanja/'.$produks->id.''). '" class="btn btn-danger btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja" id="btnBeliSekarang"><b style="font-size:15px"> Beli </b><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
			}
			else{
				$daftar_produk .= '<a href="'. url('/keranjang-belanja/tambah-produk-keranjang-belanja/'.$produks->id.''). '" id="btnBeliSekarang" class="btn btn-danger btn-round" rel="tooltip" title="Tambah Ke Keranjang Belanja"><b style="font-size:18px"> Beli Sekarang </b><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
			}
			$daftar_produk .= '
			</div>
			</div>
			</div>
			</div>';
		}
		return $daftar_produk;
	}

	public function detail_produk($id){
		SEOMeta::setTitle('War-Mart.id');
		SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		SEOMeta::setCanonical('https://war-mart.id');
		SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

		OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		OpenGraph::setTitle('War-Mart.id');
		OpenGraph::setUrl('https://war-mart.id');
		OpenGraph::addProperty('type', 'articles'); 

		$barang = Barang::find($id);   
        //Pilih warung yang sudah dikonfirmasi admin
		$data_warung = User::select(['id_warung'])->where('id_warung', '!=' ,'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
		$array_warung = array();
		foreach ($data_warung as $data_warungs) {
			array_push($array_warung, $data_warungs->id_warung);
		}

		$data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->where('foto', '!=', 'NULL' )->where('kategori_barang_id', $barang->kategori_barang_id )->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);
		$daftar_produk_sama = $this->listProduk($data_produk); 

		$data_produk = Barang::select(['id','kode_barang', 'kode_barcode', 'nama_barang', 'harga_jual', 'foto', 'deskripsi_produk', 'kategori_barang_id', 'id_warung'])->where('foto', '!=', 'NULL' )->where('id_warung', $barang->id_warung )->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);
		$daftar_produk_warung = $this->listProduk($data_produk);             

		$keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
		$cek_belanjaan = $keranjang_belanjaan->count();   

		return view('layouts.detail_produk', ['id' => $id, 'barang' => $barang,'cek_belanjaan'=>$cek_belanjaan,'daftar_produk_sama'=>$daftar_produk_sama,'daftar_produk_warung'=>$daftar_produk_warung]); 

	}
}
