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

	public function subtotalLaporan($total_hpp, $total_hpp_masuk, $total_hpp_keluar)
	{
		$total_stok_awal  = $total_hpp->stok_awal;
		$total_nilai_awal = $total_hpp->total_awal;
		$total_stok_masuk  = $total_hpp_masuk->stok_masuk;
		$total_nilai_masuk = $total_hpp_masuk->total_masuk;
		$total_stok_keluar  = $total_hpp_keluar->stok_keluar;
		$total_nilai_keluar = $total_hpp_keluar->total_keluar;
		$total_stok_akhir  = ($total_stok_awal + $total_stok_masuk) - $total_stok_keluar;
		$total_nilai_akhir = ($total_nilai_awal + $total_nilai_masuk) - $total_nilai_keluar;

		$response['total_stok_awal']  = round($total_stok_awal, 2);
		$response['total_nilai_awal'] = round($total_nilai_awal, 2);
		$response['total_stok_masuk']  = round($total_stok_masuk, 2);
		$response['total_nilai_masuk'] = round($total_nilai_masuk, 2);
		$response['total_stok_keluar']  = round($total_stok_keluar, 2);
		$response['total_nilai_keluar'] = round($total_nilai_keluar, 2);
		$response['total_stok_akhir']  = round($total_stok_akhir, 2);
		$response['total_nilai_akhir'] = round($total_nilai_akhir, 2);

		return $response;
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

	public function pencarian(Request $request)
	{
		$daftar_produk = Barang::cariDaftarProduk($request)->paginate(10);

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

	public function subtotalMutasiStok(Request $request)
	{
		$total_hpp = Hpp::totalAwal($request);
		$total_hpp_masuk = Hpp::totalMasuk($request);
		$total_hpp_keluar = Hpp::totalKeluar($request);

		$response = $this->subtotalLaporan($total_hpp, $total_hpp_masuk, $total_hpp_keluar);

		return response()->json($response);
	}
}
