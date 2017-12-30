<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    protected $fillable = ['id_kas', 'id_pesanan', 'id_pelanggan', 'total', 'id_warung'];

    // relasi ke kas
    public function kas()
    {
        return $this->hasOne('App\Kas', 'id', 'id_kas');
    }

    // relasi ke pesanan
    public function pesanan()
    {
        return $this->hasOne('App\PesananPelanggan', 'id', 'id_pesanan');
    }
    // relasi ke pelanggan
    public function pelanggan()
    {
        return $this->hasOne('App\User', 'id', 'id_pelanggan');
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    // LAP. LABA KOTOR PENJUALAN PESANAN
    public function scopeLaporanLabaKotorPesanan($query_laporan_laba_kotor, $request)
    {
        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
            $query_laporan_laba_kotor = Penjualan::select(['penjualans.id', 'penjualans.id_pelanggan', 'penjualans.total', 'penjualans.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualans.id_pelanggan')
                ->where(DB::raw('DATE(penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('penjualans.id', 'desc');
        } else {
            $query_laporan_laba_kotor = Penjualan::select(['penjualans.id', 'penjualans.id_pelanggan', 'penjualans.total', 'penjualans.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualans.id_pelanggan')
                ->where(DB::raw('DATE(penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('users.id', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->orderBy('penjualans.id', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // CARI LAP. LABA KOTOR PENJUALAN PESANAN
    public function scopeCariLaporanLabaKotorPesanan($query_laporan_laba_kotor, $request)
    {
        if ($request->pelanggan == "") {
            $search                   = $request->search;
            $query_laporan_laba_kotor = Penjualan::select(['penjualans.id', 'penjualans.id_pelanggan', 'penjualans.total', 'penjualans.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualans.id_pelanggan')
                ->where(DB::raw('DATE(penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('penjualans.id', 'LIKE', '%' . $search . '%')
                        ->orwhere('users.name', 'LIKE', '%' . $search . '%');
                })->orderBy('penjualans.id', 'desc');
        } else {
            $search                   = $request->search;
            $query_laporan_laba_kotor = Penjualan::select(['penjualans.id', 'penjualans.id_pelanggan', 'penjualans.total', 'penjualans.created_at', 'users.name'])
                ->leftJoin('users', 'users.id', '=', 'penjualans.id_pelanggan')
                ->where(DB::raw('DATE(penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('users.id', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('penjualans.id', 'LIKE', '%' . $search . '%')
                        ->orwhere('users.name', 'LIKE', '%' . $search . '%');
                })->orderBy('penjualans.id', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

}
