<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailPenjualanPos;
use App\DetailPenjualan;
use App\Barang;
use Illuminate\Support\Facades\DB;
use Auth;

class LaporanPenjualanTerbaikController extends Controller
{
    //
        public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function random_color()
    {
        return '#' . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

        public function prosesPenjualanTerbaik(Request $request, $dari_tanggal, $sampai_tanggal, $tampil_terbaik)
    {  

	       	$data_barang  = Barang::where('id_warung', Auth::user()->id_warung)->count();
	       	
	           $laporan_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($tampil_terbaik)->get();
	           foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
			        $respons['nama_barang'][] =  $laporan_penjualan_terbaiks->nama_barang;
		           $respons['jumlah_produk'][]     = $laporan_penjualan_terbaiks->jumlah_produk;   
		           $respons['color'][]      = $this->random_color();  
	       }
        //DATA PAGINATION
        return response()->json($respons);
     }

       public function prosesPenjualanTerbaikOnline(Request $request, $dari_tanggal, $sampai_tanggal, $tampil_terbaik)
    {  

	       	$data_barang  = Barang::where('id_warung', Auth::user()->id_warung)->count();
	       	
	           $laporan_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($tampil_terbaik)->get();
	           foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
			        $respons['nama_barang'][] =  $laporan_penjualan_terbaiks->nama_barang;
		           $respons['jumlah_produk'][]     = $laporan_penjualan_terbaiks->jumlah_produk;   
		           $respons['color'][]      = $this->random_color();  
	       }
        //DATA PAGINATION
        return response()->json($respons);
     }

}
