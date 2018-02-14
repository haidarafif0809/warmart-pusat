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

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->first();
        $total_penjualan_terbesar = $satu + $data_penjualan_pos->total;

        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur           = PenjualanPos::countFaktur($request)->first()->no_faktur;
            $total_faktur_kelipatan = PenjualanPos::countFaktur($request)
                ->whereBetween('total', array($satu, $kelipatan))
                ->first()->no_faktur;

            $respons['kelipatan'][]    = $satu . " - " . $kelipatan;
            $respons['total_faktur'][] = $total_faktur_kelipatan;
            $respons['color'][]        = $this->random_color();

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

}
