<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class ReturPembelian extends Model
{
	use AuditableTrait;
	protected $fillable = ['no_faktur_retur', 'suplier_id', 'keterangan', 'total', 'total_bayar', 'potongan', 'potong_hutang', 'tax', 'ppn', 'warung_id'];
}
