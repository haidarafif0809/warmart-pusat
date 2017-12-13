<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailItemKeluar extends Model
{
    protected $fillable   = ['no_faktur', 'id_produk', 'jumlah_produk', 'warung_id'];
    protected $primaryKey = 'id_detail_item_keluar';

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

    public function cek_stok_produk_edit($id_produk, $no_faktur)
    {

        $stok_produk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_produk')])->where('id_produk', $id_produk)
            ->where('warung_id', Auth::user()->id_warung)->where('no_faktur', '!=', $no_faktur)->first();

        return $sisa_stok_keluar = $stok_produk->jumlah_produk;

    }

}
