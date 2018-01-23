<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class EditTbsPembayaranPiutang extends Model
{
    use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur_pembayaran', 'subtotal_piutang', 'no_faktur_penjualan', 'jatuh_tempo', 'piutang', 'potongan', 'jumlah_bayar', 'pelanggan_id', 'warung_id'];
    protected $primaryKey = 'id_edit_tbs_pembayaran_piutang';
}
