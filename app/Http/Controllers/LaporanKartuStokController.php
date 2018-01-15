<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use Auth;
use Illuminate\Http\Request;

class LaporanKartuStokController extends Controller
{

    //METHOD PAGINATION
    public function dataPagination($laporan_kartu_stok, $array_kartu_stok)
    {
        $respons['current_page']   = $laporan_kartu_stok->currentPage();
        $respons['data']           = $array_kartu_stok;
        $respons['first_page_url'] = url('/laporan-kartu-stok/view?page=' . $laporan_kartu_stok->firstItem());
        $respons['from']           = 1;
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

            if ($data_kartu_stoks->jenis_hpp == 1) {
                $saldo_awal = ($saldo_awal + $data_kartu_stoks->jumlah_masuk);
            } else {
                $saldo_awal = $saldo_awal - $data_kartu_stoks->jumlah_keluar;
            }

            array_push($data_kartu_stok, ['data_kartu_stoks' => $data_kartu_stoks, 'saldo_awal' => $saldo_awal]);
        }
        return $data_kartu_stok;
    }

    public function prosesLaporanKartuStok(Request $request)
    {
        $laporan_kartu_stok = Hpp::dataKartuStok($request)->paginate(10);
        $saldo_awal         = Hpp::dataSaldoAwal($request)->first()->saldo_awal;
        $data_kartu_stok    = $this->foreachLaporan($laporan_kartu_stok, $saldo_awal);

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_kartu_stok, $data_kartu_stok);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $laporan_kartu_stok = Hpp::cariKartuStok($request)->paginate(10);
        $saldo_awal         = Hpp::dataSaldoAwal($request)->first()->saldo_awal;
        $data_kartu_stok    = $this->foreachLaporan($laporan_kartu_stok, $saldo_awal);

        //DATA PAGINATION
        $respons = $this->dataPagination($laporan_kartu_stok, $data_kartu_stok);
        return response()->json($respons);
    }

    public function totalSaldoAwal(Request $request)
    {
        $saldo_awal = Hpp::dataSaldoAwal($request)->first()->saldo_awal;
        return $saldo_awal;
    }

}
