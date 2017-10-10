<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class UserWarung extends Authenticatable
{

    use LogsActivity;
    use Notifiable;
    use LaratrustUserTrait;

    protected $table = 'users';

   	protected $fillable = ['email','password','name', 'alamat', 'wilayah', 'tipe_user', 'id_warung', 'status_konfirmasi', 'no_telp', 'kode_verifikasi'];

   	protected $hidden = [
        'password', 'remember_token',
    ];   

    //relasi dengan model kelurahan
    public function kelurahan(){
      return $this->hasOne('App\Kelurahan','id','wilayah');
    }

    //relasi dengan model warung
    public function warung(){
      return $this->hasOne('App\Warung','id','id_warung');
    }

}