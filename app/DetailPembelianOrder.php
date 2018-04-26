<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class DetailPembelianOrder extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_order', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id', 'status_harga'];
	protected $primaryKey = 'id_detail_pembelian_order';

	public function produk()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}

	public function getNamaProdukAttribute()
	{
		return title_case($this->produk->nama_barang);
	}

	public function scopeDetailPembelianOrder($query_order, $warung_id, $no_faktur_order){
		$query_order->select(['detail_pembelian_orders.no_faktur_order', 'detail_pembelian_orders.id_produk', 'detail_pembelian_orders.jumlah_produk', 'detail_pembelian_orders.satuan_id', 'detail_pembelian_orders.harga_produk', 'detail_pembelian_orders.subtotal', 'detail_pembelian_orders.tax', 'detail_pembelian_orders.potongan', 'detail_pembelian_orders.warung_id', 'detail_pembelian_orders.status_harga', 'satuans.nama_satuan'])
		->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelian_orders.satuan_id')
		->where('detail_pembelian_orders.no_faktur_order', $no_faktur_order)
		->where('detail_pembelian_orders.warung_id', $warung_id);

		return $query_order;
	}
}
