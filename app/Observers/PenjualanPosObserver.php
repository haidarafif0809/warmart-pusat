<?php

namespace App\Observers;

use App\PenjualanPos;
use App\DetailPenjualanPos;
use App\TransaksiPiutang;
use App\TransaksiKas;
use App\Hpp;
use Auth;

class PenjualanPosObserver
{
    //HAPUS ITEM KELUAR
	public function deleting(PenjualanPos $PenjualanPos){
		DetailPenjualanPos::where('id_penjualan_pos', $PenjualanPos->id)->where('warung_id', $PenjualanPos->warung_id)->delete();
		Hpp::where('no_faktur', $PenjualanPos->id)->where('warung_id', $PenjualanPos->warung_id)->delete();
		TransaksiPiutang::where('no_faktur', $PenjualanPos->id)->delete();
		TransaksiKas::where('no_faktur', $PenjualanPos->id)->delete();

		return true;
	}
}