<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use Session;
use Auth;

class DetailItemKeluar extends Model
{
    protected $fillable = ['no_faktur','id_produk','jumlah_produk','warung_id'];
    protected $primaryKey = 'id_detail_item_keluar';
    
// relasi ke produk
    public function produk(){
    	return $this->hasOne('App\Barang','id','id_produk');
	}

	public function stok_produk($id_produk, $jumlah_keluar){

		$stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])->where('id_produk', $id_produk)
		->where('warung_id', Auth::user()->id_warung)->first();

		$data_produk = Barang::select('nama_barang')->where('id', $id_produk)->first();

		$sisa_stok_keluar = $stok_produk->jumlah_produk - $jumlah_keluar;

		if ($sisa_stok_keluar < 0) {

			$pesan_alert = 
				'<div class="container-fluid">
					<div class="alert-icon">
	                    <i class="material-icons">error</i>
	                </div>
	                	<b>Gagal : Stok " '.$data_produk->nama_barang.' " Tidak Mencukupi Untuk Dikeluarkan, Sisa Produk = " '.$stok_produk->jumlah_produk.' "</b>
                </div>';

                Session::flash("flash_notification", [
                	"level"     => "danger",
                	"message"   => $pesan_alert
                ]);

			return false;
		}
		else{
			return true;
		}	
	}
}