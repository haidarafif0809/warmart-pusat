<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;  
use SEOMeta;
use OpenGraph;
use Twitter;
use App\KeranjangBelanja; 
use App\Barang;
use App\Warung;
use App\Hpp;  
use App\PesananPelanggan;  
use App\DetailPesananPelanggan;  
use Jenssegers\Agent\Agent;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Auth;
use DB;

class PemesananController extends Controller
{ 

	public function selesaikanPemesanan()
	{ 
		SEOMeta::setTitle('War-Mart.id');
		SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		SEOMeta::setCanonical('https://war-mart.id');
		SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

		OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
		OpenGraph::setTitle('War-Mart.id');
		OpenGraph::setUrl('https://war-mart.id');
		OpenGraph::addProperty('type', 'articles'); 

		$agent = new Agent();

		$keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
		$cek_belanjaan = $keranjang_belanjaan->count();  

		$jumlah_produk = KeranjangBelanja::select([DB::raw('IFNULL(SUM(jumlah_produk),0) as total_produk')])->first();  
		//FOTO WARMART
		$logo_warmart = "".asset('/assets/img/examples/warmart_logo.png')."";

		$subtotal = 0; 
		foreach ($keranjang_belanjaan as $keranjang_belanjaans) {   
			$harga_produk = $keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk;  
			$subtotal = $subtotal += $harga_produk;  
		}  

		$user = Auth::user();

		return view('layouts.selesaikan_pemesanan',['keranjang_belanjaan'=>$keranjang_belanjaan,'cek_belanjaan'=>$cek_belanjaan,'agent'=>$agent,'jumlah_produk'=>$jumlah_produk,'logo_warmart'=>$logo_warmart,'subtotal'=>$subtotal,'user'=>$user]);
	}



	public function prosesSelesaikanPemesanan(Request $request)
	{  

		$keranjang_belanjaan = KeranjangBelanja::select('keranjang_belanjas.id_keranjang_belanja AS id_keranjang_belanja','keranjang_belanjas.id_produk AS id_produk','keranjang_belanjas.id_pelanggan AS id_pelanggan','keranjang_belanjas.jumlah_produk AS jumlah_produk','barangs.harga_jual AS harga_jual','barangs.id_warung AS id_warung')
		->leftJoin('barangs','keranjang_belanjas.id_produk','=','barangs.id')
		->where('id_pelanggan',Auth::user()->id);

		$keranjang = $keranjang_belanjaan->get(); 

		$id_warung = $keranjang_belanjaan->limit(1)->first()->id_warung; 
		$id_user = Auth::user()->id;

		$pesanan_pelanggan = PesananPelanggan::create([
			'id_pelanggan'     => $id_user,      
			'nama_pemesan'     => $request->name,              
			'no_telp_pemesan'     => $request->no_telp,
			'alamat_pemesan' => $request->alamat,
			'jumlah_produk'     => $request->jumlah_produk,
			'subtotal'     => $request->subtotal,
			'id_warung' 	=> $id_warung
		]);   

		$id_pesanan_pelanggan =  $pesanan_pelanggan->id;


		$warung = Warung::find($id_warung);

		$nomor_tujuan = $warung->no_telpon;


		$this->kirimSmsKeWarung($nomor_tujuan,$id_pesanan_pelanggan);

		foreach ($keranjang as $keranjang_belanjaans) {  

			DetailPesananPelanggan::create([
				'id_pesanan_pelanggan'     => $pesanan_pelanggan->id,              
				'id_produk'     => $keranjang_belanjaans['id_produk'],
				'id_pelanggan' => $keranjang_belanjaans['id_pelanggan'],
				'harga_produk' => $keranjang_belanjaans['harga_jual'],
				'jumlah_produk'     => $keranjang_belanjaans['jumlah_produk'], 
			]);   

			KeranjangBelanja::destroy($keranjang_belanjaans['id_keranjang_belanja']);

		}


		return redirect()->route('daftar_produk.index');

	}

	public function kirimSmsKeWarung($nomor_tujuan,$id_pesanan_pelanggan){



		$userkey = env('USERKEY');
		$passkey = env('PASSKEY');
		$isi_pesan = urlencode('Warmart: Assalamualaikum, Ada Pesanan baru Silakan Cek war-mart.id/detail-pesanan-warung/'.$id_pesanan_pelanggan);
		
		if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$nomor_tujuan.'&pesan='.$isi_pesan.''); 

            }

        }

    }
