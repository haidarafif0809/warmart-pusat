<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;


class Warung extends Model
{
    use AuditableTrait;
	use LogsActivity;
	
   	protected $fillable = ['name','alamat','wilayah', 'no_telpon', 'email','provinsi','kabupaten','kecamatan'];

   	//relasi dengan model kelurahan
   	   	public function kelurahan(){
			return $this->hasOne('App\Kelurahan','id','wilayah');
		}

	//relasi dengan model kelurahan
   	   	public function bank_warung(){
			return $this->hasOne('App\BankWarung','warung_id','id');
		}
 
}
