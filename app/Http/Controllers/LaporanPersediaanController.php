<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;

class LaporanPersediaanController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {

    }

    public function view()
    {

        $laporan_persediaan = Barang::with('satuan')->where('id_warung', Auth::user()->id_warung)->where('hitung_stok', 1)->orderBy('id', 'desc')->paginate(10);
        $array              = array();

        foreach ($laporan_persediaan as $laporan_persediaans) {

            $hpp         = new Hpp();
            $stok_produk = $hpp->stok_produk($laporan_persediaans->id);
            $nilai       = $hpp->nilai($laporan_persediaans->id);
            $hpp_produk  = $hpp->hpp($laporan_persediaans->id);

            array_push($array, [
                'kode_produk' => $laporan_persediaans->kode_barang,
                'nama_produk' => $laporan_persediaans->NamaProduk,
                'satuan'      => $laporan_persediaans->satuan->nama_satuan,
                'stok'        => $stok_produk,
                'nilai'       => $nilai,
                'hpp'         => $hpp_produk]);
        }

        $url     = '/laporan-persediaan/view';
        $respons = $this->paginationData($laporan_persediaan, $array, $url);

        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {

        $laporan_persediaan = Barang::with('satuan')->where('id_warung', Auth::user()->id_warung)->where('hitung_stok', 1)->where(function ($query) use ($request) {
            $query->orWhere('kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('nama_barang', 'LIKE', $request->search . '%');
        })->orderBy('id', 'desc')->paginate(10);
        $array = array();

        foreach ($laporan_persediaan as $laporan_persediaans) {

            $hpp         = new Hpp();
            $stok_produk = $hpp->stok_produk($laporan_persediaans->id);
            $nilai       = $hpp->nilai($laporan_persediaans->id);
            $hpp_produk  = $hpp->hpp($laporan_persediaans->id);

            array_push($array, [
                'kode_produk' => $laporan_persediaans->kode_barang,
                'nama_produk' => $laporan_persediaans->NamaProduk,
                'satuan'      => $laporan_persediaans->satuan->nama_satuan,
                'stok'        => $stok_produk,
                'nilai'       => $nilai,
                'hpp'         => $hpp_produk]);
        }

        $url    = '/laporan-persediaan/pencarian';
        $search = $request->search;

        $respons = $this->paginationPencarianData($laporan_persediaan, $array, $url, $search);

        return response()->json($respons);
    }

    public function paginationData($laporan_persediaan, $array, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $laporan_persediaan->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url . '?page=' . $laporan_persediaan->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_persediaan->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $laporan_persediaan->lastPage());
        $respons['next_page_url']  = $laporan_persediaan->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $laporan_persediaan->perPage();
        $respons['prev_page_url']  = $laporan_persediaan->previousPageUrl();
        $respons['to']             = $laporan_persediaan->perPage();
        $respons['total']          = $laporan_persediaan->total();
        //DATA PAGINATION

        return $respons;
    }
    public function paginationPencarianData($laporan_persediaan, $array, $url, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $laporan_persediaan->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url . '?page=' . $laporan_persediaan->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $laporan_persediaan->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $laporan_persediaan->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $laporan_persediaan->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $laporan_persediaan->perPage();
        $respons['prev_page_url']  = $laporan_persediaan->previousPageUrl();
        $respons['to']             = $laporan_persediaan->perPage();
        $respons['total']          = $laporan_persediaan->total();
        //DATA PAGINATION

        return $respons;
    }
}
