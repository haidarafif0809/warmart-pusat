<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hpp;
use App\Suplier;
use App\Pembelian;
use App\TbsReturPembelian;
use Auth;

class ReturPembelianController extends Controller
{

    public function supplier(){

        $suplier = Suplier::select('id', 'nama_suplier')->where('warung_id', Auth::user()->id_warung)->get();
        $array     = [];
        foreach ($suplier as $supliers) {
            array_push($array, [
                'id'             => $supliers->id,
                'nama_suplier' => $supliers->nama_suplier]);
        }

        return response()->json($array);

    }

    // DATA PAGINTION
    public function dataPagination($retur_pembelian, $array, $no_faktur, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $retur_pembelian->currentPage();
        $respons['data']           = $array;
        $respons['no_faktur']      = $no_faktur;
        $respons['first_page_url'] = url($url . '?page=' . $retur_pembelian->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $retur_pembelian->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $retur_pembelian->lastPage());
        $respons['next_page_url']  = $retur_pembelian->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $retur_pembelian->perPage();
        $respons['prev_page_url']  = $retur_pembelian->previousPageUrl();
        $respons['to']             = $retur_pembelian->perPage();
        $respons['total']          = $retur_pembelian->total();
        //DATA PAGINATION

        return $respons;
    }

    public function foreachTbs($data_tbss, $session_id, $db){

        $array = [];
        foreach ($data_tbss as $data_tbs) {

            $diskon_persen = ($data_tbs->potongan / ($data_tbs->jumlah_retur * $data_tbs->harga_produk)) * 100;

            $ppn = $db::select('ppn')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->where('ppn', '!=', '')->limit(1);

            if ($ppn->count() > 0) {

                $ppn_produk = $ppn->first()->ppn;
                if ($data_tbs->tax == 0) {
                    $tax_persen = 0;
                } else {
                    if ($data_tbs->ppn == "Include") {
                        $tax_kembali = $data_tbs->subtotal - $data_tbs->tax;
                        //tax untuk mendapatkan 1,1
                        $tax_format = $data_tbs->subtotal / $tax_kembali - 1;
                        $tax_persen = $tax_format * 100;

                    } else if ($data_tbs->ppn == "Exclude") {
                        $tax_persen = ($data_tbs->tax * 100) / ($data_tbs->jumlah_produk * $data_tbs->harga_produk - $data_tbs->potongan);
                    }
                }
            } else {
                $ppn_produk = "";
                $tax_persen = 0;
            }

            array_push($array, [
                'data_tbs'          => $data_tbs,
                'nama_satuan'       => strtoupper($data_tbs->nama_satuan),
                'potongan_persen'   => $diskon_persen,
                'ppn_produk'        => $ppn_produk,
                'tax_persen'        => $tax_persen,
                ]);
        }

        return $array;
    }

    // VIEW TBS
    public function viewTbs()
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;

        $tbs_retur = TbsReturPembelian::dataTransaksiTbsReturPembelian($session_id, $user_warung)
        ->orderBy('tbs_retur_pembelians.id_tbs_retur_pembelian', 'desc')->paginate(10);
        
        $db = "App\TbsReturPembelian";
        $array = $this->foreachTbs($tbs_retur, $session_id, $db);

        $url     = '/retur-pembelian/view-tbs';
        $respons = $this->dataPagination($tbs_retur, $array, $no_faktur, $url);

        return response()->json($respons);
    }

    // VIEW DATA PEMBELIAAN
    public function dataPembelian(Request $request)
    {
        $no_faktur   = '';
        $warung_id = Auth::user()->id_warung;

        $pembelians = Pembelian::dataPembelian($request->supplier, $warung_id)        
        ->groupBy('detail_pembelians.id_produk')
        ->orderBy('pembelians.created_at', 'asc')
        ->paginate(10);
        $array = [];
        foreach ($pembelians as $pembelian) {
            $stok_produk = Hpp::stok_produk($pembelian->id_produk);

            array_push($array, [
                'pembelian'     => $pembelian,
                'stok_produk'   => $stok_produk
                ]);
        }

        $url     = '/retur-pembelian/data-pembelian';
        $respons = $this->dataPagination($pembelians, $array, $no_faktur, $url);

        return response()->json($respons);
    }

    // PENCARIAN DATA PEMBELIAN
    public function pencarianDataPembelian(Request $request)
    {
        $no_faktur   = '';
        $warung_id = Auth::user()->id_warung;        
        $search = $request->search;

        $pembelians = Pembelian::dataPembelian($request->supplier, $warung_id)
        ->where(function ($query) use ($search) {
            $query->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
        })->groupBy('detail_pembelians.id_produk')->orderBy('pembelians.created_at', 'asc')->paginate(10);

        $array = [];
        foreach ($pembelians as $pembelian) {
            $stok_produk = Hpp::stok_produk($pembelian->id_produk);

            array_push($array, [
                'pembelian'     => $pembelian,
                'stok_produk'   => $stok_produk
                ]);
        }

        $url     = '/retur-pembelian/data-pembelian';
        $respons = $this->dataPagination($pembelians, $array, $no_faktur, $url);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
