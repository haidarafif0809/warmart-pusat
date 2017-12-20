<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

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
    public function getSubtotalTbsAttribute()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }
    public function getHargaAttribute()
    {
        return number_format($this->harga_produk, 2, ',', '.');
    }
    public function getJumlahAttribute()
    {
        return number_format($this->jumlah_produk, 2, ',', '.');
    }
}
