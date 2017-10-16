<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class Kas extends Model
{
    use AuditableTrait;
    use LogsActivity;

    protected $fillable = ['kode_kas','nama_kas','status_kas', 'default_kas', 'warung_id'];

}
