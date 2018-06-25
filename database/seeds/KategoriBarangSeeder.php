<?php

use Illuminate\Database\Seeder;
use App\KategoriBarang;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                //
    // Membuat sample kategori barang
    $kategori_barang = new KategoriBarang();
    $kategori_barang->nama_kategori_barang = "SEMBAKOK"; 
    $kategori_barang->warung_id = 1; 
    $kategori_barang->save();

    // Membuat sample kategori barang
    $kategori_barang = new KategoriBarang();
    $kategori_barang->nama_kategori_barang = "OBAT-OBATAN"; 
    $kategori_barang->warung_id = 1; 
    $kategori_barang->save();

    // Membuat sample kategori barang
    $kategori_barang = new KategoriBarang();
    $kategori_barang->nama_kategori_barang = "ATK"; 
    $kategori_barang->warung_id = 1; 
    $kategori_barang->save();
    
    // Membuat sample kategori barang
    $kategori_barang = new KategoriBarang();
    $kategori_barang->nama_kategori_barang = "UMUM"; 
    $kategori_barang->warung_id = 1; 
    $kategori_barang->save();
    }
}
