<?php

namespace App\Observers;

use App\Hpp;
use App\StokOpname;
use Auth;

class StokOpnameObserver
{
    //INSERT HPP
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

    //UPDATE HPP
    public function updating(StokOpname $StokOpname)
    {
        $selisih_fisik = $StokOpname->selisih_fisik;

        if ($selisih_fisik < 0) {

            $selisih_fisik = $selisih_fisik * -1;
            $total_nilai   = $StokOpname->total * -1;

            Hpp::where('no_faktur', $StokOpname->no_faktur)
                ->where('warung_id', Auth::user()->id_warung)
                ->update([
                    'jumlah_masuk'  => 0,
                    'jumlah_keluar' => $selisih_fisik,
                    'total_nilai'   => $total_nilai,
                ]);

        } else {

            Hpp::where('no_faktur', $StokOpname->no_faktur)
                ->where('warung_id', Auth::user()->id_warung)
                ->update([
                    'jumlah_keluar' => 0,
                    'jumlah_masuk'  => $selisih_fisik,
                    'total_nilai'   => $StokOpname->total,
                ]);
        }

        return true;
    } // OBERVERS UPDATING

    //DELETE HPP
    public function deleting(StokOpname $StokOpname)
    {

        if ($StokOpname->selisih_fisik < 0) {
            $jenis_hpp = 2;
        } else {
            $jenis_hpp = 1;
        }

        $hpp = Hpp::where('no_faktur', $StokOpname->no_faktur)
            ->where('id_produk', $StokOpname->produk_id)
            ->where('jenis_hpp', $jenis_hpp)
            ->where('warung_id', $StokOpname->warung_id);

        if (!$hpp->delete()) {
            return false;
        } else {
            return true;
        }

    }
}
