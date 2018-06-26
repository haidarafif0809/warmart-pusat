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
}
