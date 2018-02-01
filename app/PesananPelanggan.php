<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

}
