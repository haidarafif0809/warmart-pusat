<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingJasaPengiriman extends Model
{
	public $fillable = ['jasa_pengiriman', 'tampil_jasa_pengiriman', 'default_jasa_pengiriman', 'warung_id'];

	public static function daftar($warung_id)
	{
		$setting                          = new SettingJasaPengiriman();
		$setting->jasa_pengiriman         = "jne";
		$setting->tampil_jasa_pengiriman  = 1;
		$setting->default_jasa_pengiriman = 1;
		$setting->warung_id               = $warung_id;
		$setting->logo_jasa               = "jne.png";
		$setting->save();

		$setting                          = new SettingJasaPengiriman();
		$setting->jasa_pengiriman         = "tiki";
		$setting->tampil_jasa_pengiriman  = 1;
		$setting->default_jasa_pengiriman = 0;
		$setting->warung_id               = $warung_id;
		$setting->logo_jasa               = "tiki.png";
		$setting->save();

		$setting                          = new SettingJasaPengiriman();
		$setting->jasa_pengiriman         = "pos";
		$setting->tampil_jasa_pengiriman  = 1;
		$setting->default_jasa_pengiriman = 0;
		$setting->warung_id               = $warung_id;
		$setting->logo_jasa               = "pos-indo.png";
		$setting->save();

		$setting                          = new SettingJasaPengiriman();
		$setting->jasa_pengiriman         = "cod";
		$setting->tampil_jasa_pengiriman  = 1;
		$setting->default_jasa_pengiriman = 0;
		$setting->warung_id               = $warung_id;
		$setting->logo_jasa               = "COD.png";
		$setting->save();

		$setting                          = new SettingJasaPengiriman();
		$setting->jasa_pengiriman         = "ojek";
		$setting->tampil_jasa_pengiriman  = 1;
		$setting->default_jasa_pengiriman = 0;
		$setting->warung_id               = $warung_id;
		$setting->logo_jasa               = "ojek.png";
		$setting->save();
	}

}
