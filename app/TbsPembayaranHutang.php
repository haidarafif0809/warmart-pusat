<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class TbsPembayaranHutang extends Model
{
    //
        use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur_pembayaran', 'no_faktur_pembelian', 'jatuh_tempo', 'hutang', 'potongan', 'jumlah_bayar', 'suplier_id','warung_id','subtotal_hutang'];
    protected $primaryKey = 'id_tbs_pembayaran_hutang';

// DATA TBS PEMBAYARAN hutang
    public function scopeDataTbsPembayaranHutang($query_tbs, $session_id)
    {
        $query_tbs = TbsPembayaranHutang::select(['tbs_pembayaran_hutangs.no_faktur_pembelian', 'tbs_pembayaran_hutangs.subtotal_hutang', 'tbs_pembayaran_hutangs.id_tbs_pembayaran_hutang', 'tbs_pembayaran_hutangs.suplier_id', 'tbs_pembayaran_hutangs.jatuh_tempo', 'tbs_pembayaran_hutangs.hutang', 'tbs_pembayaran_hutangs.potongan', 'tbs_pembayaran_hutangs.jumlah_bayar', 'supliers.nama_suplier'])
            ->leftJoin('supliers', 'tbs_pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->where('tbs_pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where('tbs_pembayaran_hutangs.session_id', $session_id)->orderBy('tbs_pembayaran_hutangs.id_tbs_pembayaran_hutang', 'desc');

        return $query_tbs;
    }

    // PENCARIAN TBS PEMBAYARAN hutang
    public function scopeCariTbsPembayaranHutang($query_tbs, $request, $session_id)
    {
        $search    = $request->search;
        $query_tbs = TbsPembayaranHutang::select(['tbs_pembayaran_hutangs.no_faktur_pembelian', 'tbs_pembayaran_hutangs.subtotal_hutang', 'tbs_pembayaran_hutangs.id_tbs_pembayaran_hutang', 'tbs_pembayaran_hutangs.suplier_id', 'tbs_pembayaran_hutangs.jatuh_tempo', 'tbs_pembayaran_hutangs.hutang', 'tbs_pembayaran_hutangs.potongan', 'tbs_pembayaran_hutangs.jumlah_bayar', 'supliers.nama_suplier'])
            ->leftJoin('supliers', 'tbs_pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->where('tbs_pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where('tbs_pembayaran_hutangs.session_id', $session_id)
            ->where(function ($query) use ($search) {
                $query->orwhere('tbs_pembayaran_hutangs.no_faktur_pembelian', 'LIKE', '%' . $search . '%')
                    ->orwhere('tbs_pembayaran_hutangs.jatuh_tempo', 'LIKE', '%' . $search . '%')
                    ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
            })->orderBy('tbs_pembayaran_hutangs.id_tbs_pembayaran_hutang', 'desc');

        return $query_tbs;
    }

        // relasi ke suppier
    public function suplier()
    {
        return $this->hasOne('App\Suplier', 'id', 'suplier_id');
    }
    // relasi ke kas
    public function kas()
    {
        return $this->hasOne('App\Kas', 'id', 'cara_bayar');
    }

        public function subtotalTbs($user_warung,$session_id)
    {
        $tbs_penjualan = TbsPembayaranHutang::select([DB::raw('SUM(jumlah_bayar) as jumlah_bayar')])->where('warung_id', $user_warung)->where('session_id', $session_id)->first();
                if ($tbs_penjualan->jumlah_bayar == null || $tbs_penjualan->jumlah_bayar == '') {
                  return 0;
                 }else{
                return $tbs_penjualan->jumlah_bayar;
                }
    }
    
}
