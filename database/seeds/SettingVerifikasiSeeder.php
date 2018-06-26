<?php

use App\SettingVerifikasi;
use Illuminate\Database\Seeder;

class SettingVerifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingVerifikasi::create([
            'id_warung' => 1,
            'email'     => 1,
            'no_telp'   => 1,
        ]);
    }
}
