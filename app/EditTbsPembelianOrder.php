<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class EditTbsPembelianOrder extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_order', 'session_id', 'id_produk', 'jumlah_produk', 'satuan_id', 'satuan_dasar', 'harga_produk', 'subtotal', 'tax', 'potongan', 'status_harga', 'warung_id', 'ppn', 'tax_include'];
	protected $primaryKey = 'id_edit_tbs_pembelian_order';
}
