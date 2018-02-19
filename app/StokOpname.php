<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class StokOpname extends Model
{
    use AuditableTrait;
    protected $fillable = ['no_faktur', 'produk_id', 'stok_sekarang', 'jumlah_fisik', 'selisih_fisik', 'harga', 'total', 'warung_id', 'keterangan'];

    public function scopeDataStokOpname($query_stok_opname)
    {
        $query_stok_opname->select(['stok_opnames.no_faktur', 'stok_opnames.produk_id', 'stok_opnames.stok_sekarang', 'stok_opnames.jumlah_fisik', 'stok_opnames.selisih_fisik', 'stok_opnames.harga', 'stok_opnames.total', 'stok_opnames.keterangan', 'stok_opnames.created_by', 'stok_opnames.created_at', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'stok_opnames.produk_id')
            ->where('stok_opnames.warung_id', Auth::user()->id_warung)->orderBy('stok_opnames.id', 'desc');

        return $query_stok_opname;
    }

    public function scopeCariDataStokOpname($query_cari_stok_opname, $request)
    {
        $search = $request->search;
        $query_cari_stok_opname->select(['stok_opnames.no_faktur', 'stok_opnames.produk_id', 'stok_opnames.stok_sekarang', 'stok_opnames.jumlah_fisik', 'stok_opnames.selisih_fisik', 'stok_opnames.harga', 'stok_opnames.total', 'stok_opnames.keterangan', 'stok_opnames.created_by', 'stok_opnames.created_at', 'barangs.nama_barang'])
            ->leftJoin('barangs', 'barangs.id', '=', 'stok_opnames.produk_id')
            ->where('stok_opnames.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('stok_opnames.no_faktur', 'LIKE', $search . '%')
                    ->orWhere('barangs.nama_barang', 'LIKE', $search . '%');
            })->orderBy('stok_opnames.id', 'desc');

        return $query_cari_stok_opname;
    }
}
