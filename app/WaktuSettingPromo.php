<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class WaktuSettingPromo extends Model
{
    use Notifiable;
	protected $fillable = ['id_setting_promo', 'waktu_promo','id_warung'];

		public function setting_promo()
	{
		return $this->hasOne('App\SettingPromo', 'id_setting_promo', 'id_setting_promo');
	}

		public function filter_setting_promo()
	{
		return $this->hasOne('App\FilterSettingPromo','id','waktu_promo');
	}
}
