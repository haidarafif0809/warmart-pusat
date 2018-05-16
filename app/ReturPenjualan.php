<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class ReturPenjualan extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_retur', 'id_pelanggan', 'keterangan', 'total', 'total_bayar','tax','potongan','id_kas','ppn','warung_id'];
    protected $primaryKey = 'id_retur_penjualan';
}
