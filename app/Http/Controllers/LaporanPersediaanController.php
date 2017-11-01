<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Hpp;
use App\Barang;
use Laratrust; 
use Auth;


class LaporanPersediaanController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('user-must-warung');
	}


	public function index(Request $request, Builder $htmlBuilder)
	{ 
		if ($request->ajax()) {  
			$barang = Barang::with('satuan')->where('id_warung', Auth::user()->id_warung)->where('hitung_stok',1);
			return Datatables::of($barang) 
			->addColumn('stok', function($hpp){
				$transaksi_kas = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as jumlah_kas')])->where('id_produk',$hpp->id)->where('warung_id',$hpp->id_warung)->first();
				return $transaksi_kas->jumlah_kas;
			})
			->addColumn('nilai', function($hpp){ 
				$nila_masuk = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk')])->where('id_produk',$hpp->id)->where('jenis_hpp',1)->where('warung_id',Auth::user()->id_warung)->first();
				$nila_keluar = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_keluar')])->where('id_produk',$hpp->id)->where('jenis_hpp',2)->where('warung_id',Auth::user()->id_warung)->first();
				$prose_total_persedian = $nila_masuk->total_masuk - $nila_keluar->total_keluar; 
				$total_persedian = number_format($prose_total_persedian,0,',','.');

				return $total_persedian;
			})
			->addColumn('hpp', function($hpp){

				$total_nilai = Hpp::select([DB::raw('IFNULL(SUM(total_nilai),0) as total_masuk'),DB::raw('IFNULL(SUM(jumlah_masuk),0) as jumlah_masuk')])->where('id_produk',$hpp->id)->where('jenis_hpp',1)->where('warung_id',Auth::user()->id_warung)->first(); 
				if ($total_nilai->total_masuk == 0 || $total_nilai->jumlah_masuk == 0) {
					$hpp = 0;
				} 
				else {
					$proses_hpp = $total_nilai->total_masuk / $total_nilai->jumlah_masuk;
					$hpp = round($proses_hpp,2);
				}
				return $hpp;
				
			})->make(true);
		}
		$html = $htmlBuilder 
		->addColumn(['data' => 'kode_barang', 'name' => 'kode_barang', 'title' => 'Kode Produk'])
		->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama Produk'])
		->addColumn(['data' => 'satuan.nama_satuan', 'name' => 'satuan.nama_satuan', 'title' => 'Satuan', 'orderable' => false, 'searchable'=>false])
		->addColumn(['data' => 'stok', 'name' => 'stok', 'title' => 'Stok', 'orderable' => false, 'searchable'=>false])
		->addColumn(['data' => 'nilai', 'name' => 'nilai', 'title' => 'Nilai', 'orderable' => false, 'searchable'=>false])
		->addColumn(['data' => 'hpp', 'name' => 'hpp', 'title' => 'Hpp', 'orderable' => false, 'searchable'=>false]); 
		return view('laporan_persediaan')->with(compact('html')); 

	}
}
