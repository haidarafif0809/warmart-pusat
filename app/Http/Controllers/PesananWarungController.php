<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder; 
use Yajra\Datatables\Datatables; 
use Illuminate\Support\Facades\DB;  
use App\KeranjangBelanja; 
use App\Barang;
use App\Hpp;  
use App\PesananPelanggan;  
use App\DetailPesananPelanggan;   
use Auth; 

class PesananWarungController extends Controller
{
	public function __construct() 
	{ 
		$this->middleware('user-must-warung'); 
	} 

	public function pesananWarung(Request $request, Builder $htmlBuilder)
	{

		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		}else{ 
			
			if ($request->ajax()) { 
				$pesanan_warung = PesananPelanggan::with('pelanggan');
				return Datatables::of($pesanan_warung)
				->addColumn('konfirmasi_pesanan', function($konfirmasi_pesanan){
					$status = "";
					if ($konfirmasi_pesanan->konfirmasi_pesanan == 0) {
						$status .= '<b  style="color:red">Belum Di Konfirmasi</b>'; 
					}elseif ($konfirmasi_pesanan->konfirmasi_pesanan == 1) {
						$status .= '<b  style="color:orange">Sedang Konfirmasi</b>'; 
					}elseif ($konfirmasi_pesanan->konfirmasi_pesanan == 2) {
						$status .= '<b  style="color:#01573e">Sudah Di Konfirmasi</b>'; 
					}
					return $status;
				})->addColumn('subtotal', function($subtotal){
					$subtotal_baru =  number_format($subtotal->subtotal,0,',','.'); 
					return $subtotal_baru;
				})
				->addColumn('data_pengirim', function($data){
					return view('pesanan_warung.detail_pengirim', [ 
						'detail_pengirim'     => $data,
					]);
				}) ->addColumn('pemesan', function($pemesan){
					$data_pemesan = "". $pemesan->pelanggan->name ."(".$pemesan->pelanggan->no_telp.")" ; 
					return $data_pemesan;
				})->make(true);
			}
			$html = $htmlBuilder
			->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'Pesanan'])  
			->addColumn(['data' => 'pemesan', 'name' => 'pemesan', 'title' => 'Pemesan']) 
			->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah']) 
			->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Total'])  
			->addColumn(['data' => 'konfirmasi_pesanan', 'name' => 'konfirmasi_pesanan', 'title' => 'Status', 'orderable' => false, 'searchable'=>false])
			->addColumn(['data' => 'data_pengirim', 'name' => 'data_pengirim', 'title' => 'Pengiriman', 'orderable' => false, 'searchable'=>false]) ; 
			return view('pesanan_warung.index')->with(compact('html'));
		}
	}

	public function detailPesananWarung(Request $request, Builder $htmlBuilder,$id)
	{

		if (Auth::user()->id_warung == '') {
			Auth::logout();
			return response()->view('error.403');
		}else{ 

			$pesanan = PesananPelanggan::with('pelanggan')->find($id)->first();
			$detail_pesanan_pelanggan = DetailPesananPelanggan::with(['produk','pelanggan','pesanan_pelanggan'])->where('id_pesanan_pelanggan',$id)->get(); 

			$subtotal = 0;
			foreach ($detail_pesanan_pelanggan as $detail_pesanan_pelanggans) { 

				$harga_produk = $detail_pesanan_pelanggans->produk->harga_jual * $detail_pesanan_pelanggans->jumlah_produk;
				$subtotal = $subtotal += $harga_produk;

			}

			$pesanan->subtotal = $subtotal;
			$pesanan->save();

			$status_pesanan = ''; 
			if ($pesanan->konfirmasi_pesanan == 0) {
				$status_pesanan .= '<td><b  style="color:red">Belum Di Konfirmasi</b></td>'; 
			}elseif ($pesanan->konfirmasi_pesanan == 1) {
				$status_pesanan .= '<td><b  style="color:orange">Sudah Di Konfirmasi</b></td>'; 
			}elseif ($pesanan->konfirmasi_pesanan == 2) {
				$status_pesanan .= '<td><b  style="color:#01573e">Selesai</b></td>'; 
			}elseif ($pesanan->konfirmasi_pesanan == 3) {
				$status_pesanan .= '<td><b  style="color:red">Pesanan Di Batalkan</b></td>'; 
			}

			return view('pesanan_warung.detail_pesanan',['pesanan'=>$pesanan,'detail_pesanan' => $detail_pesanan_pelanggan,'status_pesanan' => $status_pesanan,'subtotal'=>$subtotal]); 
		}
	}


	public function konfirmasiPesananWarung($id)
	{ 
		PesananPelanggan::where('id',$id)->update(['konfirmasi_pesanan' => '1']);
		return redirect()->back();
	}

	public function selesaiKonfirmasiPesananWarung($id)
	{ 
		PesananPelanggan::where('id',$id)->update(['konfirmasi_pesanan' => '2']);
		return redirect()->back();
	}

	public function batalkanPesananWarung($id)
	{ 
		PesananPelanggan::where('id',$id)->update(['konfirmasi_pesanan' => '3']);
		return redirect()->back();
	}

	public function batalkanKonfirmasiPesananWarung($id)
	{ 
		PesananPelanggan::where('id',$id)->update(['konfirmasi_pesanan' => '0']);
		return redirect()->back();
	}

	public function tambahProdukPesananWarung($id)
	{ 
		$detail_pesanan = DetailPesananPelanggan::find($id); 
		$pesanan = PesananPelanggan::with('pelanggan')->find($detail_pesanan->id_pesanan_pelanggan);
		$pesanan->subtotal += $detail_pesanan->harga_produk;
		$pesanan->save();

		$detail_pesanan->jumlah_produk += 1;
		$detail_pesanan->save();

		return redirect()->back();
	}

	public function kurangProdukPesananWarung($id)
	{ 
		$detail_pesanan = DetailPesananPelanggan::find($id); 
		$pesanan = PesananPelanggan::with('pelanggan')->find($detail_pesanan->id_pesanan_pelanggan);
		$pesanan->subtotal -= $detail_pesanan->harga_produk;
		$pesanan->save();

		$detail_pesanan->jumlah_produk -= 1;
		$detail_pesanan->save();

		return redirect()->back();
	}

}