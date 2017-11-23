<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;
use Laravel\Scout\Searchable;


class Barang extends Model
{
    //       
	use AuditableTrait;
	use LogsActivity;
	use Searchable;


	protected $fillable = ['kode_barang','kode_barcode','nama_barang', 'harga_beli','harga_jual','satuan_id','kategori_barang_id','status_aktif','hitung_stok','id_warung', 'deskripsi_produk', 'konfirmasi_admin'];

	public function satuan()
	{
		return $this->hasOne('App\Satuan','id','satuan_id');
	}

	public function kategori_barang()
	{
		return $this->hasOne('App\KategoriBarang','id','kategori_barang_id');
	}

	public function warung()
	{
		return $this->hasOne('App\Warung','id','id_warung');
	}

	public function getHppAttribute(){

		$hpp_masuk = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk), 0) as total_produk_masuk'), DB::raw('IFNULL(SUM(total_nilai), 0) as total_nilai_masuk')])
		->where('id_produk', $this->id)
		->where('warung_id', $this->id_warung)->where('jenis_hpp', 1)->first();

		$hpp_produk = $hpp_masuk->total_nilai_masuk / $hpp_masuk->total_produk_masuk;

		return $hpp_produk;
	}

	public function getNamaAttribute(){
		return title_case($this->nama_barang);
	}

	public function getRupiahAttribute(){
		return 'Rp '. number_format($this->harga_jual,0,',','.');
	}

	public function getStokAttribute()
	{
		
		$stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $this->id)->first();

		return $stok->stok_produk;
		
	}



}
