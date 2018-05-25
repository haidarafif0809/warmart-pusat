<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class EditTbsReturPembelian extends Model
{
	use AuditableTrait;
	protected $fillable   = ['no_faktur_retur', 'session_id', 'no_faktur_pembelian', 'id_produk', 'jumlah_beli', 'jumlah_retur', 'satuan_id', 'satuan_dasar', 'satuan_beli', 'harga_produk', 'subtotal', 'potongan', 'tax', 'tax_include', 'ppn', 'warung_id', 'created_at', 'updated_at', 'supplier'];
	protected $primaryKey = 'id_edit_tbs_retur_pembelian';
}
