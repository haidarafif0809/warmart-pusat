<?php

namespace App\Observers;

use App\DetailReturPembelian;
use App\Hpp;
use App\SatuanKonversi;
use Auth;
use Illuminate\Support\Facades\DB;

class DetailReturPembelianObserver
{
    public function creating(DetailReturPembelian $DetailReturPembelian)
    {

        $warung_id = Auth::user()->id_warung;
        $total_nilai = $DetailReturPembelian->jumlah_produk * $DetailReturPembelian->harga_produk;

        if ($DetailReturPembelian->satuan_id == $DetailReturPembelian->satuan_dasar) {

            $jumlah_produk_keluar = $DetailReturPembelian->jumlah_produk;

        } else {

            $jumlah_konversi = SatuanKonversi::select('jumlah_konversi')->where('warung_id', $warung_id)
            ->where('id_produk', $DetailReturPembelian->id_produk)
            ->where('id_satuan', $DetailReturPembelian->satuan_id)->first()->jumlah_konversi;

            $jumlah_dasar = SatuanKonversi::select('jumlah_konversi')->where('id_satuan', $DetailReturPembelian->satuan_dasar);
            if ($jumlah_dasar->count() > 0) {
                $jumlah_konversi_dasar = intval($DetailReturPembelian->jumlah_produk) * (intval($jumlah_dasar->first()->jumlah_konversi) * intval($jumlah_konversi));
            } else {
                $jumlah_konversi_dasar = intval($DetailReturPembelian->jumlah_produk) * intval($jumlah_konversi);
            }

            $jumlah_produk_keluar = $jumlah_konversi_dasar;
        }

        
        $hpp_produk = $DetailReturPembelian->produk->hpp;
        $total_hpp  = $hpp_produk * $jumlah_produk_keluar;
        $stok = $DetailReturPembelian->produk->stok;

        $sisa_stok_keluar = $stok - $jumlah_produk_keluar;

        if ($sisa_stok_keluar < 0) {
            return false;
        }else{
            Hpp::create([
                'no_faktur'       => $DetailReturPembelian->no_faktur_retur,
                'id_produk'       => $DetailReturPembelian->id_produk,
                'jenis_transaksi' => 'Retur Pembelian',
                'jumlah_keluar'   => $jumlah_produk_keluar,
                'harga_unit'      => $hpp_produk,
                'total_nilai'     => $total_hpp,
                'warung_id'       => $warung_id,
                'jenis_hpp'       => 2,
                'created_at'      => $DetailReturPembelian->created_at,
                ]);

            return true;
        }
    }

    //HAPUS PENJUALAN
    public function deleting(DetailReturPembelian $DetailReturPembelian)
    {

        $hpp = Hpp::where('no_faktur', $DetailReturPembelian->no_faktur_retur)->where('id_produk', $DetailReturPembelian->id_produk)
        ->where('jenis_hpp', 2)->where('warung_id', $DetailReturPembelian->warung_id);

        if ($hpp->count() > 0) {

            if (!$hpp->delete()) {
                return false;
            } else {
                return true;
            }

        }

    }

}
