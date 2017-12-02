<?php

namespace App\Http\Controllers;

use App\KasKeluar;
use App\KasMasuk;
use App\KategoriTransaksi;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;

class KategoriTransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('kategori_transaksi.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori_transaksi.create');
    }

    public function statusTransaksi($kategori_transaksi)
    {
        $data_kategori_masuk  = KasMasuk::where('kategori', $kategori_transaksi->id)->where('id_warung', $kategori_transaksi->id_warung)->count();
        $data_kategori_keluar = KasKeluar::where('kategori', $kategori_transaksi->id)->where('warung_id', $kategori_transaksi->id_warung)->count();

        if ($data_kategori_masuk > 0 or $data_kategori_keluar > 0) {
            $status_transaksi = 1;
        } else {
            $status_transaksi = 0;
        }
        return $status_transaksi;
    }

    public function dataPagination($data_kategori_transaksi, $array_kategori_transaksi)
    {

        $respons['current_page']   = $data_kategori_transaksi->currentPage();
        $respons['data']           = $array_kategori_transaksi;
        $respons['first_page_url'] = url('/kategori-transaksi/view?page=' . $data_kategori_transaksi->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_kategori_transaksi->lastPage();
        $respons['last_page_url']  = url('/kategori-transaksi/view?page=' . $data_kategori_transaksi->lastPage());
        $respons['next_page_url']  = $data_kategori_transaksi->nextPageUrl();
        $respons['path']           = url('/kategori-transaksi/view');
        $respons['per_page']       = $data_kategori_transaksi->perPage();
        $respons['prev_page_url']  = $data_kategori_transaksi->previousPageUrl();
        $respons['to']             = $data_kategori_transaksi->perPage();
        $respons['total']          = $data_kategori_transaksi->total();

        return $respons;
    }

    public function view()
    {
        $data_kategori_transaksi  = KategoriTransaksi::where('id_warung', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
        $array_kategori_transaksi = array();
        foreach ($data_kategori_transaksi as $kategori_transaksi) {
            $status_transaksi = $this->statusTransaksi($kategori_transaksi);

            array_push($array_kategori_transaksi, [
                'id'                      => $kategori_transaksi->id,
                'nama_kategori_transaksi' => $kategori_transaksi->nama_kategori_transaksi,
                'status_transaksi'        => $status_transaksi]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kategori_transaksi, $array_kategori_transaksi);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $data_kategori_transaksi = KategoriTransaksi::where('id_warung', Auth::user()->id_warung)
            ->where('nama_kategori_transaksi', 'LIKE', "%$request->search%")
            ->orderBy('id', 'desc')->paginate(10);
        $array_kategori_transaksi = array();
        foreach ($data_kategori_transaksi as $kategori_transaksi) {
            $status_transaksi = $this->statusTransaksi($kategori_transaksi);

            array_push($array_kategori_transaksi, [
                'id'                      => $kategori_transaksi->id,
                'nama_kategori_transaksi' => $kategori_transaksi->nama_kategori_transaksi,
                'status_transaksi'        => $status_transaksi]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kategori_transaksi, $array_kategori_transaksi);
        return response()->json($respons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_kategori_transaksi' => 'required|unique:kategori_transaksis,nama_kategori_transaksi,NULL,id,id_warung,' . Auth::user()->id_warung . '',
        ]);

        if (Auth::user()->id_warung != "") {
            $kategori_transaksi = KategoriTransaksi::create([
                'nama_kategori_transaksi' => $request->nama_kategori_transaksi,
                'id_warung'               => Auth::user()->id_warung]);
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kategori_transaksi = KategoriTransaksi::find($id);
        return $kategori_transaksi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id_warung          = Auth::user()->id_warung;
        $kategori_transaksi = KategoriTransaksi::find($id);

        if ($id_warung == $kategori_transaksi->id_warung) {
            return view('kategori_transaksi.edit')->with(compact('kategori_transaksi'));
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id_warung = Auth::user()->id_warung;
        $this->validate($request, [
            'nama_kategori_transaksi' => 'required|unique:kategori_transaksis,nama_kategori_transaksi,' . $id . ',id,id_warung,' . Auth::user()->id_warung,
        ]);

        $kategori_transaksi = KategoriTransaksi::find($id);

        if ($id_warung == $kategori_transaksi->id_warung) {
            $kategori_transaksi = KategoriTransaksi::find($id)->update([
                'nama_kategori_transaksi' => $request->nama_kategori_transaksi,
            ]);
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id_warung          = Auth::user()->id_warung;
        $kategori_transaksi = KategoriTransaksi::find($id);

        if ($id_warung == $kategori_transaksi->id_warung) {
            KategoriTransaksi::destroy($id);
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }
}
