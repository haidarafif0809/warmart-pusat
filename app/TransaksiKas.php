<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaksiKas extends Model
{

    protected $fillable = ['no_faktur', 'jenis_transaksi', 'tipe_transaksi', 'jumlah_masuk', 'jumlah_keluar', 'kas', 'warung_id'];

//HITUNGA TOTAL KAS
    public static function total_kas($request)
    {
        $sum_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))->where('kas', $request->kas)
            ->where('warung_id', Auth::user()->id_warung)->first();
        return $sum_kas->total_kas;
    }

//HITUNGA TOTAL KAS
    public static function total_kas_mutasi($dari_kas)
    {
        $sum_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))->where('kas', $dari_kas)
            ->where('warung_id', Auth::user()->id_warung)->first();
        return $sum_kas->total_kas;
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    //DATA KAS MASUK
    public function scopeDataKasMasuk($query_kas_masuk, $request)
    {
        $query_kas_masuk = TransaksiKas::select(['transaksi_kas.no_faktur', 'transaksi_kas.jenis_transaksi', 'transaksi_kas.jumlah_masuk', 'transaksi_kas.kas', 'transaksi_kas.created_at', 'kas.nama_kas'])
            ->leftJoin('kas', 'kas.id', '=', 'transaksi_kas.kas')
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('transaksi_kas.kas', $request->kas)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.warung_id', Auth::user()->id_warung);

        return $query_kas_masuk;
    }

    //DATA KAS KELUAR
    public function scopeDataKasKeluar($query_kas_keluar, $request)
    {
        $query_kas_keluar = TransaksiKas::select(['transaksi_kas.no_faktur', 'transaksi_kas.jenis_transaksi', 'transaksi_kas.jumlah_keluar', 'transaksi_kas.kas', 'transaksi_kas.created_at', 'kas.nama_kas'])
            ->leftJoin('kas', 'kas.id', '=', 'transaksi_kas.kas')
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('transaksi_kas.kas', $request->kas)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where('transaksi_kas.warung_id', Auth::user()->id_warung);

        return $query_kas_keluar;
    }

    //CARI KAS MASUK
    public function scopeCariKasMasuk($query_kas_masuk, $request)
    {
        $search          = $request->search;
        $query_kas_masuk = TransaksiKas::select(['transaksi_kas.no_faktur', 'transaksi_kas.jenis_transaksi', 'transaksi_kas.jumlah_masuk', 'transaksi_kas.kas', 'transaksi_kas.created_at', 'kas.nama_kas'])
            ->leftJoin('kas', 'kas.id', '=', 'transaksi_kas.kas')
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('transaksi_kas.kas', $request->kas)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('transaksi_kas.jenis_transaksi', 'LIKE', '%' . $search . '%');
            })
            ->where('transaksi_kas.warung_id', Auth::user()->id_warung);

        return $query_kas_masuk;
    }

    //CARI KAS KELUAR
    public function scopeCariKasKeluar($query_kas_keluar, $request)
    {
        $search           = $request->search;
        $query_kas_keluar = TransaksiKas::select(['transaksi_kas.no_faktur', 'transaksi_kas.jenis_transaksi', 'transaksi_kas.jumlah_keluar', 'transaksi_kas.kas', 'transaksi_kas.created_at', 'kas.nama_kas'])
            ->leftJoin('kas', 'kas.id', '=', 'transaksi_kas.kas')
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('transaksi_kas.kas', $request->kas)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('transaksi_kas.jenis_transaksi', 'LIKE', '%' . $search . '%');
            })
            ->where('transaksi_kas.warung_id', Auth::user()->id_warung);

        return $query_kas_keluar;
    }

    //SUBTOTAL KAS MASUK
    public function scopeSubtotalLaporanKasMasukDetail($query_kas_masuk, $request)
    {
        $query_kas_masuk = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as subtotal')])
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('kas', $request->kas)
            ->where('jumlah_keluar', 0)
            ->where('warung_id', Auth::user()->id_warung);

        return $query_kas_masuk;
    }
}
