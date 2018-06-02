<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class DetailReturPenjualan extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_retur', 'no_faktur_penjualan', 'id_produk', 'jumlah_jual', 'jumlah_retur', 'id_satuan', 'id_satuan_jual', 'harga_produk', 'subtotal', 'tax','potongan','warung_id','id_pelanggan','satuan_dasar','created_at'];
    protected $primaryKey = 'id_detail_retur_penjualan';

        // relasi ke produk
	public function produk()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}

		public function scopeDataDetailRetur($query_detail, $no_faktur_retur)
	{
		$query_detail = DetailReturPenjualan::select(['detail_retur_penjualans.no_faktur_penjualan','detail_retur_penjualans.harga_produk', 'detail_retur_penjualans.subtotal', 'detail_retur_penjualans.potongan','detail_retur_penjualans.jumlah_retur', 'barangs.nama_barang','barangs.kode_barang', 'satuans.nama_satuan'])
		->leftJoin('barangs', 'barangs.id', '=', 'detail_retur_penjualans.id_produk')
		->leftJoin('satuans', 'satuans.id', '=', 'detail_retur_penjualans.id_satuan')
		->where('detail_retur_penjualans.warung_id', Auth::user()->id_warung)
		->where('detail_retur_penjualans.no_faktur_retur', $no_faktur_retur)
		->orderBy('detail_retur_penjualans.id_detail_retur_penjualan', 'desc');

		return $query_detail;
	}

}
