<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class EditTbsPembayaranHutang extends Model
{
    //
        //
    use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur_pembayaran', 'no_faktur_pembelian', 'jatuh_tempo', 'hutang', 'potongan', 'jumlah_bayar', 'suplier_id','warung_id','subtotal_hutang'];
    protected $primaryKey = 'id_edit_tbs_pembayaran_hutang';

    // DATA TBS PEMBAYARAN hutang dataEditTbsPembayaranHutang
    public function scopeDataEditTbsPembayaranHutang($query_tbs, $session_id)
    {
        $query_tbs = EditTbsPembayaranHutang::select(['edit_tbs_pembayaran_hutangs.no_faktur_pembelian', 'edit_tbs_pembayaran_hutangs.subtotal_hutang', 'edit_tbs_pembayaran_hutangs.id_edit_tbs_pembayaran_hutang', 'edit_tbs_pembayaran_hutangs.suplier_id', 'edit_tbs_pembayaran_hutangs.jatuh_tempo', 'edit_tbs_pembayaran_hutangs.hutang', 'edit_tbs_pembayaran_hutangs.potongan', 'edit_tbs_pembayaran_hutangs.jumlah_bayar', 'supliers.nama_suplier'])
            ->leftJoin('supliers', 'edit_tbs_pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->where('edit_tbs_pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where('edit_tbs_pembayaran_hutangs.session_id', $session_id)->orderBy('edit_tbs_pembayaran_hutangs.id_edit_tbs_pembayaran_hutang', 'desc');

        return $query_tbs;
    }

    // PENCARIAN TBS PEMBAYARAN hutang
    public function scopeDataCariEditTbsPembayaranHutang($query_tbs, $request, $session_id)
    {
        $search    = $request->search;
        $query_tbs = EditTbsPembayaranHutang::select(['edit_tbs_pembayaran_hutangs.no_faktur_pembelian', 'edit_tbs_pembayaran_hutangs.subtotal_hutang', 'edit_tbs_pembayaran_hutangs.id_edit_tbs_pembayaran_hutang', 'edit_tbs_pembayaran_hutangs.suplier_id', 'edit_tbs_pembayaran_hutangs.jatuh_tempo', 'edit_tbs_pembayaran_hutangs.hutang', 'edit_tbs_pembayaran_hutangs.potongan', 'edit_tbs_pembayaran_hutangs.jumlah_bayar', 'supliers.nama_suplier'])
            ->leftJoin('supliers', 'edit_tbs_pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->where('edit_tbs_pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where('edit_tbs_pembayaran_hutangs.session_id', $session_id)
            ->where(function ($query) use ($search) {
                $query->orwhere('edit_tbs_pembayaran_hutangs.no_faktur_pembelian', 'LIKE', '%' . $search . '%')
                    ->orwhere('edit_tbs_pembayaran_hutangs.jatuh_tempo', 'LIKE', '%' . $search . '%')
                    ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
            })->orderBy('edit_tbs_pembayaran_hutangs.id_edit_tbs_pembayaran_hutang', 'desc');

        return $query_tbs;
    }

            public function subtotalTbs($user_warung,$session_id)
    {
        $tbs_penjualan = EditTbsPembayaranHutang::select([DB::raw('SUM(jumlah_bayar) as jumlah_bayar')])->where('warung_id', $user_warung)->where('session_id', $session_id)->first();
                if ($tbs_penjualan->jumlah_bayar == null || $tbs_penjualan->jumlah_bayar == '') {
                  return 0;
                 }else{
                return $tbs_penjualan->jumlah_bayar;
                }
    }


}
