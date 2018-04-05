<?php

namespace App\Http\Controllers;

use App\Penjualan;
use App\PenjualanPos;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class LaporanBucketSizeController extends Controller
{
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

    public function prosesLaporanBucketSize(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan)
    {
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = PenjualanPos::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->no_faktur;

            $respons['kelipatan'][]    = $satu . " - " . $kelipatan;
            $respons['total_faktur'][] = $total_faktur_kelipatan;
            $respons['color'][]        = $this->random_color();

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

        public function prosesLaporanBucketSizeData(Request $request)
    {
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $array = array();
        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = PenjualanPos::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->no_faktur;

            $respons['kelipatan']    = $satu . " - " . $kelipatan;
            $respons['total_faktur'] = $total_faktur_kelipatan;
            $respons['color']        = $this->random_color();

            array_push($array,$respons);

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($array);
    }

        public function prosesLaporanBucketSizeOnlineData(Request $request)
    {
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $array = array();
        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = Penjualan::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->id_pesanan;

            $respons['kelipatan']    = $satu . " - " . $kelipatan;
            $respons['total_faktur'] = $total_faktur_kelipatan;

            array_push($array,$respons);

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($array);
    }

    public function prosesLaporanBucketSizeOnline(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan)
    {
        $satu      = 1;
        $kelipatan = intval($request->kelipatan);

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = Penjualan::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->id_pesanan;

            $respons['kelipatan'][]    = $satu . " - " . $kelipatan;
            $respons['total_faktur'][] = $total_faktur_kelipatan;
            $respons['color'][]        = $this->random_color();

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

                    public function labelSheet($sheet, $row) 
            { 
                $sheet->row($row, [ 
                    'Kelipatan', 
                    'Jumlah Terjual', 
                ]); 
                return $sheet; 
            } 
 
                //DOWNLOAD EXCEL - LAPORAN PENJUALAN  
    public function downloadLaporanPos(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan) 
    { 
                    $request['dari_tanggal']   = $dari_tanggal; 
                    $request['sampai_tanggal'] = $sampai_tanggal; 
                    $request['kelipatan'] = $kelipatan; 
 
                     $satu      = 1; 
                     $kelipatan = $request->kelipatan; 
 
                     $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first(); 
                     $total_penjualan_terbesar = $satu + $data_penjualan_pos->total; 
 
                    Excel::create('Laporan Bucket Size POS', function ($excel) use ($request, $satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                        // Set property 
                        $excel->sheet('Laporan Bucket Size POS', function ($sheet) use ($request,$satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                            $row = 1; 
                            $sheet->row($row, [ 
                                'LAPORAN PENJUALAN POS', 
                            ]); 
 
                            $row   = 3; 
                            $sheet = $this->labelSheet($sheet, $row); 
 
                            while ($kelipatan <= $total_penjualan_terbesar) { 
                            $total_faktur_kelipatan = PenjualanPos::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->no_faktur; 
                                     
                                    $sheet->row(++$row, [ 
                                     $satu . " - " . $kelipatan, 
                                     $total_faktur_kelipatan, 
                                    ]); 
 
                                    $data_kelipatan = $request->kelipatan; 
                                    $kelipatan += $data_kelipatan; 
                                    $satu += $data_kelipatan; 
                            } 
              
                             
                        }); 
                    })->export('xls'); 
 
    } 

                    //DOWNLOAD EXCEL - LAPORAN PENJUALAN  
    public function downloadLaporanOnline(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan) 
    { 
                    $request['dari_tanggal']   = $dari_tanggal; 
                    $request['sampai_tanggal'] = $sampai_tanggal; 
                    $request['kelipatan'] = $kelipatan; 
 
                     $satu      = 1; 
                     $kelipatan = $request->kelipatan; 
 
                     $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first(); 
                     $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total; 
 
                    Excel::create('Laporan Bucket Size Online', function ($excel) use ($request, $satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                        // Set property 
                        $excel->sheet('Laporan Bucket Size Online', function ($sheet) use ($request,$satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                            $row = 1; 
                            $sheet->row($row, [ 
                                'LAPORAN PENJUALAN ONLINE', 
                            ]); 
 
                            $row   = 3; 
                            $sheet = $this->labelSheet($sheet, $row); 
 
                            while ($kelipatan <= $total_penjualan_terbesar) { 
                            $total_faktur_kelipatan = Penjualan::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->id_pesanan; 
                                     
                                    $sheet->row(++$row, [ 
                                     $satu . " - " . $kelipatan, 
                                     $total_faktur_kelipatan, 
                                    ]); 
 
                                    $data_kelipatan = $request->kelipatan; 
                                    $kelipatan += $data_kelipatan; 
                                    $satu += $data_kelipatan; 
                            } 
              
                             
                        }); 
                    })->export('xls'); 
 
    } 

}
