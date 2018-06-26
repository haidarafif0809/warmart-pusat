<?php

namespace App\Observers;

use App\DetailReturPenjualan;
use App\Hpp;
use App\SatuanKonversi;
use Auth;
use Illuminate\Support\Facades\DB;

class DetailReturPenjualanObserver
{
    public function creating(DetailReturPenjualan $DetailReturPenjualan)
    {

        $warung_id = Auth::user()->id_warung;
        $total_nilai = $DetailReturPenjualan->jumlah_retur * $DetailReturPenjualan->harga_produk;

        if ($DetailReturPenjualan->id_satuan == $DetailReturPenjualan->satuan_dasar) {

            $jumlah_retur_masuk = $DetailReturPenjualan->jumlah_retur;

        } else {

            $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', $warung_id)
            ->where('id_produk', $DetailReturPenjualan->id_produk)
            ->where('id_satuan', $DetailReturPenjualan->id_satuan)->first()->jumlah_konversi;

            $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $DetailReturPenjualan->satuan_dasar);
            if ($jumlah_dasar->count() > 0) {
                $jumlah_konversi_dasar = intval($DetailReturPenjualan->jumlah_retur) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
            } else {
                $jumlah_konversi_dasar = intval($DetailReturPenjualan->jumlah_retur) * intval($jumlah_konversi);
            }

            $jumlah_retur_masuk = $jumlah_konversi_dasar;
        }

        
        $hpp_produk = $DetailReturPenjualan->produk->hpp;
        $total_hpp  = $hpp_produk * $jumlah_retur_masuk;


            Hpp::create([
                'no_faktur'       => $DetailReturPenjualan->no_faktur_retur,
                'id_produk'       => $DetailReturPenjualan->id_produk,
                'jenis_transaksi' => 'Retur Penjualan',
                'jumlah_masuk'   => $jumlah_retur_masuk,
                'harga_unit'      => $hpp_produk,
                'total_nilai'     => $total_hpp,
                'warung_id'       => $warung_id,
                'jenis_hpp'       => 1,
                'created_at'      => $DetailReturPenjualan->created_at,
                ]);

            return true;
    }

    //HAPUS PENJUALAN
    public function deleting(DetailReturPenjualan $DetailReturPenjualan)
    {

        $hpp = Hpp::where('no_faktur', $DetailReturPenjualan->no_faktur_retur)->where('id_produk', $DetailReturPenjualan->id_produk)
        ->where('jenis_hpp', 2)->where('warung_id', $DetailReturPenjualan->warung_id);

        if ($hpp->count() > 0) {

            if (!$hpp->delete()) {
                return false;
            } else {
                return true;
            }

        }

    }

}
