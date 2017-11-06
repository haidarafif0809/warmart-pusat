<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use Session;
use Auth;

class DetailPembelian extends Model
{
    protected $fillable = ['no_faktur','satuan_id','id_produk','jumlah_produk','harga_produk','subtotal','tax','potongan','warung_id'];
    protected $primaryKey = 'id_detail_pembelian';
    
// relasi ke produk
    public function produk(){
    	return $this->hasOne('App\Barang','id','id_produk');
	}
}
