<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;
use Yajra\Auditable\AuditableTrait;

class TransaksiPiutang extends Model
{

    use LaratrustUserTrait;
    use AuditableTrait;

    protected $fillable = ['jenis_transaksi', 'pelanggan_id', 'no_faktur', 'jumlah_masuk', 'jumlah_keluar', 'warung_id'];
}
