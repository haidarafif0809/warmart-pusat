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
        $data_kategori_masuk = KasMasuk::where('kategori', $KategoriTransaksi->id)->where('id_warung', Auth::user()->id_warung)->count();
        $data_kategori_keluar = KasKeluar::where('kategori', $KategoriTransaksi->id)->where('id_warung', Auth::user()->id_warung)->count();

    	if ($data_kategori_masuk > 0 OR $data_kategori_keluar > 0) {
    		$pesan_alert = 
                '<div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <b>Gagal : Kategori Transaksi Tidak Bisa Dihapus. Karena Sudah Terpakai.</b>
                </div>';

            Session:: flash("flash_notification", [
                "level"=>"danger",
                "message"=> $pesan_alert
                ]);
            return false;
    	}
    	else{

            $pesan_alert = 
            '<div class="container-fluid">
                <div class="alert-icon">
                    <i class="material-icons">check</i>
                </div>
                    <b>Sukses : Kategori Transaksi Berhasil Dihapus</b>
            </div>';

            KategoriTransaksi::where('id', $KategoriTransaksi->id)->where('id_warung', Auth::user()->id_warung)->delete();

            Session:: flash("flash_notification", [
                    "level"=>"success",
                    "message"=> $pesan_alert
                ]);
            return true;
    	}

    }

}