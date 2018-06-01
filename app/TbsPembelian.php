<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class TbsPembelian extends Model
{

    use AuditableTrait;
    protected $fillable   = ['session_id', 'satuan_id', 'id_produk', 'jumlah_produk', 'harga_produk', 'subtotal', 'potongan', 'tax', 'warung_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'ppn', 'tax_include', 'satuan_dasar', 'status_harga', 'faktur_order', 'faktur_penerimaan'];
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

    public function subtotalTbs($user_warung, $session_id)
    {
        $tbs_pembelian = TbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->first();
        if ($tbs_pembelian->subtotal == null || $tbs_pembelian->subtotal == '') {
            return 0;
        } else {
            return $tbs_pembelian->subtotal;
        }
    }

    // Transaksi Tbs Pembelian
    public function scopeDataTransaksiTbsPembelian($query, $session_id, $user_warung)
    {
        $query->select('tbs_pembelians.id_tbs_pembelian AS id_tbs_pembelian', 'tbs_pembelians.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_pembelians.id_produk AS id_produk', 'tbs_pembelians.harga_produk AS harga_produk', 'tbs_pembelians.potongan AS potongan', 'tbs_pembelians.tax AS tax', 'tbs_pembelians.subtotal AS subtotal', 'tbs_pembelians.ppn AS ppn', 'tbs_pembelians.satuan_id AS satuan_id', 'satuans.nama_satuan')
        ->leftJoin('barangs', 'barangs.id', '=', 'tbs_pembelians.id_produk')
        ->leftJoin('satuans', 'satuans.id', '=', 'tbs_pembelians.satuan_id')
        ->where('tbs_pembelians.session_id', $session_id)->where('tbs_pembelians.warung_id', $user_warung);
        return $query;
    }
}
