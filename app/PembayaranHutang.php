<?php

namespace App;

use Auth;
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

        public function scopeDatapembayaranHutang($query_pembayaran_hutang)
    {
        $query_pembayaran_hutang =  PembayaranHutang::select('pembayaran_hutangs.id_pembayaran_hutang as id', 'pembayaran_hutangs.no_faktur_pembayaran as no_faktur', 'supliers.nama_suplier as nama_suplier', 'pembayaran_hutangs.created_at as created_at', 'pembayaran_hutangs.total as total', 'kas.nama_kas as nama_kas','users.name as name','pembayaran_hutangs.keterangan as keterangan')->leftJoin('supliers', 'pembayaran_hutangs.suplier_id', '=', 'supliers.id')->leftJoin('kas', 'pembayaran_hutangs.cara_bayar', '=', 'kas.id')->leftJoin('users', 'pembayaran_hutangs.created_by', '=', 'users.id')
            ->where('pembayaran_hutangs.warung_id', Auth::user()->id_warung)->orderBy('pembayaran_hutangs.id_pembayaran_hutang');

        return $query_pembayaran_hutang;
    }

        // PENCARIAN TBS PEMBAYARAN hutang
    public function scopeDatacariPembayaranHutang($query_pembayaran_hutang, $request)
    {
        $search    = $request->search;
        $query_pembayaran_hutang = PembayaranHutang::select('pembayaran_hutangs.id_pembayaran_hutang as id', 'pembayaran_hutangs.no_faktur_pembayaran as no_faktur', 'supliers.nama_suplier as nama_suplier', 'pembayaran_hutangs.created_at as created_at', 'pembayaran_hutangs.total as total', 'kas.nama_kas as nama_kas','users.name as name','pembayaran_hutangs.keterangan as keterangan')->leftJoin('supliers', 'pembayaran_hutangs.suplier_id', '=', 'supliers.id')->leftJoin('kas', 'pembayaran_hutangs.cara_bayar', '=', 'kas.id')->leftJoin('users', 'pembayaran_hutangs.created_by', '=', 'users.id')
            ->where('pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('pembayaran_hutangs.no_faktur_pembayaran', 'LIKE', '%' . $search . '%')
                     ->orwhere('pembayaran_hutangs.keterangan', 'LIKE', '%' . $search . '%');
            })->orderBy('pembayaran_hutangs.id_pembayaran_hutang', 'desc');

        return $query_pembayaran_hutang;
    }

            public function scopeQueryCetak($query_pembayaran_hutang,$id)
    {
        $query_pembayaran_hutang =  PembayaranHutang::select('warungs.name AS nama_warung','warungs.alamat AS alamat_warung','warungs.no_telpon AS no_telp_warung','pembayaran_hutangs.id_pembayaran_hutang as id', 'pembayaran_hutangs.no_faktur_pembayaran as no_faktur', 'supliers.nama_suplier as nama_suplier', DB::raw('DATE_FORMAT(pembayaran_hutangs.created_at, "%d/%m/%Y %H:%i:%s") as waktu_beli'), 'pembayaran_hutangs.total as total', 'kas.nama_kas as nama_kas','users.name as name','pembayaran_hutangs.keterangan as keterangan')
            ->leftJoin('supliers', 'pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->leftJoin('kas', 'pembayaran_hutangs.cara_bayar', '=', 'kas.id')
            ->leftJoin('users', 'pembayaran_hutangs.created_by', '=', 'users.id')
            ->leftJoin('warungs','pembayaran_hutangs.warung_id','=','warungs.id')
            ->where('pembayaran_hutangs.id_pembayaran_hutang',$id)
            ->where('pembayaran_hutangs.warung_id', Auth::user()->id_warung)->orderBy('pembayaran_hutangs.id_pembayaran_hutang');

        return $query_pembayaran_hutang;
    }
}
