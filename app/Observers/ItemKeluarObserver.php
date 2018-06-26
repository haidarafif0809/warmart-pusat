<?php

namespace App\Observers;

use App\ItemKeluar;
use App\DetailItemKeluar;
use App\Hpp;
use Auth;

class ItemKeluarObserver
{
    public function creating(ItemKeluar $ItemKeluar){

        $total_nilai_item_keluar = Hpp::where('no_faktur', $ItemKeluar->no_faktur)->where('warung_id', $ItemKeluar->warung_id)->sum('total_nilai');
        $ItemKeluar->total = $total_nilai_item_keluar;
        
        return true;
    }
    public function updating(ItemKeluar $ItemKeluar){

        $total_nilai_item_keluar = Hpp::where('no_faktur', $ItemKeluar->no_faktur)->where('warung_id', $ItemKeluar->warung_id)->sum('total_nilai');
        $ItemKeluar->total = $total_nilai_item_keluar;
        
        return true;
    }

    //HAPUS ITEM KELUAR
    public function deleting(ItemKeluar $ItemKeluar){
    	DetailItemKeluar::where('no_faktur', $ItemKeluar->no_faktur)->where('warung_id', $ItemKeluar->warung_id)->delete();
        Hpp::where('no_faktur', $ItemKeluar->no_faktur)->where('warung_id', $ItemKeluar->warung_id)->delete();

        return true;
    }
}