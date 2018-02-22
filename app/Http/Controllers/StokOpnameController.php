<?php

namespace App\Http\Controllers;

use App\DetailPembelian;
use App\Hpp;
use App\StokOpname;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    public function dataPagination($data_stok_opname, $array_stok_opname)
    {

        $respons['current_page']   = $data_stok_opname->currentPage();
        $respons['data']           = $array_stok_opname;
        $respons['first_page_url'] = url('/stok-  opname/view?page=' . $data_stok_opname->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_stok_opname->lastPage();
        $respons['last_page_url']  = url('/stok-  opname/view?page=' . $data_stok_opname->lastPage());
        $respons['next_page_url']  = $data_stok_opname->nextPageUrl();
        $respons['path']           = url('/stok-  opname/view');
        $respons['per_page']       = $data_stok_opname->perPage();
        $respons['prev_page_url']  = $data_stok_opname->previousPageUrl();
        $respons['to']             = $data_stok_opname->perPage();
        $respons['total']          = $data_stok_opname->total();

        return $respons;
    }

    public function foreachStokOpname($data_stok_opname)
    {

        $array_stok_opname = array();
        foreach ($data_stok_opname as $stok_opname) {
            $nama_produk = title_case($stok_opname->nama_barang);

            array_push($array_stok_opname, ['stok_opname' => $stok_opname, 'nama_produk' => $nama_produk]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_stok_opname, $array_stok_opname);

        return $respons;
    }

    public function view()
    {
        $data_stok_opname = StokOpname::dataStokOpname()->paginate(10);
        $respons          = $this->foreachStokOpname($data_stok_opname);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $data_stok_opname = StokOpname::cariDataStokOpname($request)->paginate(10);
        $respons          = $this->foreachStokOpname($data_stok_opname);
        return response()->json($respons);
    }

/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index()
    {
        //
    }

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function create()
    {
        //
    }

/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $warung_id     = Auth::user()->id_warung;
        $no_faktur     = StokOpname::no_faktur($warung_id);
        $stok_sekarang = Hpp::stok_produk($request->produk);
        $selisih_fisik = $request->jumlah_produk - $stok_sekarang;

        if ($selisih_fisik < 0) {
            // Harga Hpp
            $harga = Hpp::hargaHpp($request->produk, $warung_id);
        } else {
            // Harga Produk / Pembelian
            $data_pembelian = DetailPembelian::hargaProduk($request->produk, $warung_id);
            if ($data_pembelian->count() > 0) {
                $harga = $data_pembelian->first()->harga_produk;
            } else {
                $harga = $request->harga_produk;
            }
        }

        $total_selisih = $harga * $selisih_fisik;

        $this->validate($request, [
            'jumlah_produk' => 'required',
        ]);

        $stok_opname = StokOpname::create([
            'no_faktur'     => $no_faktur,
            'produk_id'     => $request->produk,
            'stok_sekarang' => $stok_sekarang,
            'jumlah_fisik'  => $request->jumlah_produk,
            'selisih_fisik' => $selisih_fisik,
            'harga'         => $harga,
            'total'         => $total_selisih,
            'warung_id'     => $warung_id,
            'keterangan'    => "Tes #0",
        ]);

        DB::commit();
        return response(200);
    }

/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function show($id)
    {
        //
    }

/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function edit($id)
    {
        //
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
        DB::beginTransaction();
        $data_stok_opname = StokOpname::find($id);
        $selisih_fisik    = $request->jumlah_produk - $data_stok_opname->stok_sekarang;
        $total            = $data_stok_opname->harga * $selisih_fisik;

        $this->validate($request, [
            'jumlah_produk' => 'required',
        ]);
        $data_stok_opname->update([
            'jumlah_fisik'  => $request->jumlah_produk,
            'selisih_fisik' => $selisih_fisik,
            'total'         => $total,
        ]);

        DB::commit();
        return response(200);

    }

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function destroy($id)
    {
        if (!StokOpname::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }
}
