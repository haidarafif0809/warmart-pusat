<?php

use App\KategoriTransaksi;
use Illuminate\Database\Seeder;

class KategoriTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriTransaksi::create(['nama_kategori_transaksi' => 'Modal', 'id_warung' => 1]);
        KategoriTransaksi::create(['nama_kategori_transaksi' => 'Biaya Gaji', 'id_warung' => 1]);
        KategoriTransaksi::create(['nama_kategori_transaksi' => 'Biaya Listrik', 'id_warung' => 1]);
        KategoriTransaksi::create(['nama_kategori_transaksi' => 'Biaya Transportaso', 'id_warung' => 1]);
    }
}
