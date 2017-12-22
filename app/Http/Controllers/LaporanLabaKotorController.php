<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Hpp;
use App\Penjualan;
use App\PenjualanPos;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanLabaKotorController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function pilihPelanggan()
    {
        $pelanggan = Customer::select(['id', 'name'])->where('tipe_user', 3)->get();
        return response()->json($pelanggan);
    }

    public function dataPagination($laporan_laba_kotor, $array_laba_kotor)
    {

        $respons['current_page']   = $laporan_laba_kotor->currentPage();
        $respons['data']           = $array_laba_kotor;
        $respons['first_page_url'] = url('/laporan-laba-kotor/view?page=' . $laporan_laba_kotor->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_laba_kotor->lastPage();
        $respons['last_page_url']  = url('/laporan-laba-kotor/view?page=' . $laporan_laba_kotor->lastPage());
        $respons['next_page_url']  = $laporan_laba_kotor->nextPageUrl();
        $respons['path']           = url('/laporan-laba-kotor/view');
        $respons['per_page']       = $laporan_laba_kotor->perPage();
        $respons['prev_page_url']  = $laporan_laba_kotor->previousPageUrl();
        $respons['to']             = $laporan_laba_kotor->perPage();
        $respons['total']          = $laporan_laba_kotor->total();

        return $respons;
    }

    public function prosesLaporanLabaKotor(Request $request)
    {
        $laporan_laba_kotor = PenjualanPos::laporanLabaKotorPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $laba_kotor->total - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor);
        return response()->json($respons);
    }

    public function prosesLaporanLabaKotorPesanan(Request $request)
    {
        $laporan_laba_kotor = Penjualan::laporanLabaKotorPesanan($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'penjualan')->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $laba_kotor->total - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - 0;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $laporan_laba_kotor = PenjualanPos::cariLaporanLabaKotorPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $laba_kotor->total - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor);
        return response()->json($respons);
    }

    public function pencarianPesanan(Request $request)
    {
        $laporan_laba_kotor = Penjualan::cariLaporanLabaKotorPesanan($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'penjualan')->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $laba_kotor->total - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - 0;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor);
        return response()->json($respons);
    }
}
