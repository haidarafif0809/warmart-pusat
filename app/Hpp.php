<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hpp extends Model
{
    //

    protected $fillable = ['no_faktur', 'no_faktur_hpp_masuk', 'no_faktur_hpp_keluar', 'id_produk', 'jenis_transaksi', 'jumlah_masuk', 'jumlah_keluar', 'harga_unit', 'total_nilai', 'jenis_hpp', 'warung_id', 'created_at'];

    protected $table = 'hpps';

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

    public function nilai($id_produk)
    {

        $nilai_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->where('id_produk', $id_produk)->where('jenis_hpp', 1)->where('warung_id', Auth::user()->id_warung)->first();

        $nilai_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->where('id_produk', $id_produk)->where('jenis_hpp', 2)->where('warung_id', Auth::user()->id_warung)->first();

        $prose_total_persedian = $nilai_masuk->total_masuk - $nilai_keluar->total_keluar;

        $total_persedian = number_format($prose_total_persedian, 0, ',', '.');

        return $total_persedian;

    }

    public function hpp($id_produk)
    {

        $total_nilai = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk'), DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_masuk')])->where('id_produk', $id_produk)->where('jenis_hpp', 1)->where('warung_id', Auth::user()->id_warung)->first();

        if ($total_nilai->total_masuk == 0 || $total_nilai->jumlah_masuk == 0) {
            $hpp = 0;
        } else {
            $proses_hpp = $total_nilai->total_masuk / $total_nilai->jumlah_masuk;
            $hpp        = round($proses_hpp, 2);
        }
        return $hpp;

    }

}
