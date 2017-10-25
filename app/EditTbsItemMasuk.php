<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EditTbsItemMasuk extends Model
{
    //


      protected $fillable = ['id_edit_tbs_item_masuk','no_faktur','session_id','id_produk','jumlah_produk','warung_id'];
     protected $primaryKey = 'id_edit_tbs_item_masuk';

    	public function produk()
		  {
		  	return $this->hasOne('App\Barang','id','id_produk');
		  }


	//MODEL EVENT DELETE ITEM MASUK -> EDIT TBS ITEM MASUK
  	public static function boot() {
    parent::boot();
      
    self::deleting(function($itemMasuk) {

     

      $hpp_terpakai =  Hpp::where('no_faktur', $itemMasuk->no_faktur)->where('id_produk',$itemMasuk->id_produk)->where('warung_id', Auth::user()->id_warung)->count();
      
      if ($hpp_terpakai > 0) { 
        return false;
      }
      else {
        DetailItemMasuk::where('no_faktur', $itemMasuk->no_faktur)->delete();
        Hpp::where('no_faktur', $itemMasuk->no_faktur)->delete();

        return true;
      }
 
    
    });  
    } 
}
