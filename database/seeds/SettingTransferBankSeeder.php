<?php

use App\SettingTransferBank;
use Illuminate\Database\Seeder;

class SettingTransferBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "bca";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 1;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "bca.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "bni";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "bni.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "bri";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "bri.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "btn";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "btn.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "bukopin";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "bukopin.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "mandiri";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "mandiri.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "mega";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "mega.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "muamalat";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "muamalat.png";
        $setting->save();

        $setting               = new SettingTransferBank();
        $setting->nama_bank    = "permata";
        $setting->tampil_bank  = 1;
        $setting->default_bank = 0;
        $setting->warung_id    = 1;
        $setting->logo_bank    = "permata.png";
        $setting->save();
    }
}
