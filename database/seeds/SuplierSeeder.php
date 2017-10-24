<?php

use Illuminate\Database\Seeder;
use App\Suplier;

class SuplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // sample suplier
            $suplier = new Suplier();
            $suplier->nama_suplier = "PT MAJU MUNDUR";
            $suplier->alamat = "Jln selayang pandang";
            $suplier->no_telp = "085249452658";
            $suplier->warung_id = "1";
    }
}
