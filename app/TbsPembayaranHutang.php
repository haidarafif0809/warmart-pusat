<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TbsPembayaranHutang extends Model
{
    //
        use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur_pembayaran', 'no_faktur_pembelian', 'jatuh_tempo', 'hutang', 'potongan', 'jumlah_bayar', 'suplier_id','warung_id','subtotal_hutang'];
    protected $primaryKey = 'id_tbs_pembayaran_hutang';

        // relasi ke suppier
    public function suplier()
    {
        return $this->hasOne('App\Suplier', 'id', 'suplier_id');
    }
    // relasi ke kas
    public function kas()
    {
        return $this->hasOne('App\Kas', 'id', 'cara_bayar');
    }
    
}
