<?php

use Illuminate\Database\Seeder;
use App\FilterSettingPromo;

class FilterSettingPromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Hari
        $senin = FilterSettingPromo::create(['name'=> 'senin','display_name' => 'Senin','grup'=>'hari']);
    	$selasa = FilterSettingPromo::create(['name'=> 'selasa','display_name' => 'Selasa','grup'=>'hari']);
    	$rabu = FilterSettingPromo::create(['name'=> 'rabu','display_name' => 'Rabu','grup'=>'hari']);
    	$kamis = FilterSettingPromo::create(['name'=> 'kamis','display_name' => 'Kamis','grup'=>'hari']);
    	$jumat = FilterSettingPromo::create(['name'=> 'jumat','display_name' => 'Jumat','grup'=>'hari']);
    	$sabtu = FilterSettingPromo::create(['name'=> 'sabtu','display_name' => 'Sabtu','grup'=>'hari']);
    	$minggu = FilterSettingPromo::create(['name'=> 'minggu','display_name' => 'Minggu','grup'=>'hari']);  

    	//Jam 
    	$senin = FilterSettingPromo::create(['name'=> '00_00_00_59','display_name' => '00:00 - 00:59','grup'=>'jam']);
    	$selasa = FilterSettingPromo::create(['name'=> '01_00_01_59','display_name' => '01:00 - 01:59','grup'=>'jam']);
    	$rabu = FilterSettingPromo::create(['name'=> '02_00_02_59','display_name' => '02:00 - 02:59','grup'=>'jam']);
    	$kamis = FilterSettingPromo::create(['name'=> '03_00_03_59','display_name' => '03:00 - 03:59','grup'=>'jam']);
    	$jumat = FilterSettingPromo::create(['name'=> '04_00_04_59','display_name' => '04:00 - 04:59','grup'=>'jam']);
    	$sabtu = FilterSettingPromo::create(['name'=> '05_00_05_59','display_name' => '05:00 - 05:59','grup'=>'jam']);
    	$minggu = FilterSettingPromo::create(['name'=> '06_00_06_59','display_name' => '06:00 - 06:59','grup'=>'jam']);
    	$senin = FilterSettingPromo::create(['name'=> '07_00_07_59','display_name' => '07:00 - 07:59','grup'=>'jam']);
    	$selasa = FilterSettingPromo::create(['name'=> '08_00_08_59','display_name' => '08:00 - 08:59','grup'=>'jam']);
    	$rabu = FilterSettingPromo::create(['name'=> '09_00_09_59','display_name' => '09:00 - 09:59','grup'=>'jam']);
    	$kamis = FilterSettingPromo::create(['name'=> '10_00_10_59','display_name' => '10:00 - 10:59','grup'=>'jam']);
    	$jumat = FilterSettingPromo::create(['name'=> '11_00_11_59','display_name' => '11:00 - 11:59','grup'=>'jam']);
    	$sabtu = FilterSettingPromo::create(['name'=> '12_00_12_59','display_name' => '12:00 - 12:59','grup'=>'jam']);
    	$minggu = FilterSettingPromo::create(['name'=> '13_00_13_59','display_name' => '13:00 - 13:59','grup'=>'jam']); 
    	$senin = FilterSettingPromo::create(['name'=> '14_00_14_59','display_name' => '14:00 - 14:59','grup'=>'jam']);
    	$selasa = FilterSettingPromo::create(['name'=> '15_00_15_59','display_name' => '15:00 - 15:59','grup'=>'jam']);
    	$rabu = FilterSettingPromo::create(['name'=> '16_00_16_59','display_name' => '16:00 - 16:59','grup'=>'jam']);
    	$kamis = FilterSettingPromo::create(['name'=> '17_00_17_59','display_name' => '17:00 - 17:59','grup'=>'jam']);
    	$jumat = FilterSettingPromo::create(['name'=> '18_00_18_59','display_name' => '18:00 - 18:59','grup'=>'jam']);
    	$sabtu = FilterSettingPromo::create(['name'=> '19_00_19_59','display_name' => '19:00 - 19:59','grup'=>'jam']);
    	$minggu = FilterSettingPromo::create(['name'=> '20_00_20_59','display_name' => '20:00 - 20:59','grup'=>'jam']);   
    	$jumat = FilterSettingPromo::create(['name'=> '21_00_21_59','display_name' => '21:00 - 21:59','grup'=>'jam']);
    	$sabtu = FilterSettingPromo::create(['name'=> '22_00_22_59','display_name' => '22:00 - 22:59','grup'=>'jam']);
    	$minggu = FilterSettingPromo::create(['name'=> '23_00_23_59','display_name' => '23:00 - 23:59','grup'=>'jam']);  	

    }
}
