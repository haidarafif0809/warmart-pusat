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

	// pencarian edit tbs penjualan
	public function scopePencarian($query,$user_warung,$id,$request){
		
		$query->select('edit_tbs_penjualans.id_edit_tbs_penjualans AS id_edit_tbs_penjualans', 'edit_tbs_penjualans.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_penjualans.id_produk AS id_produk', 'edit_tbs_penjualans.potongan AS potongan', 'edit_tbs_penjualans.subtotal AS subtotal', 'edit_tbs_penjualans.harga_produk AS harga_produk','barangs.harga_jual AS harga_jual')
		->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_penjualans.id_produk')
		->where('warung_id', $user_warung)->where('id_penjualan_pos', $id)
		->where(function ($query) use ($request) {

			$query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
			->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%');

		})->orderBy('edit_tbs_penjualans.id_edit_tbs_penjualans', 'desc');
		return $query;
	}

	public function produk()
	{
		return $this->hasOne('App\Barang','id','id_produk');
	}
	public function getNamaProdukAttribute()
	{
		return title_case($this->produk->nama_barang);
	}
	
}
