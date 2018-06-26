<?php

use Illuminate\Database\Seeder;
use App\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // sample barang 1
            $barang = new Barang();
            $barang->kode_barang = "B001";
            $barang->kode_barcode = "5700011001";
            $barang->nama_barang = "KECAP ASIN ABC";
            $barang->harga_beli = "55000";
            $barang->harga_jual = "6500";
            $barang->satuan_id = "1";
            $barang->kategori_barang_id = "1";
            $barang->status_aktif = "1";
            $barang->hitung_stok = "1";
            $barang->id_warung = "1";
            $barang->created_by = "1";
            $barang->updated_by = "1";
            $barang->save();
    }
}
