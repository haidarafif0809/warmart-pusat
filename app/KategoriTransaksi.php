<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class KategoriTransaksi extends Model
{
    use AuditableTrait;
    use LogsActivity; 

    protected $fillable = ['nama_kategori_transaksi','id_warung'];
}
