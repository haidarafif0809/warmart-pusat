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
use App\Hpp; 
use App\User; 
use Jenssegers\Agent\Agent;
use Auth;
use DB;
use App\Http\Controllers\DaftarProdukController;


class DetailProdukController extends Controller
{
    // 
	public function detail_produk($id){

		$this->seoDetailProduk();
		$barang = Barang::find($id);   
		$array_warung = DaftarProdukController::dataWarungTervalidasi();
		$daftar_produk_sama =$this->produkSekategori($barang,$array_warung); 
		$daftar_produk_warung = $this->produkSewarung($barang,$array_warung);  
		$cek_belanjaan = KeranjangBelanja::jumlahBelanja();
		$sisa_stok_keluar = DaftarProdukController::cekStokProduk($barang);
		return view('layouts.detail_produk', ['id' => $id, 'barang' => $barang,'cek_belanjaan'=>$cek_belanjaan,'daftar_produk_sama'=>$daftar_produk_sama,'daftar_produk_warung'=>$daftar_produk_warung,'cek_produk'=>$sisa_stok_keluar]); 

	}

	public function dataWarungTervalidasi(){
		$data_warung = User::select(['id_warung'])->where('id_warung', '!=' ,'NULL')->where('konfirmasi_admin', 1)->groupBy('id_warung')->get();
		$array_warung = array();
		foreach ($data_warung as $data_warungs) {
			array_push($array_warung, $data_warungs->id_warung);
		}

		return $array_warung;

	}

	public function produkSewarung($barang,$array_warung){
		$data_produk = Barang::where('foto', '!=', 'NULL' )->where('id_warung', $barang->id_warung )->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);
		$daftar_produk_warung = DaftarProdukController::daftarProduk($data_produk);         
		return $daftar_produk_warung;
	}

	public function produkSekategori($barang,$array_warung){
		$data_produk = Barang::where('foto', '!=', 'NULL' )->where('kategori_barang_id', $barang->kategori_barang_id )->whereIn('id_warung', $array_warung)->inRandomOrder()->paginate(4);
		$daftar_produk_sama = DaftarProdukController::daftarProduk($data_produk); 
		return $daftar_produk_sama;
	}
	public function seoDetailProduk(){
		SEOMeta::setTitle('War-Mart.id');
		SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		SEOMeta::setCanonical('https://war-mart.id');
		SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

		OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		OpenGraph::setTitle('War-Mart.id');
		OpenGraph::setUrl('https://war-mart.id');
		OpenGraph::addProperty('type', 'articles'); 

	}



}
