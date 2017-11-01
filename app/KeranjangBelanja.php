<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeranjangBelanja extends Model
{
    //
	protected $fillable = ['id_produk','id_pelanggan','jumlah_produk'];
	protected $primaryKey = 'id_keranjang_belanja';
      // relasi ke produk
	public function produk(){
		return $this->hasOne('App\Barang','id','id_produk');
	} 
      // relasi ke pelanggan
	public function pelanggan(){
		return $this->hasOne('App\User','id','id_pelanggan');
	} 
}
