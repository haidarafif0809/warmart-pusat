<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laratrust\Traits\LaratrustUserTrait;
use Yajra\Auditable\AuditableTrait;

class TransaksiPiutang extends Model
{

    use LaratrustUserTrait;
    use AuditableTrait;

    protected $fillable = ['jenis_transaksi', 'id_transaksi', 'pelanggan_id', 'no_faktur', 'jumlah_masuk', 'jumlah_keluar', 'warung_id'];

    public function getWaktuAttribute()
    {
        $tanggal       = date($this->created_at);
        $date          = date_create($tanggal);
        $date_terbalik = date_format($date, "d-m-Y H:i:s");
        return $date_terbalik;
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    // DATA PENJUALAN PIUTANG
    public function scopeDataPenjualanPiutang($query_penjualan_piutang)
    {
        $query_penjualan_piutang = TransaksiPiutang::select(['penjualan_pos.id', 'penjualan_pos.no_faktur', 'penjualan_pos.pelanggan_id', 'penjualan_pos.kredit', 'penjualan_pos.tanggal_jt_tempo', 'users.name', DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) AS sisa_piutang')])
            ->leftJoin('penjualan_pos', 'transaksi_piutangs.id_transaksi', '=', 'penjualan_pos.id')
            ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
            ->where('penjualan_pos.status_penjualan', 'Piutang')
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_piutangs.id_transaksi')
            ->having('sisa_piutang', '>', 0);

        return $query_penjualan_piutang;
    }

    // DATA PENJUALAN PIUTANG /FAKTUR
    public function scopeDataPenjualanPiutangPerFaktur($query_penjualan_piutang, $id)
    {
        $query_penjualan_piutang = TransaksiPiutang::select(['penjualan_pos.id', 'penjualan_pos.no_faktur', 'penjualan_pos.pelanggan_id', 'penjualan_pos.kredit', 'penjualan_pos.tanggal_jt_tempo', 'users.name', DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) AS sisa_piutang')])
            ->leftJoin('penjualan_pos', 'transaksi_piutangs.id_transaksi', '=', 'penjualan_pos.id')
            ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
            ->where('penjualan_pos.status_penjualan', 'Piutang')
            ->where('penjualan_pos.id', $id)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_piutangs.id_transaksi');

        return $query_penjualan_piutang;
    }

         // DATA LAPORAN PIUTANG BEREDAR
public function scopeGetDataPiutangBeredar($data_pelanggan_piutang,$request)
{
    if ($request->pelanggan != "") {
        $data_pelanggan_piutang = $this->queryLaporanPiutangBeredar($request)
        ->where('penjualan_pos.status_penjualan', 'Piutang')
        ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
        ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
        ->groupBy('transaksi_piutangs.id_transaksi');
    }else{
     $data_pelanggan_piutang = $this->queryLaporanPiutangBeredar($request)
     ->where('penjualan_pos.status_penjualan', 'Piutang')
     ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
     ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
     ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
     ->groupBy('transaksi_piutangs.id_transaksi');
 }  
 return $data_pelanggan_piutang;
}

public function queryLaporanPiutangBeredar($request)
{
  $data_pelanggan_piutang =  TransaksiPiutang::select(['penjualan_pos.pelanggan_id as pelanggan_id','users_pelanggan.name as nama_pelanggan','users_petugas.name as name','transaksi_piutangs.created_at','penjualan_pos.id', 'penjualan_pos.no_faktur','penjualan_pos.created_at', 'penjualan_pos.pelanggan_id',DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) AS total'), 'penjualan_pos.tanggal_jt_tempo as tanggal_jt_tempo',DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) AS sisa_piutang'),DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) AS pembayaran'),DB::raw('DATEDIFF(penjualan_pos.tanggal_jt_tempo,DATE(NOW())) AS usia_piutang')])
  ->leftJoin('penjualan_pos', 'transaksi_piutangs.id_transaksi', '=', 'penjualan_pos.id')
  ->leftJoin('users as users_petugas','transaksi_piutangs.created_by','=','users_petugas.id')            
  ->leftJoin('users as users_pelanggan','penjualan_pos.pelanggan_id','=','users_pelanggan.id');

  return $data_pelanggan_piutang;
}


//           // DATA LAPORAN PIUTANG BEREDAR
public function scopeCariDataPiutangBeredar($data_pelanggan_piutang,$request)
{
        $search = $request->search;
        if ($request->pelanggan != "") {
            $data_pelanggan_piutang = $this->queryLaporanPiutangBeredar($request)
            ->where('penjualan_pos.status_penjualan', 'Piutang')
            ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
            ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orWhere('penjualan_pos.no_faktur', 'LIKE', '%' . $search . '%')
                ->orWhere('users_pelanggan.name', 'LIKE', '%' . $search . '%');
            })->groupBy('transaksi_piutangs.id_transaksi');
        }else{
             $data_pelanggan_piutang = $this->queryLaporanPiutangBeredar($request)
             ->where('penjualan_pos.status_penjualan', 'Piutang')
             ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
             ->where(DB::raw('DATE(transaksi_piutangs.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
             ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
             ->where(function ($query) use ($search) {
                $query->orWhere('penjualan_pos.no_faktur', 'LIKE', '%' . $search . '%')
                ->orWhere('users_pelanggan.name', 'LIKE', '%' . $search . '%');
            })->groupBy('transaksi_piutangs.id_transaksi');
        }  
    return $data_pelanggan_piutang;
}

}
