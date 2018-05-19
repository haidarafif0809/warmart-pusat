<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class TbsReturPenjualan extends Model
{
       use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur_retur', 'no_faktur_penjualan', 'id_produk', 'jumlah_jual', 'jumlah_retur', 'id_satuan', 'id_satuan_jual', 'harga_produk', 'subtotal', 'tax','potongan','warung_id'];
    protected $primaryKey = 'id_tbs_retur_penjualan';


    // DATA TBS PEMBAYARAN hutang
    public function scopeDataTbsReturPenjualan($query_tbs, $session_id)
    {
        $query_tbs = TbsReturPenjualan::select(['tbs_retur_penjualans.session_id', 'tbs_retur_penjualans.id_tbs_retur_penjualan as id_tbs', 'tbs_retur_penjualans.no_faktur_penjualan', 'tbs_retur_penjualans.jumlah_jual', 'tbs_retur_penjualans.jumlah_retur', 'satuans.nama_satuan as satuan', 'tbs_retur_penjualans.harga_produk', 'tbs_retur_penjualans.potongan', 'tbs_retur_penjualans.subtotal','barangs.nama_barang','barangs.kode_barang'])
        	 ->leftJoin('barangs', 'tbs_retur_penjualans.id_produk', '=', 'barangs.id')
             ->leftJoin('satuans', 'tbs_retur_penjualans.id_satuan', '=', 'satuans.id')
            ->where('tbs_retur_penjualans.warung_id', Auth::user()->id_warung)
            ->where('tbs_retur_penjualans.session_id', $session_id)->orderBy('tbs_retur_penjualans.id_tbs_retur_penjualan', 'desc');

        return $query_tbs;
    }

        // PENCARIAN TBS PEMBAYARAN hutang
    public function scopeCariTbsReturPenjualan($query_tbs, $request, $session_id)
    {
        $search    = $request->search;
        $query_tbs = TbsReturPenjualan::select(['tbs_retur_penjualans.session_id', 'tbs_retur_penjualans.id_tbs_retur_penjualan as id_tbs', 'tbs_retur_penjualans.no_faktur_penjualan', 'tbs_retur_penjualans.jumlah_jual', 'tbs_retur_penjualans.jumlah_retur', 'satuans.nama_satuan as satuan', 'tbs_retur_penjualans.harga_produk', 'tbs_retur_penjualans.potongan', 'tbs_retur_penjualans.subtotal','barangs.nama_barang','barangs.kode_barang'])
            ->leftJoin('barangs', 'tbs_retur_penjualans.id_produk', '=', 'barangs.id')
             ->leftJoin('satuans', 'tbs_retur_penjualans.id_satuan', '=', 'satuans.id')
            ->where('tbs_retur_penjualans.warung_id', Auth::user()->id_warung)
            ->where('tbs_retur_penjualans.session_id', $session_id)
            ->where(function ($query) use ($search) {
                $query->orwhere('tbs_retur_penjualans.no_faktur_penjualan', 'LIKE', '%' . $search . '%');
            })->orderBy('tbs_retur_penjualans.id_tbs_retur_penjualan', 'desc');

        return $query_tbs;
    }

}
