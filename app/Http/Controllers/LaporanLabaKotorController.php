<?php

namespace App\Http\Controllers;

use App\Customer;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
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

    public function dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view)
    {

        $respons['current_page']   = $laporan_laba_kotor->currentPage();
        $respons['data']           = $array_laba_kotor;
        $respons['first_page_url'] = url('/laporan-laba-kotor/' . $link_view . '?page=' . $laporan_laba_kotor->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_laba_kotor->lastPage();
        $respons['last_page_url']  = url('/laporan-laba-kotor/' . $link_view . '?page=' . $laporan_laba_kotor->lastPage());
        $respons['next_page_url']  = $laporan_laba_kotor->nextPageUrl();
        $respons['path']           = url('/laporan-laba-kotor/' . $link_view . '');
        $respons['per_page']       = $laporan_laba_kotor->perPage();
        $respons['prev_page_url']  = $laporan_laba_kotor->previousPageUrl();
        $respons['to']             = $laporan_laba_kotor->perPage();
        $respons['total']          = $laporan_laba_kotor->total();

        return $respons;
    }

    public function subtotalLaporan($sub_total_penjualan, $potongan, $sub_hpp)
    {

        $subtotal_penjualan  = $sub_total_penjualan->subtotal;
        $subtotal_hpp        = $sub_hpp->total_hpp;
        $subtotal_laba_kotor = $subtotal_penjualan - $subtotal_hpp;
        $subtotal_potongan   = $potongan;
        $subtotal_laba_jual  = $subtotal_laba_kotor - $subtotal_potongan;

        $response['subtotal_penjualan']  = $subtotal_penjualan;
        $response['subtotal_hpp']        = $subtotal_hpp;
        $response['subtotal_laba_kotor'] = $subtotal_laba_kotor;
        $response['subtotal_potongan']   = $subtotal_potongan;
        $response['subtotal_laba_jual']  = $subtotal_laba_jual;

        return $response;
    }

    public function prosesLaporanLabaKotor(Request $request)
    {
        $laporan_laba_kotor = PenjualanPos::laporanLabaKotorPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan_pos', $laba_kotor->id)->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal]);
        }
        $link_view = 'view';
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function prosesLaporanLabaKotorPesanan(Request $request)
    {
        $laporan_laba_kotor = Penjualan::laporanLabaKotorPesanan($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'penjualan')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan = DetailPenjualan::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan', $laba_kotor->id)->first();
            $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - 0;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal]);
        }
        $link_view = 'view-pesanan';
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $laporan_laba_kotor = PenjualanPos::cariLaporanLabaKotorPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan_pos', $laba_kotor->id)->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal]);
        }
        $link_view = 'view';
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function pencarianPesanan(Request $request)
    {
        $laporan_laba_kotor = Penjualan::cariLaporanLabaKotorPesanan($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'penjualan')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan = DetailPenjualan::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan', $laba_kotor->id)->first();
            $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - 0;

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal]);
        }
        $link_view = 'view-pesanan';
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function subtotalLabaKotor(Request $request)
    {
        //SUBTOTAL KESELURUHAN
        $sub_total_penjualan = DetailPenjualanPos::subtotalLaporanLabaKotor($request)->first();

        //DISKON KESELURUHAN
        $sub_potongan = PenjualanPos::potonganLaporanLabaKotor($request)->first();
        $potongan     = $sub_potongan->potongan;
        //TOTAL HPP KESELURUHAN
        $jenis_transaksi = "PenjualanPos";
        $sub_hpp         = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();

        $response = $this->subtotalLaporan($sub_total_penjualan, $potongan, $sub_hpp);

        return response()->json($response);
    }

    public function subtotalLabaKotorPesanan(Request $request)
    {
        //SUBTOTAL KESELURUHAN
        $sub_total_penjualan = DetailPenjualan::subtotalLaporanLabaKotorPesanan($request)->first();
        $potongan            = 0;
        //TOTAL HPP KESELURUHAN
        $jenis_transaksi = "penjualan";
        $sub_hpp         = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();

        $response = $this->subtotalLaporan($sub_total_penjualan, $potongan, $sub_hpp);

        return response()->json($response);
    }
}
