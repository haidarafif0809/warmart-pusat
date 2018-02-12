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
        $satu = 1;

        $data_penjualan_pos = PenjualanPos::select([DB::raw('MAX(total) as total')])->first();
        $total_kelipatan    = $satu + $data_penjualan_pos->total;
        while (50000 <= $total_kelipatan) {
            $total_semua_faktur = PenjualanPos::select([DB::raw('COUNT(no_faktur)')])->first();
            $total_faktur       = PenjualanPos::select([DB::raw('COUNT(no_fakturr)')])
                ->whereBetween('total', array($satu, 50000))->first();
            $respons['kelipatan']    = $satu;
            $respons['total_faktur'] = $total_semua_faktur;
        }
        return response()->json($respons);
    }

}
