<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

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
}
