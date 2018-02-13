<?php

namespace App\Http\Controllers;

use App\Customer;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\Hpp;
use App\Penjualan;
use App\PenjualanPos;
use App\SettingAplikasi;
use App\Warung;
use Auth;
use Excel;
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

//PROSES LABA KOTOR POS (OFFLINE)
    public function prosesLaporanLabaKotor(Request $request)
    {
        $laporan_laba_kotor = PenjualanPos::laporanLabaKotorPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan_pos', $laba_kotor->id)->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

            if ($laba_kotor->pelanggan_id == 0) {
                $pelanggan = "Umum";
            } else {
                $pelanggan = $laba_kotor->name;
            }

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal, 'pelanggan' => $pelanggan]);
        }
        $link_view = 'view';
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

//PROSES LABA KOTOR PESANAN (ONLINE)
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

    public function tanggal($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "d-m-Y");
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

    public function totalAkhirLabaKotor(Request $request)
    {
        //SUBTOTAL PESANAN (ONLINE)
        $sub_total_penjualan = DetailPenjualan::subtotalLaporanLabaKotorPesanan($request)->first();
        $potongan            = 0;
        $jenis_transaksi     = "penjualan";
        $sub_hpp             = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();
        //SUBTOTAL PESANAN (ONLINE)

        //SUBTOTAL POS (OFFLINE)
        $sub_total_penjualan_pos = DetailPenjualanPos::subtotalLaporanLabaKotor($request)->first();
        $sub_potongan            = PenjualanPos::potonganLaporanLabaKotor($request)->first();
        $potongan_pos            = $sub_potongan->potongan;
        $jenis_transaksi         = "PenjualanPos";
        $sub_hpp_pos             = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();
        //SUBTOTAL POS (OFFLINE)

        $total_penjualan  = $sub_total_penjualan->subtotal + $sub_total_penjualan_pos->subtotal;
        $total_hpp        = $sub_hpp->total_hpp + $sub_hpp_pos->total_hpp;
        $total_laba_kotor = ($total_penjualan - $total_hpp);
        $total_potongan   = $potongan + $potongan_pos;
        $total_laba_jual  = ($total_laba_kotor - $total_potongan);

        $response['total_penjualan']  = $total_penjualan;
        $response['total_hpp']        = $total_hpp;
        $response['total_laba_kotor'] = $total_laba_kotor;
        $response['total_potongan']   = $total_potongan;
        $response['total_laba_jual']  = $total_laba_jual;

        return $response;
    }

    public function labelSheet($sheet, $row)
    {

        $sheet->row($row, [
            'No. Transaksi',
            'Waktu',
            'Pelanggan',
            'Penjualan',
            'Hpp',
            'Laba Kotor',
            'Diskon Faktur',
            'Laba Jual',
        ]);
        return $sheet;
    }

    //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $pelanggan)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['pelanggan']      = $pelanggan;

        //QUERY LABA KOTOR POS
        $laporan_laba_kotor  = PenjualanPos::laporanLabaKotorPos($request);
        $sub_total_penjualan = DetailPenjualanPos::subtotalLaporanLabaKotor($request)->first();
        $sub_potongan        = PenjualanPos::potonganLaporanLabaKotor($request)->first();
        $potongan            = $sub_potongan->potongan;
        $jenis_transaksi     = "PenjualanPos";
        $sub_hpp             = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();
        //QUERY LABA KOTOR POS

        //QUERY LABA KOTOR PESANAN
        $laporan_laba_kotor_pesanan  = Penjualan::laporanLabaKotorPesanan($request);
        $sub_total_penjualan_pesanan = DetailPenjualan::subtotalLaporanLabaKotorPesanan($request)->first();
        $jenis_transaksi             = "penjualan";
        $sub_hpp_pesanan             = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();
        //QUERY LABA KOTOR PESANAN

        Excel::create('Laporan Laba Kotor Pelanggan', function ($excel) use ($laporan_laba_kotor, $request, $sub_total_penjualan, $potongan, $sub_hpp, $laporan_laba_kotor_pesanan, $sub_total_penjualan_pesanan, $sub_hpp_pesanan) {
            // Set property
            $excel->sheet('Laporan Laba Kotor Pelanggan', function ($sheet) use ($laporan_laba_kotor, $request, $sub_total_penjualan, $potongan, $sub_hpp, $laporan_laba_kotor_pesanan, $sub_total_penjualan_pesanan, $sub_hpp_pesanan) {
                $row = 1;
                $sheet->row($row, [
                    'LABA KOTOR PENJUALAN POS',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                if ($laporan_laba_kotor->count() > 0) {
                    //MENCEGAH JIKA DATA KOSONG KETIKA DI DOWLOAD, AGAR TIDAK ERROR
                    //LABA KOTOR /PELANGGAN
                    foreach ($laporan_laba_kotor->get() as $laba_kotor) {
                        $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
                        $detail_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan_pos', $laba_kotor->id)->where('warung_id', Auth::user()->id_warung)->first();
                        $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
                        $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

                        $sheet->row(++$row, [
                            $laba_kotor->id,
                            $laba_kotor->created_at,
                            $laba_kotor->name,
                            $detail_penjualan->subtotal = round($detail_penjualan->subtotal, 2),
                            $hpp->total_hpp = round($hpp->total_hpp, 2),
                            $total_laba_kotor = round($total_laba_kotor, 2),
                            $laba_kotor->potongan,
                            $laba_jual = round($laba_jual, 2),
                        ]);
                    }

                    $row = ++$row;
                    $sheet->row(++$row, [
                        'TOTAL',
                        '',
                        '',
                        $subtotal_penjualan = $sub_total_penjualan->subtotal,
                        $subtotal_hpp = $sub_hpp->total_hpp,
                        $subtotal_laba_kotor = $subtotal_penjualan - $subtotal_hpp,
                        $subtotal_potongan = $potongan,
                        $subtotal_laba_jual = $subtotal_laba_kotor - $subtotal_potongan,
                    ]);
                }

                $row = ++$row + 3;
                $sheet->row($row, [
                    'LABA KOTOR PENJUALAN ONLINE',
                ]);

                $row   = ++$row + 1;
                $sheet = $this->labelSheet($sheet, $row);

                if ($laporan_laba_kotor_pesanan->count() > 0) {
                    //MENCEGAH JIKA DATA KOSONG KETIKA DI DOWLOAD, AGAR TIDAK ERROR

                    //LABA KOTOR /PRODUK
                    foreach ($laporan_laba_kotor_pesanan->get() as $laba_kotor_pesanan) {
                        $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor_pesanan->id)->where('jenis_transaksi', 'penjualan')->where('warung_id', Auth::user()->id_warung)->first();
                        $detail_penjualan = DetailPenjualan::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan', $laba_kotor_pesanan->id)->first();
                        $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
                        $laba_jual        = $total_laba_kotor - 0;

                        $sheet->row(++$row, [
                            $laba_kotor_pesanan->id,
                            $laba_kotor_pesanan->created_at,
                            $laba_kotor_pesanan->name,
                            $detail_penjualan->subtotal = round($detail_penjualan->subtotal, 2),
                            $hpp->total_hpp = round($hpp->total_hpp, 2),
                            $total_laba_kotor = round($total_laba_kotor, 2),
                            0,
                            $laba_jual = round($laba_jual, 2),
                        ]);
                    }

                    $row = ++$row;
                    $sheet->row(++$row, [
                        'TOTAL',
                        '',
                        '',
                        $subtotal_penjualan = $sub_total_penjualan_pesanan->subtotal,
                        $subtotal_hpp = $sub_hpp_pesanan->total_hpp,
                        $subtotal_laba_kotor = $subtotal_penjualan - $subtotal_hpp,
                        0,
                        $subtotal_laba_jual = $subtotal_laba_kotor - 0,
                    ]);
                }
            });

        })->export('xls');
    }

    public function foreachPos($laporan_laba_kotor)
    {
        $data_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $hpp              = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor->id)->where('jenis_transaksi', 'PenjualanPos')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan = DetailPenjualanPos::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan_pos', $laba_kotor->id)->where('warung_id', Auth::user()->id_warung)->first();
            $total_laba_kotor = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual        = $total_laba_kotor - $laba_kotor->potongan;

            array_push($data_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $hpp->total_hpp, 'total_laba_kotor' => $total_laba_kotor, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal]);
        }

        return $data_laba_kotor;
    }

    public function foreachPesanan($laporan_laba_kotor_pesanan)
    {
        $data_laba_kotor_pesanan = array();
        foreach ($laporan_laba_kotor_pesanan as $laba_kotor_pesanan) {
            $hpp                      = Hpp::select(DB::raw('SUM(total_nilai) as total_hpp'))->where('no_faktur', $laba_kotor_pesanan->id)->where('jenis_transaksi', 'penjualan')->where('warung_id', Auth::user()->id_warung)->first();
            $detail_penjualan         = DetailPenjualan::select(DB::raw('SUM(subtotal) as subtotal'))->where('id_penjualan', $laba_kotor_pesanan->id)->first();
            $total_laba_kotor_pesanan = $detail_penjualan->subtotal - $hpp->total_hpp;
            $laba_jual                = $total_laba_kotor_pesanan - 0;

            array_push($data_laba_kotor_pesanan, ['laba_kotor_pesanan' => $laba_kotor_pesanan, 'hpp' => $hpp->total_hpp, 'total_laba_kotor_pesanan' => $total_laba_kotor_pesanan, 'laba_jual' => $laba_jual, 'total' => $detail_penjualan->subtotal]);
        }

        return $data_laba_kotor_pesanan;
    }

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $pelanggan)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['pelanggan']      = $pelanggan;

        //PENJUALAN POS
        $laporan_laba_kotor = PenjualanPos::laporanLabaKotorPos($request)->get();

        $sub_total_penjualan = DetailPenjualanPos::subtotalLaporanLabaKotor($request)->first();
        $sub_potongan        = PenjualanPos::potonganLaporanLabaKotor($request)->first();
        $potongan            = $sub_potongan->potongan;
        $jenis_transaksi     = "PenjualanPos";
        $sub_hpp             = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();

        $subtotal_penjualan  = $sub_total_penjualan->subtotal;
        $subtotal_hpp        = $sub_hpp->total_hpp;
        $subtotal_laba_kotor = $subtotal_penjualan - $subtotal_hpp;
        $subtotal_potongan   = $potongan;
        $subtotal_laba_jual  = $subtotal_laba_kotor - $subtotal_potongan;

        $data_laba_kotor = $this->foreachPos($laporan_laba_kotor);
        //PENJUALAN POS

        //PENJUALAN PESANAN
        $laporan_laba_kotor_pesanan = Penjualan::laporanLabaKotorPesanan($request)->get();

        $sub_total_penjualan_pesanan = DetailPenjualan::subtotalLaporanLabaKotorPesanan($request)->first();
        $jenis_transaksi             = "penjualan";
        $sub_hpp_pesanan             = Hpp::hppLaporanLabaKotor($request, $jenis_transaksi)->first();

        $subtotal_penjualan_pesanan  = $sub_total_penjualan_pesanan->subtotal;
        $subtotal_hpp_pesanan        = $sub_hpp_pesanan->total_hpp;
        $subtotal_laba_kotor_pesanan = $subtotal_penjualan_pesanan - $subtotal_hpp_pesanan;
        $subtotal_laba_jual_pesanan  = $subtotal_laba_kotor_pesanan - 0;

        $data_laba_kotor_pesanan = $this->foreachPesanan($laporan_laba_kotor_pesanan);
        //PENJUALAN PESANAN

        //TOTAL KESELURUHAN
        $total_akhir = $this->totalAkhirLabaKotor($request);

        $data_warung = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_laba_kotor',
            [
                'data_laba_kotor'             => $data_laba_kotor,
                'data_laba_kotor_pesanan'     => $data_laba_kotor_pesanan,
                'data_warung'                 => $data_warung,
                'subtotal_penjualan'          => $subtotal_penjualan,
                'subtotal_hpp'                => $subtotal_hpp,
                'subtotal_laba_kotor'         => $subtotal_laba_kotor,
                'subtotal_potongan'           => $subtotal_potongan,
                'subtotal_laba_jual'          => $subtotal_laba_jual,
                'subtotal_penjualan_pesanan'  => $subtotal_penjualan_pesanan,
                'subtotal_hpp_pesanan'        => $subtotal_hpp_pesanan,
                'subtotal_laba_kotor_pesanan' => $subtotal_laba_kotor_pesanan,
                'subtotal_laba_jual_pesanan'  => $subtotal_laba_jual_pesanan,
                'total_akhir'                 => $total_akhir,
                'dari_tanggal'                => $this->tanggal($dari_tanggal),
                'sampai_tanggal'              => $this->tanggal($sampai_tanggal),
                'pelanggan'                   => $pelanggan,
                'setting_aplikasi'            => $setting_aplikasi,
            ])->with(compact('html'));
    }
}
