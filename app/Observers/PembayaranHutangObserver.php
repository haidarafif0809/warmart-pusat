<?php

namespace App\Observers;

use App\DetailPembayaranHutang;
use App\PembayaranHutang;
use App\TransaksiHutang;
use App\TransaksiKas;

class PembayaranHutangObserver
{
    public function deleting(PembayaranHutang $PembayaranHutang)
    {
    


        //HAPUS DETAIL PEMBAYARAN Hutang
        DetailPembayaranHutang::where('no_faktur_pembayaran', $PembayaranHutang->no_faktur_pembayaran)->where('warung_id', $PembayaranHutang->warung_id)->delete();
        //HAPUS TRANSAKSI Hutang
        TransaksiHutang::where('no_faktur', $PembayaranHutang->no_faktur_pembayaran)->where('warung_id', $PembayaranHutang->warung_id)->delete();
        
        //HAPUS TRANSAKSI KAS
        TransaksiKas::where('no_faktur', $PembayaranHutang->no_faktur_pembayaran)->where('warung_id', $PembayaranHutang->warung_id)->delete();

        return true;
    }
}
