<?php

namespace App\Http\Controllers;

use App\Kas;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;

class LaporanKasController extends Controller
{
    public function dataKas()
    {
        $kas = Kas::select('id', 'nama_kas')->where('warung_id', Auth::user()->id_warung)->orderBy('id', 'DESC')->get();
        return response()->json($kas);
    }

    public function dataPagination($laporan_kas, $data_laporan_kas)
    {
        $respons['current_page']   = $laporan_kas->currentPage();
        $respons['data']           = $data_laporan_kas;
        $respons['first_page_url'] = url('/laporan-kas/view?page=' . $laporan_kas->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_kas->lastPage();
        $respons['last_page_url']  = url('/laporan-kas/view?page=' . $laporan_kas->lastPage());
        $respons['next_page_url']  = $laporan_kas->nextPageUrl();
        $respons['path']           = url('/laporan-kas/view');
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
            } else {
                $jenis_transaksi = $data_laporan_kass->jenis_transaksi;
            }
            array_push($data_laporan_kas, ['data_laporan' => $data_laporan_kass, 'jenis_transaksi' => $jenis_transaksi]);
        }
        return $data_laporan_kas;
    }

    public function prosesLaporanKasDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::dataKasMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_kas, $data_laporan_kas);
        return response()->json($respons);
    }

    public function pencarianLaporanKasDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::cariKasMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_kas, $data_laporan_kas);
        return response()->json($respons);
    }

    public function subtotalLaporanKasDetailMasuk(Request $request)
    {
        $subtotal_lap_kas_masuk_detail = TransaksiKas::subtotalLaporanKasMasukDetail($request)->first()->subtotal;
        return $subtotal_lap_kas_masuk_detail;
    }
}
