<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class SettingPembedaAplikasi extends Model
{
	protected $fillable = ['warung_id','app_address'];

	public function scopeUrlSekarang($query){

		$address_current = str_replace("//", "", Request::url());
		$query = SettingPembedaAplikasi::select('warung_id', 'app_address')->where("app_address", $address_current);
		return $query;

	}
}
