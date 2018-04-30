<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class EditTbsPembelianOrder extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_order', 'session_id', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'status_harga', 'warung_id', 'ppn', 'tax_include'];
	protected $primaryKey = 'id_edit_tbs_pembelian_order';

	// Transaksi Edit Tbs Pembelian
	public function scopeDataTransaksiEditTbsPembelianOrder($query, $no_faktur, $user_warung)
	{
		$query->select('edit_tbs_pembelian_orders.id_edit_tbs_pembelian_order', 'edit_tbs_pembelian_orders.jumlah_produk', 'barangs.nama_barang', 'barangs.kode_barang', 'edit_tbs_pembelian_orders.id_produk', 'edit_tbs_pembelian_orders.harga_produk', 'edit_tbs_pembelian_orders.potongan', 'edit_tbs_pembelian_orders.tax', 'edit_tbs_pembelian_orders.subtotal', 'edit_tbs_pembelian_orders.ppn', 'edit_tbs_pembelian_orders.satuan_id', 'satuans.nama_satuan')
		->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_pembelian_orders.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'edit_tbs_pembelian_orders.satuan_id')
		->where('edit_tbs_pembelian_orders.no_faktur_order', $no_faktur)->where('edit_tbs_pembelian_orders.warung_id', $user_warung);
		return $query;
	}
}
