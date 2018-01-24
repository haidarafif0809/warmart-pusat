<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laratrust\Traits\LaratrustUserTrait;
use Yajra\Auditable\AuditableTrait;

class TransaksiPiutang extends Model
{

    use LaratrustUserTrait;
    use AuditableTrait;

    protected $fillable = ['jenis_transaksi', 'id_transaksi', 'pelanggan_id', 'no_faktur', 'jumlah_masuk', 'jumlah_keluar', 'warung_id'];

    // DATA PENJUALAN PIUTANG
    public function scopeDataPenjualanPiutang($query_penjualan_piutang)
    {
        $query_penjualan_piutang = TransaksiPiutang::select(['penjualan_pos.id', 'penjualan_pos.no_faktur', 'penjualan_pos.pelanggan_id', 'penjualan_pos.kredit', 'penjualan_pos.tanggal_jt_tempo', 'users.name', DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) AS sisa_piutang')])
            ->leftJoin('penjualan_pos', 'transaksi_piutangs.id_transaksi', '=', 'penjualan_pos.id')
            ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
            ->where('penjualan_pos.status_penjualan', 'Piutang')
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_piutangs.id_transaksi')
            ->having('sisa_piutang', '>', 0);

        return $query_penjualan_piutang;
    }

    // DATA PENJUALAN PIUTANG /FAKTUR
    public function scopeDataPenjualanPiutangPerFaktur($query_penjualan_piutang, $id)
    {
        $query_penjualan_piutang = TransaksiPiutang::select(['penjualan_pos.id', 'penjualan_pos.no_faktur', 'penjualan_pos.pelanggan_id', 'penjualan_pos.kredit', 'penjualan_pos.tanggal_jt_tempo', 'users.name', DB::raw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) AS sisa_piutang')])
            ->leftJoin('penjualan_pos', 'transaksi_piutangs.id_transaksi', '=', 'penjualan_pos.id')
            ->leftJoin('users', 'users.id', '=', 'penjualan_pos.pelanggan_id')
            ->where('penjualan_pos.status_penjualan', 'Piutang')
            ->where('penjualan_pos.id', $id)
            ->where('penjualan_pos.warung_id', Auth::user()->id_warung)
            ->groupBy('transaksi_piutangs.id_transaksi');

        return $query_penjualan_piutang;
    }
}
