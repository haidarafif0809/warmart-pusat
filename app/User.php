<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Yajra\Auditable\AuditableTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;
    use AuditableTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','alamat','status_konfirmasi','tipe_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

        public function role()
          {
            return $this->hasOne('App\Otoritas','user_id','id');
          }
}
