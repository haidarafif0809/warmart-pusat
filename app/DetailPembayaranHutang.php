<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class DetailPembayaranHutang extends Model
{
    //
        use AuditableTrait;
    protected $fillable   = ['no_faktur_pembayaran', 'no_faktur_pembelian', 'jatuh_tempo', 'hutang', 'potongan', 'jumlah_bayar', 'suplier_id', 'warung_id','subtotal_hutang'];
    protected $primaryKey = 'id_detail_pembayaran_hutang';
}
