<?php

use Illuminate\Database\Seeder;
use App\SettingFixel;

class SettingFixelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // SettingFixelSeeder
    	SettingFixel::create(['fixel'=> 'Google','id_pixel' => '#','warung_id'=>'kategori_transaksi']);
    	SettingFixel::create(['fixel'=> 'Facebook','id_pixel' => '#','warung_id'=>'kategori_transaksi']);
    }
}
