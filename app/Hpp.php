<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hpp extends Model
{
    //

    protected $fillable = ['no_faktur', 'no_faktur_hpp_masuk', 'no_faktur_hpp_keluar', 'id_produk', 'jenis_transaksi', 'jumlah_masuk', 'jumlah_keluar', 'harga_unit', 'total_nilai', 'jenis_hpp', 'warung_id', 'created_at'];

    protected $table = 'hpps';

    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }

    public function stok_produk($id_produk)
    {

        $stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])->where('id_produk', $id_produk)
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $sisa_stok_keluar = number_format($stok_produk->jumlah_produk, 2, ',', '.');

    }

    public function nilai($id_produk)
    {

        $nilai_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->where('id_produk', $id_produk)->where('jenis_hpp', 1)->where('warung_id', Auth::user()->id_warung)->first();

        $nilai_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->where('id_produk', $id_produk)->where('jenis_hpp', 2)->where('warung_id', Auth::user()->id_warung)->first();

        $prose_total_persedian = $nilai_masuk->total_masuk - $nilai_keluar->total_keluar;

        $total_persedian = number_format($prose_total_persedian, 2, ',', '.');

        return $total_persedian;

    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function hpp($id_produk)
    {

        $total_nilai = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk'), DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_masuk')])->where('id_produk', $id_produk)->where('jenis_hpp', 1)->where('warung_id', Auth::user()->id_warung)->first();

        if ($total_nilai->total_masuk == 0 || $total_nilai->jumlah_masuk == 0) {
            $hpp = 0;
        } else {
            $proses_hpp = $total_nilai->total_masuk / $total_nilai->jumlah_masuk;
            $hpp        = $proses_hpp;
        }
        return number_format($hpp, 2, ',', '.');

    }

    // HPP LABA KOTOR PENJUALAN POS
    public function scopeHppLaporanLabaKotor($query_sub_hpp, $request, $jenis_transaksi)
    {
        if ($request->pelanggan == "" || $request->pelanggan == null || $request->pelanggan == 0) {
            $query_sub_hpp = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('jenis_transaksi', $jenis_transaksi)
            ->where('warung_id', Auth::user()->id_warung);
        } else {
            if ($jenis_transaksi == "PenjualanPos") {
                $query_sub_hpp = Hpp::select(DB::raw('SUM(hpps.total_nilai) as total_hpp'))
                ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'hpps.no_faktur')
                ->where(DB::raw('DATE(hpps.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('hpps.jenis_transaksi', 'PenjualanPos')
                ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
                ->where('hpps.warung_id', Auth::user()->id_warung);
            } else {
                $query_sub_hpp = Hpp::select(DB::raw('SUM(hpps.total_nilai) as total_hpp'))
                ->leftJoin('penjualans', 'penjualans.id', '=', 'hpps.no_faktur')
                ->where(DB::raw('DATE(hpps.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('hpps.jenis_transaksi', 'penjualan')
                ->where('penjualans.id_pelanggan', $request->pelanggan)
                ->where('hpps.warung_id', Auth::user()->id_warung);
            }
        }

        return $query_sub_hpp;
    }

    // HPP LABA KOTOR /PRODUK PENJUALAN POS
    public function scopeHppLaporanLabaKotorProduk($query_sub_hpp, $request, $jenis_transaksi)
    {
        if ($request->produk == "" || $request->produk == null || $request->produk == 0) {
            $query_sub_hpp = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('jenis_transaksi', $jenis_transaksi)
            ->where('warung_id', Auth::user()->id_warung);
        } else {
            $query_sub_hpp = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('jenis_transaksi', $jenis_transaksi)
            ->where('id_produk', $request->produk)
            ->where('warung_id', Auth::user()->id_warung);
        }

        return $query_sub_hpp;
    }

    // Data Awal
    public function scopeDataAwal($query_masuk, $daftar_produks, $request)
    {
        //HPP MASUK
        $query_masuk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as stok_masuk'), DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])
        ->where('id_produk', $daftar_produks->id)
        ->where('jenis_hpp', 1)
        ->where(DB::raw('DATE(created_at)'), '<', $this->tanggalSql($request->dari_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        //HPP KELUAR
        $query_keluar = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as stok_keluar'), DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])
        ->where('id_produk', $daftar_produks->id)
        ->where('jenis_hpp', 2)
        ->where(DB::raw('DATE(created_at)'), '<', $this->tanggalSql($request->dari_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        $query_masuk['stok_awal'] = $query_masuk->stok_masuk - $query_keluar->stok_keluar;
        $query_masuk['total_awal'] = $query_masuk->total_masuk - $query_keluar->total_keluar;

        return $query_masuk;
    }

    // Data Masuk
    public function scopeDataMasuk($query_masuk, $daftar_produks, $request)
    {
        //HPP MASUK
        $query_masuk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as stok_masuk'), DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])
        ->where('id_produk', $daftar_produks->id)
        ->where('jenis_hpp', 1)
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $query_masuk;
    }

    // Data Keluar
    public function scopeDataKeluar($query_keluar, $daftar_produks, $request)
    {
        //HPP KELUAR
        $query_keluar = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as stok_keluar'), DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])
        ->where('id_produk', $daftar_produks->id)
        ->where('jenis_hpp', 2)
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $query_keluar;
    }

    // Total Awal
    public function scopeTotalAwal($query_masuk, $request)
    {
        //HPP MASUK
        $query_masuk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as stok_masuk'), DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])
        ->where('jenis_hpp', 1)
        ->where(DB::raw('DATE(created_at)'), '<', $this->tanggalSql($request->dari_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        //HPP KELUAR
        $query_keluar = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as stok_keluar'), DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])
        ->where('jenis_hpp', 2)
        ->where(DB::raw('DATE(created_at)'), '<', $this->tanggalSql($request->dari_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        $query_masuk['stok_awal'] = $query_masuk->stok_masuk - $query_keluar->stok_keluar;
        $query_masuk['total_awal'] = $query_masuk->total_masuk - $query_keluar->total_keluar;

        return $query_masuk;
    }

    // Total Masuk
    public function scopeTotalMasuk($query_masuk, $request)
    {
        //HPP MASUK
        $query_masuk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) as stok_masuk'), DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])
        ->where('jenis_hpp', 1)
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $query_masuk;
    }

    // Total Keluar
    public function scopeTotalKeluar($query_keluar, $request)
    {
        //HPP KELUAR
        $query_keluar = Hpp::select([DB::raw('IFNULL(SUM(jumlah_keluar),0) as stok_keluar'), DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])
        ->where('jenis_hpp', 2)
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $query_keluar;
    }

}
