<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class TbsPembayaranPiutang extends Model
{
    use AuditableTrait;
    protected $fillable   = ['session_id', 'subtotal_piutang', 'no_faktur_penjualan', 'jatuh_tempo', 'piutang', 'potongan', 'jumlah_bayar', 'pelanggan_id', 'warung_id'];
    protected $primaryKey = 'id_tbs_pembayaran_piutang';

    // DATA TBS PEMBAYARAN PIUTANG
    public function scopeDataTbsPembayaranPiutang($query_tbs, $session_id)
    {
        $query_tbs = TbsPembayaranPiutang::select(['tbs_pembayaran_piutangs.no_faktur_penjualan', 'tbs_pembayaran_piutangs.subtotal_piutang', 'tbs_pembayaran_piutangs.id_tbs_pembayaran_piutang', 'tbs_pembayaran_piutangs.pelanggan_id', 'tbs_pembayaran_piutangs.jatuh_tempo', 'tbs_pembayaran_piutangs.piutang', 'tbs_pembayaran_piutangs.potongan', 'tbs_pembayaran_piutangs.jumlah_bayar', 'users.name'])
            ->leftJoin('users', 'tbs_pembayaran_piutangs.pelanggan_id', '=', 'users.id')
            ->where('tbs_pembayaran_piutangs.warung_id', Auth::user()->id_warung)
            ->where('tbs_pembayaran_piutangs.session_id', $session_id)->orderBy('tbs_pembayaran_piutangs.id_tbs_pembayaran_piutang', 'desc');

        return $query_tbs;
    }

    // PENCARIAN TBS PEMBAYARAN PIUTANG
    public function scopeCariTbsPembayaranPiutang($query_tbs, $request, $session_id)
    {
        $search    = $request->search;
        $query_tbs = TbsPembayaranPiutang::select(['tbs_pembayaran_piutangs.no_faktur_penjualan', 'tbs_pembayaran_piutangs.subtotal_piutang', 'tbs_pembayaran_piutangs.id_tbs_pembayaran_piutang', 'tbs_pembayaran_piutangs.pelanggan_id', 'tbs_pembayaran_piutangs.jatuh_tempo', 'tbs_pembayaran_piutangs.piutang', 'tbs_pembayaran_piutangs.potongan', 'tbs_pembayaran_piutangs.jumlah_bayar', 'users.name'])
            ->leftJoin('users', 'tbs_pembayaran_piutangs.pelanggan_id', '=', 'users.id')
            ->where('tbs_pembayaran_piutangs.warung_id', Auth::user()->id_warung)
            ->where('tbs_pembayaran_piutangs.session_id', $session_id)
            ->where(function ($query) use ($search) {
                $query->orwhere('tbs_pembayaran_piutangs.no_faktur_penjualan', 'LIKE', '%' . $search . '%')
                    ->orwhere('tbs_pembayaran_piutangs.jatuh_tempo', 'LIKE', '%' . $search . '%')
                    ->orwhere('users.name', 'LIKE', '%' . $search . '%');
            })->orderBy('tbs_pembayaran_piutangs.id_tbs_pembayaran_piutang', 'desc');

        return $query_tbs;
    }

    public function subtotalTbs($user_warung,$session_id)
    {
        $tbs_penjualan = TbsPembayaranPiutang::select([DB::raw('SUM(jumlah_bayar) as jumlah_bayar')])->where('warung_id', $user_warung)->where('session_id', $session_id)->first();
                if ($tbs_penjualan->jumlah_bayar == null || $tbs_penjualan->jumlah_bayar == '') {
                  return 0;
                 }else{
                return $tbs_penjualan->jumlah_bayar;
                }
    }
}
