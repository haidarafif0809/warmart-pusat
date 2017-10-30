<?php

namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\DetailItemMasuk;
use App\EditTbsItemMasuk;
use App\Hpp;
use Auth;
use Session;
use App\Barang;

class DetailItemMasukObserver
{	
	//MEMBUAT HPP MASUK
	public function creating(DetailItemMasuk $DetailItemMasuk){ 
		$total_nilai = $DetailItemMasuk->jumlah_produk *  $DetailItemMasuk->produk->harga_beli;
 
				Hpp::create([
					'no_faktur'  	 => $DetailItemMasuk->no_faktur, 
					'id_produk'  	 => $DetailItemMasuk->id_produk, 
					'jenis_transaksi'=> 'item_masuk',
					'jumlah_masuk' 	 => $DetailItemMasuk->jumlah_produk, 
					'harga_unit' 	 => $DetailItemMasuk->produk->harga_beli,
					'total_nilai' 	 => $total_nilai, 
					'jenis_hpp' 	 => '1',
					'warung_id'		 => $DetailItemMasuk->warung_id, 
				]);
 
 				return true;   
    } // OBERVERS CREATING
 
    //HAPUS ITEM MASUK
    public function deleting(DetailItemMasuk $DetailItemMasuk){
    	
    	$edit_tbs_item_masuk = EditTbsItemMasuk::select([DB::raw('SUM(jumlah_produk) as total_produk')])->where('no_faktur',$DetailItemMasuk->no_faktur)->where('id_produk', $DetailItemMasuk->id_produk)->where('warung_id',$DetailItemMasuk->warung_id);

		$stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('no_faktur' ,'!=',$DetailItemMasuk->no_faktur)->where('id_produk', $DetailItemMasuk->id_produk)
		->where('warung_id', $DetailItemMasuk->warung_id)->first(); 
		$data = $edit_tbs_item_masuk->first()->total_produk + $stok->stok_produk;  
		if ($edit_tbs_item_masuk->count() < 0) {
			if ($stok->stok_produk < 0) {
				$pesan_alert = 
					'<div class="container-fluid">
						<div class="alert-icon">
		                    <i class="material-icons">error</i>
		                </div>
				                	<b>Gagal : Stok Tidak Mencukupi "</b>
	                </div>';

	                Session::flash("flash_notification", [
	                	"level"     => "danger",
	                	"message"   => $pesan_alert
	                ]);

				return false;
			}
			else{
		        $hpp = Hpp::where('no_faktur', $DetailItemMasuk->no_faktur)->where('id_produk', $DetailItemMasuk->id_produk)->where('jenis_hpp', 1)
		        ->where('warung_id', $DetailItemMasuk->warung_id);

			    if (!$hpp->delete()) {
			       	return false;
			    }
			    else{
			       	return true;
			    } 
			} 
		}elseif ($edit_tbs_item_masuk->count() > 0) {
			if ($data < 0) {
				$pesan_alert = 
					'<div class="container-fluid">
						<div class="alert-icon">
		                    <i class="material-icons">error</i>
		                </div>
				                	<b>Gagal : Stok Tidak Mencukupi "</b>
	                </div>';

	                Session::flash("flash_notification", [
	                	"level"     => "danger",
	                	"message"   => $pesan_alert
	                ]);

				return false;
			}
			else{
		        $hpp = Hpp::where('no_faktur', $DetailItemMasuk->no_faktur)->where('id_produk', $DetailItemMasuk->id_produk)->where('jenis_hpp', 1)
		        ->where('warung_id', $DetailItemMasuk->warung_id);

			    if (!$hpp->delete()) {
			       	return false;
			    }
			    else{
			       	return true;
			    } 
			} 
		}
 
    }
}