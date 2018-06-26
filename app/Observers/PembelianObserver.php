<?php  
namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\Pembelian;
use App\TransaksiKas;
use App\TransaksiHutang;
use App\DetailPembelian;
use App\PembelianOrder;
use App\PenerimaanProduk;
use App\Hpp;
use Auth;
use Session;

class PembelianObserver
{

    public function deleting(Pembelian $Pembelian){
        $detail_pembelian =  DetailPembelian::where('no_faktur', $Pembelian->no_faktur)->where('warung_id', $Pembelian->warung_id)->first();

        $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $detail_pembelian->id_produk)
        ->where('warung_id', $detail_pembelian->warung_id)->where('no_faktur' ,'!=',$Pembelian->no_faktur)->first(); 

        if ($stok->stok_produk < 0) {
            $pesan_alert = 
            '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">error</i>
            </div>
            <b>Gagal : Stok Tidak Mencukupi </b>
        </div>';

        Session::flash("flash_notification", [
            "level"     => "danger",
            "message"   => $pesan_alert
            ]);

        return false; 

    } else {

        $detail_order = DetailPembelian::select('faktur_order')->where('no_faktur', $Pembelian->no_faktur)
        ->where('faktur_order', '!=', NULL)->where('warung_id', Auth::user()->id_warung);

        $detail_penerimaan = DetailPembelian::select('faktur_penerimaan')->where('no_faktur', $Pembelian->no_faktur)
        ->where('faktur_penerimaan', '!=', NULL)->where('warung_id', Auth::user()->id_warung);

        if ($detail_order->count() > 0) {
            PembelianOrder::where('no_faktur_order', $detail_order->first()->faktur_order)->update(['status_order' => 1]);
        }

        if ($detail_penerimaan->count() > 0) {
            PenerimaanProduk::where('no_faktur_penerimaan', $detail_penerimaan->first()->faktur_penerimaan)->update(['status_penerimaan' => 1]);
        }

        DetailPembelian::where('no_faktur', $Pembelian->no_faktur)->where('warung_id', $Pembelian->warung_id)->delete();
        Hpp::where('no_faktur', $Pembelian->no_faktur)->where('warung_id', $Pembelian->warung_id)->where('jenis_transaksi','pembelian')->delete();
        TransaksiKas::where('no_faktur',$Pembelian->no_faktur)->where('warung_id',$Pembelian->warung_id)->where('jenis_transaksi','pembelian')->delete();
        TransaksiHutang::where('no_faktur',$Pembelian->no_faktur)->where('warung_id',$Pembelian->warung_id)->where('jenis_transaksi','pembelian')->delete();

        return true;
    }

}

}