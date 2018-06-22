<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class EditTbsPenerimaanProduk extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_penerimaan', 'session_id', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'status_harga', 'warung_id', 'ppn', 'tax_include', 'no_faktur_order', 'jumlah_fisik', 'selisih_fisik'];
	protected $primaryKey = 'id_edit_tbs_penerimaan_produk';

	// Transaksi Edit Tbs Pembelian
	public function scopeDataTransaksiEditTbsPenerimaanProduk($query, $no_faktur, $user_warung)
	{
		$query->select('edit_tbs_penerimaan_produks.id_edit_tbs_penerimaan_produk', 'edit_tbs_penerimaan_produks.jumlah_produk', 'barangs.nama_barang', 'barangs.kode_barang', 'edit_tbs_penerimaan_produks.id_produk', 'edit_tbs_penerimaan_produks.harga_produk', 'edit_tbs_penerimaan_produks.potongan', 'edit_tbs_penerimaan_produks.tax', 'edit_tbs_penerimaan_produks.subtotal', 'edit_tbs_penerimaan_produks.ppn', 'edit_tbs_penerimaan_produks.satuan_id', 'edit_tbs_penerimaan_produks.no_faktur_order', 'edit_tbs_penerimaan_produks.jumlah_fisik', 'edit_tbs_penerimaan_produks.selisih_fisik', 'satuans.nama_satuan')
		->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_penerimaan_produks.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'edit_tbs_penerimaan_produks.satuan_id')
		->where('edit_tbs_penerimaan_produks.no_faktur_penerimaan', $no_faktur)->where('edit_tbs_penerimaan_produks.warung_id', $user_warung);
		return $query;
	}
}
