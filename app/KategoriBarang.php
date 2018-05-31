<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;


class KategoriBarang extends Model
{
    //
	use AuditableTrait;
	use LogsActivity;

	protected $fillable = ['nama_kategori_barang','kategori_icon', 'warung_id'];
}
