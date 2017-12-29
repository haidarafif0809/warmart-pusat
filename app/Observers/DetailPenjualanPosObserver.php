<?php

namespace App\Observers;

use App\DetailPenjualanPos;
use App\Hpp;
use Auth;
use Illuminate\Support\Facades\DB;

class DetailPenjualanPosObserver
{
    public function creating(DetailPenjualanPos $DetailPenjualanPos)
    {

        $jumlah_produk_keluar = $DetailPenjualanPos->jumlah_produk;

        //HANYA PRODUK DENGAN GOLONGAN BARANG = BARANG YG DI CREATING KE HPP
        if ($DetailPenjualanPos->produk->hitung_stok == 1) {

            $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $DetailPenjualanPos->id_produk)
            ->where('warung_id', Auth::user()->id_warung)->first();

            $sisa_stok_keluar = $stok->stok_produk - $jumlah_produk_keluar;

            if ($sisa_stok_keluar < 0) {

                return false;
            } else {
                //HPP PRODUK (MODEL)
                $hpp_produk = $DetailPenjualanPos->produk->hpp;
                $total_hpp  = $hpp_produk * $jumlah_produk_keluar;

                Hpp::create([
                    'no_faktur'       => $DetailPenjualanPos->id_penjualan_pos,
                    'id_produk'       => $DetailPenjualanPos->id_produk,
                    'jenis_transaksi' => 'PenjualanPos',
                    'jumlah_keluar'   => $jumlah_produk_keluar,
                    'harga_unit'      => $hpp_produk,
                    'total_nilai'     => $total_hpp,
                    'warung_id'       => Auth::user()->id_warung,
                    'jenis_hpp'       => 2,
                ]);

                return true;

            } // END IF STOK < 0

        } // END GOL. BARANG == BARANG

    } // OBERVERS CREATING


    //HAPUS PENJUALAN
    public function deleting(DetailPenjualanPos $DetailPenjualanPos)
    {

        $hpp = Hpp::where('no_faktur', $DetailPenjualanPos->id_penjualan_pos)->where('id_produk', $DetailPenjualanPos->id_produk)->where('jenis_hpp', 2)
        ->where('warung_id', $DetailPenjualanPos->warung_id);

        if (!$hpp->delete()) {
            return false;
        } else {
            return true;
        }

    } //

}
