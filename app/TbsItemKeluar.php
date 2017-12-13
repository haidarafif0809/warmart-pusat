<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TbsItemKeluar extends Model
{
    //

    protected $fillable   = ['session_id', 'id_produk', 'jumlah_produk', 'warung_id'];
    protected $primaryKey = 'id_tbs_item_keluar';

    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }
    public function getTitleCaseProdukAttribute()
    {
        return title_case($this->produk->nama_barang);
    }
}
