<?php

namespace App\Observers;

use App\DetailPenjualanPos;
use App\Hpp;
use App\SettingPenjualanPos;
use App\SatuanKonversi;
use Auth;
use Illuminate\Support\Facades\DB;

class DetailPenjualanPosObserver
{
    public function creating(DetailPenjualanPos $DetailPenjualanPos)
    {

        $user_warung = Auth::user()->id_warung;
        $settings    = SettingPenjualanPos::where('id_warung', $user_warung);
        $total_nilai = $DetailPenjualanPos->jumlah_produk * $DetailPenjualanPos->harga_produk;

        if ($settings->count() == 0) {
            $cek_setting_stok = 0;
        } else {
            $cek_setting_stok = $settings->first()->stok;
        }

        if ($DetailPenjualanPos->satuan_id == $DetailPenjualanPos->satuan_dasar) {

            $jumlah_produk_keluar = $DetailPenjualanPos->jumlah_produk;

        } else {

            $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', $user_warung)
            ->where('id_produk', $DetailPenjualanPos->id_produk)
            ->where('id_satuan', $DetailPenjualanPos->satuan_id)->first()->jumlah_konversi;

            $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $DetailPenjualanPos->satuan_dasar);
            if ($jumlah_dasar->count() > 0) {
                $jumlah_konversi_dasar = intval($DetailPenjualanPos->jumlah_produk) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
            } else {
                $jumlah_konversi_dasar = intval($DetailPenjualanPos->jumlah_produk) * intval($jumlah_konversi);
            }

            $jumlah_produk_keluar = $jumlah_konversi_dasar;

        }

        
        $hpp_produk = $DetailPenjualanPos->produk->hpp;
        $total_hpp  = $hpp_produk * $jumlah_produk_keluar;

        //HANYA PRODUK DENGAN GOLONGAN BARANG = BARANG YG DI CREATING KE HPP
        if ($DetailPenjualanPos->produk->hitung_stok == 1 and $cek_setting_stok == 0) {

            $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $DetailPenjualanPos->id_produk)
            ->where('warung_id', Auth::user()->id_warung)->first();

            $sisa_stok_keluar = $stok->stok_produk - $jumlah_produk_keluar;

            if ($sisa_stok_keluar < 0) {

                return false;
            }else{

                Hpp::create([
                    'no_faktur'       => $DetailPenjualanPos->id_penjualan_pos,
                    'id_produk'       => $DetailPenjualanPos->id_produk,
                    'jenis_transaksi' => 'PenjualanPos',
                    'jumlah_keluar'   => $jumlah_produk_keluar,
                    'harga_unit'      => $hpp_produk,
                    'total_nilai'     => $total_hpp,
                    'warung_id'       => Auth::user()->id_warung,
                    'jenis_hpp'       => 2,
                    'created_at'      => $DetailPenjualanPos->created_at,
                    ]);

                return true;
            }

        }elseif($DetailPenjualanPos->produk->hitung_stok == 1 and $cek_setting_stok == 1){

            Hpp::create([
                'no_faktur'       => $DetailPenjualanPos->id_penjualan_pos,
                'id_produk'       => $DetailPenjualanPos->id_produk,
                'jenis_transaksi' => 'PenjualanPos',
                'jumlah_keluar'   => $jumlah_produk_keluar,
                'harga_unit'      => $hpp_produk,
                'total_nilai'     => $total_hpp,
                'warung_id'       => Auth::user()->id_warung,
                'jenis_hpp'       => 2,
                'created_at'      => $DetailPenjualanPos->created_at,
                ]);

            return true;
        }


    }

    //HAPUS PENJUALAN
    public function deleting(DetailPenjualanPos $DetailPenjualanPos)
    {

        $hpp = Hpp::where('no_faktur', $DetailPenjualanPos->id_penjualan_pos)->where('id_produk', $DetailPenjualanPos->id_produk)->where('jenis_hpp', 2)
        ->where('warung_id', $DetailPenjualanPos->warung_id);

        if ($hpp->count() > 0) {

            if (!$hpp->delete()) {
                return false;
            } else {
                return true;
            }

        }

    } //

}
