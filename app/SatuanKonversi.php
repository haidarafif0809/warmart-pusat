<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class SatuanKonversi extends Model
{
    use AuditableTrait;

    protected $fillable   = ['id_satuan', 'id_produk', 'jumlah_konversi', 'harga_jual_konversi', 'warung_id'];
    protected $primaryKey = 'id_satuan_konversi';
}
