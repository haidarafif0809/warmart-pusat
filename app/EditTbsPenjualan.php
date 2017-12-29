<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;
use Auth;


class EditTbsPenjualan extends Model
{
	use AuditableTrait;
	protected $fillable = ['session_id','id_penjualan_pos','no_faktur','satuan_id','id_produk','jumlah_produk','harga_produk','subtotal','potongan','tax','warung_id','ppn'];
	protected $primaryKey = 'id_edit_tbs_penjualans';

	public function produk()
	{
		return $this->hasOne('App\Barang','id','id_produk');
	}
	public function getNamaBarangAttribute()
	{
		return title_case($this->produk->nama_barang);
	}
	
}
