<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{ 
	protected $fillable = ['id_kas','id_pesanan','id_pelanggan','total'];

	// relasi ke kas
	public function kas(){
		return $this->hasOne('App\Kas','id','id_kas');
	} 

	// relasi ke pesanan
	public function pesanan(){
		return $this->hasOne('App\PesananPelanggan','id','id_pesanan');
	} 
	// relasi ke pelanggan
	public function pelanggan(){
		return $this->hasOne('App\User','id','id_pelanggan');
	} 
}
