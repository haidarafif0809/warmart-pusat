<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class EditTbsPembayaranPiutang extends Model
{
    use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur_pembayaran', 'subtotal_piutang', 'no_faktur_penjualan', 'jatuh_tempo', 'piutang', 'potongan', 'jumlah_bayar', 'pelanggan_id', 'warung_id'];
    protected $primaryKey = 'id_edit_tbs_pembayaran_piutang';

    // DATA TBS PEMBAYARAN PIUTANG
    public function scopeDataEditTbsPembayaranPiutang($query_tbs, $session_id)
    {
        $query_tbs = EditTbsPembayaranPiutang::select(['edit_tbs_pembayaran_piutangs.no_faktur_penjualan', 'edit_tbs_pembayaran_piutangs.subtotal_piutang', 'edit_tbs_pembayaran_piutangs.id_edit_tbs_pembayaran_piutang', 'edit_tbs_pembayaran_piutangs.pelanggan_id', 'edit_tbs_pembayaran_piutangs.jatuh_tempo', 'edit_tbs_pembayaran_piutangs.piutang', 'edit_tbs_pembayaran_piutangs.potongan', 'edit_tbs_pembayaran_piutangs.jumlah_bayar', 'users.name'])
            ->leftJoin('users', 'edit_tbs_pembayaran_piutangs.pelanggan_id', '=', 'users.id')
            ->where('edit_tbs_pembayaran_piutangs.warung_id', Auth::user()->id_warung)
            ->where('edit_tbs_pembayaran_piutangs.session_id', $session_id)->orderBy('edit_tbs_pembayaran_piutangs.id_edit_tbs_pembayaran_piutang', 'desc');

        return $query_tbs;
    }

    // PENCARIAN TBS PEMBAYARAN PIUTANG
    public function scopeCariEditTbsPembayaranPiutang($query_tbs, $request, $session_id)
    {
        $search    = $request->search;
        $query_tbs = EditTbsPembayaranPiutang::select(['edit_tbs_pembayaran_piutangs.no_faktur_penjualan', 'edit_tbs_pembayaran_piutangs.subtotal_piutang', 'edit_tbs_pembayaran_piutangs.id_edit_tbs_pembayaran_piutang', 'edit_tbs_pembayaran_piutangs.pelanggan_id', 'edit_tbs_pembayaran_piutangs.jatuh_tempo', 'edit_tbs_pembayaran_piutangs.piutang', 'edit_tbs_pembayaran_piutangs.potongan', 'edit_tbs_pembayaran_piutangs.jumlah_bayar', 'users.name'])
            ->leftJoin('users', 'edit_tbs_pembayaran_piutangs.pelanggan_id', '=', 'users.id')
            ->where('edit_tbs_pembayaran_piutangs.warung_id', Auth::user()->id_warung)
            ->where('edit_tbs_pembayaran_piutangs.session_id', $session_id)
            ->where(function ($query) use ($search) {
                $query->orwhere('edit_tbs_pembayaran_piutangs.no_faktur_penjualan', 'LIKE', '%' . $search . '%')
                    ->orwhere('edit_tbs_pembayaran_piutangs.jatuh_tempo', 'LIKE', '%' . $search . '%')
                    ->orwhere('users.name', 'LIKE', '%' . $search . '%');
            })->orderBy('edit_tbs_pembayaran_piutangs.id_edit_tbs_pembayaran_piutang', 'desc');

        return $query_tbs;
    }
}
