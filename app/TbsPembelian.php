<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class TbsPembelian extends Model
{

    use AuditableTrait;
    protected $fillable   = ['session_id', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id','created_by', 'updated_by','created_at', 'updated_at', 'ppn','tax_include'];
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

            public function subtotalTbs($user_warung,$session_id)
    {
        $tbs_pembelian = TbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
                if ($tbs_pembelian->subtotal == null || $tbs_pembelian->subtotal == '') {
                  return 0;
                 }else{
                return $tbs_pembelian->subtotal;
                }
    }
}
