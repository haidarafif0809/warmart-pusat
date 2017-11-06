<?php 

namespace App; 

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\DB; 
use Yajra\Auditable\AuditableTrait; 

use Session; 
use Auth; 

class DetailPembelian extends Model 
{ 
	use AuditableTrait; 
	protected $fillable = ['no_faktur','satuan_id','id_produk','jumlah_produk','harga_produk','subtotal','tax','potongan','warung_id','ppn']; 
	protected $primaryKey = 'id_detail_pembelian'; 
	
// relasi ke produk 
	public function produk(){ 
		return $this->hasOne('App\Barang','id','id_produk'); 
	} 
	
	public function getTitleCaseBarangAttribute() 
	{ 
		return title_case($this->produk->nama_barang); 
	} 
	public function getPemisahJumlahAttribute() 
	{   
		return number_format($this->jumlah_produk,2,',','.'); 
	} 
	public function getPemisahHargaAttribute() 
	{   
		return number_format($this->harga_produk,2,',','.'); 
	} 
	public function getPemisahPotonganAttribute() 
	{   
		return number_format($this->potongan,2,',','.'); 
	} 
	public function getPemisahTaxAttribute() 
	{   
		return number_format($this->tax,2,',','.'); 
	} 
	public function getPemisahSubtotalAttribute() 
	{   
		return number_format($this->subtotal,2,',','.'); 
	} 
} 