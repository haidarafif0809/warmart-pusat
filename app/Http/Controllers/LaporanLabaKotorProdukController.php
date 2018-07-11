<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPenjualan;
use App\DetailPenjualanPos;
use App\Hpp;
use App\SettingAplikasi;
use App\Warung;
use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanLabaKotorProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function tanggal($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "d-m-Y");
        return $date_format;
    }

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }

    public function pilihProduk()
    {
        $produk       = Barang::select(['id', 'nama_barang','harga_jual'])->where('id_warung', Auth::user()->id_warung)->get();
        $array_produk = array(['id' => '', 'nama_produk' => 'SEMUA PRODUK','harga_jual'=>'']);
        foreach ($produk as $produks) {
            array_push($array_produk, [
                'id'          => $produks->id,
                'nama_produk' => title_case($produks->nama_barang),
                'harga_jual'  => $produks->harga_jual,
            ]);
        }
        return response()->json($array_produk);
    }

    public function dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view)
    {

        $respons['current_page']   = $laporan_laba_kotor->currentPage();
        $respons['data']           = $array_laba_kotor;
        $respons['first_page_url'] = url('/laporan-laba-kotor-produk/' . $link_view . '?page=' . $laporan_laba_kotor->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_laba_kotor->lastPage();
        $respons['last_page_url']  = url('/laporan-laba-kotor-produk/' . $link_view . '?page=' . $laporan_laba_kotor->lastPage());
        $respons['next_page_url']  = $laporan_laba_kotor->nextPageUrl();
        $respons['path']           = url('/laporan-laba-kotor-produk/' . $link_view . '');
        $respons['per_page']       = $laporan_laba_kotor->perPage();
        $respons['prev_page_url']  = $laporan_laba_kotor->previousPageUrl();
        $respons['to']             = $laporan_laba_kotor->perPage();
        $respons['total']          = $laporan_laba_kotor->total();

        return $respons;
    }

    public function subtotalLaporan($sub_hpp, $sub_total_penjualan)
    {
        if ($sub_hpp->total_hpp == "" || $sub_total_penjualan->subtotal == "" || $sub_hpp->total_hpp == 0 || $sub_total_penjualan->subtotal == 0) {
            $subtotal_hpp                   = 0;
            $subtotal_penjualan             = 0;
            $subtotal_laba_kotor            = 0;
            $subtotal_persentase_laba_kotor = 0;
            $subtotal_persentase_gpm        = 0;
        } else {
            $subtotal_hpp                   = $sub_hpp->total_hpp;
            $subtotal_penjualan             = $sub_total_penjualan->subtotal;
            $subtotal_laba_kotor            = $subtotal_penjualan - $subtotal_hpp;
            $subtotal_persentase_laba_kotor = ($subtotal_laba_kotor * 100) / $subtotal_hpp;
            $subtotal_persentase_gpm        = ($subtotal_laba_kotor * 100) / $subtotal_penjualan;
        }

        $response['subtotal_hpp']                   = $subtotal_hpp;
        $response['subtotal_penjualan']             = $subtotal_penjualan;
        $response['subtotal_laba_kotor']            = $subtotal_laba_kotor;
        $response['subtotal_persentase_laba_kotor'] = $subtotal_persentase_laba_kotor;
        $response['subtotal_persentase_gpm']        = $subtotal_persentase_gpm;

        return $response;
    }

    public function subtotalLaporanPesanan($sub_hpp_pesanan, $sub_total_penjualan_pesanan)
    {
        if ($sub_hpp_pesanan->total_hpp == "" || $sub_hpp_pesanan->total_hpp == 0 || $sub_total_penjualan_pesanan->subtotal == 0 || $sub_total_penjualan_pesanan->subtotal == "") {
            $subtotal_hpp                   = 0;
            $subtotal_penjualan             = 0;
            $subtotal_laba_kotor            = 0;
            $subtotal_persentase_laba_kotor = 0;
            $subtotal_persentase_gpm        = 0;
        } else {
            $subtotal_hpp                   = $sub_hpp_pesanan->total_hpp;
            $subtotal_penjualan             = $sub_total_penjualan_pesanan->subtotal;
            $subtotal_laba_kotor            = $subtotal_penjualan - $subtotal_hpp;
            $subtotal_persentase_laba_kotor = ($subtotal_laba_kotor * 100) / $subtotal_hpp;
            $subtotal_persentase_gpm        = ($subtotal_laba_kotor * 100) / $subtotal_penjualan;
        }

        $response_pesanan['subtotal_hpp']                   = $subtotal_hpp;
        $response_pesanan['subtotal_penjualan']             = $subtotal_penjualan;
        $response_pesanan['subtotal_laba_kotor']            = $subtotal_laba_kotor;
        $response_pesanan['subtotal_persentase_laba_kotor'] = $subtotal_persentase_laba_kotor;
        $response_pesanan['subtotal_persentase_gpm']        = $subtotal_persentase_gpm;

        return $response_pesanan;
    }

    // HPP
    public function nilaiHpp($laba_kotor, $request)
    {
        //HPP
        $hpp_masuk = Hpp::select(DB::raw('SUM(total_nilai) as hpp'))
            ->where('id_produk', $laba_kotor->id_produk)
            ->where('jenis_transaksi', 'PenjualanPos')
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('warung_id', Auth::user()->id_warung)->first();

        if ($hpp_masuk->hpp == 0 || $hpp_masuk->hpp == null) {
            $total_hpp             = 0;
            $total_laba_kotor      = $laba_kotor->subtotal - $total_hpp;
            $persentase_laba_kotor = 100;
            $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor->subtotal;
        } else {
            $total_hpp             = $hpp_masuk->hpp;
            $total_laba_kotor      = $laba_kotor->subtotal - $total_hpp;
            $persentase_laba_kotor = ($total_laba_kotor * 100) / $total_hpp;
            $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor->subtotal;
        }

        $respons['total_hpp']             = $total_hpp;
        $respons['total_laba_kotor']      = $total_laba_kotor;
        $respons['persentase_laba_kotor'] = $persentase_laba_kotor;
        $respons['persentase_gpm']        = $persentase_gpm;

        return $respons;
    }

    // HPP
    public function nilaiHppPesanan($laba_kotor, $request)
    {
        //HPP
        $hpp_masuk = Hpp::select(DB::raw('SUM(total_nilai) as hpp'))
            ->where('id_produk', $laba_kotor->id_produk)
            ->where('jenis_transaksi', 'Penjualan')
            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
            ->where('warung_id', Auth::user()->id_warung)->first();

        if ($hpp_masuk->hpp == 0 || $hpp_masuk->hpp == null) {
            $total_hpp             = 0;
            $total_laba_kotor      = $laba_kotor->subtotal - $total_hpp;
            $persentase_laba_kotor = 100;
            $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor->subtotal;
        } else {
            $total_hpp             = $hpp_masuk->hpp;
            $total_laba_kotor      = $laba_kotor->subtotal - $total_hpp;
            $persentase_laba_kotor = ($total_laba_kotor * 100) / $total_hpp;
            $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor->subtotal;
        }

        $respons['total_hpp']             = $total_hpp;
        $respons['total_laba_kotor']      = $total_laba_kotor;
        $respons['persentase_laba_kotor'] = $persentase_laba_kotor;
        $respons['persentase_gpm']        = $persentase_gpm;

        return $respons;
    }

//METHOD UNTUK TABEL PENJUALAN POS (OFLLINE)

    public function prosesLaporanLabaKotorProduk(Request $request)
    {

        $laporan_laba_kotor = DetailPenjualanPos::laporanLabaKotorProdukPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $respons = $this->nilaiHpp($laba_kotor, $request);

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $respons['total_hpp'], 'total_laba_kotor' => $respons['total_laba_kotor'], 'persentase_laba_kotor' => $respons['persentase_laba_kotor'], 'persentase_gpm' => $respons['persentase_gpm']]);
        }

        $link_view = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $laporan_laba_kotor = DetailPenjualanPos::cariLaporanLabaKotorProdukPos($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $respons = $this->nilaiHpp($laba_kotor, $request);

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $respons['total_hpp'], 'total_laba_kotor' => $respons['total_laba_kotor'], 'persentase_laba_kotor' => $respons['persentase_laba_kotor'], 'persentase_gpm' => $respons['persentase_gpm']]);
        }

        $link_view = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function subtotalLabaKotorProduk(Request $request)
    {
        //SUBTOTAL KESELURUHAN
        $sub_total_penjualan = DetailPenjualanPos::subtotalLaporanLabaKotorProduk($request)->first();

        //TOTAL HPP /PRODUK KESELURUHAN
        $jenis_transaksi = "PenjualanPos";
        $sub_hpp         = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();

        $response = $this->subtotalLaporan($sub_hpp, $sub_total_penjualan);

        return response()->json($response);
    }

//METHOD UNTUK TABEL PENJUALAN POS (OFLLINE)

//METHOD UNTUK TABEL PENJUALAN PESANAN (ONLINE)

    public function prosesLaporanLabaKotorProdukPesanan(Request $request)
    {
        $laporan_laba_kotor = DetailPenjualan::laporanLabaKotorProdukPesanan($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $respons = $this->nilaiHppPesanan($laba_kotor, $request);

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $respons['total_hpp'], 'total_laba_kotor' => $respons['total_laba_kotor'], 'persentase_laba_kotor' => $respons['persentase_laba_kotor'], 'persentase_gpm' => $respons['persentase_gpm']]);
        }

        $link_view = 'view-pesanan';

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function pencarianPesanan(Request $request)
    {
        $laporan_laba_kotor = DetailPenjualan::cariLaporanLabaKotorProdukPesanan($request)->paginate(10);

        $array_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $respons = $this->nilaiHppPesanan($laba_kotor, $request);

            array_push($array_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $respons['total_hpp'], 'total_laba_kotor' => $respons['total_laba_kotor'], 'persentase_laba_kotor' => $respons['persentase_laba_kotor'], 'persentase_gpm' => $respons['persentase_gpm']]);
        }

        $link_view = 'view-pesanan';

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_laba_kotor, $array_laba_kotor, $link_view);
        return response()->json($respons);
    }

    public function subtotalLabaKotorProdukPesanan(Request $request)
    {
        //SUBTOTAL KESELURUHAN
        $sub_total_penjualan = DetailPenjualan::subtotalLaporanLabaKotorProdukPesanan($request)->first();

        //TOTAL HPP /PRODUK KESELURUHAN
        $jenis_transaksi = "penjualan";
        $sub_hpp         = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();

        $response = $this->subtotalLaporan($sub_hpp, $sub_total_penjualan);

        return response()->json($response);
    }
//METHOD UNTUK TABEL PENJUALAN PESANAN (ONLINE)

    public function totalAkhirLabaKotorProduk(Request $request)
    {
        //SUBTOTAL PESANAN (ONLINE)
        $sub_total_penjualan = DetailPenjualan::subtotalLaporanLabaKotorProdukPesanan($request)->first();
        $jenis_transaksi     = "penjualan";
        $sub_hpp             = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();
        //SUBTOTAL PESANAN (ONLINE)

        //SUBTOTAL POS (OFFLINE)
        $sub_total_penjualan_pos = DetailPenjualanPos::subtotalLaporanLabaKotorProduk($request)->first();
        $jenis_transaksi         = "PenjualanPos";
        $sub_hpp_pos             = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();
        //SUBTOTAL POS (OFFLINE)

        if ($sub_hpp->total_hpp == "" || $sub_total_penjualan->subtotal == "" || $sub_hpp->total_hpp == 0 || $sub_total_penjualan->subtotal == 0 || $sub_hpp_pos->total_hpp == "" || $sub_total_penjualan_pos->subtotal == "" || $sub_hpp_pos->total_hpp == 0 || $sub_total_penjualan_pos->subtotal == 0) {
            $subtotal_hpp                   = 0;
            $subtotal_penjualan             = 0;
            $subtotal_laba_kotor            = 0;
            $subtotal_persentase_laba_kotor = 0;
            $subtotal_persentase_gpm        = 0;
        } else {
            $subtotal_hpp                   = $sub_hpp->total_hpp + $sub_hpp_pos->total_hpp;
            $subtotal_penjualan             = $sub_total_penjualan->subtotal + $sub_total_penjualan_pos->subtotal;
            $subtotal_laba_kotor            = $subtotal_penjualan - $subtotal_hpp;
            $subtotal_persentase_laba_kotor = ($subtotal_laba_kotor * 100) / $subtotal_hpp;
            $subtotal_persentase_gpm        = ($subtotal_laba_kotor * 100) / $subtotal_penjualan;
        }

        $response['subtotal_hpp']                   = $subtotal_hpp;
        $response['subtotal_penjualan']             = $subtotal_penjualan;
        $response['subtotal_laba_kotor']            = $subtotal_laba_kotor;
        $response['subtotal_persentase_laba_kotor'] = $subtotal_persentase_laba_kotor;
        $response['subtotal_persentase_gpm']        = $subtotal_persentase_gpm;

        return $response;
    }

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Kode Produk',
            'Nama Produk',
            'Hpp',
            'Penjualan',
            'Laba Kotor',
            'Laba Kotor (%)',
            'Gross Profit Margin (%)',
        ]);
        return $sheet;
    }

    //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PRODUK
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $produk)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['produk']         = $produk;

        //QUERY LABA KOTOR POS
        $laporan_laba_kotor  = DetailPenjualanPos::laporanLabaKotorProdukPos($request);
        $sub_total_penjualan = DetailPenjualanPos::subtotalLaporanLabaKotorProduk($request)->first();
        $jenis_transaksi     = "PenjualanPos";
        $sub_hpp             = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();
        //QUERY LABA KOTOR POS

        //QUERY LABA KOTOR PESANAN
        $laporan_laba_kotor_pesanan  = DetailPenjualan::laporanLabaKotorProdukPesanan($request);
        $sub_total_penjualan_pesanan = DetailPenjualan::subtotalLaporanLabaKotorProdukPesanan($request)->first();
        $jenis_transaksi             = "penjualan";
        $sub_hpp_pesanan             = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();
        //QUERY LABA KOTOR PESANAN

        Excel::create('Laporan Laba Kotor Produk', function ($excel) use ($laporan_laba_kotor, $request, $sub_total_penjualan, $sub_hpp, $laporan_laba_kotor_pesanan, $sub_total_penjualan_pesanan, $sub_hpp_pesanan) {
            // Set property
            $excel->sheet('Laporan Laba Kotor Produk', function ($sheet) use ($laporan_laba_kotor, $request, $sub_total_penjualan, $sub_hpp, $laporan_laba_kotor_pesanan, $sub_total_penjualan_pesanan, $sub_hpp_pesanan) {
                $row = 1;
                $sheet->row($row, [
                    'LABA KOTOR PENJUALAN POS',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                if ($laporan_laba_kotor->count() > 0) {
                    //MENCEGAH JIKA DATA KOSONG KETIKA DI DOWLOAD, AGAR TIDAK ERROR

                    //LABA KOTOR /PRODUK
                    foreach ($laporan_laba_kotor->get() as $laba_kotor) {
                        $hpp_masuk = Hpp::select(DB::raw('SUM(total_nilai) as hpp'))
                            ->where('id_produk', $laba_kotor->id_produk)
                            ->where('jenis_transaksi', 'PenjualanPos')
                            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                            ->where('warung_id', Auth::user()->id_warung)->first();

                        if ($hpp_masuk->hpp == 0 || $hpp_masuk->hpp == null) {
                            $total_hpp             = 0;
                            $total_laba_kotor      = $laba_kotor->subtotal - $total_hpp;
                            $persentase_laba_kotor = 100;
                            $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor->subtotal;
                        } else {
                            $total_hpp             = $hpp_masuk->hpp;
                            $total_laba_kotor      = $laba_kotor->subtotal - $total_hpp;
                            $persentase_laba_kotor = ($total_laba_kotor * 100) / $total_hpp;
                            $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor->subtotal;
                        }

                        $sheet->row(++$row, [
                            $laba_kotor->kode_barang,
                            $laba_kotor->nama_barang,
                            $total_hpp = round($total_hpp, 2),
                            $laba_kotor->subtotal = round($laba_kotor->subtotal, 2),
                            $total_laba_kotor = round($total_laba_kotor, 2),
                            $persentase_laba_kotor = round($persentase_laba_kotor, 2),
                            $persentase_gpm = round($persentase_gpm, 2),
                        ]);
                    }

                    $row = ++$row;

                    $sheet->row(++$row, [
                        'TOTAL',
                        '',
                        $subtotal_hpp = round($sub_hpp->total_hpp, 2),
                        $subtotal_penjualan = round($sub_total_penjualan->subtotal, 2),
                        $subtotal_laba_kotor = round($subtotal_penjualan - $subtotal_hpp, 2),
                        $subtotal_persentase_laba_kotor = round(($subtotal_laba_kotor * 100) / $subtotal_hpp, 2),
                        $subtotal_persentase_gpm = round(($subtotal_laba_kotor * 100) / $subtotal_penjualan, 2),
                    ]);
                }

                $row = ++$row + 3;
                $sheet->row($row, [
                    'LABA KOTOR PENJUALAN ONLINE',
                ]);

                $row = ++$row + 1;

                $sheet = $this->labelSheet($sheet, $row);

                if ($laporan_laba_kotor_pesanan->count() > 0) {
                    //MENCEGAH JIKA DATA KOSONG KETIKA DI DOWLOAD, AGAR TIDAK ERROR

                    //LABA KOTOR /PRODUK
                    foreach ($laporan_laba_kotor_pesanan->get() as $laba_kotor_pesanan) {
                        $hpp_masuk = Hpp::select(DB::raw('SUM(total_nilai) as hpp'))
                            ->where('id_produk', $laba_kotor_pesanan->id_produk)
                            ->where('jenis_transaksi', 'penjualan')
                            ->where(DB::raw('DATE(created_at)'), '>=', $this->tanggalSql($request->dari_tanggal))
                            ->where(DB::raw('DATE(created_at)'), '<=', $this->tanggalSql($request->sampai_tanggal))
                            ->where('warung_id', Auth::user()->id_warung)->first();
                        $total_hpp             = $hpp_masuk->hpp;
                        $total_laba_kotor      = $laba_kotor_pesanan->subtotal - $total_hpp;
                        $persentase_laba_kotor = ($total_laba_kotor * 100) / $total_hpp;
                        $persentase_gpm        = ($total_laba_kotor * 100) / $laba_kotor_pesanan->subtotal;

                        $sheet->row(++$row, [
                            $laba_kotor_pesanan->kode_barang,
                            $laba_kotor_pesanan->nama_barang,
                            $total_hpp = round($total_hpp, 2),
                            $laba_kotor_pesanan->subtotal = round($laba_kotor_pesanan->subtotal, 2),
                            $total_laba_kotor = round($total_laba_kotor, 2),
                            $persentase_laba_kotor = round($persentase_laba_kotor, 2),
                            $persentase_gpm = round($persentase_gpm, 2),
                        ]);
                    }

                    $row = ++$row;
                    $sheet->row(++$row, [
                        'TOTAL',
                        '',
                        $subtotal_hpp = round($sub_hpp_pesanan->total_hpp, 2),
                        $subtotal_penjualan = round($sub_total_penjualan_pesanan->subtotal, 2),
                        $subtotal_laba_kotor = round($subtotal_penjualan - $subtotal_hpp, 2),
                        $subtotal_persentase_laba_kotor = round(($subtotal_laba_kotor * 100) / $subtotal_hpp, 2),
                        $subtotal_persentase_gpm = round(($subtotal_laba_kotor * 100) / $subtotal_penjualan, 2),
                    ]);

                }
            });
        })->export('xls');
    }

    public function foreachPos($laporan_laba_kotor, $request)
    {
        $data_laba_kotor = array();
        foreach ($laporan_laba_kotor as $laba_kotor) {
            $respons = $this->nilaiHpp($laba_kotor, $request);

            array_push($data_laba_kotor, ['laba_kotor' => $laba_kotor, 'hpp' => $respons['total_hpp'], 'total_laba_kotor' => $respons['total_laba_kotor'], 'persentase_laba_kotor' => $respons['persentase_laba_kotor'], 'persentase_gpm' => $respons['persentase_gpm']]);
        }

        return $data_laba_kotor;
    }

    public function foreachPesanan($laporan_laba_kotor_pesanan, $request)
    {
        $data_laba_kotor_pesanan = array();
        foreach ($laporan_laba_kotor_pesanan as $laba_kotor_pesanan) {
            $respons = $this->nilaiHppPesanan($laba_kotor_pesanan, $request);

            array_push($data_laba_kotor_pesanan, ['laba_kotor_pesanan' => $laba_kotor_pesanan, 'hpp' => $respons['total_hpp'], 'total_laba_kotor_pesanan' => $respons['total_laba_kotor'], 'persentase_laba_kotor_pesanan' => $respons['persentase_laba_kotor'], 'persentase_gpm' => $respons['persentase_gpm']]);
        }

        return $data_laba_kotor_pesanan;
    }

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $produk)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['produk']         = $produk;

        //PENJUALAN POS
        $laporan_laba_kotor = DetailPenjualanPos::laporanLabaKotorProdukPos($request)->get();

        $sub_total_penjualan = DetailPenjualanPos::subtotalLaporanLabaKotorProduk($request)->first();
        $jenis_transaksi     = "PenjualanPos";
        $sub_hpp             = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();
        $response            = $this->subtotalLaporan($sub_hpp, $sub_total_penjualan);

        $data_laba_kotor = $this->foreachPos($laporan_laba_kotor, $request);
        //PENJUALAN POS

        //PENJUALAN PESANAN
        $laporan_laba_kotor_pesanan = DetailPenjualan::laporanLabaKotorProdukPesanan($request)->get();

        $sub_total_penjualan_pesanan = DetailPenjualan::subtotalLaporanLabaKotorProdukPesanan($request)->first();
        $jenis_transaksi             = "penjualan";
        $sub_hpp_pesanan             = Hpp::hppLaporanLabaKotorProduk($request, $jenis_transaksi)->first();
        $response_pesanan            = $this->subtotalLaporanPesanan($sub_hpp_pesanan, $sub_total_penjualan_pesanan);

        $data_laba_kotor_pesanan = $this->foreachPesanan($laporan_laba_kotor_pesanan, $request);
        //PENJUALAN PESANAN

        //TOTAL KESELURUHAN
        $total_akhir = $this->totalAkhirLabaKotorProduk($request);

        $data_warung = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_laba_kotor_produk',
            [
                'data_laba_kotor'                        => $data_laba_kotor,
                'data_laba_kotor_pesanan'                => $data_laba_kotor_pesanan,
                'data_warung'                            => $data_warung,
                'subtotal_hpp'                           => $response['subtotal_hpp'],
                'subtotal_penjualan'                     => $response['subtotal_penjualan'],
                'subtotal_laba_kotor'                    => $response['subtotal_laba_kotor'],
                'subtotal_persentase_laba_kotor'         => $response['subtotal_persentase_laba_kotor'],
                'subtotal_persentase_gpm'                => $response['subtotal_persentase_gpm'],
                'subtotal_hpp_pesanan'                   => $response_pesanan['subtotal_hpp'],
                'subtotal_penjualan_pesanan'             => $response_pesanan['subtotal_penjualan'],
                'subtotal_laba_kotor_pesanan'            => $response_pesanan['subtotal_laba_kotor'],
                'subtotal_persentase_laba_kotor_pesanan' => $response_pesanan['subtotal_persentase_laba_kotor'],
                'subtotal_persentase_gpm_pesanan'        => $response_pesanan['subtotal_persentase_gpm'],
                'total_akhir'                            => $total_akhir,
                'dari_tanggal'                           => $this->tanggal($dari_tanggal),
                'sampai_tanggal'                         => $this->tanggal($sampai_tanggal),
                'produk'                                 => $produk,
                'setting_aplikasi'                       => $setting_aplikasi,
            ])->with(compact('html'));
    }

}
