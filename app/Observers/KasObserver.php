<?php

namespace App\Observers;

use App\Kas;
use App\TransaksiKas;
use Session;
use Auth;

class KasObserver
{
    public function deleting(Kas $Kas)
    {       
    	$data_kas = Kas::select('default_kas')->where('id', $Kas->id)->where('warung_id', $Kas->warung_id)->first();
        $data_transaksi_kas = TransaksiKas::select('kas')->where('kas',$Kas->id)->where('warung_id', $Kas->warung_id)->count();

    	if ($data_kas->default_kas == 1 ) {
    		$pesan_alert = 
                '<div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <b>Gagal : Kas Yang Menjadi Default Kas Tidak Bisa Dihapus</b>
                </div>';

            Session:: flash("flash_notification", [
                "level"=>"danger",
                "message"=> $pesan_alert
                ]);
            return false;
    	}
        else if ($data_transaksi_kas > 0) {
            $pesan_alert = 
                '<div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <b>Gagal : Kas Tidak Bisa Dihapus. Karena Sudah Terpakai.</b>
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
                    <b>Sukses : Kas Berhasil Dihapus</b>
                </div>';

    		Kas::where('id', $Kas->id)->where('warung_id', $Kas->warung_id)->delete();

            Session:: flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
                ]);

    		return true;
    	}

    }

}