<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;  
use SEOMeta;
use OpenGraph;
use Twitter;
use App\KeranjangBelanja; 
use App\Barang;
use App\Hpp;  
use Jenssegers\Agent\Agent;
use Auth;
use DB;

class KeranjangBelanjaController extends Controller
{
    // 


	public function daftar_belanja()
	{
		SEOMeta::setTitle('War-Mart.id');
		SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		SEOMeta::setCanonical('https://war-mart.id');
		SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

		OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		OpenGraph::setTitle('War-Mart.id');
		OpenGraph::setUrl('https://war-mart.id');
		OpenGraph::addProperty('type', 'articles'); 

		$agent = new Agent();

		$keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
		$cek_belanjaan = $keranjang_belanjaan->count();  

		$jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->first();  
		//FOTO WARMART
		$logo_warmart = "".asset('/assets/img/examples/warmart_logo.png')."";
      	//MEANMPILKAN PRODUK BELANJAAN 
		$produk_belanjaan = '';
		$subtotal = 0;
		foreach ($keranjang_belanjaan as $keranjang_belanjaans) {  
			$barang = Barang::where('id',$keranjang_belanjaans->id_produk)->first();
			
			$stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $keranjang_belanjaans->id_produk)
			->where('warung_id', $barang->id_warung)->first();

			$sisa_stok_keluar = $stok->stok_produk - $keranjang_belanjaans->jumlah_produk;

			$harga_produk = $keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk;

			$produk_belanjaan .= '
			<tr class="card" style="margin-bottom: 3px;margin-top: 3px;width: 725px;">
			<td>
			<div class="img-container"> ';
			if ($keranjang_belanjaans->produk->foto != NULL) {
				$produk_belanjaan .= '<img src="foto_produk/'.$keranjang_belanjaans->produk->foto.'">';
			}
			else{
				$produk_belanjaan .= '<img src="image/foto_default.png">';
			}
			$produk_belanjaan .= '
			</div>
			</td>
			<td class="td-name">
			<a href="'. url('detail-produk/'.$keranjang_belanjaans->id_produk.''). '">'. $keranjang_belanjaans->produk->nama_barang .'</a>
			<br />
			<small><i class="material-icons">store</i>  '. $keranjang_belanjaans->produk->warung->name .' </small>
			</td>  
			<td class="td-number">
			<b>Rp. '. number_format($harga_produk,0,',','.') .'</b> 
			</td> 
			<td class="td-number">
			<div class="btn-group">';

			if ($keranjang_belanjaans->jumlah_produk == 1) {
				$produk_belanjaan .= '
				<a class="btn btn-round btn-info btn-xs"   style="background-color: #01573e" disabled="true"> <i class="material-icons">remove</i> </a>'; 
			}
			else {
				$produk_belanjaan .= ' 
				<a href=" '. url('/keranjang-belanja/kurang-jumlah-produk-keranjang-belanja/'.$keranjang_belanjaans->id_keranjang_belanja.''). '" class="btn btn-round btn-info btn-xs"   style="background-color: #01573e"> <i class="material-icons">remove</i></a>';
			}

			$produk_belanjaan .= ' <a class="btn btn-round btn-info btn-xs"   style="background-color: #01573e">'. $keranjang_belanjaans->jumlah_produk .' </a>';


			if ($sisa_stok_keluar <= 0) {
				$produk_belanjaan .= '
				<a class="btn btn-round btn-info btn-xs"   style="background-color: #01573e" disabled="true"> <i class="material-icons">add</i> </a>'; 
			}
			else {
				$produk_belanjaan .= '
				<a href=" '. url('/keranjang-belanja/tambah-jumlah-produk-keranjang-belanja/'.$keranjang_belanjaans->id_keranjang_belanja.''). '" class="btn btn-round btn-info btn-xs"   style="background-color: #01573e"> <i class="material-icons">add</i> </a>';
			}
			$produk_belanjaan .= '
			</div>
			</td>   
			<td class="td-actions">
			<a id="btnHapusProduk" href=" '. url('/keranjang-belanja/hapus-produk-keranjang-belanja/'.$keranjang_belanjaans->id_keranjang_belanja.''). '" type="button" rel="tooltip" data-placement="left" title="Remove item" class="btn btn-simple">
			<i class="material-icons">close</i>
			</a>
			</td>
			</tr>  
			';  
			$subtotal = $subtotal += $harga_produk;
		}


		return view('layouts.keranjang_belanja',['keranjang_belanjaan'=>$keranjang_belanjaan,'cek_belanjaan'=>$cek_belanjaan,'agent'=>$agent,'produk_belanjaan'=>$produk_belanjaan,'jumlah_produk'=>$jumlah_produk,'logo_warmart'=>$logo_warmart,'subtotal'=>number_format($subtotal,0,',','.')]);

	}

	public function hapus_produk_keranjang_belanjaan($id)
	{

        // jika gagal hapus
		if (!KeranjangBelanja::destroy($id)) {
			return redirect()->back();
		}
		else{ 
			return redirect()->back();
		}
	}

	public function tambah_jumlah_produk_keranjang_belanjaan($id)
	{
		$produk = KeranjangBelanja::find($id); 
		$produk->jumlah_produk += 1;
		$produk->save();

		return redirect()->back();
	}

	public function kurang_jumlah_produk_keranjang_belanjaan($id)
	{
		$produk = KeranjangBelanja::find($id); 
		$produk->jumlah_produk -= 1;
		$produk->save();

		return redirect()->back();

	}

	public function tambah_produk_keranjang_belanjaan($id)
	{

		$pelanggan =  Auth::user()->id ; 
		$datakeranjang_belanjaan = KeranjangBelanja::where('id_pelanggan',$pelanggan)->orWhere('id_produk',$id);
		$keranjang_belanjaan = $datakeranjang_belanjaan->first();

		if ($datakeranjang_belanjaan->count() > 0 AND $keranjang_belanjaan->id_pelanggan == $pelanggan AND $keranjang_belanjaan->id_produk == $id) {
			$barang = Barang::find($id);   

			$keranjang_belanjaan->jumlah_produk += 1;
			$keranjang_belanjaan->save(); 

		}else{

			$produk = KeranjangBelanja::create(); 
			$produk->id_produk = $id;
			$produk->id_pelanggan =  $pelanggan;
			$produk->jumlah_produk += 1;
			$produk->save(); 		
		}
		return redirect()->back();

	}
}
