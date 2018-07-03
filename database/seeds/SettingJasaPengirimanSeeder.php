<?php

use App\SettingJasaPengiriman;
use Illuminate\Database\Seeder;

class SettingJasaPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting                          = new SettingJasaPengiriman();
        $setting->jasa_pengiriman         = "jne";
        $setting->tampil_jasa_pengiriman  = 1;
        $setting->default_jasa_pengiriman = 1;
        $setting->warung_id               = 1;
        $setting->logo_jasa               = "jne.png";
        $setting->save();

        $setting                          = new SettingJasaPengiriman();
        $setting->jasa_pengiriman         = "tiki";
        $setting->tampil_jasa_pengiriman  = 1;
        $setting->default_jasa_pengiriman = 0;
        $setting->warung_id               = 1;
        $setting->logo_jasa               = "tiki.png";
        $setting->save();

        $setting                          = new SettingJasaPengiriman();
        $setting->jasa_pengiriman         = "pos";
        $setting->tampil_jasa_pengiriman  = 1;
        $setting->default_jasa_pengiriman = 0;
        $setting->warung_id               = 1;
        $setting->logo_jasa               = "pos-indo.png";
        $setting->save();

        $setting                          = new SettingJasaPengiriman();
        $setting->jasa_pengiriman         = "cod";
        $setting->tampil_jasa_pengiriman  = 1;
        $setting->default_jasa_pengiriman = 0;
        $setting->warung_id               = 1;
        $setting->logo_jasa               = "COD.png";
        $setting->save();

        $setting                          = new SettingJasaPengiriman();
        $setting->jasa_pengiriman         = "ojek";
        $setting->tampil_jasa_pengiriman  = 1;
        $setting->default_jasa_pengiriman = 0;
        $setting->warung_id               = 1;
        $setting->logo_jasa               = "ojek.png";
        $setting->save();
    }
}
