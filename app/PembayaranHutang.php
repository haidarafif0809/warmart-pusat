<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class PembayaranHutang extends Model
{
    //
        use AuditableTrait;
    protected $fillable = ['no_faktur_pembayaran', 'total', 'suplier_id', 'cara_bayar', 'warung_id', 'keterangan'];
    protected $primaryKey = 'id_pembayaran_hutang';

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
