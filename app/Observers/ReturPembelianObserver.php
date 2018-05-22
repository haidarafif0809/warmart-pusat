<?php

namespace App\Observers;

use App\ReturPembelian;
use App\DetailReturPembelian;
use App\TransaksiKas;
use App\Hpp;
use Auth;

class ReturPembelianObserver
{
    //HAPUS ITEM KELUAR
	public function deleting(ReturPembelian $ReturPembelian){
		DetailReturPembelian::where('no_faktur_retur', $ReturPembelian->no_faktur_retur)
		->where('warung_id', $ReturPembelian->warung_id)->delete();

		Hpp::where('no_faktur', $ReturPembelian->no_faktur_retur)->where('warung_id', $ReturPembelian->warung_id)->delete();

		TransaksiKas::where('no_faktur', $ReturPembelian->no_faktur_retur)->delete();

		return true;
	}
}