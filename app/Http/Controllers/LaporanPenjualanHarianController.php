<?php

namespace App\Http\Controllers;

use App\Penjualan;
use App\PenjualanPos;
use Illuminate\Http\Request;

class LaporanPenjualanHarianController extends Controller
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

    public function prosesLaporanPenjualanHarian(Request $request, $dari_tanggal, $sampai_tanggal)
    {
        $data_penjualan_pos = PenjualanPos::dataPenjualanHarian($request)->get();

        foreach ($data_penjualan_pos as $penjualan_pos) {
            $respons['tanggal'][] = $penjualan_pos->tanggal;
            $respons['total'][]   = $penjualan_pos->total;
        }
        return response()->json($respons);
    }

    public function prosesLaporanPenjualanHarianOnline(Request $request, $dari_tanggal, $sampai_tanggal)
    {
        $data_penjualan = Penjualan::dataPenjualanHarian($request)->get();

        foreach ($data_penjualan as $penjualan) {
            $respons['tanggal'][] = $penjualan->tanggal;
            $respons['total'][]   = $penjualan->total;
        }
        return response()->json($respons);
    }
}
