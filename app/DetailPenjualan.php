<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailPenjualan extends Model
{
    protected $fillable = ['id_penjualan', 'id_produk', 'harga', 'jumlah', 'potongan', 'subtotal'];

    // relasi ke penjualan
    public function penjualan()
    {
        return $this->hasOne('App\Penjualan', 'id', 'id_penjualan');
    }

    // relasi ke produk
    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    // SUBTOTAL LABA KOTOR PENJUALAN POS
    public function scopeSubtotalLaporanLabaKotorPesanan($query_sub_total_penjualan, $request)
    {
        if ($request->pelanggan == "") {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

}
