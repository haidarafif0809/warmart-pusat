<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hpp extends Model
{
    //

	protected $fillable = ['no_faktur', 'no_faktur_hpp_masuk', 'no_faktur_hpp_keluar', 'id_produk', 'jenis_transaksi', 'jumlah_masuk','jumlah_keluar', 'harga_unit', 'total_nilai', 'jenis_hpp','warung_id','created_at'];

	protected $table = 'hpps';

	public function produk()
	{
		return $this->hasOne('App\Barang','id','id_produk');
	}


}
