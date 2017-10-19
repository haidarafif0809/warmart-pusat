<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EditTbsItemKeluar extends Model
{
    //

          protected $fillable = ['id_edit_tbs_item_keluar','no_faktur','session_id','id_produk','jumlah_produk','warung_id'];
     protected $primaryKey = 'id_edit_tbs_item_keluar';

    	public function produk()
		  {
		  	return $this->hasOne('App\Barang','id','id_produk');
		  }
}
