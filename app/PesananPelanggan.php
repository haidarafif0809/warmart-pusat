<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesananPelanggan extends Model
{ 
	protected $fillable = ['id_pelanggan','nama_pemesan','no_telp_pemesan','alamat_pemesan','jumlah_produk','subtotal','konfirmasi_pesanan','id_warung'];

      // relasi ke pelanggan
	public function pelanggan(){
		return $this->hasOne('App\User','id','id_pelanggan');
	}  
}
