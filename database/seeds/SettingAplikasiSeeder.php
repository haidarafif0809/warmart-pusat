<?php

use App\SettingAplikasi;
use Illuminate\Database\Seeder;

class SettingAplikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting_aplikasi                = new SettingAplikasi();
        $setting_aplikasi->tipe_aplikasi = 1;
        $setting_aplikasi->save();
    }
}
