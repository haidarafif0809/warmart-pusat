<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class DetailPembayaranPiutang extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_pembayaran', 'no_faktur_penjualan', 'jatuh_tempo', 'piutang', 'potongan', 'jumlah_bayar', 'pelanggan_id', 'warung_id', 'created_at', 'updated_at'];
    protected $primaryKey = 'id_detail_pembayaran_piutang';

    // DATA DETAIL PEMBAYARAN PIUTANG
    public function scopeDataDetailPembayaranPiutang($query_detail, $user_warung, $no_faktur)
    {
        $query_detail = DetailPembayaranPiutang::select(['detail_pembayaran_piutangs.no_faktur_penjualan', 'detail_pembayaran_piutangs.id_detail_pembayaran_piutang', 'detail_pembayaran_piutangs.pelanggan_id', 'detail_pembayaran_piutangs.jatuh_tempo', 'detail_pembayaran_piutangs.piutang', 'detail_pembayaran_piutangs.potongan', 'detail_pembayaran_piutangs.jumlah_bayar', 'users.name'])
            ->leftJoin('users', 'detail_pembayaran_piutangs.pelanggan_id', '=', 'users.id')
            ->where('detail_pembayaran_piutangs.warung_id', $user_warung)
            ->where('detail_pembayaran_piutangs.no_faktur_pembayaran', $no_faktur)
            ->orderBy('detail_pembayaran_piutangs.id_detail_pembayaran_piutang', 'desc');

        return $query_detail;
    }

    // PENCARIAN DETAIL PEMBAYARAN PIUTANG
    public function scopeCariDetailPembayaranPiutang($query_detail, $request, $user_warung, $no_faktur)
    {
        $search       = $request->search;
        $query_detail = DetailPembayaranPiutang::select(['detail_pembayaran_piutangs.no_faktur_penjualan', 'detail_pembayaran_piutangs.id_detail_pembayaran_piutang', 'detail_pembayaran_piutangs.pelanggan_id', 'detail_pembayaran_piutangs.jatuh_tempo', 'detail_pembayaran_piutangs.piutang', 'detail_pembayaran_piutangs.potongan', 'detail_pembayaran_piutangs.jumlah_bayar', 'users.name'])
            ->leftJoin('users', 'detail_pembayaran_piutangs.pelanggan_id', '=', 'users.id')
            ->where('detail_pembayaran_piutangs.warung_id', $user_warung)
            ->where('detail_pembayaran_piutangs.no_faktur_pembayaran', $no_faktur)
            ->where(function ($query) use ($search) {
                $query->orwhere('detail_pembayaran_piutangs.no_faktur_penjualan', 'LIKE', '%' . $search . '%')
                    ->orwhere('detail_pembayaran_piutangs.jatuh_tempo', 'LIKE', '%' . $search . '%')
                    ->orwhere('users.name', 'LIKE', '%' . $search . '%');
            })->orderBy('detail_pembayaran_piutangs.id_detail_pembayaran_piutang', 'desc');

        return $query_detail;
    }
}
