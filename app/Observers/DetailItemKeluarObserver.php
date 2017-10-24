<?php

namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\DetailItemKeluar;
use App\Hpp;
use Auth;
use Session;

class DetailItemKeluarObserver
{
	public function creating(DetailItemKeluar $DetailItemKeluar){
		

		$jumlah_produk_keluar = $DetailItemKeluar->jumlah_produk;		

		//HANYA PRODUK DENGAN GOLONGAN BARANG = BARANG YG DI CREATING KE HPP
			if ($DetailItemKeluar->produk->hitung_stok == 1) {

				$stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $DetailItemKeluar->id_produk)
				->where('warung_id', $DetailItemKeluar->warung_id)->first();

				$sisa_stok_keluar = $stok->stok_produk - $jumlah_produk_keluar;

				if ($sisa_stok_keluar < 0) {

					$pesan_alert = 
						'<div class="container-fluid">
							<div class="alert-icon">
			                    <i class="material-icons">error</i>
			                </div>
			                	<b>Gagal : Stok " '.$DetailItemKeluar->produk->nama_barang.' " Tidak Mencukupi Untuk Dikeluarkan, Sisa Produk = " '.$stok->stok_produk.' "</b>
		                </div>';

		                Session::flash("flash_notification", [
		                	"level"     => "danger",
		                	"message"   => $pesan_alert
		                ]);

					return false;
				}
				else{
					//HPP PRODUK (MODEL)
						$hpp_produk = $DetailItemKeluar->produk->hpp;
						$total_hpp = $hpp_produk * $jumlah_produk_keluar;

								Hpp::create([
									'no_faktur' 		  => $DetailItemKeluar->no_faktur,
									'id_produk' 		  => $DetailItemKeluar->id_produk, 
									'jenis_transaksi'	  => 'item_keluar', 
									'jumlah_keluar'		  => $jumlah_produk_keluar, 
									'harga_unit'		  => $hpp_produk, 
									'total_nilai'		  => $total_hpp,
									'warung_id'			  => $DetailItemKeluar->warung_id, 
									'jenis_hpp'			  => 2
								]);


					return true;

				} // END IF STOK < 0

			} // END GOL. BARANG == BARANG		
		
    } // OBERVERS CREATING

    //HAPUS ITEM KELUAR
    public function deleting(DetailItemKeluar $DetailItemKeluar){

        $hpp = Hpp::where('no_faktur', $DetailItemKeluar->no_faktur)->where('id_produk', $DetailItemKeluar->id_produk)->where('jenis_hpp', 2)
        ->where('warung_id', $DetailItemKeluar->warung_id);
        
        if (!$hpp->delete()) {
        	return false;
        }
        else{
        	return true;
        }
        
    } // OBERVERS DELETING
}