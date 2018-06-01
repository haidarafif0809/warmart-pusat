<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;


class ReturPenjualan extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_retur', 'id_pelanggan', 'keterangan', 'total', 'total_bayar','tax','potongan','id_kas','ppn','warung_id','created_at'];
    protected $primaryKey = 'id_retur_penjualan';


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
        $retur_penjualan = ReturPenjualan::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur_retur'])->where('warung_id', $warung_id)->orderBy('id_retur_penjualan', 'DESC')->first();

        if ($retur_penjualan != null) {
            $pisah_nomor = explode("/", $retur_penjualan->no_faktur_retur);
            $ambil_nomor = $pisah_nomor[0];
            $bulan_akhir = $retur_penjualan->bulan;
        } else {
            $ambil_nomor = 1;
            $bulan_akhir = 13;
        }
        /*jika bulan terakhir dari retur_penjualan tidak sama dengan bulan sekarang,
        maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
         */
        if ($bulan_akhir != $bulan_sekarang) {
            $no_faktur = "1/RJ/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
        } else {
            $nomor     = 1 + $ambil_nomor;
            $no_faktur = $nomor . "/RJ/" . $data_bulan_terakhir . "/" . $tahun_terakhir;
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

    public function getWaktuEditAttribute()
    {
        $tanggal       = date($this->updated_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d-m-Y H:i:s");
        return $date_terbalik;
    }

    public function getTotalSeparatorAttribute()
    {
        $total_bayar = number_format($this->total_bayar, 2, ',', '.');
        return $total_bayar;
    }


        // DATA RETUR PENJUALAN
    public function scopeDataReturPenjualan($query_retur_penjualan)
    {
        $query_retur_penjualan = ReturPenjualan::select('retur_penjualans.id_retur_penjualan as id', 'retur_penjualans.no_faktur_retur as no_faktur', 'retur_penjualans.created_at', 'retur_penjualans.updated_at', 'retur_penjualans.total_bayar as total_bayar', 'kas.nama_kas as nama_kas', 'userbuat.name as petugas', 'retur_penjualans.keterangan as keterangan','userpelanggan.name as pelanggan')
            ->leftJoin('kas', 'retur_penjualans.id_kas', '=', 'kas.id')
            ->leftJoin('users AS userbuat', 'retur_penjualans.created_by', '=', 'userbuat.id')
            ->leftJoin('users AS userpelanggan', 'retur_penjualans.id_pelanggan', '=', 'userpelanggan.id')
            ->where('retur_penjualans.warung_id', Auth::user()->id_warung)
            ->orderBy('retur_penjualans.id_retur_penjualan');

        return $query_retur_penjualan;
    }

    public function scopePencarianDataReturPenjualan($query_retur_penjualan,$request)
    {
        $search = $request->search;
        $query_retur_penjualan = ReturPenjualan::select('retur_penjualans.id_retur_penjualan as id', 'retur_penjualans.no_faktur_retur as no_faktur', 'retur_penjualans.created_at', 'retur_penjualans.updated_at', 'retur_penjualans.total_bayar as total_bayar', 'kas.nama_kas as nama_kas', 'userbuat.name as petugas', 'retur_penjualans.keterangan as keterangan','userpelanggan.name as pelanggan')
            ->leftJoin('kas', 'retur_penjualans.id_kas', '=', 'kas.id')
            ->leftJoin('users AS userbuat', 'retur_penjualans.created_by', '=', 'userbuat.id')
            ->leftJoin('users AS userpelanggan', 'retur_penjualans.id_pelanggan', '=', 'userpelanggan.id')
            ->where('retur_penjualans.warung_id', Auth::user()->id_warung)->where(function ($query) use ($search) {
                    $query->orwhere('retur_penjualans.no_faktur_retur', 'LIKE', '%' . $search . '%')
                          ->orwhere('kas.nama_kas', 'LIKE', '%' . $search . '%')
                           ->orwhere('retur_penjualans.total_bayar', 'LIKE', '%' . $search . '%');
                })->orderBy('retur_penjualans.id_retur_penjualan');
            

        return $query_retur_penjualan;        
    }

}
