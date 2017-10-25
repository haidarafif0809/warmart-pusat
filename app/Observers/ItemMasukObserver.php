<?php

namespace App\Observers;

use App\ItemMasuk;
use App\DetailItemMasuk;
use App\Hpp;
use Auth;
use DB;

class ItemMasukObserver
{ 
 
    public function creating(ItemMasuk $itemMasuk){

        $total_nilai_item_masuk = Hpp::where('no_faktur', $itemMasuk->no_faktur)->where('warung_id',Auth::user()->id_warung)->sum('total_nilai');
        $itemMasuk->total = $total_nilai_item_masuk;
        return true;
    }

    public function updating(ItemMasuk $ItemMasuk){

        $total_nilai_item_masuk = Hpp::where('no_faktur', $ItemMasuk->no_faktur)->where('warung_id', $ItemMasuk->warung_id)->sum('total_nilai');
        $ItemMasuk->total = $total_nilai_item_masuk;
        
        return true;
    }

    //HAPUS ITEM MASUK
    public function deleting(ItemMasuk $itemMasuk){ 
    $detail_item_masuk =  DetailItemMasuk::where('no_faktur', $itemMasuk->no_faktur)->where('warung_id', $itemMasuk->warung_id)->first();

    $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $detail_item_masuk->id_produk)
    ->where('warung_id', $detail_item_masuk->warung_id)->first(); 

        if ($stok->stok_produk < 0) {
          $pesan_alert = 
            '<div class="container-fluid">
              <div class="alert-icon">
                          <i class="material-icons">error</i>
                      </div>
                            <b>Gagal : Stok Tidak Mencukupi "</b>
                    </div>';

                    Session::flash("flash_notification", [
                      "level"     => "danger",
                      "message"   => $pesan_alert
                    ]);

          return false; 
          
        } else {
          DetailItemMasuk::where('no_faktur', $itemMasuk->no_faktur)->where('warung_id', $itemMasuk->warung_id)->delete();
          Hpp::where('no_faktur', $itemMasuk->no_faktur)->where('warung_id', $itemMasuk->warung_id)->delete();

          return true; 
        }
    }
}