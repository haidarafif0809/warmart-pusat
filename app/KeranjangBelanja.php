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

	public function getNamaProdukAttribute() 
	{   
		return title_case($this->produk->nama_barang); 

	}


	public function getNamaProdukMobileAttribute() 
	{   
		if (strlen(strip_tags($this->produk->nama_barang)) <= 33) {

			$nama_produk =title_case( strip_tags($this->produk->nama_barang));
		}else{

			$nama_produk = title_case(''.strip_tags(substr($this->produk->nama_barang, 0, 30)).'...'); 
		}

		return $nama_produk;
	}
	

}
