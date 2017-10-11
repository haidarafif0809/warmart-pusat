<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class Barang extends Model
{
    //       
	    use AuditableTrait;
	    use LogsActivity;


    	protected $fillable = ['kode_barang','kode_barcode','nama_barang', 'harga_beli','harga_jual','satuan_id','kategori_barang_id','status_aktif','hitung_stok','id_warung'];

      	public function satuan()
		  {
		  	return $this->hasOne('App\Satuan','id','satuan_id');
		  }

    	public function kategori_barang()
		  {
			return $this->hasOne('App\KategoriBarang','id','kategori_barang_id');
		  }


}
