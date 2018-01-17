<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class DetailPembayaranPiutang extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_pembayaran', 'no_faktur_penjualan', 'jatuh_tempo', 'piutang', 'potongan', 'jumlah_bayar', 'pelanggan_id', 'warung_id'];
    protected $primaryKey = 'id_detail_pembayaran_piutang';
}
