<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Kelurahan extends Model
{
	use LogsActivity;
	
	protected $fillable = ['id','nama'];
}
