<?php 
namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\DetailPembelian;
use App\Hpp;
use Auth;
use Session;

class DetailPembelianObserver
{
	public function creating(DetailPembelian $DetailPembelian){

		$total_nilai = $DetailPembelian->jumlah_produk *  $DetailPembelian->harga_produk; 

		Hpp::create(['no_faktur' => $DetailPembelian->no_faktur, 'id_produk' => $DetailPembelian->id_produk, 'jenis_transaksi' => 'pembelian', 'jumlah_masuk' => $DetailPembelian->jumlah_produk, 'harga_unit' => $DetailPembelian->harga_produk, 'total_nilai' => $total_nilai, 'jenis_hpp' => '1','warung_id'=>Auth::user()->id_warung]);

		return true;
		
		
   	} // OBERVERS CREATING

   }