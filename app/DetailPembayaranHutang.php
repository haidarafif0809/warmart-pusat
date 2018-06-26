<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class DetailPembayaranHutang extends Model
{
    //
        use AuditableTrait;
    protected $fillable   = ['no_faktur_pembayaran', 'no_faktur_pembelian', 'jatuh_tempo', 'hutang', 'potongan', 'jumlah_bayar', 'suplier_id', 'warung_id','subtotal_hutang'];
    protected $primaryKey = 'id_detail_pembayaran_hutang';

    // DATA DETAIL PEMBAYARAN hutang
    public function scopeDataDetailPembayaranHutang($query_detail, $no_faktur_pembayaran)
    {
        $query_detail = DetailPembayaranHutang::select(['detail_pembayaran_hutangs.no_faktur_pembelian', 'detail_pembayaran_hutangs.subtotal_hutang', 'detail_pembayaran_hutangs.id_detail_pembayaran_hutang', 'detail_pembayaran_hutangs.suplier_id',DB::raw('DATE_FORMAT(detail_pembayaran_hutangs.jatuh_tempo, "%d/%m/%Y") as jatuh_tempo'), 'detail_pembayaran_hutangs.hutang', 'detail_pembayaran_hutangs.potongan', 'detail_pembayaran_hutangs.jumlah_bayar', 'supliers.nama_suplier'])
            ->leftJoin('supliers', 'detail_pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->where('detail_pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where('detail_pembayaran_hutangs.no_faktur_pembayaran', $no_faktur_pembayaran)
          	->orderBy('detail_pembayaran_hutangs.id_detail_pembayaran_hutang', 'desc');

        return $query_detail;
    }

    // PENCARIAN DETAIL PEMBAYARAN hutang
    public function scopeCariDetailPembayaranHutang($query_detail, $request, $no_faktur_pembayaran)
    {
        $search    = $request->search;
        $query_detail = DetailPembayaranHutang::select(['detail_pembayaran_hutangs.no_faktur_pembelian', 'detail_pembayaran_hutangs.subtotal_hutang', 'detail_pembayaran_hutangs.id_detail_pembayaran_hutang', 'detail_pembayaran_hutangs.suplier_id', DB::raw('DATE_FORMAT(detail_pembayaran_hutangs.jatuh_tempo, "%d/%m/%Y") as jatuh_tempo'), 'detail_pembayaran_hutangs.hutang', 'detail_pembayaran_hutangs.potongan', 'detail_pembayaran_hutangs.jumlah_bayar', 'supliers.nama_suplier'])
            ->leftJoin('supliers', 'detail_pembayaran_hutangs.suplier_id', '=', 'supliers.id')
            ->where('detail_pembayaran_hutangs.no_faktur_pembayaran', $no_faktur_pembayaran)
            ->where('detail_pembayaran_hutangs.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('detail_pembayaran_hutangs.no_faktur_pembelian', 'LIKE', '%' . $search . '%')
                    ->orwhere('detail_pembayaran_hutangs.jatuh_tempo', 'LIKE', '%' . $search . '%')
                    ->orwhere('supliers.nama_suplier', 'LIKE', '%' . $search . '%');
            })->orderBy('detail_pembayaran_hutangs.id_detail_pembayaran_hutang', 'desc');

        return $query_detail;
    }
}
