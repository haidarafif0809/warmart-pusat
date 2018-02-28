<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class KategoriTransaksi extends Model
{
    use AuditableTrait;
    use LogsActivity;

    protected $fillable = ['nama_kategori_transaksi', 'id_warung'];

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function queryFilterPeriodeKategoriTransaksi($query, $request)
    {
        $query->select('kategori_transaksis.id', 'kategori_transaksis.nama_kategori_transaksi', DB::raw('IFNULL(SUM(kas_masuks.jumlah), 0) AS transaksi_masuk'), DB::raw('IFNULL(SUM(kas_keluars.jumlah), 0) AS transaksi_keluar'))
            ->leftJoin('kas_masuks', 'kas_masuks.kategori', '=', 'kategori_transaksis.id')
            ->leftJoin('kas_keluars', 'kas_keluars.kategori', '=', 'kategori_transaksis.id');

        return $query;
    }

    public function scopeFilterKategori($query, $request)
    {
        $query = $this->queryFilterPeriodeKategoriTransaksi($query, $request)
            ->orWhere(function ($query_masuk) use ($request) {
                $query_masuk
                    ->where('kategori_transaksis.id_warung', Auth::user()->id_warung)
                    ->where(DB::raw('DATE(kas_masuks.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                    ->where(DB::raw('DATE(kas_masuks.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));
            })
            ->orWhere(function ($query_keluar) use ($request) {
                $query_keluar
                    ->where('kategori_transaksis.id_warung', Auth::user()->id_warung)
                    ->where(DB::raw('DATE(kas_keluars.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                    ->where(DB::raw('DATE(kas_keluars.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal));
            })
            ->groupBy('kategori_transaksis.nama_kategori_transaksi');

        return $query;

    }

    public function scopeCariFilterKategori($query, $request)
    {
        $query = $this->queryFilterPeriodeKategoriTransaksi($query, $request)
            ->orWhere(function ($query_masuk) use ($request) {
                $query_masuk
                    ->where('kategori_transaksis.id_warung', Auth::user()->id_warung)
                    ->where(DB::raw('DATE(kas_masuks.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                    ->where(DB::raw('DATE(kas_masuks.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                    ->where('kategori_transaksis.nama_kategori_transaksi', 'LIKE', '%' . $request->search . '%');
            })
            ->orWhere(function ($query_keluar) use ($request) {
                $query_keluar
                    ->where('kategori_transaksis.id_warung', Auth::user()->id_warung)
                    ->where(DB::raw('DATE(kas_keluars.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                    ->where(DB::raw('DATE(kas_keluars.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                    ->where('kategori_transaksis.nama_kategori_transaksi', 'LIKE', '%' . $request->search . '%');
            })
            ->groupBy('kategori_transaksis.nama_kategori_transaksi');

        return $query;

    }
}
