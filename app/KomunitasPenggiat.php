<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomunitasPenggiat extends Model
{
    //
     protected $fillable = ['nama_penggiat','alamat_penggiat','komunitas_id'];


    public function komunitas(){
    	
		  	return $this->belongsTo('App\Komunitas','komunitas_id','id');
		  
    }

}
