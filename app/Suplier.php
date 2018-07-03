<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;
class Suplier extends Model
{
	use AuditableTrait;
	use LogsActivity;
    //
       	protected $fillable = ['nama_suplier','alamat','no_telp','warung_id','contact_person'];

}
