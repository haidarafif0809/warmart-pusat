<?php

namespace App\Http\Controllers;

use App\Kas;
use App\SettingAplikasi;
use App\TransaksiKas;
use App\Warung;
use Auth;
use Excel;
use Illuminate\Http\Request;

class LaporanKasController extends Controller
{
    public function tanggal($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "d-m-Y");
        return $date_format;
    }

    public function dataKas()
    {
        $kas = Kas::select('id', 'nama_kas')->where('warung_id', Auth::user()->id_warung)->orderBy('id', 'DESC')->get();
        return response()->json($kas);
    }

    public function dataPagination($laporan_kas, $data_laporan_kas, $link_view)
    {
        $respons['current_page']   = $laporan_kas->currentPage();
        $respons['data']           = $data_laporan_kas;
        $respons['first_page_url'] = url('/laporan-kas/' . $link_view . '?page=' . $laporan_kas->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_kas->lastPage();
        $respons['last_page_url']  = url('/laporan-kas/' . $link_view . '?page=' . $laporan_kas->lastPage());
        $respons['next_page_url']  = $laporan_kas->nextPageUrl();
        $respons['path']           = url('/laporan-kas/' . $link_view . '');
        $respons['per_page']       = $laporan_kas->perPage();
        $respons['prev_page_url']  = $laporan_kas->previousPageUrl();
        $respons['to']             = $laporan_kas->perPage();
        $respons['total']          = $laporan_kas->total();

        return $respons;
    }

    public function foreachLaporan($laporan_kas)
    {
        $data_laporan_kas = array();
        foreach ($laporan_kas as $data_laporan_kass) {
            if ($data_laporan_kass->jenis_transaksi == 'PenjualanPos') {
                $jenis_transaksi = "Penjualan POS";
            } elseif ($data_laporan_kass->jenis_transaksi == 'penjualan') {
                $jenis_transaksi = "Penjualan Online";
            } elseif ($data_laporan_kass->jenis_transaksi == 'kas_masuk') {
                $jenis_transaksi = "Kas Masuk";
            } elseif ($data_laporan_kass->jenis_transaksi == 'kas_mutasi') {
                $jenis_transaksi = "Kas Mutasi";
            } elseif ($data_laporan_kass->jenis_transaksi == 'kas_keluar') {
                $jenis_transaksi = "Kas Keluar";
            } elseif ($data_laporan_kass->jenis_transaksi == 'pembelian') {
                $jenis_transaksi = "Pembelian";
            } else {
                $jenis_transaksi = $data_laporan_kass->jenis_transaksi;
            }
            array_push($data_laporan_kas, ['data_laporan' => $data_laporan_kass, 'jenis_transaksi' => $jenis_transaksi]);
        }
        return $data_laporan_kas;
    }

//LAPORAN DETAIL
    //KAS MASUK
    public function prosesLaporanKasDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::dataKasMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::cariKasMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasDetailMasuk(Request $request)
    {
        $subtotal_lap_kas_masuk_detail = TransaksiKas::subtotalLaporanKasMasukDetail($request)->first()->jumlah_masuk;
        return $subtotal_lap_kas_masuk_detail;
    }
//KAS MASUK

//KAS KELUAR
    public function prosesLaporanKasKeluarDetail(Request $request)
    {
        //KAS KELUAR
        $laporan_kas      = TransaksiKas::dataKasKeluar($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-keluar";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasKeluarDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::cariKasKeluar($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-keluar";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasDetailKeluar(Request $request)
    {
        $subtotal_lap_kas_keluar_detail = TransaksiKas::subtotalLaporanKasKeluarDetail($request)->first()->jumlah_keluar;
        return $subtotal_lap_kas_keluar_detail;
    }
//KAS KELUAR

//KAS MUTASI (MASUK)
    public function prosesLaporanKasMutasiMasukDetail(Request $request)
    {
        //KAS MUTASI (MASUK)
        $laporan_kas      = TransaksiKas::dataKasMutasiMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-masuk";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasMutasiMasukDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::cariKasMutasiMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-masuk";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasDetailMutasiMasuk(Request $request)
    {
        $subtotal_lap_kas_mutasi_masuk_detail = TransaksiKas::subtotalLaporanKasMutasiMasukDetail($request)->first()->jumlah_masuk;
        return $subtotal_lap_kas_mutasi_masuk_detail;
    }
//KAS MUTASI (MASUK)

//KAS MUTASI (KELUAR)
    public function prosesLaporanKasMutasiKeluarDetail(Request $request)
    {
        //KAS MUTASI (KELUAR)
        $laporan_kas      = TransaksiKas::dataKasMutasiKeluar($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-keluar";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasMutasiKeluarDetail(Request $request)
    {
        //KAS KELUAR
        $laporan_kas      = TransaksiKas::cariKasMutasiKeluar($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-keluar";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasDetailMutasiKeluar(Request $request)
    {
        $subtotal_lap_kas_mutasi_masuk_detail = TransaksiKas::subtotalLaporanKasMutasiKeluarDetail($request)->first()->jumlah_keluar;
        return $subtotal_lap_kas_mutasi_masuk_detail;
    }
//KAS MUTASI (KELUAR)

//TOTAL KAS
    public function subtotalLaporanKasDetail(Request $request)
    {
        $total_awal  = TransaksiKas::totalAwalLaporan($request)->first()->kas_awal;
        $total_akhir = TransaksiKas::totalAkhirLaporan($request)->first()->kas_akhir;

        $respon_total['total_awal']    = $total_awal;
        $respon_total['total_akhir']   = $total_awal + $total_akhir;
        $respon_total['perubahan_kas'] = ($total_awal + $total_akhir) - $total_awal;

        return $respon_total;
    }
//TOTAL KAS
    //LAPORAN DETAIL

//LAPORAN REKAP
    //KAS MASUK
    public function prosesLaporanKasRekap(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::dataKasMasukRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasRekap(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::cariKasMasukRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasRekapMasuk(Request $request)
    {
        $subtotal_lap_kas_masuk_rekap = TransaksiKas::subtotalLaporanKasMasukRekap($request)->first()->jumlah_masuk;
        return $subtotal_lap_kas_masuk_rekap;
    }
    //KAS MASUK

    //KAS KELUAR
    public function prosesLaporanKasKeluarRekap(Request $request)
    {
        //KAS KELUAR
        $laporan_kas      = TransaksiKas::dataKasKeluarRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-keluar-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasKeluarRekap(Request $request)
    {
        //KAS KELUAR
        $laporan_kas      = TransaksiKas::cariKasKeluarRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-keluar-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasRekapKeluar(Request $request)
    {
        $subtotal_lap_kas_keluar_rekap = TransaksiKas::subtotalLaporanKasKeluarRekap($request)->first()->jumlah_keluar;
        return $subtotal_lap_kas_keluar_rekap;
    }
    //KAS KELUAR

    //KAS MUTASI (MASUK) REKAP
    public function prosesLaporanKasMutasiMasukRekap(Request $request)
    {
        //KAS MUTASI (MASUK)
        $laporan_kas      = TransaksiKas::dataKasMutasiMasukRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-masuk-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasMutasiMasukRekap(Request $request)
    {
        //KAS MUTASI (MASUK)
        $laporan_kas      = TransaksiKas::cariKasMutasiMasukRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-masuk-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasRekapMutasiMasuk(Request $request)
    {
        $subtotal_lap_kas_mutasi_masuk_rekap = TransaksiKas::subtotalLaporanKasMutasiMasukRekap($request)->first()->jumlah_masuk;
        return $subtotal_lap_kas_mutasi_masuk_rekap;
    }
    //KAS MUTASI (MASUK) REKAP

    //KAS MUTASI (KELUAR) REKAP
    public function prosesLaporanKasMutasiKeluarRekap(Request $request)
    {
        //KAS MUTASI (KELUAR)
        $laporan_kas      = TransaksiKas::dataKasMutasiKeluarRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-masuk-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasMutasiKeluarRekap(Request $request)
    {
        //KAS MUTASI (KELUAR)
        $laporan_kas      = TransaksiKas::cariKasMutasiKeluarRekap($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view-mutasi-masuk-rekap";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasRekapMutasiKeluar(Request $request)
    {
        $subtotal_lap_kas_mutasi_keluar_rekap = TransaksiKas::subtotalLaporanKasMutasiKeluarRekap($request)->first()->jumlah_keluar;
        return $subtotal_lap_kas_mutasi_keluar_rekap;
    }
    //KAS MUTASI (KELUAR) REKAP

//LAPORAN REKAP

    //CETAK LAPORAN KAS DETAIL
    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $kas, $jenis_laporan)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        $data_warung      = Warung::where('id', Auth::user()->id_warung)->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['kas']            = $kas;
        $request['jenis_laporan']  = $jenis_laporan;

        if ($jenis_laporan == 0) {
//DETAIL

            //KAS MASUK
            $laporan_kas        = TransaksiKas::dataKasMasuk($request)->get();
            $data_laporan_kas   = $this->foreachLaporan($laporan_kas);
            $subtotal_kas_masuk = $this->subtotalLaporanKasDetailMasuk($request);

            //KAS KELUAR
            $laporan_kas_keluar      = TransaksiKas::dataKasKeluar($request)->get();
            $data_laporan_kas_keluar = $this->foreachLaporan($laporan_kas_keluar);
            $subtotal_kas_keluar     = $this->subtotalLaporanKasDetailKeluar($request);

            //KAS MUTASI (MASUK)
            $laporan_kas_mutasi_masuk      = TransaksiKas::dataKasMutasiMasuk($request)->paginate(10);
            $data_laporan_kas_mutasi_masuk = $this->foreachLaporan($laporan_kas_mutasi_masuk);
            $subtotal_kas_mutasi_masuk     = $this->subtotalLaporanKasDetailMutasiMasuk($request);

            //KAS MUTASI (KELUAR)
            $laporan_kas_mutasi_keluar      = TransaksiKas::dataKasMutasiKeluar($request)->paginate(10);
            $data_laporan_kas_mutasi_keluar = $this->foreachLaporan($laporan_kas_mutasi_keluar);
            $subtotal_kas_mutasi_keluar     = $this->subtotalLaporanKasDetailMutasiKeluar($request);

            //LAPORAN KAS DETAIL

        } else {
//REKAP

            //LAPORAN KAS REKAP
            //KAS MASUK
            $laporan_kas        = TransaksiKas::dataKasMasukRekap($request)->paginate(10);
            $data_laporan_kas   = $this->foreachLaporan($laporan_kas);
            $subtotal_kas_masuk = $this->subtotalLaporanKasRekapMasuk($request);

            //KAS KELUAR
            $laporan_kas_keluar      = TransaksiKas::dataKasKeluarRekap($request)->get();
            $data_laporan_kas_keluar = $this->foreachLaporan($laporan_kas_keluar);
            $subtotal_kas_keluar     = $this->subtotalLaporanKasRekapKeluar($request);

            //KAS MUTASI (MASUK)
            $laporan_kas_mutasi_masuk      = TransaksiKas::dataKasMutasiMasukRekap($request)->paginate(10);
            $data_laporan_kas_mutasi_masuk = $this->foreachLaporan($laporan_kas_mutasi_masuk);
            $subtotal_kas_mutasi_masuk     = $this->subtotalLaporanKasRekapMutasiMasuk($request);

            //KAS MUTASI (KELUAR)
            $laporan_kas_mutasi_keluar      = TransaksiKas::dataKasMutasiKeluarRekap($request)->paginate(10);
            $data_laporan_kas_mutasi_keluar = $this->foreachLaporan($laporan_kas_mutasi_keluar);
            $subtotal_kas_mutasi_keluar     = $this->subtotalLaporanKasRekapMutasiKeluar($request);

            //LAPORAN KAS REKAP

        }
        //TOTAL KAS
        $total_kas = $this->subtotalLaporanKasDetail($request);

        return view('laporan.cetak_laporan_kas',
            [
                'data_warung'                    => $data_warung,
                'setting_aplikasi'               => $setting_aplikasi,
                'dari_tanggal'                   => $this->tanggal($dari_tanggal),
                'sampai_tanggal'                 => $this->tanggal($sampai_tanggal),
                'petugas'                        => Auth::user()->name,
                'jenis_laporan'                  => $jenis_laporan,
                'data_laporan_kas'               => $data_laporan_kas,
                'subtotal_kas_masuk'             => $subtotal_kas_masuk,
                'data_laporan_kas_keluar'        => $data_laporan_kas_keluar,
                'subtotal_kas_keluar'            => $subtotal_kas_keluar,
                'data_laporan_kas_mutasi_masuk'  => $data_laporan_kas_mutasi_masuk,
                'subtotal_kas_mutasi_masuk'      => $subtotal_kas_mutasi_masuk,
                'data_laporan_kas_mutasi_keluar' => $data_laporan_kas_mutasi_keluar,
                'subtotal_kas_mutasi_keluar'     => $subtotal_kas_mutasi_keluar,
                'total_kas'                      => $total_kas,
            ])->with(compact('html'));
    }

    //DOWNLOAD LAPORAN KAS DETAIL
    public function downloadLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $kas, $jenis_laporan)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        $data_warung      = Warung::where('id', Auth::user()->id_warung)->first();

        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $request['kas']            = $kas;
        $request['jenis_laporan']  = $jenis_laporan;

        if ($jenis_laporan == 0) {
            //DOWNLOAD DETAIL

            //KAS MASUK
            $laporan_kas        = TransaksiKas::dataKasMasuk($request)->get();
            $data_laporan_kas   = $this->foreachLaporan($laporan_kas);
            $subtotal_kas_masuk = $this->subtotalLaporanKasDetailMasuk($request);

            //KAS KELUAR
            $laporan_kas_keluar      = TransaksiKas::dataKasKeluar($request)->get();
            $data_laporan_kas_keluar = $this->foreachLaporan($laporan_kas_keluar);
            $subtotal_kas_keluar     = $this->subtotalLaporanKasDetailKeluar($request);

            //KAS MUTASI (MASUK)
            $laporan_kas_mutasi_masuk      = TransaksiKas::dataKasMutasiMasuk($request)->paginate(10);
            $data_laporan_kas_mutasi_masuk = $this->foreachLaporan($laporan_kas_mutasi_masuk);
            $subtotal_kas_mutasi_masuk     = $this->subtotalLaporanKasDetailMutasiMasuk($request);

            //KAS MUTASI (KELUAR)
            $laporan_kas_mutasi_keluar      = TransaksiKas::dataKasMutasiKeluar($request)->paginate(10);
            $data_laporan_kas_mutasi_keluar = $this->foreachLaporan($laporan_kas_mutasi_keluar);
            $subtotal_kas_mutasi_keluar     = $this->subtotalLaporanKasDetailMutasiKeluar($request);

        } else {
            //DOWNLOAD REKAP

            //KAS MASUK
            $laporan_kas        = TransaksiKas::dataKasMasukRekap($request)->paginate(10);
            $data_laporan_kas   = $this->foreachLaporan($laporan_kas);
            $subtotal_kas_masuk = $this->subtotalLaporanKasRekapMasuk($request);

            //KAS KELUAR
            $laporan_kas_keluar      = TransaksiKas::dataKasKeluarRekap($request)->get();
            $data_laporan_kas_keluar = $this->foreachLaporan($laporan_kas_keluar);
            $subtotal_kas_keluar     = $this->subtotalLaporanKasRekapKeluar($request);

            //KAS MUTASI (MASUK)
            $laporan_kas_mutasi_masuk      = TransaksiKas::dataKasMutasiMasukRekap($request)->paginate(10);
            $data_laporan_kas_mutasi_masuk = $this->foreachLaporan($laporan_kas_mutasi_masuk);
            $subtotal_kas_mutasi_masuk     = $this->subtotalLaporanKasRekapMutasiMasuk($request);

            //KAS MUTASI (KELUAR)
            $laporan_kas_mutasi_keluar      = TransaksiKas::dataKasMutasiKeluarRekap($request)->paginate(10);
            $data_laporan_kas_mutasi_keluar = $this->foreachLaporan($laporan_kas_mutasi_keluar);
            $subtotal_kas_mutasi_keluar     = $this->subtotalLaporanKasRekapMutasiKeluar($request);
        }

        //TOTAL KAS
        $total_kas = $this->subtotalLaporanKasDetail($request);

        Excel::create('Laporan Kas Periode', function ($excel) use ($data_warung, $setting_aplikasi, $dari_tanggal, $sampai_tanggal, $jenis_laporan, $data_laporan_kas, $subtotal_kas_masuk, $data_laporan_kas_keluar, $subtotal_kas_keluar, $data_laporan_kas_mutasi_masuk, $subtotal_kas_mutasi_masuk, $data_laporan_kas_mutasi_keluar, $subtotal_kas_mutasi_keluar, $total_kas) {
            // Set property
            $excel->sheet('Laporan Kas Periode', function ($sheet) use ($data_warung, $setting_aplikasi, $dari_tanggal, $sampai_tanggal, $jenis_laporan, $data_laporan_kas, $subtotal_kas_masuk, $data_laporan_kas_keluar, $subtotal_kas_keluar, $data_laporan_kas_mutasi_masuk, $subtotal_kas_mutasi_masuk, $data_laporan_kas_mutasi_keluar, $subtotal_kas_mutasi_keluar, $total_kas) {

                $sheet->loadView('laporan.download_laporan_kas', [
                    'data_warung'                    => $data_warung,
                    'setting_aplikasi'               => $setting_aplikasi,
                    'dari_tanggal'                   => $this->tanggal($dari_tanggal),
                    'sampai_tanggal'                 => $this->tanggal($sampai_tanggal),
                    'petugas'                        => Auth::user()->name,
                    'jenis_laporan'                  => $jenis_laporan,
                    'data_laporan_kas'               => $data_laporan_kas,
                    'subtotal_kas_masuk'             => $subtotal_kas_masuk,
                    'data_laporan_kas_keluar'        => $data_laporan_kas_keluar,
                    'subtotal_kas_keluar'            => $subtotal_kas_keluar,
                    'data_laporan_kas_mutasi_masuk'  => $data_laporan_kas_mutasi_masuk,
                    'subtotal_kas_mutasi_masuk'      => $subtotal_kas_mutasi_masuk,
                    'data_laporan_kas_mutasi_keluar' => $data_laporan_kas_mutasi_keluar,
                    'subtotal_kas_mutasi_keluar'     => $subtotal_kas_mutasi_keluar,
                    'total_kas'                      => $total_kas,
                ]);

            });

        })->export('xls');
    }
}
