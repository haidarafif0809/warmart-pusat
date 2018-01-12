<?php

use App\KasMasuk;
use App\TransaksiKas;
use Illuminate\Database\Seeder;

class KasMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //BUAT NO. FAKTUR
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tahun_terakhir = substr($tahun_sekarang, 2);

        //mengecek jumlah karakter dari bulan sekarang
        $cek_jumlah_bulan = strlen($bulan_sekarang);
        //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
        if ($cek_jumlah_bulan == 1) {
            $data_bulan_terakhir = "0" . $bulan_sekarang;
        } else {
            $data_bulan_terakhir = $bulan_sekarang;
        }
        $no_faktur = "1/KM/" . $data_bulan_terakhir . "/" . $tahun_terakhir;

        //SEEDER KAS MASUK - TRANSAKSI KAS
        KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => 2, 'kategori' => 1, 'jumlah' => 20000000, 'keterangan' => 'Modal Awal', 'id_warung' => 1]);
        TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi' => 'kas_masuk', 'jumlah_masuk' => 20000000, 'kas' => 2, 'warung_id' => 1]);
    }
}
