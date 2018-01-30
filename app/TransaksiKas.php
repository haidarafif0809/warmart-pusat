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

    public function tanggalTampil($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "d/m/Y");
        return $date_format;
    }

    //QUERY PENCARIAN DAN PROSES LAPORAN KAS (DETAIL)
    public function queryLaporanKasDetail($request)
    {
        $query_laporan_kas = TransaksiKas::select(['transaksi_kas.no_faktur', 'transaksi_kas.jenis_transaksi', 'transaksi_kas.jumlah_masuk', 'transaksi_kas.jumlah_keluar', 'transaksi_kas.kas', 'transaksi_kas.created_at', 'kas.nama_kas'])
            ->leftJoin('kas', 'kas.id', '=', 'transaksi_kas.kas')
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('transaksi_kas.kas', $request->kas)
            ->where('transaksi_kas.warung_id', Auth::user()->id_warung);

        return $query_laporan_kas;
    }

    //QUERY PENCARIAN DAN PROSES LAPORAN KAS (REKAP)
    public function queryLaporanKasRekap($request)
    {
        $query_laporan_kas = TransaksiKas::select([DB::raw('IFNULL(SUM(transaksi_kas.jumlah_masuk),0) as jumlah_masuk'), DB::raw('IFNULL(SUM(transaksi_kas.jumlah_keluar),0) as jumlah_keluar'), 'transaksi_kas.kas', 'transaksi_kas.created_at', 'kas.nama_kas'])
            ->leftJoin('kas', 'kas.id', '=', 'transaksi_kas.kas')
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(transaksi_kas.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('transaksi_kas.kas', $request->kas)
            ->where('transaksi_kas.warung_id', Auth::user()->id_warung);

        return $query_laporan_kas;
    }

    //QUERY SUBTOTAL LAPORAN KAS (DETAIL)
    public function querySubtotalLaporanKasDetail($request)
    {
        $query_subtotal_laporan_kas = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_masuk'), DB::raw('IFNULL(SUM(jumlah_keluar),0) as jumlah_keluar')])
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('kas', $request->kas)
            ->where('warung_id', Auth::user()->id_warung);

        return $query_subtotal_laporan_kas;
    }

    //DATA KAS MASUK
    public function scopeDataKasMasuk($query_kas_masuk, $request)
    {
        $query_kas_masuk = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi');

        return $query_kas_masuk;
    }

    //DATA KAS KELUAR
    public function scopeDataKasKeluar($query_kas_keluar, $request)
    {
        $query_kas_keluar = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi');

        return $query_kas_keluar;
    }

    //DATA KAS MUTASI MASUK
    public function scopeDataKasMutasiMasuk($query_kas_mutasi_masuk, $request)
    {
        $query_kas_mutasi_masuk = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '=', 'kas_mutasi');

        return $query_kas_mutasi_masuk;
    }

    //DATA KAS MUTASI KELUAR
    public function scopeDataKasMutasiKeluar($query_kas_mutasi_keluar, $request)
    {
        $query_kas_mutasi_keluar = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '=', 'kas_mutasi');

        return $query_kas_mutasi_keluar;
    }

    //CARI KAS MASUK
    public function scopeCariKasMasuk($query_kas_masuk, $request)
    {
        $search          = $request->search;
        $query_kas_masuk = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi')
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('transaksi_kas.jenis_transaksi', 'LIKE', '%' . $search . '%');
            });

        return $query_kas_masuk;
    }

    //CARI KAS KELUAR
    public function scopeCariKasKeluar($query_kas_keluar, $request)
    {
        $search           = $request->search;
        $query_kas_keluar = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi')
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('transaksi_kas.jenis_transaksi', 'LIKE', '%' . $search . '%');
            });

        return $query_kas_keluar;
    }

    //CARI KAS MUTASI (MASUK)
    public function scopeCariKasMutasiMasuk($query_kas_mutasi_masuk, $request)
    {
        $search                 = $request->search;
        $query_kas_mutasi_masuk = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '=', 'kas_mutasi')
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('transaksi_kas.jenis_transaksi', 'LIKE', '%' . $search . '%');
            });

        return $query_kas_mutasi_masuk;
    }

    //CARI KAS MUTASI (KELUAR)
    public function scopeCariKasMutasiKeluar($query_kas_mutasi_keluar, $request)
    {
        $search                  = $request->search;
        $query_kas_mutasi_keluar = $this->queryLaporanKasDetail($request)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '=', 'kas_mutasi')
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('transaksi_kas.jenis_transaksi', 'LIKE', '%' . $search . '%');
            });

        return $query_kas_mutasi_keluar;
    }

    //SUBTOTAL KAS MASUK
    public function scopeSubtotalLaporanKasMasukDetail($query_kas_masuk, $request)
    {
        $query_kas_masuk = $this->querySubtotalLaporanKasDetail($request)
            ->where('jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi');

        return $query_kas_masuk;
    }

    //SUBTOTAL KAS KELUAR
    public function scopeSubtotalLaporanKasKeluarDetail($query_kas_keluar, $request)
    {
        $query_kas_keluar = $this->querySubtotalLaporanKasDetail($request)
            ->where('jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi');

        return $query_kas_keluar;
    }

    //SUBTOTAL KAS MUTASI(MASUK)
    public function scopeSubtotalLaporanKasMutasiMasukDetail($query_kas_mutasi_masuk, $request)
    {
        $query_kas_mutasi_masuk = $this->querySubtotalLaporanKasDetail($request)
            ->where('jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '=', 'kas_mutasi');

        return $query_kas_mutasi_masuk;
    }

    //SUBTOTAL KAS MUTASI(KELUAR)
    public function scopeSubtotalLaporanKasMutasiKeluarDetail($query_kas_mutasi_keluar, $request)
    {
        $query_kas_mutasi_keluar = $this->querySubtotalLaporanKasDetail($request)
            ->where('jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '=', 'kas_mutasi');

        return $query_kas_mutasi_keluar;
    }

    //TOTAL AWAL KAS
    public function scopeTotalAwalLaporan($query_laporan, $request)
    {
        $query_laporan = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as kas_awal')])
            ->where(DB::raw('DATE(created_at)'), '<', $this->tanggalSql($request->dari_tanggal))
            ->where('kas', $request->kas)
            ->where('warung_id', Auth::user()->id_warung);

        return $query_laporan;
    }

    //TOTAL AKHIR KAS
    public function scopeTotalAkhirLaporan($query_laporan, $request)
    {
        $query_laporan = TransaksiKas::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as kas_akhir')])
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('kas', $request->kas)
            ->where('warung_id', Auth::user()->id_warung);

        return $query_laporan;
    }

    //DATA KAS MASUK REKAP
    public function scopeDataKasMasukRekap($query_kas_masuk_rekap, $request)
    {
        $query_kas_masuk_rekap = $this->queryLaporanKasRekap($request)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi')
            ->groupBy(DB::raw('DATE(transaksi_kas.created_at)'));

        return $query_kas_masuk_rekap;
    }

    //DATA KAS KELUAR REKAP
    public function scopeDataKasKeluarRekap($query_kas_keluar_rekap, $request)
    {
        $query_kas_keluar_rekap = $this->queryLaporanKasRekap($request)
            ->where('transaksi_kas.jumlah_masuk', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi')
            ->groupBy(DB::raw('DATE(transaksi_kas.created_at)'));

        return $query_kas_keluar_rekap;
    }

    //CARI KAS MASUK REKAP
    public function scopeCariKasMasukRekap($query_kas_masuk_rekap, $request)
    {
        $search                = $request->search;
        $query_kas_masuk_rekap = $this->queryLaporanKasRekap($request)
            ->where('transaksi_kas.jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi')
            ->where(function ($query) use ($search) {
                $query->orwhere('transaksi_kas.created_at', 'LIKE', '%' . $search . '%');
            })->groupBy(DB::raw('DATE(transaksi_kas.created_at)'));

        return $query_kas_masuk_rekap;
    }

    //SUBTOTAL KAS MASUK REKAP
    public function scopeSubtotalLaporanKasMasukRekap($query_kas_masuk, $request)
    {
        $query_kas_masuk = $this->querySubtotalLaporanKasDetail($request)
            ->where('jumlah_keluar', 0)
            ->where('transaksi_kas.jenis_transaksi', '!=', 'kas_mutasi');

        return $query_kas_masuk;
    }
}
