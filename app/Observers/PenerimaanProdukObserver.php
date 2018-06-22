<?php  
namespace App\Observers;

use App\DetailPenerimaanProduk;
use App\PenerimaanProduk;
use App\PembelianOrder;

class PenerimaanProdukObserver
{
	
  public function deleting(PenerimaanProduk $PenerimaanProduk){

    $pembelian_order = PenerimaanProduk::where('status_penerimaan', 3)->where('id', $PenerimaanProduk->id)->count();

    if ($pembelian_order > 0) {

      return false;

    }else{

      DetailPenerimaanProduk::where('no_faktur_penerimaan', $PenerimaanProduk->no_faktur_penerimaan)->where('warung_id', $PenerimaanProduk->warung_id)->delete();
      PembelianOrder::where('no_faktur_order', $PenerimaanProduk->faktur_order)->where('suplier_id', $PenerimaanProduk->suplier_id)->update(['status_order' => 1]);

      return true;

    }

  }

}