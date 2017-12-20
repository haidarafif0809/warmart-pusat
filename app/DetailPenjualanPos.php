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
}
