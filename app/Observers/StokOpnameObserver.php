<?php

namespace App\Observers;

use App\Hpp;
use App\StokOpname;

class StokOpnameObserver
{
    //MEMBUAT HPP MASUK
    public function creating(StokOpname $StokOpname)
    {
        $selisih_fisik = $StokOpname->selisih_fisik;

        if ($selisih_fisik < 0) {

            $selisih_fisik = $selisih_fisik * -1;
            $total_nilai   = $StokOpname->total * -1;
            Hpp::create([
                'no_faktur'       => $StokOpname->no_faktur,
                'id_produk'       => $StokOpname->produk_id,
                'jenis_transaksi' => 'Stok Opname',
                'jumlah_keluar'   => $selisih_fisik,
                'harga_unit'      => $StokOpname->harga,
                'total_nilai'     => $total_nilai,
                'jenis_hpp'       => '2',
                'warung_id'       => $StokOpname->warung_id,
            ]);

        } else {

            Hpp::create([
                'no_faktur'       => $StokOpname->no_faktur,
                'id_produk'       => $StokOpname->produk_id,
                'jenis_transaksi' => 'Stok Opname',
                'jumlah_masuk'    => $selisih_fisik,
                'harga_unit'      => $StokOpname->harga,
                'total_nilai'     => $StokOpname->total,
                'jenis_hpp'       => '1',
                'warung_id'       => $StokOpname->warung_id,
            ]);

        }

        return true;
    } // OBERVERS CREATING

    //HAPUS ITEM MASUK
    public function deleting(StokOpname $StokOpname)
    {
    }
}
