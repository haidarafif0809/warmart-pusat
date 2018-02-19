<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class StokOpname extends Model
{
    use AuditableTrait;
    protected $fillable = ['no_faktur', 'produk_id', 'stok_sekarang', 'jumlah_fisik', 'selisih_fisik', 'harga', 'total', 'warung_id', 'keterangan'];
}
