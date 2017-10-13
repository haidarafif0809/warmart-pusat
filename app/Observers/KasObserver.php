<?php

namespace App\Observers;

use App\Kas;
use Session;

class KasObserver
{
    public function deleting(Kas $Kas)
    {       
    	$data_kas = Kas::select('default_kas')->where('id', $Kas->id)->first();

    	if ($data_kas->default_kas == 1) {
    		$pesan_alert = 
                '<div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <b>Gagal : Kas Yang Menjadi Defaul Kas Tidak Bisa Dihapus</b>
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

    		Kas::where('id', $Kas->id)->delete();

            Session:: flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
                ]);

    		return true;
    	}

    }

}