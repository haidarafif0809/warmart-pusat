<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPenjualanPos;
use App\SettingAplikasi;
use App\User;
use App\Warung;
use Auth;
use Excel;
use Illuminate\Http\Request;

class LaporanPenjualanPosProdukController extends Controller
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

    public function dataProduk()
    {
        $produk       = Barang::select(['id', 'nama_barang'])->where('id_warung', Auth::user()->id_warung)->get();
        $array_produk = array();
        foreach ($produk as $produks) {
            array_push($array_produk, [
                'id'          => $produks->id,
                'nama_produk' => title_case($produks->nama_barang),
            ]);
        }
        return response()->json($array_produk);
    }

    public function dataPelanggan()
    {
        $pelanggan       = User::select(['id', 'name'])->where('tipe_user', 3)->get();
        $array_pelanggan = array();
        foreach ($pelanggan as $pelanggans) {
            array_push($array_pelanggan, [
                'id'             => $pelanggans->id,
                'nama_pelanggan' => title_case($pelanggans->name),
            ]);
        }
        return response()->json($array_pelanggan);
    }

    public function dataPagination($laporan_penjualan, $array_penjualan)
    {
        $respons['current_page']   = $laporan_penjualan->currentPage();
        $respons['data']           = $array_penjualan;
        $respons['first_page_url'] = url('/laporan-penjualan-pos-produk/view?page=' . $laporan_penjualan->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_penjualan->lastPage();
        $respons['last_page_url']  = url('/laporan-penjualan-pos-produk/view?page=' . $laporan_penjualan->lastPage());
        $respons['next_page_url']  = $laporan_penjualan->nextPageUrl();
        $respons['path']           = url('/laporan-penjualan-pos-produk/view');
        $respons['per_page']       = $laporan_penjualan->perPage();
        $respons['prev_page_url']  = $laporan_penjualan->previousPageUrl();
        $respons['to']             = $laporan_penjualan->perPage();
        $respons['total']          = $laporan_penjualan->total();

        return $respons;
    }

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Kode Produk',
            'Nama Produk',
            'Jumlah',
            'Satuan',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
        ]);
        return $sheet;
    }

    public function prosesLaporanPenjualanPosProduk(Request $request)
    {
        $laporan_penjualan = DetailPenjualanPos::laporanPenjualanPosProduk($request)->paginate(10);
        $array_penjualan   = array();
        foreach ($laporan_penjualan as $laporan_penjualans) {
            $sub_total = $laporan_penjualans->subtotal - $laporan_penjualans->potongan + $laporan_penjualans->tax;
            array_push($array_penjualan, ['laporan_penjualans' => $laporan_penjualans, 'sub_total' => $sub_total]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_penjualan, $array_penjualan);
        return response()->json($respons);
    }

    public function totalPenjualanPosProduk(Request $request)
    {
        // SUBTOTAL KESELURUHAN
        $total_penjualan = DetailPenjualanPos::totalLaporanPenjualanPosProduk($request)->first();

        $respons['jumlah_produk'] = $total_penjualan->jumlah_produk;
        $respons['subtotal']      = $total_penjualan->subtotal;
        $respons['potongan']      = $total_penjualan->potongan;
        $respons['pajak']         = $total_penjualan->pajak;
        $respons['total']         = $total_penjualan->total;
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $laporan_penjualan = DetailPenjualanPos::cariLaporanPenjualanPosProduk($request)->paginate(10);
        $array_penjualan   = array();
        foreach ($laporan_penjualan as $laporan_penjualans) {
            $sub_total = $laporan_penjualans->subtotal - $laporan_penjualans->potongan + $laporan_penjualans->tax;
            array_push($array_penjualan, ['laporan_penjualans' => $laporan_penjualans, 'sub_total' => $sub_total]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_penjualan, $array_penjualan);
        return response()->json($respons);
    }

    //DOWNLOAD EXCEL - LAPORAN PEMBELIAN /PRODUK
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $produk)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;

        if ($produk == 0) {
            $request['produk'] = "";
        };

        $laporan_penjualan = DetailPenjualanPos::laporanPenjualanPosProduk($request)->get();

        Excel::create('Laporan Penjualan Pos Produk', function ($excel) use ($request, $laporan_penjualan) {
            // Set property
            $excel->sheet('Laporan Penjualan Pos Produk', function ($sheet) use ($request, $laporan_penjualan) {
                $row = 1;
                $sheet->row($row, [
                    'LAPORAN PENJUALAN POS /PRODUK',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                foreach ($laporan_penjualan as $laporan_penjualans) {
                    $sub_total = $laporan_penjualans->subtotal - $laporan_penjualans->potongan + $laporan_penjualans->tax;

                    $sheet->row(++$row, [
                        $laporan_penjualans->kode_barang,
                        $laporan_penjualans->nama_barang,
                        $laporan_penjualans->jumlah_produk,
                        $laporan_penjualans->nama_satuan,
                        $laporan_penjualans->subtotal,
                        $laporan_penjualans->potongan,
                        $laporan_penjualans->tax,
                        $sub_total,
                    ]);
                }

                //SUBTOTAL KESELURUHAN
                $sub_total_penjualan = DetailPenjualanPos::totalLaporanPenjualanPosProduk($request)->first();
                $row                 = ++$row;
                $sheet->row(++$row, [
                    'TOTAL',
                    '',
                    $total_jumlah_produk = round($sub_total_penjualan->jumlah_produk, 2),
                    '',
                    $total_potongan = round($sub_total_penjualan->subtotal, 2),
                    $total_potongan = round($sub_total_penjualan->potongan, 2),
                    $total_pajak = round($sub_total_penjualan->pajak, 2),
                    $total_subtotal = round($sub_total_penjualan->total, 2),
                ]);

            });
        })->export('xls');
    }
    public function foreachLaporan($laporan_penjualan)
    {
        $data_penjualan = array();
        foreach ($laporan_penjualan as $laporan_penjualans) {
            $sub_total = $laporan_penjualans->subtotal - $laporan_penjualans->potongan + $laporan_penjualans->tax;

            array_push($data_penjualan, ['laporan_penjualans' => $laporan_penjualans, 'sub_total' => $sub_total]);
        }

        return $data_penjualan;
    }

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $produk)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['produk']         = $produk;

        if ($produk == 0) {
            $request['produk'] = "";
        };

        $laporan_penjualan = DetailPenjualanPos::laporanPenjualanPosProduk($request)->get();
        $data_penjualan    = $this->foreachLaporan($laporan_penjualan);
        $total_penjualan   = DetailPenjualanPos::totalLaporanPenjualanPosProduk($request)->first();
        $data_warung       = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_laporan_penjualan_pos_produk',
            [
                'laporan_penjualan' => $laporan_penjualan,
                'data_penjualan'    => $data_penjualan,
                'total_penjualan'   => $total_penjualan,
                'data_warung'       => $data_warung,
                'dari_tanggal'      => $this->tanggal($dari_tanggal),
                'sampai_tanggal'    => $this->tanggal($sampai_tanggal),
                'setting_aplikasi'  => $setting_aplikasi,
            ])->with(compact('html'));
    }
}
