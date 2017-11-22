<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{ 
	protected $fillable = ['id_penjualan','id_produk','harga','jumlah','potongan','subtotal'];

	// relasi ke penjualan
	public function penjualan(){
		return $this->hasOne('App\Penjualan','id','id_penjualan');
	} 

	// relasi ke produk
	public function produk(){
		return $this->hasOne('App\Barang','id','id_produk');
	} 

}
