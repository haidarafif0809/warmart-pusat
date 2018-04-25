<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TbsPenerimaanProduk extends Model
{
	use AuditableTrait;
	protected $fillable   = ['session_id', 'no_faktur_order', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'status_harga', 'warung_id'];
	protected $primaryKey = 'id_tbs_penerimaan_produk';


	// Transaksi Tbs Pnerimaan Produk
	public function scopeDataTbsPenerimaanProduk($query, $session_id, $user_warung)
	{
		$query->select('tbs_penerimaan_produks.id_tbs_penerimaan_produk', 'tbs_penerimaan_produks.jumlah_produk', 'barangs.nama_barang', 'barangs.kode_barang', 'tbs_penerimaan_produks.id_produk', 'tbs_penerimaan_produks.harga_produk', 'tbs_penerimaan_produks.potongan', 'tbs_penerimaan_produks.tax', 'tbs_penerimaan_produks.subtotal', 'tbs_penerimaan_produks.ppn', 'tbs_penerimaan_produks.satuan_id', 'satuans.nama_satuan')
		->leftJoin('barangs', 'barangs.id', '=', 'tbs_penerimaan_produks.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'tbs_penerimaan_produks.satuan_id')
		->where('tbs_penerimaan_produks.session_id', $session_id)->where('tbs_penerimaan_produks.warung_id', $user_warung);
		return $query;
	}
}
