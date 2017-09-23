<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    //

    protected $table = 'users';

   	protected $fillable = ['email','password','name', 'alamat', 'wilayah', 'link_afiliasi', 'no_telp','nama_bank', 'no_rekening','an_rekening','tipe_user'];


       public function kelurahan(){
		return $this->hasOne('App\Kelurahan','id','wilayah');
	}

}
