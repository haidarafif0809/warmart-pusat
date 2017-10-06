<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{

    use LogsActivity;
    use Notifiable;
    use LaratrustUserTrait;

    protected $table = 'users';

   	protected $fillable = ['email','password','name', 'alamat', 'wilayah', 'komunitas', 'no_telp','tgl_lahir','tipe_user', 'status_konfirmasi'];

   	protected $hidden = [
        'password', 'remember_token',
    ];
    
   	public function kelurahan(){
		return $this->hasOne('App\Kelurahan','id','wilayah');
	}

    public function getKomunitasAttribute() {
    
    $komunitas = KomunitasCustomer::where('user_id',$this->id); 

        if ($komunitas->count() == 0) {
          return "warmart";
        } 
        else {
          return $komunitas->first()->komunitas->name;
        }
    }

    public function komunitas(){
      return $this->belongsTo('App\KomunitasCustomer','user_id','id');
    }
}
