<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokingCenter extends Model
{
    //
   	protected $fillable = ['name','alamat','wilayah', 'kategori_harga', 'url_api'];

   		//relasi dengan model kelurahan
   	   	public function kelurahan(){
		return $this->hasOne('App\Kelurahan','id','wilayah');
		}

		//relasi dengan model kategori_harga
		public function kategori_harga(){
        return $this->hasOne('App\KategoriHarga','id','kategori_harga');
        }

}
