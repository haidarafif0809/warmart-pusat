<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use App\SettingAplikasi;
use App\Warung;
use Auth;
use Excel;
use Illuminate\Http\Request;

class LaporanMutasiStokController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    //METHOD PAGINATION
    public function dataPagination($laporan_mutasi_kas, $array_mutasi_kas)
    {
        $respons['current_page']   = $laporan_mutasi_kas->currentPage();
        $respons['data']           = $array_mutasi_kas;
        $respons['first_page_url'] = url('/laporan-mutasi-stok/view?page=' . $laporan_mutasi_kas->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_mutasi_kas->lastPage();
        $respons['last_page_url']  = url('/laporan-mutasi-stok/view?page=' . $laporan_mutasi_kas->lastPage());
        $respons['next_page_url']  = $laporan_mutasi_kas->nextPageUrl();
        $respons['path']           = url('/laporan-mutasi-stok/view');
        $respons['per_page']       = $laporan_mutasi_kas->perPage();
        $respons['prev_page_url']  = $laporan_mutasi_kas->previousPageUrl();
        $respons['to']             = $laporan_mutasi_kas->perPage();
        $respons['total']          = $laporan_mutasi_kas->total();

        return $respons;
    }

    public function subtotalLaporan($total_hpp, $total_hpp_masuk, $total_hpp_keluar)
    {
        $total_stok_awal    = $total_hpp->stok_awal;
        $total_nilai_awal   = $total_hpp->total_awal;
        $total_stok_masuk   = $total_hpp_masuk->stok_masuk;
        $total_nilai_masuk  = $total_hpp_masuk->total_masuk;
        $total_stok_keluar  = $total_hpp_keluar->stok_keluar;
        $total_nilai_keluar = $total_hpp_keluar->total_keluar;
        $total_stok_akhir   = ($total_stok_awal + $total_stok_masuk) - $total_stok_keluar;
        $total_nilai_akhir  = ($total_nilai_awal + $total_nilai_masuk) - $total_nilai_keluar;

        $response['total_stok_awal']    = round($total_stok_awal, 2);
        $response['total_nilai_awal']   = round($total_nilai_awal, 2);
        $response['total_stok_masuk']   = round($total_stok_masuk, 2);
        $response['total_nilai_masuk']  = round($total_nilai_masuk, 2);
        $response['total_stok_keluar']  = round($total_stok_keluar, 2);
        $response['total_nilai_keluar'] = round($total_nilai_keluar, 2);
        $response['total_stok_akhir']   = round($total_stok_akhir, 2);
        $response['total_nilai_akhir']  = round($total_nilai_akhir, 2);

        return $response;
    }

    public function dataMutasiStok($hpp, $hpp_masuk, $hpp_keluar)
    {
        $data_mutasi['stok_awal']    = $hpp->stok_awal;
        $data_mutasi['total_awal']   = $hpp->total_awal;
        $data_mutasi['stok_masuk']   = $hpp_masuk->stok_masuk;
        $data_mutasi['total_masuk']  = $hpp_masuk->total_masuk;
        $data_mutasi['stok_keluar']  = $hpp_keluar->stok_keluar;
        $data_mutasi['total_keluar'] = $hpp_keluar->total_keluar;
        $data_mutasi['stok_akhir']   = ($hpp->stok_awal + $hpp_masuk->stok_masuk) - $hpp_keluar->stok_keluar;
        $data_mutasi['total_akhir']  = ($hpp->total_awal + $hpp_masuk->total_masuk) - $hpp_keluar->total_keluar;
        return $data_mutasi;
    }

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Kode Produk',
            'Nama Produk',
            'Satuan',
            'Awal',
            'Nilai Awal',
            'Masuk',
            'Nilai Masuk',
            'Keluar',
            'Nilai Keluar',
            'Akhir',
            'Nilai Akhir',
        ]);
        return $sheet;
    }

    public function tanggal($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "d-m-Y");
        return $date_format;
    }

    public function foreachLaporan($daftar_produk, $request)
    {
        $data_mutasi_stok = array();
        foreach ($daftar_produk as $daftar_produks) {
            $hpp        = Hpp::dataAwal($daftar_produks, $request);
            $hpp_masuk  = Hpp::dataMasuk($daftar_produks, $request);
            $hpp_keluar = Hpp::dataKeluar($daftar_produks, $request);

            $data_mutasi = $this->dataMutasiStok($hpp, $hpp_masuk, $hpp_keluar);

            array_push($data_mutasi_stok, ['daftar_produks' => $daftar_produks, 'stok_awal' => $data_mutasi['stok_awal'], 'total_awal' => $data_mutasi['total_awal'], 'stok_masuk' => $data_mutasi['stok_masuk'], 'total_masuk' => $data_mutasi['total_masuk'], 'stok_keluar' => $data_mutasi['stok_keluar'], 'total_keluar' => $data_mutasi['total_keluar'], 'stok_akhir' => $data_mutasi['stok_akhir'], 'total_akhir' => $data_mutasi['total_akhir']]);
        }

        return $data_mutasi_stok;
    }

    public function prosesLaporanMutasiStok(Request $request)
    {
        $daftar_produk = Barang::daftarProduk()->paginate(10);

        $array_mutasi_kas = array();
        foreach ($daftar_produk as $daftar_produks) {
            $hpp        = Hpp::dataAwal($daftar_produks, $request);
            $hpp_masuk  = Hpp::dataMasuk($daftar_produks, $request);
            $hpp_keluar = Hpp::dataKeluar($daftar_produks, $request);

            $data_mutasi = $this->dataMutasiStok($hpp, $hpp_masuk, $hpp_keluar);

            array_push($array_mutasi_kas, ['daftar_produks' => $daftar_produks, 'stok_awal' => $data_mutasi['stok_awal'], 'total_awal' => $data_mutasi['total_awal'], 'stok_masuk' => $data_mutasi['stok_masuk'], 'total_masuk' => $data_mutasi['total_masuk'], 'stok_keluar' => $data_mutasi['stok_keluar'], 'total_keluar' => $data_mutasi['total_keluar'], 'stok_akhir' => $data_mutasi['stok_akhir'], 'total_akhir' => $data_mutasi['total_akhir']]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($daftar_produk, $array_mutasi_kas);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $daftar_produk = Barang::cariDaftarProduk($request)->paginate(10);

        $array_mutasi_kas = array();
        foreach ($daftar_produk as $daftar_produks) {
            $hpp        = Hpp::dataAwal($daftar_produks, $request);
            $hpp_masuk  = Hpp::dataMasuk($daftar_produks, $request);
            $hpp_keluar = Hpp::dataKeluar($daftar_produks, $request);

            $data_mutasi = $this->dataMutasiStok($hpp, $hpp_masuk, $hpp_keluar);

            array_push($array_mutasi_kas, ['daftar_produks' => $daftar_produks, 'stok_awal' => $data_mutasi['stok_awal'], 'total_awal' => $data_mutasi['total_awal'], 'stok_masuk' => $data_mutasi['stok_masuk'], 'total_masuk' => $data_mutasi['total_masuk'], 'stok_keluar' => $data_mutasi['stok_keluar'], 'total_keluar' => $data_mutasi['total_keluar'], 'stok_akhir' => $data_mutasi['stok_akhir'], 'total_akhir' => $data_mutasi['total_akhir']]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($daftar_produk, $array_mutasi_kas);
        return response()->json($respons);
    }

    public function subtotalMutasiStok(Request $request)
    {
        $total_hpp        = Hpp::totalAwal($request);
        $total_hpp_masuk  = Hpp::totalMasuk($request);
        $total_hpp_keluar = Hpp::totalKeluar($request);

        $response = $this->subtotalLaporan($total_hpp, $total_hpp_masuk, $total_hpp_keluar);

        return $response;
    }

    //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $daftar_produk             = Barang::daftarProduk()->get();

        Excel::create('Laporan Mutasi Stok', function ($excel) use ($request, $daftar_produk) {
            // Set property
            $excel->sheet('Laporan Mutasi Stok', function ($sheet) use ($request, $daftar_produk) {
                $row = 1;
                $sheet->row($row, [
                    'LAPORAN MUTASI STOK',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                foreach ($daftar_produk as $daftar_produks) {
                    $hpp        = Hpp::dataAwal($daftar_produks, $request);
                    $hpp_masuk  = Hpp::dataMasuk($daftar_produks, $request);
                    $hpp_keluar = Hpp::dataKeluar($daftar_produks, $request);

                    $data_mutasi = $this->dataMutasiStok($hpp, $hpp_masuk, $hpp_keluar);

                    $sheet->row(++$row, [
                        $daftar_produks->kode_barang,
                        $daftar_produks->nama_barang,
                        $daftar_produks->nama_satuan,
                        $data_mutasi['stok_awal'],
                        $data_mutasi['total_awal'],
                        $data_mutasi['stok_masuk'],
                        $data_mutasi['total_masuk'],
                        $data_mutasi['stok_keluar'],
                        $data_mutasi['total_keluar'],
                        $data_mutasi['stok_akhir'],
                        $data_mutasi['total_akhir'],
                    ]);
                }

                $total_hpp        = Hpp::totalAwal($request);
                $total_hpp_masuk  = Hpp::totalMasuk($request);
                $total_hpp_keluar = Hpp::totalKeluar($request);

                $row = ++$row;
                $sheet->row(++$row, [
                    'TOTAL',
                    '',
                    '',
                    $total_stok_awal = round($total_hpp->stok_awal, 2),
                    $total_nilai_awal = round($total_hpp->total_awal, 2),
                    $total_stok_masuk = round($total_hpp_masuk->stok_masuk, 2),
                    $total_nilai_masuk = round($total_hpp_masuk->total_masuk, 2),
                    $total_stok_keluar = round($total_hpp_keluar->stok_keluar, 2),
                    $total_nilai_keluar = round($total_hpp_keluar->total_keluar, 2),
                    $total_stok_akhir = round(($total_stok_awal + $total_stok_masuk) - $total_stok_keluar, 2),
                    $total_nilai_akhir = round(($total_nilai_awal + $total_nilai_masuk) - $total_nilai_keluar, 2),
                ]);

            });
        })->export('xls');
    }

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;

        $daftar_produk        = Barang::daftarProduk()->paginate(10);
        $data_mutasi_stok     = $this->foreachLaporan($daftar_produk, $request);
        $subtotal_mutasi_stok = $this->subtotalMutasiStok($request);

        $data_warung = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_kartu_stok',
            [
                'daftar_produk'        => $daftar_produk,
                'data_mutasi_stok'     => $data_mutasi_stok,
                'subtotal_mutasi_stok' => $subtotal_mutasi_stok,
                'data_warung'          => $data_warung,
                'dari_tanggal'         => $this->tanggal($dari_tanggal),
                'sampai_tanggal'       => $this->tanggal($sampai_tanggal),
                'setting_aplikasi'     => $setting_aplikasi,
            ])->with(compact('html'));
    }
}
