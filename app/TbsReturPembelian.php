<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TbsReturPembelian extends Model
{

	use AuditableTrait;
	protected $fillable   = ['session_id', 'no_faktur_pembelian', 'id_produk', 'jumlah_beli', 'jumlah_retur', 'satuan_id', 'satuan_dasar', 'satuan_beli', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id', 'created_at', 'updated_at'];
	protected $primaryKey = 'id_tbs_retur_pembelian';
}
