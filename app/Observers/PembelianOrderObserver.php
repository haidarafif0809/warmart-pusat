<?php  
namespace App\Observers;

use App\DetailPembelianOrder;
use App\PembelianOrder;

class PembelianOrderObserver
{
	
  public function deleting(PembelianOrder $PembelianOrder){

    $pembelian_order = PembelianOrder::where('status_order', 3)->where('id', $PembelianOrder->id)->count();

    if ($pembelian_order > 0) {

      return false;

    }else{

      DetailPembelianOrder::where('no_faktur_order', $PembelianOrder->no_faktur_order)->where('warung_id', $PembelianOrder->warung_id)->delete();

      return true;

    }

  }

}