<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Auth;

class DetailReturPembelian extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_retur', 'no_faktur_pembelian', 'no_faktur_hutang', 'id_produk', 'jumlah_produk', 'jumlah_beli', 'satuan_id', 'satuan_dasar', 'satuan_beli', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id',  'created_at', 'updated_at', 'warung_id', 'tax_include', 'ppn', 'supplier'];
	protected $primaryKey = 'id_detail_retur_pembelian';

    // relasi ke produk
	public function produk()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}


	public function scopeDataDetailRetur($query_detail, $no_faktur_retur)
	{
		$query_detail = DetailReturPembelian::select(['detail_retur_pembelians.harga_produk', 'detail_retur_pembelians.subtotal', 'detail_retur_pembelians.potongan', 'detail_retur_pembelians.tax', 'detail_retur_pembelians.jumlah_produk', 'barangs.nama_barang', 'satuans.nama_satuan'])
		->leftJoin('barangs', 'barangs.id', '=', 'detail_retur_pembelians.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'detail_retur_pembelians.satuan_id')
		->where('detail_retur_pembelians.warung_id', Auth::user()->id_warung)
		->where('detail_retur_pembelians.no_faktur_retur', $no_faktur_retur)
		->orderBy('detail_retur_pembelians.id_detail_retur_pembelian', 'desc');

		return $query_detail;
	}
}
