<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPesananWarung extends Model
{
    //
	protected $fillable = ['id_pesanan_warung','id_produk','id_pelanggan','harga_produk','jumlah_produk']; 
      // relasi ke produk
	public function produk(){
		return $this->hasOne('App\Barang','id','id_produk');
	} 
      // relasi ke pelanggan
	public function pelanggan(){
		return $this->hasOne('App\User','id','id_pelanggan');
	} 
      // relasi ke pesanan warung
	public function pesanan_warung(){
		return $this->hasOne('App\PesananWarung','id','id_pesanan_warung');
	} 
}
