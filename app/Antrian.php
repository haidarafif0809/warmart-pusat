<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
	protected $fillable = ['id','pelanggan_id','no_antrian','warung_id','session_id'];

	public function pelanggan()
	{
		return $this->hasOne('App\User', 'id', 'pelanggan_id');
	}
}
