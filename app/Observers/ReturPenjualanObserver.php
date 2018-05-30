<?php
namespace App\Observers;

use App\ReturPenjualan;
use App\DetailReturPenjualan;
use App\TransaksiKas;
use App\TransaksiHutang;
use App\Hpp;
use Auth;


class ReturPenjualanObserver
{
    //HAPUS ITEM KELUAR
	public function deleting(ReturPenjualan $ReturPenjualan){
		DetailReturPenjualan::where('no_faktur_retur', $ReturPenjualan->no_faktur_retur)
		->where('warung_id', $ReturPenjualan->warung_id)->delete();

		Hpp::where('no_faktur', $ReturPenjualan->no_faktur_retur)->where('warung_id', $ReturPenjualan->warung_id)->delete();

		TransaksiKas::where('no_faktur', $ReturPenjualan->no_faktur_retur)->delete();

		TransaksiHutang::where('no_faktur', $ReturPenjualan->no_faktur_retur)->delete();

		return true;
	}
}