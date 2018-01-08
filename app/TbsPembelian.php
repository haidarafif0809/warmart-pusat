<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TbsPembelian extends Model
{

    use AuditableTrait;
    protected $fillable   = ['session_id', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id', 'ppn'];
    protected $primaryKey = 'id_tbs_pembelian';

    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }
    public function getTitleCaseBarangAttribute()
    {
        return title_case($this->produk->nama_barang);
    }
    public function getPemisahSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', '.');
    }
    public function getPemisahHargaAttribute()
    {
        return number_format($this->harga_produk, 2, ',', '.');
    }
    public function getPemisahJumlahAttribute()
    {
        return number_format($this->jumlah_produk, 2, ',', '.');
    }
        public function getPemisahPotonganAttribute()
    {
        return number_format($this->potongan, 2, ',', '.');
    }
        public function getPemisahTaxAttribute()
    {
        return number_format($this->tax, 2, ',', '.');
    }
}
