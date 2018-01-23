<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class PembayaranHutang extends Model
{
    //
        use AuditableTrait;
    protected $fillable = ['no_faktur_pembayaran', 'total', 'suplier_id', 'cara_bayar', 'warung_id', 'keterangan'];
    protected $primaryKey = 'id_pembayaran_hutang';

    public static function no_faktur($warung_id)
    {
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

        //ambil bulan dan no_faktur dari tanggal pembayaran_piutang terakhir
        $pembayaran_piutang = PembayaranHutang::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur_pembayaran'])->where('warung_id', $warung_id)->orderBy('id_pembayaran_hutang', 'DESC')->first();

        if ($pembayaran_piutang != null) {
            $pisah_nomor = explode("/", $pembayaran_piutang->no_faktur_pembayaran);
            $ambil_nomor = $pisah_nomor[0];
            $bulan_akhir = $pembayaran_piutang->bulan;
        } else {
            $ambil_nomor = 1;
            $bulan_akhir = 13;
        }
        /*jika bulan terakhir dari pembayaran_piutang tidak sama dengan bulan sekarang,
        maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
         */
        if ($bulan_akhir != $bulan_sekarang) {
            $no_faktur = "1/PH/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
            $nomor     = 1 + $ambil_nomor;
            $no_faktur = $nomor . "/PH/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        }

        return $no_faktur;
    }


        public function getWaktuAttribute()
    {
        $tanggal       = date($this->created_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d-m-Y H:i:s");
        return $date_terbalik;
    }
        public function getPemisahTotalAttribute()
    {
        return number_format($this->total, 2, ',', '.');
    }
}
