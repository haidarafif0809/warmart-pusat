<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class DetailPenerimaanProduk extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_penerimaan', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id', 'status_harga', 'no_faktur_order', 'jumlah_fisik', 'selisih_fisik'];
	protected $primaryKey = 'id_detail_penerimaan';

	public function produk()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}

	public function getNamaProdukAttribute()
	{
		return title_case($this->produk->nama_barang);
	}

	public function scopeDetailPenerimaanProduk($query_order, $warung_id, $no_faktur_penerimaan){
		$query_order->select(['detail_penerimaan_produks.no_faktur_penerimaan', 'detail_penerimaan_produks.no_faktur_order', 'detail_penerimaan_produks.jumlah_fisik', 'detail_penerimaan_produks.selisih_fisik', 'detail_penerimaan_produks.id_produk', 'detail_penerimaan_produks.jumlah_produk', 'detail_penerimaan_produks.satuan_id', 'detail_penerimaan_produks.harga_produk', 'detail_penerimaan_produks.subtotal', 'detail_penerimaan_produks.tax', 'detail_penerimaan_produks.potongan', 'detail_penerimaan_produks.warung_id', 'detail_penerimaan_produks.status_harga', 'satuans.nama_satuan'])
		->leftJoin('satuans', 'satuans.id', '=', 'detail_penerimaan_produks.satuan_id')
		->leftJoin('barangs', 'barangs.id', '=', 'detail_penerimaan_produks.id_produk')
		->where('detail_penerimaan_produks.no_faktur_penerimaan', $no_faktur_penerimaan)
		->where('detail_penerimaan_produks.warung_id', $warung_id);

		return $query_order;
	}

	public function scopeCetakDetailPenerimaanProduk($query_order, $warung_id, $no_faktur_penerimaan){
		$query_order->select(['detail_penerimaan_produks.no_faktur_penerimaan', 'detail_penerimaan_produks.id_produk', 'detail_penerimaan_produks.jumlah_produk', 'detail_penerimaan_produks.jumlah_fisik', 'detail_penerimaan_produks.selisih_fisik', 'detail_penerimaan_produks.no_faktur_order', 'detail_penerimaan_produks.satuan_id', 'detail_penerimaan_produks.harga_produk', 'detail_penerimaan_produks.subtotal', 'detail_penerimaan_produks.tax', 'detail_penerimaan_produks.potongan', 'detail_penerimaan_produks.warung_id', 'detail_penerimaan_produks.status_harga', 'satuans.nama_satuan', 'barangs.nama_barang', 'barangs.kode_barang'])
		->leftJoin('satuans', 'satuans.id', '=', 'detail_penerimaan_produks.satuan_id')
		->leftJoin('barangs', 'barangs.id', '=', 'detail_penerimaan_produks.id_produk')
		->where('detail_penerimaan_produks.warung_id', $warung_id)
		->where('detail_penerimaan_produks.no_faktur_penerimaan', $no_faktur_penerimaan);

		return $query_order;
	}
}
