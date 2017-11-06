<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;
use Auth;

class TbsPembelian extends Model
{
	
	use AuditableTrait;
	protected $fillable = ['session_id','satuan_id','id_produk','jumlah_produk','harga_produk','subtotal','potongan','tax','warung_id','ppn'];
	protected $primaryKey = 'id_tbs_pembelian';

	public function produk()
	{
		return $this->hasOne('App\Barang','id','id_produk');
	}
	public function getTitleCaseBarangAttribute()
	{
		return title_case($this->produk->nama_barang);
	}
	
}
