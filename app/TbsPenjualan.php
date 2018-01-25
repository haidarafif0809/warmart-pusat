<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class TbsPenjualan extends Model
{
    use AuditableTrait;
    protected $fillable   = ['session_id', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id', 'ppn'];
    protected $primaryKey = 'id_tbs_penjualan';

    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }
    public function getNamaProdukAttribute()
    {
        return title_case($this->produk->nama_barang);
    }
    
    // SCOPE PENCARIAN TBS PENJUALAN
    public function scopePencarian($query,$user_warung,$session_id,$request){

        $query->select('tbs_penjualans.id_tbs_penjualan AS id_tbs_penjualan', 'tbs_penjualans.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_penjualans.id_produk AS id_produk', 'tbs_penjualans.potongan AS potongan', 'tbs_penjualans.subtotal AS subtotal', 'tbs_penjualans.harga_produk AS harga_produk','barangs.harga_jual AS harga_jual')
        ->leftJoin('barangs', 'barangs.id', '=', 'tbs_penjualans.id_produk')
        ->where('warung_id', $user_warung)
        ->where('session_id', $session_id)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%');

        })->orderBy('tbs_penjualans.id_tbs_penjualan', 'desc');

        return $query;

    }

    public function subtotalTbs($user_warung,$session_id)
    {
        $tbs_penjualan = TbsPenjualan::select([DB::raw('SUM(subtotal) as subtotal')])->where('warung_id', $user_warung)->where('session_id', $session_id)->first();
        if ($tbs_penjualan->subtotal == null || $tbs_penjualan->subtotal == '') {
          return 0;
      }
      else{
        return $tbs_penjualan->subtotal;
    }
}
}
