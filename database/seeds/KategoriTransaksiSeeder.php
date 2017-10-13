<?php

use Illuminate\Database\Seeder;
use App\KategoriTransaksi;

class KategoriTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriTransaksi::create(['nama_kategori_transaksi' => 'BIAYA OPERASIONAL']);
        KategoriTransaksi::create(['nama_kategori_transaksi' => 'GAJI KARYAWAN']);
    }
}
