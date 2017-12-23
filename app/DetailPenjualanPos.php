<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class DetailPenjualanPos extends Model
{
    use AuditableTrait;

    protected $fillable   = ['id_penjualan_pos', 'no_faktur', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'tax', 'potongan', 'warung_id', 'ppn'];
    protected $primaryKey = 'id_detail_penjualan_pos';

    // relasi ke produk
    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }

    public function stok_produk($id_produk)
    {

        $stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])->where('id_produk', $id_produk)
            ->where('warung_id', Auth::user()->id_warung)->first();

        return $sisa_stok_keluar = $stok_produk->jumlah_produk;

    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    // SUBTOTAL LABA KOTOR PENJUALAN POS
    public function scopeSubtotalLaporanLabaKotor($query_sub_total_penjualan, $request)
    {
        if ($request->pelanggan == "") {
            $query_sub_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))
                ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('warung_id', Auth::user()->id_warung);
        } else {
            $query_sub_total_penjualan = DetailPenjualanPos::select(DB::raw('SUM(detail_penjualan_pos.subtotal) as subtotal'))
                ->leftJoin('penjualan_pos', 'penjualan_pos.id', '=', 'detail_penjualan_pos.id_penjualan_pos')
                ->where(DB::raw('DATE(.detail_penjualan_pos.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                ->where(DB::raw('DATE(.detail_penjualan_pos.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                ->where('penjualan_pos.pelanggan_id', $request->pelanggan)
                ->where('detail_penjualan_pos.warung_id', Auth::user()->id_warung);
        }

        return $query_sub_total_penjualan;
    }

}
