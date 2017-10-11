<?php

use Illuminate\Database\Seeder;
use App\Satuan;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            //
    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "PCS"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "DUS"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "RIM"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "Unit"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "KG"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "IKAT"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "KARUNG"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "BUNGKUS"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "TABUNG"; 
    $satuan->save();

    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "LITER"; 
    $satuan->save();


    // Membuat sample satuan
    $satuan = new Satuan();
    $satuan->nama_satuan = "GRAM"; 
    $satuan->save();
    }
}
