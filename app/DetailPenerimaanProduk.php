<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class DetailPenerimaanProduk extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_penerimaan', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id', 'status_harga'];
	protected $primaryKey = 'id_detail_penerimaan';
}
