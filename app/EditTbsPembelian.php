<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class EditTbsPembelian extends Model
{
    use AuditableTrait;
    protected $fillable   = ['session_id', 'no_faktur', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id', 'ppn', 'tax_include', 'satuan_dasar', 'status_harga', 'faktur_order', 'faktur_penerimaan', 'suplier_id'];
    protected $primaryKey = 'id_edit_tbs_pembelians';

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

    public function subtotalTbs($user_warung, $no_faktur)
    {
        $tbs_penjualan = EditTbsPembelian::select([DB::raw('SUM(subtotal) as subtotal')])->where('warung_id', $user_warung)->where('no_faktur', $no_faktur)->first();
        if ($tbs_penjualan->subtotal == null || $tbs_penjualan->subtotal == '') {
            return 0;
        } else {
            return $tbs_penjualan->subtotal;
        }
    }
}
