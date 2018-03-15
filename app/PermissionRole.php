<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{ 
	protected $table = 'permission_role';
	protected $fillable = ['permission_id','role_id'];

	public function permissions()
	{
		return $this->hasOne('App\Permission','id','permission_id');
	}

	



}
