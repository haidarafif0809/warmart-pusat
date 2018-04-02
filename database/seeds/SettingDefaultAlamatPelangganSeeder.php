<?php

use Illuminate\Database\Seeder;
use App\SettingDefaultAlamatPelanggan;


class SettingDefaultAlamatPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	SettingDefaultAlamatPelanggan::create([
    		"provinsi" => 18,
    		"kabupaten" => 1801,
    		"status_aktif"   => 0
    	]);
    }
}
