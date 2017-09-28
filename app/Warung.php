<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    //
   	protected $fillable = ['name','alamat','wilayah', 'url_api'];

   		//relasi dengan model kelurahan
   	   	public function kelurahan(){
		return $this->hasOne('App\Kelurahan','id','wilayah');
		}
 
}
