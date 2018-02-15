<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PenjualanPos;
use App\Penjualan;
use Illuminate\Support\Facades\DB;

class GrafikJamTransaksiPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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

        public function prosesGrafikJamPenjualan(Request $request, $tanggal)
    {  

      $jam_kelipatan = 0;
      while ($jam_kelipatan <= 23) { 
            $laporan_jam_transaksi_penjualan = PenjualanPos::grafikJamTransaksiPenjualan($tanggal)->where(DB::raw('DATE_FORMAT(created_at, "%H")'), $jam_kelipatan)->first()->hitung;
           $respons['waktu_jual'][] =  $jam_kelipatan;
           $respons['hitung'][]     = $laporan_jam_transaksi_penjualan;   
           $respons['color'][]      = $this->random_color();   

            $data_kelipatan = 1;
            $jam_kelipatan += $data_kelipatan;
        }
        
        //DATA PAGINATION
        return response()->json($respons);
     }

        public function prosesGrafikJamPenjualanOnline(Request $request, $tanggal)
    {  

      $jam_kelipatan = 0;
      while ($jam_kelipatan <= 23) { 
            $laporan_jam_transaksi_penjualan = Penjualan::grafikJamTransaksiPenjualan($tanggal)->where(DB::raw('DATE_FORMAT(created_at, "%H")'), $jam_kelipatan)->first()->hitung;
           $respons['waktu_jual'][] =  $jam_kelipatan;
           $respons['hitung'][]     = $laporan_jam_transaksi_penjualan;   
           $respons['color'][]      = $this->random_color();   

            $data_kelipatan = 1;
            $jam_kelipatan += $data_kelipatan;
        }
        
        //DATA PAGINATION
        return response()->json($respons);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
