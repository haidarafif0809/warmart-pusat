<?php

namespace App\Http\Controllers;

use App\PenjualanPos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanBucketSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    //METHOD PAGINATION
    public function dataPagination($bucket_size_pos, $array_bucket_size_pos)
    {
        $respons['current_page']   = $bucket_size_pos->currentPage();
        $respons['data']           = $array_bucket_size_pos;
        $respons['first_page_url'] = url('/laporan-bucket-size/view?page=' . $bucket_size_pos->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $bucket_size_pos->lastPage();
        $respons['last_page_url']  = url('/laporan-bucket-size/view?page=' . $bucket_size_pos->lastPage());
        $respons['next_page_url']  = $bucket_size_pos->nextPageUrl();
        $respons['path']           = url('/laporan-bucket-size/view');
        $respons['per_page']       = $bucket_size_pos->perPage();
        $respons['prev_page_url']  = $bucket_size_pos->previousPageUrl();
        $respons['to']             = $bucket_size_pos->perPage();
        $respons['total']          = $bucket_size_pos->total();

        return $respons;
    }

    public function prosesLaporanBucketSize(Request $request)
    {
        $satu                     = 1;
        $kelipatan                = 100000;
        $dari_tanggal             = '2018-01-01';
        $sampai_tanggal           = '2018-02-12';
        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->first();
        $total_penjualan_terbesar = $satu + $data_penjualan_pos->total;

        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur           = PenjualanPos::countFaktur($dari_tanggal, $sampai_tanggal)->first()->no_faktur;
            $total_faktur_kelipatan = PenjualanPos::countFaktur($dari_tanggal, $sampai_tanggal)
                ->whereBetween('total', array($satu, $kelipatan))
                ->first()->no_faktur;

            $respons['kelipatan'][]                = $satu . " - " . $kelipatan;
            $respons['datasets']['total_faktur'][] = $total_faktur_kelipatan;

            $data_kelipatan = 100000;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

}
