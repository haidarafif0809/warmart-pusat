<?php

namespace App\Http\Controllers;

use App\Barang;
use App\SettingAplikasi;
use App\Warung;
use App\Hpp;
use Auth;
use Excel;
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
        $hpp                = new Hpp();
        $total_nilai        = $hpp->totalnilai();
        foreach ($laporan_persediaan as $laporan_persediaans) {

            $stok_produk = $hpp->stok_produk($laporan_persediaans->id);
            $nilai       = $hpp->nilai($laporan_persediaans->id);
            $hpp_produk  = $hpp->hpp($laporan_persediaans->id);

            array_push($array, [
                'kode_produk' => $laporan_persediaans->kode_barang,
                'nama_produk' => $laporan_persediaans->Nama,
                'satuan'      => $laporan_persediaans->satuan->nama_satuan,
                'stok'        => $stok_produk,
                'nilai'       => $nilai,
                'hpp'         => $hpp_produk]);
        }

        $url     = '/laporan-persediaan/view';
        $respons = $this->paginationData($laporan_persediaan, $array, $total_nilai, $url);

        return response()->json($respons);
    }

        //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
    public function downloadExcel(Request $request)
    {

       $laporan_persediaan = Barang::with('satuan')->where('id_warung', Auth::user()->id_warung)->where('hitung_stok', 1)->orderBy('id', 'desc')->get();
        $array              = array();
        $hpp                = new Hpp();
        $total_nilai        = $hpp->totalnilai();

        Excel::create('Laporan Persediaan', function ($excel) use ($request, $laporan_persediaan, $total_nilai,$hpp) {
            // Set property
            $excel->sheet('Laporan Persediaan', function ($sheet) use ($request, $laporan_persediaan, $total_nilai,$hpp) {
                $row = 1;
                $sheet->row($row, [
                    'LAPORAN PERSEDIAAN',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                $row = ++$row;

                foreach ($laporan_persediaan as $laporan_persediaans) {
                    $stok_produk = $hpp->stok_produk($laporan_persediaans->id);
                    $nilai       = $hpp->nilai($laporan_persediaans->id);
                    $hpp_produk  = $hpp->hpp($laporan_persediaans->id);

                    $sheet->row(++$row, [
                        $laporan_persediaans->kode_barang,
                        $laporan_persediaans->Nama,
                        $laporan_persediaans->satuan->nama_satuan,
                        $stok_produk,
                        $hpp_produk,
                        $nilai,
                    ]);
                }
                 $sheet->row(++$row, [
                    'Total Nilai',
                    '',
                    '',
                    '',
                    '',
                    $total_nilai,
                ]);

            });
        })->export('xls');
    }

        public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Kode Produk',
            'Nama Produk',
            'Satuan',
            'Stok',
            'Hpp',
            'Nilai',
        ]);
        return $sheet;
    }

       public function cetakLaporan(Request $request)
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $data_warung = Warung::where('id', Auth::user()->id_warung)->first();

        $laporan_persediaan = Barang::with('satuan')->where('id_warung', Auth::user()->id_warung)->where('hitung_stok', 1)->orderBy('id', 'desc')->get();
        $array              = array();
        $hpp                = new Hpp();
        $total_nilai        = $hpp->totalnilai();

        foreach ($laporan_persediaan as $laporan_persediaans) {

            $stok_produk = $hpp->stok_produk($laporan_persediaans->id);
            $nilai       = $hpp->nilai($laporan_persediaans->id);
            $hpp_produk  = $hpp->hpp($laporan_persediaans->id);

            array_push($array, [
                'kode_produk' => $laporan_persediaans->kode_barang,
                'nama_produk' => $laporan_persediaans->Nama,
                'satuan'      => $laporan_persediaans->satuan->nama_satuan,
                'stok'        => $stok_produk,
                'nilai'       => $nilai,
                'hpp'         => $hpp_produk]);
        }

        return view('laporan.cetak_persediaan',
            [
                'data_persediaan'  => $array,
                'total_nilai' => $total_nilai ,
                'data_warung'      => $data_warung,
                'petugas'          => Auth::user()->name,
                'setting_aplikasi' => $setting_aplikasi,
            ])->with(compact('html'));
    }

    public function pencarian(Request $request)
    {

        $laporan_persediaan = Barang::with('satuan')->where('id_warung', Auth::user()->id_warung)->where('hitung_stok', 1)->where(function ($query) use ($request) {
            $query->orWhere('kode_barang', 'LIKE', $request->search . '%')
                ->orWhere('nama_barang', 'LIKE', $request->search . '%');
        })->orderBy('id', 'desc')->paginate(10);
        $array       = array();
        $hpp         = new Hpp();
        $total_nilai = $hpp->totalnilai();

        foreach ($laporan_persediaan as $laporan_persediaans) {

            $stok_produk = $hpp->stok_produk($laporan_persediaans->id);
            $nilai       = $hpp->nilai($laporan_persediaans->id);
            $hpp_produk  = $hpp->hpp($laporan_persediaans->id);

            array_push($array, [
                'kode_produk' => $laporan_persediaans->kode_barang,
                'nama_produk' => $laporan_persediaans->Nama,
                'satuan'      => $laporan_persediaans->satuan->nama_satuan,
                'stok'        => $stok_produk,
                'nilai'       => $nilai,
                'hpp'         => $hpp_produk]);
        }

        $url    = '/laporan-persediaan/pencarian';
        $search = $request->search;

        $respons = $this->paginationPencarianData($laporan_persediaan, $array, $total_nilai, $url, $search);

        return response()->json($respons);
    }

    public function paginationData($laporan_persediaan, $array, $total_nilai, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $laporan_persediaan->currentPage();
        $respons['data']           = $array;
        $respons['totalnilai']     = $total_nilai;
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
    public function paginationPencarianData($laporan_persediaan, $array, $total_nilai, $url, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $laporan_persediaan->currentPage();
        $respons['data']           = $array;
        $respons['totalnilai']     = $total_nilai;
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
