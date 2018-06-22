<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPesananPelanggan extends Model
{
    //
	protected $fillable = ['id_pesanan_pelanggan','id_produk','id_pelanggan','harga_produk','jumlah_produk']; 
      // relasi ke produk
	public function produk(){
		return $this->hasOne('App\Barang','id','id_produk');
	} 
      // relasi ke pelanggan
	public function pelanggan(){
		return $this->hasOne('App\User','id','id_pelanggan');
	} 
      // relasi ke pesanan pelanggan
	public function pesanan_pelanggan(){
		return $this->hasOne('App\PesananPelanggan','id','id_pesanan_pelanggan');
	}
	public function getNamaBarangAttribute() 
	{ 
		return title_case($this->produk->nama_barang); 
	} 
}
