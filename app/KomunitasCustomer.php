<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomunitasCustomer extends Model
{
    //

    protected $fillable = ['komunitas_id','user_id'];


    public function komunitas(){


		  	return $this->belongsTo('App\Komunitas','komunitas_id','id');
		  
    }
}
