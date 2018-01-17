<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class PembayaranPiutang extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_pembayaran', 'total', 'cara_bayar', 'warung_id', 'keterangan'];
    protected $primaryKey = 'id_pembayaran_piutang';

    public function getWaktuAttribute()
    {
        $tanggal       = date($this->created_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d-m-Y H:i:s");
        return $date_terbalik;
    }
    public function getPemisahTotalAttribute()
    {
        return number_format($this->total, 2, ',', '.');
    }
}
