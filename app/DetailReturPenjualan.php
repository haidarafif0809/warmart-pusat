<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;

class DetailReturPenjualan extends Model
{
    use AuditableTrait;
    protected $fillable   = ['no_faktur_retur', 'no_faktur_penjualan', 'id_produk', 'jumlah_jual', 'jumlah_retur', 'id_satuan', 'id_satuan_jual', 'harga_produk', 'subtotal', 'tax','potongan','warung_id','id_pelanggan','satuan_dasar','created_at'];
    protected $primaryKey = 'id_detail_retur_penjualan';

        // relasi ke produk
	public function produk()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}

}
