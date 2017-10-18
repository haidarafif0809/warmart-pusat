<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DetailItemMasuk extends Model
{
    //
         protected $fillable = ['no_faktur','id_produk','jumlah_produk','warung_id'];
     protected $primaryKey = 'id_detail_item_masuk';
      // relasi ke produk
    public function produk(){
		  	return $this->hasOne('App\Barang','id','id_produk');
	}

	public static function boot() {
		parent::boot();
			
		self::created(function($itemKeluar) {

			$total_nilai = $itemKeluar->jumlah_produk *  $itemKeluar->produk->harga_beli; 

			Hpp::create(['no_faktur' => $itemKeluar->no_faktur, 'id_produk' => $itemKeluar->id_produk, 'jenis_transaksi' => 'item_masuk', 'jumlah_masuk' => $itemKeluar->jumlah_produk, 'harga_unit' => $itemKeluar->produk->harga_beli, 'total_nilai' => $total_nilai, 'jenis_hpp' => '1','warung_id'=>Auth::user()->id_warung]);

			return true;
		
		});
	} //model event

}
