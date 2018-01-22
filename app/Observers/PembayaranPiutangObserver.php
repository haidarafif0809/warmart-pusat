<?php

namespace App\Observers;

use App\DetailPembayaranPiutang;
use App\PembayaranPiutang;
use App\TransaksiPiutang;

class PembayaranPiutangObserver
{
    public function deleting(PembayaranPiutang $PembayaranPiutang)
    {

        //HAPUS DETAIL PEMBAYARAN PIUTANG
        DetailPembayaranPiutang::where('no_faktur_pembayaran', $PembayaranPiutang->no_faktur_pembayaran)->where('warung_id', $PembayaranPiutang->warung_id)->delete();
        //HAPUS TRANSAKSI PIUTANG
        TransaksiPiutang::where('no_faktur', $PembayaranPiutang->no_faktur_pembayaran)->where('warung_id', $PembayaranPiutang->warung_id)->delete();

        return true;
    }
}
