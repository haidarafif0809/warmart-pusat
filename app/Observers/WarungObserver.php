<?php

namespace App\Observers;

use App\Warung;
use App\User;
use App\BankWarung;
use Session;

class WarungObserver
{
    public function deleting(Warung $Warung)
    {       

      $user_warung_terpakai =  User::where('id_warung', $Warung->id)->count();
      
      if ($user_warung_terpakai > 0) {

         $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">error</i>
                    </div>
                    <b>Gagal : Warung Sudah Terpakai Tidak Bisa Dihapus !</b>
                </div>';

          Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return false;
      }
      else {

        BankWarung::where('warung_id', $Warung->id)->delete();
        return true;
      }

    }

}