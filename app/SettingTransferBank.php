<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingTransferBank extends Model
{
	public $fillable = ['nama_bank', 'tampil_bank', 'default_bank', 'warung_id'];


	public static function daftar($warung_id)
	{
		
		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "bca";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 1;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "bca.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "bni";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "bni.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "bri";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "bri.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "btn";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "btn.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "bukopin";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "bukopin.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "mandiri";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "mandiri.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "mega";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "mega.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "muamalat";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "muamalat.png";
		$setting->save();

		$setting               = new SettingTransferBank();
		$setting->nama_bank    = "permata";
		$setting->tampil_bank  = 1;
		$setting->default_bank = 0;
		$setting->warung_id    = $warung_id;
		$setting->logo_bank    = "permata.png";
		$setting->save();
	}

}
