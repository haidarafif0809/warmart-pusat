<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailPembelian;
use App\Suplier;
use App\Warung;
use Auth;
use Excel;
use Illuminate\Http\Request;

class LaporanPembelianProdukController extends Controller
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

    public function dataSupplier()
    {
        $supplier       = Suplier::select(['id', 'nama_suplier'])->where('warung_id', Auth::user()->id_warung)->get();
        $array_supplier = array();
        foreach ($supplier as $suppliers) {
            array_push($array_supplier, [
                'id'           => $suppliers->id,
                'nama_suplier' => title_case($suppliers->nama_suplier),
            ]);
        }
        return response()->json($array_supplier);
    }

    public function dataPagination($laporan_pembelian, $array_pembelian)
    {
        $respons['current_page']   = $laporan_pembelian->currentPage();
        $respons['data']           = $array_pembelian;
        $respons['first_page_url'] = url('/laporan-pembelian-produk/view?page=' . $laporan_pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_pembelian->lastPage();
        $respons['last_page_url']  = url('/laporan-pembelian-produk/view?page=' . $laporan_pembelian->lastPage());
        $respons['next_page_url']  = $laporan_pembelian->nextPageUrl();
        $respons['path']           = url('/laporan-pembelian-produk/view');
        $respons['per_page']       = $laporan_pembelian->perPage();
        $respons['prev_page_url']  = $laporan_pembelian->previousPageUrl();
        $respons['to']             = $laporan_pembelian->perPage();
        $respons['total']          = $laporan_pembelian->total();

        return $respons;
    }

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Kode Produk',
            'Nama Produk',
            'Supplier',
            'Jumlah',
            'Satuan',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
        ]);
        return $sheet;
    }

    public function foreachLaporan($laporan_pembelian)
    {
        $data_pembelian = array();
        foreach ($laporan_pembelian as $laporan_pembelians) {
            $sub_total = $laporan_pembelians->subtotal - $laporan_pembelians->potongan + $laporan_pembelians->tax;

            array_push($data_pembelian, ['laporan_pembelians' => $laporan_pembelians, 'sub_total' => $sub_total]);
        }

        return $data_pembelian;
    }

    public function prosesLaporanPembelianProduk(Request $request)
    {
        $laporan_pembelian = DetailPembelian::laporanPembelianProduk($request)->paginate(10);

        $array_pembelian = array();
        foreach ($laporan_pembelian as $laporan_pembelians) {
            $sub_total = $laporan_pembelians->subtotal - $laporan_pembelians->potongan + $laporan_pembelians->tax;

            array_push($array_pembelian, ['laporan_pembelians' => $laporan_pembelians, 'sub_total' => $sub_total]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_pembelian, $array_pembelian);
        return response()->json($respons);
    }

    public function subtotalPembelianProduk(Request $request)
    {
        //SUBTOTAL KESELURUHAN
        $sub_total_penjualan = DetailPembelian::subtotalLaporanPembelianProduk($request)->first();

        return response()->json($sub_total_penjualan);
    }

    public function pencarian(Request $request)
    {
        $laporan_pembelian = DetailPembelian::cariLaporanPembelianProduk($request)->paginate(10);

        $array_pembelian = array();
        foreach ($laporan_pembelian as $laporan_pembelians) {
            array_push($array_pembelian, ['laporan_pembelians' => $laporan_pembelians]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_pembelian, $array_pembelian);
        return response()->json($respons);
    }

    //DOWNLOAD EXCEL - LAPORAN PEMBELIAN /PRODUK
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $produk, $suplier)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;

        if ($produk == 0) {
            $request['produk'] = "";
        };

        if ($suplier == 0) {
            $request['suplier'] = "";
        };

        $laporan_pembelian = DetailPembelian::laporanPembelianProduk($request)->get();

        Excel::create('Laporan Pembelian Produk', function ($excel) use ($request, $laporan_pembelian) {
            // Set property
            $excel->sheet('Laporan Pembelian Produk', function ($sheet) use ($request, $laporan_pembelian) {
                $row = 1;
                $sheet->row($row, [
                    'LAPORAN PEMBELIAN /PRODUK',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                foreach ($laporan_pembelian as $laporan_pembelians) {
                    $sub_total = $laporan_pembelians->subtotal - $laporan_pembelians->potongan + $laporan_pembelians->tax;

                    $sheet->row(++$row, [
                        $laporan_pembelians->kode_barang,
                        $laporan_pembelians->nama_barang,
                        $laporan_pembelians->nama_suplier,
                        $laporan_pembelians->jumlah_produk,
                        $laporan_pembelians->nama_satuan,
                        $laporan_pembelians->subtotal,
                        $laporan_pembelians->potongan,
                        $laporan_pembelians->tax,
                        $sub_total,
                    ]);
                }

                //SUBTOTAL KESELURUHAN
                $sub_total_penjualan = DetailPembelian::subtotalLaporanPembelianProduk($request)->first();
                $row                 = ++$row;
                $sheet->row(++$row, [
                    'TOTAL',
                    '',
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

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $produk, $suplier)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['produk']         = $produk;
        $request['suplier']        = $suplier;

        if ($produk == 0) {
            $request['produk'] = "";
        };

        if ($suplier == 0) {
            $request['suplier'] = "";
        };

        $laporan_pembelian  = DetailPembelian::laporanPembelianProduk($request)->get();
        $data_pembelian     = $this->foreachLaporan($laporan_pembelian);
        $subtotal_pembelian = DetailPembelian::subtotalLaporanPembelianProduk($request)->first();
        $data_warung        = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_laporan_pembelian_produk',
            [
                'laporan_pembelian'  => $laporan_pembelian,
                'data_pembelian'     => $data_pembelian,
                'subtotal_pembelian' => $subtotal_pembelian,
                'data_warung'        => $data_warung,
                'dari_tanggal'       => $this->tanggal($dari_tanggal),
                'sampai_tanggal'     => $this->tanggal($sampai_tanggal),
                'produk'             => $produk,
                'suplier'            => $suplier,
            ])->with(compact('html'));
    }
}
