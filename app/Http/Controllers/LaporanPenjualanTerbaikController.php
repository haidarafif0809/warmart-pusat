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

            //cek atau hitung item di detail penjualan 
            $hitung_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->count();
                if ($hitung_penjualan_terbaik < $tampil_terbaik) {
                    $item_tampil_terbaik = $hitung_penjualan_terbaik;
                }
                else{
                    $item_tampil_terbaik = $tampil_terbaik;
                }
            //cek atau hitung item di detail penjualan 

               $laporan_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->get();
               foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                    $respons['nama_barang'][] =  title_case($laporan_penjualan_terbaiks->nama_barang);
                   $respons['jumlah_produk'][]     = $laporan_penjualan_terbaiks->jumlah_produk;   
                   $respons['color'][]      = $this->random_color();  
           }
        //DATA PAGINATION
        return response()->json($respons);
     }


       public function prosesPenjualanTerbaikOnline(Request $request, $dari_tanggal, $sampai_tanggal, $tampil_terbaik)
    {  

            $data_barang  = Barang::where('id_warung', Auth::user()->id_warung)->count();
            
            //cek atau hitung item di detail penjualan 
            $hitung_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->count();
                if ($hitung_penjualan_terbaik < $tampil_terbaik) {
                    $item_tampil_terbaik = $hitung_penjualan_terbaik;
                }
                else{
                    $item_tampil_terbaik = $tampil_terbaik;
                }
            //cek atau hitung item di detail penjualan 
                
               $laporan_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->get();
               foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                    $respons['nama_barang'][] =  title_case($laporan_penjualan_terbaiks->nama_barang);
                   $respons['jumlah_produk'][]     = $laporan_penjualan_terbaiks->jumlah_produk;   
                   $respons['color'][]      = $this->random_color();  
           }
        //DATA PAGINATION
        return response()->json($respons);
     }

     

             public function prosesPenjualanTerbaikData(Request $request)
    {  

            $data_barang  = Barang::where('id_warung', Auth::user()->id_warung)->count();

            //cek atau hitung item di detail penjualan 
            $hitung_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->count();
                if ($hitung_penjualan_terbaik < $request->tampil_terbaik) {
                    $item_tampil_terbaik = $hitung_penjualan_terbaik;
                }
                else{
                    $item_tampil_terbaik = $request->tampil_terbaik;
                }
            //cek atau hitung item di detail penjualan 

               $laporan_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->paginate(10);

               $array = array();
               foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                    $nama_barang =  title_case($laporan_penjualan_terbaiks->nama_barang);

                    array_push($array, ['laporan_penjualan_terbaik' => $laporan_penjualan_terbaiks,'nama_barang'=>$nama_barang]);
                }

                //DATA PAGINATION
                $respons = $this->dataPagination($laporan_penjualan_terbaik, $array);
                return response()->json($respons);
     }



        public function cekTampilTerbaik(Request $request, $dari_tanggal, $sampai_tanggal, $jenis_penjualan)
    {  
                if ($jenis_penjualan == 0) {
                    # code...
                    $laporan_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->count();
                }else{
                    $laporan_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->count();
                } 
               $response['count_barang_terbaik'] = $laporan_penjualan_terbaik;
                //DATA PAGINATION
                return response()->json($response);
     }

         public function dataPagination($laporan_penjualan_terbaik, $array)
    {
        $respons['current_page']   = $laporan_penjualan_terbaik->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/laporan-pembelian-produk/view-pos-data?page=' . $laporan_penjualan_terbaik->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_penjualan_terbaik->lastPage();
        $respons['last_page_url']  = url('/laporan-pembelian-produk/view-pos-data?page=' . $laporan_penjualan_terbaik->lastPage());
        $respons['next_page_url']  = $laporan_penjualan_terbaik->nextPageUrl();
        $respons['path']           = url('/laporan-pembelian-produk/view-pos-data');
        $respons['per_page']       = $laporan_penjualan_terbaik->perPage();
        $respons['prev_page_url']  = $laporan_penjualan_terbaik->previousPageUrl();
        $respons['to']             = $laporan_penjualan_terbaik->perPage();
        $respons['total']          = $laporan_penjualan_terbaik->total();

        return $respons;
    }


}
