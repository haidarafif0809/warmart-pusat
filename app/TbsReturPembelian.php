<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class TbsReturPembelian extends Model
{

	use AuditableTrait;
	protected $fillable   = ['session_id', 'no_faktur_pembelian', 'id_produk', 'jumlah_beli', 'jumlah_retur', 'satuan_id', 'satuan_dasar', 'satuan_beli', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id', 'created_at', 'updated_at'];
	protected $primaryKey = 'id_tbs_retur_pembelian';


	public function scopeDataTransaksiTbsReturPembelian($query, $session_id, $warung_id) {
		$query->select('tbs_retur_pembelians.id_tbs_retur_pembelian', 'tbs_retur_pembelians.jumlah_beli', 'tbs_retur_pembelians.jumlah_retur', 'barangs.nama_barang', 'barangs.kode_barang', 'tbs_retur_pembelians.id_produk', 'tbs_retur_pembelians.harga_produk', 'tbs_retur_pembelians.potongan', 'tbs_retur_pembelians.tax', 'tbs_retur_pembelians.subtotal', 'tbs_retur_pembelians.ppn', 'tbs_retur_pembelians.satuan_id', 'satuans.nama_satuan')
		->leftJoin('barangs', 'barangs.id', '=', 'tbs_retur_pembelians.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'tbs_retur_pembelians.satuan_id')
		->where('tbs_retur_pembelians.session_id', $session_id)->where('tbs_retur_pembelians.warung_id', $warung_id);
		return $query;
	}

	public function scopeSubtotalTbs($query, $user_warung, $session_id)
	{
		$tbs_retur_pembelian = TbsReturPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
		if ($tbs_retur_pembelian->subtotal == null || $tbs_retur_pembelian->subtotal == '') {
			return $query = 0;
		} else {
			return $query = $tbs_retur_pembelian->subtotal;
		}
	}
}
