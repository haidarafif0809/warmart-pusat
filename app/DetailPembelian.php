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

	public function tanggalSql($tangal)
	{
		$date        = date_create($tangal);
		$date_format = date_format($date, "Y-m-d");
		return $date_format;
	}

	// LAPORAN PEMBELIAN /PRODUK
	public function scopeLaporanPembelianProduk($query_laporan_pembelian, $request)
	{
		if ($request->suplier == "" && $request->produk != "") {
			$query_laporan_pembelian = DetailPembelian::select(['detail_pembelians.no_faktur', 'detail_pembelians.id_produk', 'detail_pembelians.jumlah_produk', 'detail_pembelians.harga_produk', 'detail_pembelians.subtotal', 'detail_pembelians.tax', 'detail_pembelians.potongan', 'satuans.nama_satuan', 'supliers.nama_suplier', 'barangs.kode_barang', 'barangs.nama_barang'])
			->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')
			->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelians.satuan_id')
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('detail_pembelians.id_produk', $request->produk)
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');
			# code...
		}elseif ($request->suplier != "" && $request->produk == "") {
			$query_laporan_pembelian = DetailPembelian::select(['detail_pembelians.no_faktur', 'detail_pembelians.id_produk', 'detail_pembelians.jumlah_produk', 'detail_pembelians.harga_produk', 'detail_pembelians.subtotal', 'detail_pembelians.tax', 'detail_pembelians.potongan', 'satuans.nama_satuan', 'supliers.nama_suplier', 'barangs.kode_barang', 'barangs.nama_barang'])
			->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')
			->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelians.satuan_id')
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('pembelians.suplier_id', $request->suplier)
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');
			# code...		
		}elseif ($request->suplier != "" && $request->produk != "") {
			$query_laporan_pembelian = DetailPembelian::select(['detail_pembelians.no_faktur', 'detail_pembelians.id_produk', 'detail_pembelians.jumlah_produk', 'detail_pembelians.harga_produk', 'detail_pembelians.subtotal', 'detail_pembelians.tax', 'detail_pembelians.potongan', 'satuans.nama_satuan', 'supliers.nama_suplier', 'barangs.kode_barang', 'barangs.nama_barang'])
			->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')
			->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelians.satuan_id')
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('detail_pembelians.id_produk', $request->produk)
			->where('pembelians.suplier_id', $request->suplier)
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');
			# code...
		}else{
			$query_laporan_pembelian = DetailPembelian::select(['detail_pembelians.no_faktur', 'detail_pembelians.id_produk', 'detail_pembelians.jumlah_produk', 'detail_pembelians.harga_produk', 'detail_pembelians.subtotal', 'detail_pembelians.tax', 'detail_pembelians.potongan', 'satuans.nama_satuan', 'supliers.nama_suplier', 'barangs.kode_barang', 'barangs.nama_barang'])
			->leftJoin('barangs', 'barangs.id', '=', 'detail_pembelians.id_produk')
			->leftJoin('satuans', 'satuans.id', '=', 'detail_pembelians.satuan_id')
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->leftJoin('supliers', 'supliers.id', '=', 'pembelians.suplier_id')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');

		}

		return $query_laporan_pembelian;
	}

	// SUBTOTAL LAPORAN PEMBELIAN /PRODUK
	public function scopeSubtotalLaporanPembelianProduk($query_subtotal_laporan_pembelian, $request)
	{
		if ($request->suplier == "" && $request->produk != "") {
			$query_subtotal_laporan_pembelian = DetailPembelian::select(DB::raw('SUM(detail_pembelians.jumlah_produk) as jumlah_produk'), DB::raw('SUM(detail_pembelians.potongan) as potongan'), DB::raw('SUM(detail_pembelians.tax) as pajak'), DB::raw('SUM(detail_pembelians.subtotal) as subtotal'))
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('detail_pembelians.id_produk', $request->produk)
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');
			# code...
		}elseif ($request->suplier != "" && $request->produk == "") {
			$query_subtotal_laporan_pembelian = DetailPembelian::select(DB::raw('SUM(detail_pembelians.jumlah_produk) as jumlah_produk'), DB::raw('SUM(detail_pembelians.potongan) as potongan'), DB::raw('SUM(detail_pembelians.tax) as pajak'), DB::raw('SUM(detail_pembelians.subtotal) as subtotal'))
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('pembelians.suplier_id', $request->suplier)
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');
			# code...		
		}elseif ($request->suplier != "" && $request->produk != "") {
			$query_subtotal_laporan_pembelian = DetailPembelian::select(DB::raw('SUM(detail_pembelians.jumlah_produk) as jumlah_produk'), DB::raw('SUM(detail_pembelians.potongan) as potongan'), DB::raw('SUM(detail_pembelians.tax) as pajak'), DB::raw('SUM(detail_pembelians.subtotal) as subtotal'))
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('detail_pembelians.id_produk', $request->produk)
			->where('pembelians.suplier_id', $request->suplier)
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');
			# code...
		}else{
			$query_subtotal_laporan_pembelian = DetailPembelian::select(DB::raw('SUM(detail_pembelians.jumlah_produk) as jumlah_produk'), DB::raw('SUM(detail_pembelians.potongan) as potongan'), DB::raw('SUM(detail_pembelians.tax) as pajak'), DB::raw('SUM(detail_pembelians.subtotal) as subtotal'))
			->leftJoin('pembelians', 'pembelians.no_faktur', '=', 'detail_pembelians.no_faktur')
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
			->where(DB::raw('DATE(detail_pembelians.created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
			->where('pembelians.warung_id', Auth::user()->id_warung)
			->orderBy('detail_pembelians.created_at', 'desc');

		}

		return $query_subtotal_laporan_pembelian;
	}
} 