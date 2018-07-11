<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otoritas extends Model
{
    //
        protected $table = 'role_user';
    	protected $fillable = ['user_id','role_id'];

    	public function user()
		  {
		  	return $this->hasOne('App\User','id','user_id');
		  }

    	public function customer()
		  {
		  	return $this->hasOne('App\Customer','id','user_id');
		  }
		
		public function role()
		  {
			return $this->hasOne('App\Role','id','role_id');
		  }

}
