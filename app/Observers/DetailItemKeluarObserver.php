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

					while ($jumlah_produk_keluar > 0) {

						$hpp = DB::table('hpps AS hpp_masuk')->select([DB::raw('jumlah_masuk - IFNULL((SELECT SUM(jumlah_keluar) as jumlah_keluar FROM hpps WHERE id_produk = "'.$DetailItemKeluar->id_produk.'" AND no_faktur_hpp_masuk = hpp_masuk.no_faktur),0) AS sisa_hpp'), 'no_faktur', 'harga_unit'])
						->where('id_produk', $DetailItemKeluar->id_produk)->where('warung_id', $DetailItemKeluar->warung_id)->where('jenis_hpp', 1)
						->having('sisa_hpp', '>', 0)
						->orderBy('created_at', 'ASC')->first();

							$jumlah_hpp_masuk = $hpp->sisa_hpp;

							if ($jumlah_produk_keluar == $jumlah_hpp_masuk) {

								$total_nilai = $jumlah_produk_keluar * $hpp->harga_unit;

						        	Hpp::create([
									'no_faktur' 		  => $DetailItemKeluar->no_faktur,
									'no_faktur_hpp_masuk' => $hpp->no_faktur,
									'id_produk' 		  => $DetailItemKeluar->id_produk, 
									'jenis_transaksi'	  => 'item_keluar', 
									'jumlah_keluar'		  => $jumlah_produk_keluar, 
									'harga_unit'		  => $hpp->harga_unit, 
									'total_nilai'		  => $total_nilai,
									'warung_id'			  => $DetailItemKeluar->warung_id, 
									'jenis_hpp'			  => 2
								]);

						        $jumlah_produk_keluar = 0;
							}
							else if ($jumlah_produk_keluar > $jumlah_hpp_masuk) {
								

								$total_nilai = $jumlah_produk_keluar * $hpp->harga_unit;

						        Hpp::create([
									'no_faktur' 		  => $DetailItemKeluar->no_faktur,
									'no_faktur_hpp_masuk' => $hpp->no_faktur,
									'id_produk' 		  => $DetailItemKeluar->id_produk, 
									'jenis_transaksi'	  => 'item_keluar', 
									'jumlah_keluar'		  => $jumlah_hpp_masuk, 
									'harga_unit'		  => $hpp->harga_unit, 
									'total_nilai'		  => $total_nilai,
									'warung_id'			  => $DetailItemKeluar->warung_id, 
									'jenis_hpp'			  => 2
								]);

								$jumlah_produk_keluar -= $jumlah_hpp_masuk;

							}
							else if ($jumlah_produk_keluar < $jumlah_hpp_masuk) {


								$total_nilai = $jumlah_produk_keluar * $hpp->harga_unit;

						        Hpp::create([
									'no_faktur' 		  => $DetailItemKeluar->no_faktur,
									'no_faktur_hpp_masuk' => $hpp->no_faktur,
									'id_produk' 		  => $DetailItemKeluar->id_produk, 
									'jenis_transaksi'	  => 'item_keluar', 
									'jumlah_keluar'		  => $jumlah_produk_keluar, 
									'harga_unit'		  => $hpp->harga_unit, 
									'total_nilai'		  => $total_nilai,
									'warung_id'			  => $DetailItemKeluar->warung_id, 
									'jenis_hpp'			  => 2
								]);
						       	
						       	$jumlah_produk_keluar = 0;

							}

					} // END WHILE

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