<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TbsPembelianOrder extends Model
{
	use AuditableTrait;
	protected $fillable   = ['session_id', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'status_harga', 'warung_id', 'ppn', 'tax_include'];
	protected $primaryKey = 'id_tbs_pembelian_order';

	// Transaksi Tbs Pembelian
	public function scopeDataTransaksiTbsPembelianOrder($query, $session_id, $user_warung)
	{
		$query->select('tbs_pembelian_orders.id_tbs_pembelian_order', 'tbs_pembelian_orders.jumlah_produk', 'barangs.nama_barang', 'barangs.kode_barang', 'tbs_pembelian_orders.id_produk', 'tbs_pembelian_orders.harga_produk', 'tbs_pembelian_orders.potongan', 'tbs_pembelian_orders.tax', 'tbs_pembelian_orders.subtotal', 'tbs_pembelian_orders.ppn', 'tbs_pembelian_orders.satuan_id', 'satuans.nama_satuan')
		->leftJoin('barangs', 'barangs.id', '=', 'tbs_pembelian_orders.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'tbs_pembelian_orders.satuan_id')
		->where('tbs_pembelian_orders.session_id', $session_id)->where('tbs_pembelian_orders.warung_id', $user_warung);
		return $query;
	}
}
