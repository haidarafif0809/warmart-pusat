<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Komunitas extends Model
{
	use Notifiable;
    use LaratrustUserTrait;
    use LogsActivity;
    //

    protected $table = 'users'; 
   	protected $fillable = ['email','password','name', 'alamat', 'wilayah','no_telp','nama_bank', 'no_rekening','an_rekening','tipe_user','status_konfirmasi','id_warung'];


    protected $hidden = [
        'password', 'remember_token',
    ];


       public function kelurahan(){
		return $this->hasOne('App\Kelurahan','id','wilayah');
		}


       public function role()
          {
            return $this->hasOne('App\Otoritas','user_id','id');
          }

    	public function getLinkAfiliasiAttribute() {

      	 $link_afiliasi = url("aff/".$this->id);
       	 return $link_afiliasi;
        
   		}

        public function warung()
          {
            return $this->hasOne('App\Warung','id','id_warung');
          }
}
