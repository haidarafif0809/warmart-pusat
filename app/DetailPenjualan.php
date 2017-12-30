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
        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

    // LAP. LABA KOTOR PENJUALAN PRODUK POS
    public function scopeLaporanLabaKotorProdukPesanan($query_laporan_laba_kotor, $request)
    {
        if ($request->produk == "") {
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // CARI LABA KOTOR PENJUALAN PRODUK PESANAN
    public function scopeCariLaporanLabaKotorProdukPesanan($query_laporan_laba_kotor, $request)
    {
        if ($request->produk == "") {
            $search                   = $request->search;
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');})
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        } else {
            $search                   = $request->search;
            $query_laporan_laba_kotor = DetailPenjualan::select(['detail_penjualans.id_produk', 'detail_penjualans.harga', DB::raw('SUM(detail_penjualans.subtotal) as subtotal'), 'barangs.kode_barang', 'barangs.nama_barang'])
                ->leftJoin('barangs', 'barangs.id', '=', 'detail_penjualans.id_produk')
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung)
                ->where(function ($query) use ($search) {
                    $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
                        ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%');})
                ->groupBy('detail_penjualans.id_produk')
                ->orderBy('detail_penjualans.created_at', 'desc');
        }

        return $query_laporan_laba_kotor;
    }

    // SUBTOTAL LABA KOTOR /PRODUK PENJUALAN PESANAN
    public function scopeSubtotalLaporanLabaKotorProdukPesanan($query_sub_total_penjualan, $request)
    {
        if ($request->produk == "") {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualan::select(DB::raw('SUM(detail_penjualans.subtotal) as subtotal'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'detail_penjualans.id_penjualan')
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(detail_penjualans.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('detail_penjualans.id_produk', $request->produk)
                ->where('penjualans.id_warung', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

}
