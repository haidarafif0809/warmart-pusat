<?php

namespace App\Observers;

use App\Warung;
use App\Barang;
use App\UserWarung;
use Session;

class UserWarungObserver
{

    public function deleting(UserWarung $UserWarung)
    {
        $data_user = UserWarung::select('id_warung')->where('id', $UserWarung->id)->first();

        if (UserWarung::where('id_warung', $data_user->id_warung)->count() > 1) {
           UserWarung::where('id_warung', $UserWarung->id)->delete();
           return response(200);

       }
       else{
           $pesan_alert = '
           <div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">error_outline</i>
            </div>
            <b>Gagal : User Warung Tidak Bisa Dihapus.</b>
        </div>';

        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return response(404);
    }


  }

  public function updating(UserWarung $UserWarung)
  {       
    $data_produk = Barang::where('id_warung', $UserWarung->id_warung)->get();

    if ($UserWarung->konfirmasi_admin == 1) {
     foreach ($data_produk as $produk) {
      $produk->update(['konfirmasi_admin' => 1]);
    }
  }
  else{
    foreach ($data_produk as $produk) {
      $produk->update(['konfirmasi_admin' => 0]);
    }
  }

}

}