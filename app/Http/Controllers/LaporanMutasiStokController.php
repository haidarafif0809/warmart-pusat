<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barang;
use App\Hpp;

class LaporanMutasiStokController extends Controller
{
	public function __construct()
	{
		$this->middleware('user-must-warung');
	}

	//METHOD PAGINATION
	public function dataPagination($laporan_mutasi_kas, $array_mutasi_kas)
	{
		$respons['current_page']   = $laporan_mutasi_kas->currentPage();
		$respons['data']           = $array_mutasi_kas;
		$respons['first_page_url'] = url('/laporan-mutasi-stok/view?page=' . $laporan_mutasi_kas->firstItem());
		$respons['from']           = 1;
		$respons['last_page']      = $laporan_mutasi_kas->lastPage();
		$respons['last_page_url']  = url('/laporan-mutasi-stok/view?page=' . $laporan_mutasi_kas->lastPage());
		$respons['next_page_url']  = $laporan_mutasi_kas->nextPageUrl();
		$respons['path']           = url('/laporan-mutasi-stok/view');
		$respons['per_page']       = $laporan_mutasi_kas->perPage();
		$respons['prev_page_url']  = $laporan_mutasi_kas->previousPageUrl();
		$respons['to']             = $laporan_mutasi_kas->perPage();
		$respons['total']          = $laporan_mutasi_kas->total();

		return $respons;
	}



	public function prosesLaporanMutasiStok(Request $request)
	{
		$daftar_produk = Barang::daftarProduk()->paginate(10);

		$array_mutasi_kas = array();
		foreach ($daftar_produk as $daftar_produks) {
			$hpp = Hpp::dataAwal($daftar_produks, $request);
			$hpp_masuk = Hpp::dataMasuk($daftar_produks, $request);
			$hpp_keluar = Hpp::dataKeluar($daftar_produks, $request);
			
			$stok_awal = $hpp->stok_awal;
			$total_awal = $hpp->total_awal;
			
			$stok_masuk = $hpp_masuk->stok_masuk;
			$total_masuk = $hpp_masuk->total_masuk;
			
			$stok_keluar = $hpp_keluar->stok_keluar;
			$total_keluar = $hpp_keluar->total_keluar;
			
			$stok_akhir = ($stok_awal + $stok_masuk) - $stok_keluar;
			$total_akhir = ($total_awal + $total_masuk) - $total_keluar;

			array_push($array_mutasi_kas, ['daftar_produks' => $daftar_produks, 'stok_awal' => $stok_awal, 'total_awal' => $total_awal, 'stok_masuk' => $stok_masuk, 'total_masuk' => $total_masuk, 'stok_keluar' => $stok_keluar, 'total_keluar' => $total_keluar, 'stok_akhir' => $stok_akhir, 'total_akhir' => $total_akhir]);
		}
        //DATA PAGINATION
		$respons = $this->dataPagination($daftar_produk, $array_mutasi_kas);
		return response()->json($respons);
	}
}
