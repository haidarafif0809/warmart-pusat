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

            if ($total_faktur == 0 or $total_faktur_kelipatan == 0) {
                $persentase = 0;
            } else {
                $persentase = ($total_faktur_kelipatan / $total_faktur) * 100;
            }

            $respons['kelipatan'][]    = $satu . " - " . $kelipatan;
            $respons['total_faktur'][] = $total_faktur_kelipatan;
            $respons['color'][]        = $this->random_color();
            $respons['persentase'][]   = $persentase;

            $data_kelipatan = 100000;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

}
