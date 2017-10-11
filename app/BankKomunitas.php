<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankKomunitas extends Model
{
    //

     protected $fillable = ['nama_bank','atas_nama','no_rek','komunitas_id'];


    public function komunitas(){
    	
		  	return $this->belongsTo('App\Komunitas','komunitas_id','id');
		  
    }
}
