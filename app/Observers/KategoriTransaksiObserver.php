<?php

namespace App\Observers;

use App\KategoriTransaksi;
use App\KasMasuk;
use App\KasKeluar;
use Session;
use Auth;

class KategoriTransaksiObserver
{
    public function deleting(KategoriTransaksi $KategoriTransaksi)
    {       
        $data_kategori_masuk = KasMasuk::where('kategori', $KategoriTransaksi->id)->where('id_warung', $KategoriTransaksi->id_warung)->count();
        $data_kategori_keluar = KasKeluar::where('kategori', $KategoriTransaksi->id)->where('warung_id', $KategoriTransaksi->id_warung)->count();

        if ($data_kategori_masuk > 0 OR $data_kategori_keluar > 0) {
            return false;
        }
        else{
            KategoriTransaksi::where('id', $KategoriTransaksi->id)->where('id_warung', $KategoriTransaksi->id_warung)->delete();
        }

        exit();

    }

}