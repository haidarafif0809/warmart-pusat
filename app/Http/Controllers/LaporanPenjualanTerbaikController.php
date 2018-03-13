<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailPenjualanPos;
use App\DetailPenjualan;
use App\Barang;
use Illuminate\Support\Facades\DB;
use Auth;
use Excel;

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
                        $url     = 'laporan-penjualan-terbaik/view-pos-data';
                        //DATA PAGINATION
                        $respons = $this->dataPagination($laporan_penjualan_terbaik, $array,$url);
                        return response()->json($respons);
            }

                public function prosesPenjualanTerbaikOnlineData(Request $request)
            {  
                    $data_barang  = Barang::where('id_warung', Auth::user()->id_warung)->count();
                    //cek atau hitung item di detail penjualan 
                    $hitung_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->count();
                        if ($hitung_penjualan_terbaik < $request->tampil_terbaik) {
                            $item_tampil_terbaik = $hitung_penjualan_terbaik;
                        }
                        else{
                            $item_tampil_terbaik = $request->tampil_terbaik;
                        }
                    //cek atau hitung item di detail penjualan 
                       $laporan_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->paginate(10);
                       $array = array();
                       foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                            $nama_barang =  title_case($laporan_penjualan_terbaiks->nama_barang);

                            array_push($array, ['laporan_penjualan_terbaik' => $laporan_penjualan_terbaiks,'nama_barang'=>$nama_barang]);
                        }
                        $url     = 'laporan-penjualan-terbaik/view-online-data';
                        //DATA PAGINATION
                        $respons = $this->dataPagination($laporan_penjualan_terbaik, $array,$url);
                        return response()->json($respons);
            }

                    public function pencarian(Request $request)
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
                       $laporan_penjualan_terbaik = DetailPenjualanPos::cariPenjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->paginate(10);
                       $array = array();
                       foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                            $nama_barang =  title_case($laporan_penjualan_terbaiks->nama_barang);
                            array_push($array, ['laporan_penjualan_terbaik' => $laporan_penjualan_terbaiks,'nama_barang'=>$nama_barang]);
                        }

                        $search = $request->search;
                        $url     = 'laporan-penjualan-terbaik/pencarian-pos-data';
                        //DATA PAGINATION
                        $respons = $this->paginationPencarianData($laporan_penjualan_terbaik, $array,$url,$search);
                        return response()->json($respons);
            }


                     public function pencarianOnline(Request $request)
            {  
                    $data_barang  = Barang::where('id_warung', Auth::user()->id_warung)->count();
                    //cek atau hitung item di detail penjualan 
                    $hitung_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->count();
                        if ($hitung_penjualan_terbaik < $request->tampil_terbaik) {
                            $item_tampil_terbaik = $hitung_penjualan_terbaik;
                        }
                        else{
                            $item_tampil_terbaik = $request->tampil_terbaik;
                        }
                    //cek atau hitung item di detail penjualan 
                       $laporan_penjualan_terbaik = DetailPenjualan::cariPenjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->paginate(10);
                       $array = array();
                       foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                            $nama_barang =  title_case($laporan_penjualan_terbaiks->nama_barang);
                            array_push($array, ['laporan_penjualan_terbaik' => $laporan_penjualan_terbaiks,'nama_barang'=>$nama_barang]);
                        }

                        $search = $request->search;
                        $url     = 'laporan-penjualan-terbaik/pencarian-online-data';
                        //DATA PAGINATION
                        $respons = $this->paginationPencarianData($laporan_penjualan_terbaik, $array,$url,$search);
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

                 public function dataPagination($laporan_penjualan_terbaik, $array,$url)
            {
                $respons['current_page']   = $laporan_penjualan_terbaik->currentPage();
                $respons['data']           = $array;
                $respons['first_page_url'] = url($url.'?page=' . $laporan_penjualan_terbaik->firstItem());
                $respons['from']           = 1;
                $respons['last_page']      = $laporan_penjualan_terbaik->lastPage();
                $respons['last_page_url']  = url($url.'?page=' . $laporan_penjualan_terbaik->lastPage());
                $respons['next_page_url']  = $laporan_penjualan_terbaik->nextPageUrl();
                $respons['path']           = url($url);
                $respons['per_page']       = $laporan_penjualan_terbaik->perPage();
                $respons['prev_page_url']  = $laporan_penjualan_terbaik->previousPageUrl();
                $respons['to']             = $laporan_penjualan_terbaik->perPage();
                $respons['total']          = $laporan_penjualan_terbaik->total();

                return $respons;
            }

                public function paginationPencarianData($laporan_penjualan_terbaik, $array, $url, $search)
            {
                //DATA PAGINATION
                $respons['current_page']   = $laporan_penjualan_terbaik->currentPage();
                $respons['data']           = $array;
                $respons['first_page_url'] = url($url . '?page=' . $laporan_penjualan_terbaik->firstItem() . '&search=' . $search);
                $respons['from']           = 1;
                $respons['last_page']      = $laporan_penjualan_terbaik->lastPage();
                $respons['last_page_url']  = url($url . '?page=' . $laporan_penjualan_terbaik->lastPage() . '&search=' . $search);
                $respons['next_page_url']  = $laporan_penjualan_terbaik->nextPageUrl();
                $respons['path']           = url($url);
                $respons['per_page']       = $laporan_penjualan_terbaik->perPage();
                $respons['prev_page_url']  = $laporan_penjualan_terbaik->previousPageUrl();
                $respons['to']             = $laporan_penjualan_terbaik->perPage();
                $respons['total']          = $laporan_penjualan_terbaik->total();
                //DATA PAGINATION

                return $respons;
            }

            public function labelSheet($sheet, $row)
            {
                $sheet->row($row, [
                    'Kode Produk',
                    'Nama Produk',
                    'Jumlah Terjual',
                ]);
                return $sheet;
            }

             //DOWNLOAD EXCEL - LAPORAN PENJUALAN 
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $tampil_terbaik)
    {
                    $request['dari_tanggal']   = $dari_tanggal;
                    $request['sampai_tanggal'] = $sampai_tanggal;
                    $request['tampil_terbaik'] = $tampil_terbaik;

                    //cek atau hitung item di detail penjualan POS
                    $hitung_penjualan_terbaik = DetailPenjualanPos::penjualanTerbaik($request)->count();
                        if ($hitung_penjualan_terbaik < $tampil_terbaik) {
                            $item_tampil_terbaik = $hitung_penjualan_terbaik;
                        }
                        else{
                            $item_tampil_terbaik = $tampil_terbaik;
                        }
                    //cek atau hitung item di detail penjualan POS
                       $laporan_penjualan_terbaik = DetailPenjualanPos::cariPenjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->get();
                  

                    //cek atau hitung item di detail penjualan online
                    $hitung_penjualan_terbaik = DetailPenjualan::penjualanTerbaik($request)->count();
                        if ($hitung_penjualan_terbaik < $tampil_terbaik) {
                            $item_tampil_terbaik = $hitung_penjualan_terbaik;
                        }
                        else{
                            $item_tampil_terbaik = $tampil_terbaik;
                        }
                    //cek atau hitung item di detail penjualan online
                       $laporan_penjualan_terbaik_online = DetailPenjualan::penjualanTerbaik($request)->groupBy('id_produk')->orderBy('jumlah_produk', 'desc')->limit($item_tampil_terbaik)->get();

                    Excel::create('Laporan Penjualan Terbaik', function ($excel) use ($request, $laporan_penjualan_terbaik, $laporan_penjualan_terbaik_online) {
                        // Set property
                        $excel->sheet('Laporan Penjualan Terbaik', function ($sheet) use ($request, $laporan_penjualan_terbaik, $laporan_penjualan_terbaik_online) {
                            $row = 1;
                            $sheet->row($row, [
                                'LAPORAN PENJUALAN POS',
                            ]);

                            $row   = 3;
                            $sheet = $this->labelSheet($sheet, $row);
                            if ($laporan_penjualan_terbaik->count() > 0) {
                                # code...
                                foreach ($laporan_penjualan_terbaik as $laporan_penjualan_terbaiks) {
                                    $nama_barang =  title_case($laporan_penjualan_terbaiks->nama_barang);
                                    $sheet->row(++$row, [
                                        $laporan_penjualan_terbaiks->kode_barang,
                                        $nama_barang,
                                        $laporan_penjualan_terbaiks->jumlah_produk,
                                    ]);
                                }
             
                            }
                            $row = ++$row + 3;
                            $sheet->row($row, [
                                'LAPORAN PENJUALAN ONLINE',
                            ]);

                            $row = ++$row + 1;

                            $sheet = $this->labelSheet($sheet, $row);
                            if ($laporan_penjualan_terbaik_online->count() > 0) {
                                foreach ($laporan_penjualan_terbaik_online as $laporan_penjualan_terbaik_onlines) {
                                    $nama_barang =  title_case($laporan_penjualan_terbaiks->nama_barang);
                                    $sheet->row(++$row, [
                                        $laporan_penjualan_terbaik_onlines->kode_barang,
                                        $nama_barang,
                                        $laporan_penjualan_terbaik_onlines->jumlah_produk,
                                    ]);
                                }
                            }
                        });
                    })->export('xls');

    }



}
