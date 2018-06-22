<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSettingPromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
               // SETTING PROMO
    	$lihat_setting_promo = Permission::create(['name'=> 'lihat_setting_promo','display_name' => 'Lihat Setting Promo','grup'=>'setting_promo']);
    	$tambah_setting_promo = Permission::create(['name'=> 'tambah_setting_promo','display_name' => 'Tambah Setting Promo','grup'=>'setting_promo']);
    	$edit_setting_promo = Permission::create(['name'=> 'edit_setting_promo','display_name' => 'Edit Setting Promo','grup'=>'setting_promo']);
    	$hapus_setting_promo = Permission::create(['name'=> 'hapus_setting_promo','display_name' => 'Hapus Setting Promo','grup'=>'setting_promo']);
    }
}
