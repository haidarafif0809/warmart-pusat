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

    public function stok_produk_tanggal($id_produk, $tanggal)
    {

        $stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])
        ->where('id_produk', $id_produk)
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $sisa_stok_keluar = $stok_produk->jumlah_produk;

    }

    public function nilai_tanggal($id_produk, $tanggal)
    {

        $nilai_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])
        ->where('id_produk', $id_produk)
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))->where('jenis_hpp', 1)
        ->where('warung_id', Auth::user()->id_warung)->first();

        $nilai_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])
        ->where('id_produk', $id_produk)
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
        ->where('jenis_hpp', 2)->where('warung_id', Auth::user()->id_warung)->first();

        $prose_total_persedian = $nilai_masuk->total_masuk - $nilai_keluar->total_keluar;

        $total_persedian = number_format($prose_total_persedian, 2, ',', '.');

        return $total_persedian;

    }

    public function hpp_tanggal($id_produk, $tanggal)
    {

        $hpp = Hpp::select('harga_unit')
        ->where('id_produk', $id_produk)
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
        ->where('warung_id', Auth::user()->id_warung);

        // CEK PRODUK SUDAH PUNYA HPP BELUM
        if ($hpp->count() > 0) {

            $hpp_keluar = Hpp::select('harga_unit')
            ->where('id_produk', $id_produk)
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
            ->where('warung_id', Auth::user()->id_warung)
            ->where('jenis_hpp', 2)->orderBy('id', 'DESC');

            // CEK PRODUK SUDAH PUNYA HPP KELUAR BELUM
            if ($hpp_keluar->count() > 0) {

                $hpp_terakhir = Hpp::select(['jenis_hpp'])
                ->where('id_produk', $id_produk)
                ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
                ->where('warung_id', Auth::user()->id_warung)
                ->orderBy('created_at', 'DESC')->first();

                // CEK HPP TERAKHIR PRODUK == HPP MASUK ATAU HPP KELUAR
                if ($hpp_terakhir->jenis_hpp == 1) {

                    $hpp_masuk = Hpp::select(['harga_unit', 'jumlah_masuk'])
                    ->where('id_produk', $id_produk)
                    ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
                    ->where('warung_id', Auth::user()->id_warung)
                    ->where('jenis_hpp', 1)->orderBy('id', 'DESC')->first();

                    $stok_sekarang = $this->stok_produk_tanggal($id_produk, $this->tanggalSql($tanggal));
                    $stok_produk = $stok_sekarang - $hpp_masuk->jumlah_masuk;

                    $hpp_produk = ( ($hpp_keluar->first()->harga_unit * $stok_produk) + ($hpp_masuk->harga_unit * $hpp_masuk->jumlah_masuk) ) / ($stok_produk + $hpp_masuk->jumlah_masuk);

                }else{
                    $hpp_produk = $hpp_keluar->first()->harga_unit;
                }

            }else{                

                $hpp_masuk = Hpp::select('harga_unit')
                ->where('id_produk', $id_produk)
                ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($tanggal))
                ->where('warung_id', Auth::user()->id_warung)
                ->where('jenis_hpp', 1)->orderBy('id', 'DESC')->first()->harga_unit;

                return $hpp_produk = $hpp_masuk;
            }

        }else{
            $hpp_produk = 0;
        }
        return number_format($hpp_produk, 2, ',', '.');

    }

    public static function stok_produk($id_produk)
    {

        $stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])->where('id_produk', $id_produk)
        ->where('warung_id', Auth::user()->id_warung)->first();

        return $sisa_stok_keluar = $stok_produk->jumlah_produk;

    }

    public static function hargaHpp($id_produk, $warung_id)
    {

        $harga = Hpp::select('harga_unit')->where('id_produk', $id_produk)
        ->where('warung_id', $warung_id)->first();

        return $hpp = $harga->harga_unit;

    }

    public function nilai($id_produk)
    {

        $nilai_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->where('id_produk', $id_produk)->where('jenis_hpp', 1)->where('warung_id', Auth::user()->id_warung)->first();

        $nilai_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->where('id_produk', $id_produk)->where('jenis_hpp', 2)->where('warung_id', Auth::user()->id_warung)->first();

        $prose_total_persedian = $nilai_masuk->total_masuk - $nilai_keluar->total_keluar;

        $total_persedian = number_format($prose_total_persedian, 2, ',', '.');

        return $total_persedian;

    }

    public function totalnilai()
    {
        $nilai_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->leftJoin('barangs', 'barangs.id', '=', 'hpps.id_produk')->where('barangs.hitung_stok', 1)->where('hpps.jenis_hpp', 1)->where('hpps.warung_id', Auth::user()->id_warung)->first();

        $nilai_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->leftJoin('barangs', 'barangs.id', '=', 'hpps.id_produk')->where('barangs.hitung_stok', 1)->where('hpps.jenis_hpp', 2)->where('hpps.warung_id', Auth::user()->id_warung)->first();

        $prose_total_persedian = $nilai_masuk->total_masuk - $nilai_keluar->total_keluar;

        $total_persedian = number_format($prose_total_persedian, 2, ',', '.');

        return $total_persedian;

    }

    public function totalnilai_tanggal($tanggal)
    {
        $nilai_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->leftJoin('barangs', 'barangs.id', '=', 'hpps.id_produk')->where('barangs.hitung_stok', 1)->where('hpps.jenis_hpp', 1)->where('hpps.warung_id', Auth::user()->id_warung)
        ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($tanggal))->first();

        $nilai_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->leftJoin('barangs', 'barangs.id', '=', 'hpps.id_produk')->where('barangs.hitung_stok', 1)->where('hpps.jenis_hpp', 2)->where('hpps.warung_id', Auth::user()->id_warung)
        ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($tanggal))->first();

        $prose_total_persedian = $nilai_masuk->total_masuk - $nilai_keluar->total_keluar;

        $total_persedian = number_format($prose_total_persedian, 2, ',', '.');

        return $total_persedian;

    }

    public function tanggalSql($tanggal)
    {
        $date        = date_create($tanggal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function hpp($id_produk)
    {

        // $total_nilai = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk'), DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_masuk')])->where('id_produk', $id_produk)->where('jenis_hpp', 1)->where('warung_id', Auth::user()->id_warung)->first();

        // if ($total_nilai->total_masuk == 0 || $total_nilai->jumlah_masuk == 0) {
        //     $hpp = 0;
        // } else {
        //     $proses_hpp = $total_nilai->total_masuk / $total_nilai->jumlah_masuk;
        //     $hpp        = $proses_hpp;
        // }
        // return number_format($hpp, 2, ',', '.');


        $hpp = Hpp::select('harga_unit')
        ->where('id_produk', $id_produk)
        ->where('warung_id', Auth::user()->id_warung);

        // CEK PRODUK SUDAH PUNYA HPP BELUM
        if ($hpp->count() > 0) {

            $hpp_keluar = Hpp::select('harga_unit')
            ->where('id_produk', $id_produk)
            ->where('warung_id', Auth::user()->id_warung)
            ->where('jenis_hpp', 2)->orderBy('id', 'DESC');

            // CEK PRODUK SUDAH PUNYA HPP KELUAR BELUM
            if ($hpp_keluar->count() > 0) {

                $hpp_terakhir = Hpp::select(['jenis_hpp'])
                ->where('id_produk', $id_produk)
                ->where('warung_id', Auth::user()->id_warung)
                ->orderBy('created_at', 'DESC')->first();

                // CEK HPP TERAKHIR PRODUK == HPP MASUK ATAU HPP KELUAR
                if ($hpp_terakhir->jenis_hpp == 1) {

                    $hpp_masuk = Hpp::select(['harga_unit', 'jumlah_masuk'])
                    ->where('id_produk', $id_produk)
                    ->where('warung_id', Auth::user()->id_warung)
                    ->where('jenis_hpp', 1)->orderBy('id', 'DESC')->first();

                    $stok_sekarang = $this->stok_produk_tanggal($id_produk, $this->tanggalSql($tanggal));
                    $stok_produk = $stok_sekarang - $hpp_masuk->jumlah_masuk;

                    $hpp_produk = ( ($hpp_keluar->first()->harga_unit * $stok_produk) + ($hpp_masuk->harga_unit * $hpp_masuk->jumlah_masuk) ) / ($stok_produk + $hpp_masuk->jumlah_masuk);

                }else{
                    $hpp_produk = $hpp_keluar->first()->harga_unit;
                }

            }else{                

                $hpp_masuk = Hpp::select('harga_unit')
                ->where('id_produk', $id_produk)
                ->where('warung_id', Auth::user()->id_warung)
                ->where('jenis_hpp', 1)->orderBy('id', 'DESC')->first()->harga_unit;

                return $hpp_produk = $hpp_masuk;
            }

        }else{
            $hpp_produk = 0;
        }
        return number_format($hpp_produk, 2, ',', '.');

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

        $query_masuk['stok_awal']  = $query_masuk->stok_masuk - $query_keluar->stok_keluar;
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

        $query_masuk['stok_awal']  = $query_masuk->stok_masuk - $query_keluar->stok_keluar;
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

    //DATA KARTU STOK PRODUK
    public function scopeDataKartuStok($query_kartu_stok, $request)
    {
        $query_kartu_stok = Hpp::select(['hpps.no_faktur', 'hpps.id_produk', 'hpps.jenis_transaksi', 'hpps.jumlah_masuk', 'hpps.jumlah_keluar', 'hpps.harga_unit', 'hpps.jenis_hpp', 'hpps.created_at', 'users.name as pelanggan', 'supliers.nama_suplier as suplier', 'pelanggan_online.name as pelanggan_online'])
        ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'hpps.no_faktur')
        ->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'hpps.no_faktur')
        ->leftJoin('penjualans', 'penjualans.id', '=', 'hpps.no_faktur')
        ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
        ->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
        ->leftJoin('users as pelanggan_online', 'pelanggan_online.id', '=', 'penjualans.id_pelanggan')
        ->where('id_produk', $request->produk)
        ->where(DB::raw('DATE(hpps.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('hpps.warung_id', Auth::user()->id_warung)
        ->orderBy('hpps.created_at', 'ASC');

        return $query_kartu_stok;
    }

    //PENCARIAN KARTU STOK PRODUK
    public function scopeCariKartuStok($query_kartu_stok, $request)
    {
        $search = $request->search;

        $query_kartu_stok = Hpp::select(['hpps.no_faktur', 'hpps.id_produk', 'hpps.jenis_transaksi', 'hpps.jumlah_masuk', 'hpps.jumlah_keluar', 'hpps.harga_unit', 'hpps.jenis_hpp', 'hpps.created_at', 'users.name as pelanggan', 'supliers.nama_suplier as suplier', 'pelanggan_online.name as pelanggan_online'])
        ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'hpps.no_faktur')
        ->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'hpps.no_faktur')
        ->leftJoin('penjualans', 'penjualans.id', '=', 'hpps.no_faktur')
        ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
        ->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
        ->leftJoin('users as pelanggan_online', 'pelanggan_online.id', '=', 'penjualans.id_pelanggan')
        ->where('id_produk', $request->produk)
        ->where(DB::raw('DATE(hpps.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('hpps.warung_id', Auth::user()->id_warung)
        ->where(function ($query) use ($search) {
            $query->orwhere('hpps.no_faktur', 'LIKE', '%' . $search . '%')
            ->orwhere('hpps.jenis_transaksi', 'LIKE', '%' . $search . '%')
            ->orwhere('users.name', 'LIKE', '%' . $search . '%')
            ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('hpps.created_at', 'ASC');

        return $query_kartu_stok;
    }

    public function scopeDataSaldoAwal($query_saldo_awal, $request)
    {
        //SALDO AWAL PRODUK
        $data_saldo_awal = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk - jumlah_keluar),0) AS saldo_awal')])
        ->where('id_produk', $request->produk)
        ->where(DB::raw('DATE(created_at)'), '<', $this->tanggalSql($request->dari_tanggal))
        ->where('warung_id', Auth::user()->id_warung);

        $query_saldo_awal = $data_saldo_awal->first()->saldo_awal;

        return $query_saldo_awal;

    }

    public function scopeDataSaldoAkhir($query_saldo_akhir, $request)
    {
        //SALDO AWAL PRODUK
        $query_saldo_akhir = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) AS jumlah_masuk'), DB::raw('IFNULL(SUM(jumlah_keluar),0) AS jumlah_keluar')])
        ->where('id_produk', $request->produk)
        ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
        ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
        ->where('warung_id', Auth::user()->id_warung);

        return $query_saldo_akhir;

    }

    // Hpp Bulan Ini
    public function scopeHppPenjualan($query_hpp, $dari_tanggal, $sampai_tanggal, $jenis_transaksi)
    {
        if ($jenis_transaksi == "PenjualanPos") {
            $query_hpp = Hpp::select(DB::raw('SUM(hpps.total_nilai) as total_hpp'))
            ->where(DB::raw('DATE(hpps.created_at)'), '>=', $this->tanggalSql($dari_tanggal))
            ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($sampai_tanggal))
            ->where('hpps.jenis_transaksi', 'PenjualanPos')
            ->where('hpps.warung_id', Auth::user()->id_warung);
        } else {
            $query_hpp = Hpp::select(DB::raw('SUM(hpps.total_nilai) as total_hpp'))
            ->where(DB::raw('DATE(hpps.created_at)'), '>=', $this->tanggalSql($dari_tanggal))
            ->where(DB::raw('DATE(hpps.created_at)'), '<=', $this->tanggalSql($sampai_tanggal))
            ->where('hpps.jenis_transaksi', 'penjualan')
            ->where('hpps.warung_id', Auth::user()->id_warung);
        }

        return $query_hpp;
    }

}
