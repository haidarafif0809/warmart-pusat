<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PesananPelanggan extends Model
{
    protected $fillable = ['id_pelanggan', 'nama_pemesan', 'no_telp_pemesan', 'alamat_pemesan', 'jumlah_produk', 'subtotal', 'konfirmasi_pesanan', 'id_warung','kurir','layanan_kurir','metode_pembayaran','biaya_kirim','bank_transfer','kode_unik_transfer'];

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

    public function getWaktuBarangSampaiAttribute()
    {
        if ($this->layanan_kurir != "") {

            $layanan_kurir =explode(" | ", $this->layanan_kurir);
            $perkiraan_sampai = $layanan_kurir[1];

            if ($this->kurir == "pos") {
                $waktu_pengiriman = $perkiraan_sampai;
            }else{
                $waktu_pengiriman = $perkiraan_sampai . " HARI";
            }

            return $waktu_pengiriman;
        }

    }

    public function scopeQueryCetak($query, $id)
    {
        $query->select('w.name AS nama_warung', 'w.alamat AS alamat_warung', 'p.name AS pelanggan', 'pesanan_pelanggans.subtotal AS total', DB::raw('DATE_FORMAT(pesanan_pelanggans.created_at, "%d/%m/%Y %H:%i:%s") as waktu_jual'), 'w.no_telpon AS no_telp_warung','pesanan_pelanggans.id AS id', 'pesanan_pelanggans.id_pelanggan AS id_pelanggan')
        ->leftJoin('warungs AS w', 'pesanan_pelanggans.id_warung', '=', 'w.id')
        ->leftJoin('users AS p', 'p.id', '=', 'pesanan_pelanggans.id_pelanggan')
        ->where('pesanan_pelanggans.id', $id);
        return $query;
    }

    public static function kodeUnikTransfer(){
        // untuk mengambil id terakhir dari table pesanan pelanggan => limit 1
        $pesanan = PesananPelanggan::select('id')->orderBy('id', 'DESC')->limit(1);
        // jika data masih kosong
        if ($pesanan->count() == 0) {
            // id pesanan berarti sama dengan nol,
            // id pesanan = nol + 1
            $id_pesanan = $pesanan->count() + 1;
        }else{// jika tidak
            // id pesanan = id pesanan yg terakhir ditambah 1
            $id_pesanan = $pesanan->first()->id + 1;
        }

        // untuk mengetahui kode unik , kita gunakan modulus 1000 id pesanan
        // hasil nya akan berurut mulai dari 1 sampai 999 , jika id pesanan nya sudah mencapai 1000,2000.3000 dan sterusnya maka kode unik akan kembali 0 
        // jka masih bingung, silakan diicoba 
        return $kode_unik = $id_pesanan % 1000;

    }

    public function kirimEmailKonfirmasiPesananKePelanggan($request,$nama_warung){
        $data = $request;
        $pesanan_pelanggan = $this;
        $detail_pesanan = DetailPesananPelanggan::with(['produk'])->where('id_pesanan_pelanggan',$pesanan_pelanggan->id)->get();
        Mail::send('auth.emails.email_konfirmasi_pesanan', compact('data','pesanan_pelanggan','detail_pesanan'), function ($message) use ($data,$nama_warung){

            $message->from('verifikasi@andaglos.id', $nama_warung);
            $message->to($data->email, $data->name)->subject('Konfirmasi Pesanan');

        });
    }

}
