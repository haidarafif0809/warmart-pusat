<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class EditTbsReturPembelian extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_retur', 'session_id', 'no_faktur_pembelian', 'id_produk', 'jumlah_beli', 'jumlah_retur', 'satuan_id', 'satuan_dasar', 'satuan_beli', 'harga_produk', 'subtotal', 'potongan', 'tax', 'tax_include', 'ppn', 'warung_id', 'created_at', 'updated_at', 'supplier'];
	protected $primaryKey = 'id_edit_tbs_retur_pembelian';


	public function produk()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}
	

	public function scopeDataTransaksiEditTbsReturPembelian($query, $session_id, $no_faktur_retur, $warung_id) {
		$query->select('edit_tbs_retur_pembelians.id_edit_tbs_retur_pembelian', 'edit_tbs_retur_pembelians.jumlah_beli', 'edit_tbs_retur_pembelians.jumlah_retur', 'barangs.nama_barang', 'barangs.kode_barang', 'edit_tbs_retur_pembelians.id_produk', 'edit_tbs_retur_pembelians.harga_produk', 'edit_tbs_retur_pembelians.potongan', 'edit_tbs_retur_pembelians.tax', 'edit_tbs_retur_pembelians.subtotal', 'edit_tbs_retur_pembelians.ppn', 'edit_tbs_retur_pembelians.satuan_id', 'satuans.nama_satuan')
		->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_retur_pembelians.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'edit_tbs_retur_pembelians.satuan_id')
		->where('edit_tbs_retur_pembelians.no_faktur_retur', $no_faktur_retur)
		->where('edit_tbs_retur_pembelians.session_id', $session_id)
		->where('edit_tbs_retur_pembelians.warung_id', $warung_id);
		return $query;
	}

	public function scopeSubtotalTbs($query, $user_warung, $session_id, $no_faktur_retur)
	{
		$tbs_retur_pembelian = EditTbsReturPembelian::select(DB::raw('SUM(subtotal) as subtotal'))
		->where('no_faktur_retur', $no_faktur_retur)
		->where('session_id', $session_id)
		->where('warung_id', Auth::user()->id_warung)->first();
		if ($tbs_retur_pembelian->subtotal == null || $tbs_retur_pembelian->subtotal == '') {
			return $query = 0;
		} else {
			return $query = $tbs_retur_pembelian->subtotal;
		}
	}
}
