<?php

namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\DetailPenjualan;
use App\Penjualan;
use App\Hpp;
use Auth;
use Session;

class DetailPenjualanObserver
{
	public function creating(DetailPenjualan $DetailPenjualan){
		

		$jumlah_produk = $DetailPenjualan->jumlah;		

		//HANYA PRODUK DENGAN GOLONGAN BARANG = BARANG YG DI CREATING KE HPP
		if ($DetailPenjualan->produk->hitung_stok == 1) {

			$stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $DetailPenjualan->id_produk)->first();

			$sisa_stok_keluar = $stok->stok_produk - $jumlah_produk;

			if ($sisa_stok_keluar < 0) {

				$pesan_alert = 
				'<div class="container-fluid">
				<div class="alert-icon">
				<i class="material-icons">error</i>
				</div>
				<b>Gagal : Stok " '.$DetailPenjualan->produk->nama_barang.' " Tidak Mencukupi Untuk Dijual, Sisa Produk = " '.$stok->stok_produk.' "</b>
				</div>';

				Session::flash("flash_notification", [
					"level"     => "danger",
					"message"   => $pesan_alert
				]);

				return false;
			}
			else{
					//HPP PRODUK (MODEL)
				$hpp_produk = $DetailPenjualan->produk->hpp;
				$total_hpp = $hpp_produk * $jumlah_produk;
				$penjualan = Penjualan::find($DetailPenjualan->id_penjualan);

				Hpp::create([
					'no_faktur' 		  => $DetailPenjualan->id_penjualan,
					'id_produk' 		  => $DetailPenjualan->id_produk, 
					'jenis_transaksi'	  => 'penjualan', 
					'jumlah_keluar'		  => $jumlah_produk, 
					'harga_unit'		  => $hpp_produk, 
					'total_nilai'		  => $total_hpp,
					'warung_id'			  => $penjualan->id_warung, 
					'jenis_hpp'			  => 2
				]);


				return true;

				} // END IF STOK < 0

			} // END GOL. BARANG == BARANG		

    } // OBERVERS CREATING

    //HAPUS ITEM KELUAR
    public function deleting(DetailPenjualan $DetailPenjualan){

    	$hpp = Hpp::where('no_faktur', $DetailPenjualan->id_penjualan)->where('id_produk', $DetailPenjualan->id_produk)->where('jenis_hpp', 2);

    	if (!$hpp->delete()) {
    		return false;
    	}
    	else{
    		return true;
    	}

    } // OBERVERS DELETING
}