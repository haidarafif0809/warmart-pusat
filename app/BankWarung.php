<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class BankWarung extends Model
{
    use AuditableTrait;
	use LogsActivity;

    protected $fillable = ['nama_bank','atas_nama','no_rek', 'warung_id'];
}
