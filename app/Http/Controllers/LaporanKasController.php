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
        $link_view = "view";
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
        $link_view = "view-keluar";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasMutasiMasukDetail(Request $request)
    {
        //KAS MASUK
        $laporan_kas      = TransaksiKas::cariKasMutasiMasuk($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view";
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
        $link_view = "view-keluar";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function pencarianLaporanKasMutasiKeluarDetail(Request $request)
    {
        //KAS KELUAR
        $laporan_kas      = TransaksiKas::cariKasMutasiKeluar($request)->paginate(10);
        $data_laporan_kas = $this->foreachLaporan($laporan_kas);

        //DATA PAGINATION
        $link_view = "view";
        $respons   = $this->dataPagination($laporan_kas, $data_laporan_kas, $link_view);
        return response()->json($respons);
    }

    public function subtotalLaporanKasDetailMutasiKeluar(Request $request)
    {
        $subtotal_lap_kas_mutasi_masuk_detail = TransaksiKas::subtotalLaporanKasMutasiKeluarDetail($request)->first()->jumlah_keluar;
        return $subtotal_lap_kas_mutasi_masuk_detail;
    }
//KAS MUTASI (KELUAR)

}
