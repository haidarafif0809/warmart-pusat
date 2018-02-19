<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use App\SettingAplikasi;
use App\Warung;
use Auth;
use Excel;
use Illuminate\Http\Request;

class LaporanKartuStokController extends Controller
{

    //METHOD PAGINATION
    public function dataPagination($laporan_kartu_stok, $array_kartu_stok,$saldo_awal,$total_saldo)
    {
        $respons['current_page']   = $laporan_kartu_stok->currentPage();
        $respons['data']           = $array_kartu_stok;
        $respons['first_page_url'] = url('/laporan-kartu-stok/view?page=' . $laporan_kartu_stok->firstItem());
        $respons['from']           = 1;
        $respons['total_saldo']    = $total_saldo;
        $respons['saldo_awal']    = $saldo_awal;
        $respons['last_page']      = $laporan_kartu_stok->lastPage();
        $respons['last_page_url']  = url('/laporan-kartu-stok/view?page=' . $laporan_kartu_stok->lastPage());
        $respons['next_page_url']  = $laporan_kartu_stok->nextPageUrl();
        $respons['path']           = url('/laporan-kartu-stok/view');
        $respons['per_page']       = $laporan_kartu_stok->perPage();
        $respons['prev_page_url']  = $laporan_kartu_stok->previousPageUrl();
        $respons['to']             = $laporan_kartu_stok->perPage();
        $respons['total']          = $laporan_kartu_stok->total();

        return $respons;
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

    public function foreachLaporan($laporan_kartu_stok, $saldo_awal)
    {
        $data_kartu_stok = array();
        foreach ($laporan_kartu_stok as $data_kartu_stoks) {
            //SALDO AWAL
            if ($data_kartu_stoks->jenis_hpp == 1) {
                $saldo_awal = ($saldo_awal + $data_kartu_stoks->jumlah_masuk);
            } else {
                $saldo_awal = $saldo_awal - $data_kartu_stoks->jumlah_keluar;
            }
            //PELANGGAN
            if ($data_kartu_stoks->pelanggan == null) {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $data_kartu_stoks->pelanggan;
            }
            //SUPLIER
            if ($data_kartu_stoks->suplier == null) {
                $suplier = 'Umum';
            } else {
                $suplier = $data_kartu_stoks->suplier;
            }

            array_push($data_kartu_stok, ['data_kartu_stoks' => $data_kartu_stoks, 'saldo_awal' => $saldo_awal, 'pelanggan' => $pelanggan, 'suplier' => $suplier]);
        }
        return $data_kartu_stok;
    }

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'No Faktur',
            'Jenis Transaksi',
            'Harga',
            'Waktu',
            'Jumlah Masuk',
            'Jumlah Keluar',
            'Saldo',
        ]);
        return $sheet;
    }

    public function tanggal($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "d-m-Y");
        return $date_format;
    }

    public function prosesLaporanKartuStok(Request $request)
    {
        $laporan_kartu_stok = Hpp::dataKartuStok($request)->paginate(10);
        $page = $laporan_kartu_stok->currentPage();
        if ($page == 1) {
            $saldo = Hpp::dataSaldoAwal($request);
            $saldo_awal = $saldo;
            $total_saldo = $saldo;
        }else{
            $saldo_awal = $request->saldoAwal;
            $total_saldo = $saldo_awal;
        }
        
        $data_kartu_stok = array();
        foreach ($laporan_kartu_stok as $data_kartu_stoks) {
            //SALDO AWAL
            if ($data_kartu_stoks->jenis_hpp == 1) {
                $saldo_awal = ($saldo_awal + $data_kartu_stoks->jumlah_masuk);
            } else {
                $saldo_awal = $saldo_awal - $data_kartu_stoks->jumlah_keluar;
            }
            //PELANGGAN
            if ($data_kartu_stoks->pelanggan == null) {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $data_kartu_stoks->pelanggan;
            }
            //SUPLIER
            if ($data_kartu_stoks->suplier == null) {
                $suplier = 'Umum';
            } else {
                $suplier = $data_kartu_stoks->suplier;
            }

            array_push($data_kartu_stok, ['data_kartu_stoks' => $data_kartu_stoks, 'saldo_awal' => $saldo_awal, 'pelanggan' => $pelanggan, 'suplier' => $suplier]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_kartu_stok, $data_kartu_stok,$saldo_awal,$total_saldo);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $laporan_kartu_stok = Hpp::cariKartuStok($request)->paginate(10);
        $page = $laporan_kartu_stok->currentPage();
        if ($page == 1) {
            $saldo = Hpp::dataSaldoAwal($request);
            $saldo_awal = $saldo;
            $total_saldo = $saldo;
        }else{
            $saldo_awal = $request->saldoAwal;
            $total_saldo = $saldo_awal;
        }
        
        $data_kartu_stok = array();
        foreach ($laporan_kartu_stok as $data_kartu_stoks) {
            //SALDO AWAL
            if ($data_kartu_stoks->jenis_hpp == 1) {
                $saldo_awal = ($saldo_awal + $data_kartu_stoks->jumlah_masuk);
            } else {
                $saldo_awal = $saldo_awal - $data_kartu_stoks->jumlah_keluar;
            }
            //PELANGGAN
            if ($data_kartu_stoks->pelanggan == null) {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $data_kartu_stoks->pelanggan;
            }
            //SUPLIER
            if ($data_kartu_stoks->suplier == null) {
                $suplier = 'Umum';
            } else {
                $suplier = $data_kartu_stoks->suplier;
            }

            array_push($data_kartu_stok, ['data_kartu_stoks' => $data_kartu_stoks, 'saldo_awal' => $saldo_awal, 'pelanggan' => $pelanggan, 'suplier' => $suplier]);
        }

       //DATA PAGINATION
        $respons = $this->dataPagination($laporan_kartu_stok, $data_kartu_stok,$saldo_awal,$total_saldo);
        return response()->json($respons);
    }

    public function totalSaldoAwal(Request $request)
    {
        $saldo_awal = Hpp::dataSaldoAwal($request);
        return $saldo_awal;
    }

    public function totalSaldoAkhir(Request $request)
    {
        $response = Hpp::dataSaldoAkhir($request)->first();

        $saldo_akhir['jumlah_masuk']  = $response->jumlah_masuk;
        $saldo_akhir['jumlah_keluar'] = $response->jumlah_keluar;
        $saldo_akhir['saldo_akhir']   = $response->jumlah_masuk - $response->jumlah_keluar;

        return $saldo_akhir;
    }

    //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $produk)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['produk']         = $produk;

        $laporan_kartu_stok = Hpp::dataKartuStok($request)->get();
        $saldo_awal         = Hpp::dataSaldoAwal($request);

        Excel::create('Laporan Kartu Stok', function ($excel) use ($request, $laporan_kartu_stok, $saldo_awal) {
            // Set property
            $excel->sheet('Laporan Kartu Stok', function ($sheet) use ($request, $laporan_kartu_stok, $saldo_awal) {
                $row = 1;
                $sheet->row($row, [
                    'LAPORAN KARTU STOK',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                $total_saldo_awal = $this->totalSaldoAwal($request);

                $row = ++$row;
                $sheet->row(++$row, [
                    '',
                    'SALDO AWAL',
                    '',
                    '',
                    '',
                    '',
                    $total_nilai_akhir = round($total_saldo_awal, 2),
                ]);

                foreach ($laporan_kartu_stok as $laporan_kartu_stoks) {

                    if ($laporan_kartu_stoks->jenis_hpp == 1) {
                        $saldo_awal = ($saldo_awal + $laporan_kartu_stoks->jumlah_masuk);
                    } else {
                        $saldo_awal = $saldo_awal - $laporan_kartu_stoks->jumlah_keluar;
                    }

                    $sheet->row(++$row, [
                        $laporan_kartu_stoks->no_faktur,
                        $laporan_kartu_stoks->jenis_transaksi,
                        $laporan_kartu_stoks->harga_unit,
                        $laporan_kartu_stoks->created_at,
                        $laporan_kartu_stoks->jumlah_masuk,
                        $laporan_kartu_stoks->jumlah_keluar,
                        $saldo_awal,
                    ]);

                }

            });
        })->export('xls');
    }

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $produk)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['produk']         = $produk;

        $laporan_kartu_stok = Hpp::dataKartuStok($request)->paginate(10);
        $saldo_awal         = Hpp::dataSaldoAwal($request);
        $data_kartu_stok    = $this->foreachLaporan($laporan_kartu_stok, $saldo_awal);
        $total_saldo_awal   = $this->totalSaldoAwal($request);

        $data_warung = Warung::where('id', Auth::user()->id_warung)->first();
        $produk      = Barang::select(['id', 'kode_barang', 'nama_barang'])->where('id', $produk)->first();

        return view('laporan.cetak_kartu_stok',
            [
                'data_kartu_stok'  => $data_kartu_stok,
                'total_saldo_awal' => $total_saldo_awal,
                'produk'           => $produk,
                'data_warung'      => $data_warung,
                'petugas'          => Auth::user()->name,
                'dari_tanggal'     => $this->tanggal($dari_tanggal),
                'sampai_tanggal'   => $this->tanggal($sampai_tanggal),
                'setting_aplikasi' => $setting_aplikasi,
            ])->with(compact('html'));
    }

}
