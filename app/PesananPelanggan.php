<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\DB;

class PesananPelanggan extends Model
{
    protected $fillable = ['id_pelanggan', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'konfirmasi_pesanan', 'id_warung','kurir','layanan_kurir','metode_pembayaran','biaya_kirim','bank_transfer'];

    // relasi ke pelanggan
    public function pelanggan()
    {
        return $this->hasOne('App\User', 'id', 'id_pelanggan');
    }
    // relasi ke pelanggan
    public function warung()
    {
        return $this->hasOne('App\Warung', 'id', 'id_warung');
    }
    public function getWaktuPesanAttribute()
    {
        $tanggal       = date($this->created_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d/m/Y H:i:s");
        return $date_terbalik;
    }

            public function scopeQueryCetak($query, $id)
    {
        $query->select('w.name AS nama_warung', 'w.alamat AS alamat_warung', 'p.name AS pelanggan', 'pesanan_pelanggans.subtotal AS total', DB::raw('DATE_FORMAT(pesanan_pelanggans.created_at, "%d/%m/%Y %H:%i:%s") as waktu_jual'), 'w.no_telpon AS no_telp_warung','pesanan_pelanggans.id AS id', 'pesanan_pelanggans.id_pelanggan AS id_pelanggan')
            ->leftJoin('warungs AS w', 'pesanan_pelanggans.id_warung', '=', 'w.id')
            ->leftJoin('users AS p', 'p.id', '=', 'pesanan_pelanggans.id_pelanggan')
            ->where('pesanan_pelanggans.id', $id);
        return $query;
    }

}
