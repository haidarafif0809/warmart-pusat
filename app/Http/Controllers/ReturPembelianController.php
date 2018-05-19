<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hpp;
use App\Barang;
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

    // PENCARIAN TBS
    public function pencarianTbs(Request $request)
    {
        $session_id  = session()->getId();
        $no_faktur   = '';
        $user_warung = Auth::user()->id_warung;
        $search = $request->search;

        $tbs_retur = TbsReturPembelian::dataTransaksiTbsReturPembelian($session_id, $user_warung)
        ->where(function ($query) use ($search) {
            $query->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
        })->orderBy('tbs_retur_pembelians.id_tbs_retur_pembelian', 'desc')->paginate(10);
        
        $db = "App\TbsReturPembelian";
        $array = $this->foreachTbs($tbs_retur, $session_id, $db);

        $url     = '/retur-pembelian/view-tbs';
        $respons = $this->dataPagination($tbs_retur, $array, $no_faktur, $url);

        return response()->json($respons);
    }


    public function getSubtotal()
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;

        $subtotal            = TbsReturPembelian::subtotalTbs($user_warung, $session_id);
        $respons['subtotal'] = $subtotal;

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


    //PROSES TAMBAH TBS RETUR PEMBELIAN
    public function prosesTbs(Request $request)
    {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $session_id = session()->getId();
            $data_satuan = explode("|", $request->satuan_produk);
            $data_tbs   = TbsReturPembelian::where('id_produk', $request->id_produk)
            ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

            if ($data_tbs->count() > 0) {

                $subtotal_lama = $data_tbs->first()->subtotal;

                $jumlah_produk = $data_tbs->first()->jumlah_retur + $request->jumlah_retur;

                $subtotal_edit = ($jumlah_produk * $request->harga_produk) - $data_tbs->first()->potongan;

                $data_tbs->update(['jumlah_retur' => $jumlah_produk, 'subtotal' => $subtotal_edit, 'harga_produk' => $request->harga_produk, 'satuan_id' => $data_satuan[0], 'satuan_dasar' => $data_satuan[2]]);

                $subtotal = $jumlah_produk * $request->harga_produk;

                $respons['status']        = 1;
                $respons['subtotal_lama'] = $subtotal_lama;
                $respons['subtotal']      = $subtotal;
                return response()->json($respons);

            } else {

                // SUBTOTAL = JUMLAH * HARGA
                $subtotal = $request->jumlah_retur * $request->harga_produk;
                // INSERT TBS PEMBELIAN
                $insertTbs = TbsReturPembelian::create([
                    'id_produk'     => $request->id_produk,
                    'session_id'    => $session_id,
                    'jumlah_retur'  => $request->jumlah_retur,
                    'harga_produk'  => $request->harga_produk,
                    'subtotal'      => $subtotal,
                    'satuan_id'     => $data_satuan[0],
                    'satuan_dasar'  => $data_satuan[2],
                    'warung_id'     => Auth::user()->id_warung,
                    ]);

                $respons['status']   = 0;
                $respons['subtotal'] = $subtotal;

                return response()->json($respons);

            }

        }
    }


    public function hapusTbs($id)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $tbs_retur_pembelian = TbsReturPembelian::find($id);
            $respons['subtotal'] = $tbs_retur_pembelian->subtotal;
            $tbs_retur_pembelian->delete();

            return response()->json($respons);
        }
    }

    public function editJumlahReturTbs(Request $request) {

        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $tbs_retur_pembelian = TbsReturPembelian::find($request->id_tbs);

            if ($tbs_retur_pembelian->tax == 0) {
                $tax_produk = 0;
            } else {
                // TAX PRODUK = (HARGA * JUMLAH RETUR - POTONGAN) * TAX /100
                $tax_produk = (($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan) * $tax / 100;

                // TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (HARGA * JUMLAH RETUR - POTONGAN)
                $tax = ($tbs_retur_pembelian->tax * 100) / $tax_produk;
            }

            if ($tbs_retur_pembelian->ppn == 'Include') {
                // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
                $subtotal = ($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan;
            } elseif ($tbs_retur_pembelian->ppn == 'Exclude') {
                // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTOTAL
                $subtotal = (($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan) + $tax_produk;
            } else {
                $subtotal = ($tbs_retur_pembelian->harga_produk * $request->jumlah_retur) - $tbs_retur_pembelian->potongan;
            }

            // UPDATE JUMLAH RETUR, SUBTOTAL, DAN TAX
            $tbs_retur_pembelian->update(['jumlah_retur' => $request->jumlah_retur, 'subtotal' => $subtotal, 'tax' => $tax_produk]);
            $respons['subtotal'] = $subtotal;

            return response()->json($respons);
        }
    }


    public function editSatuan($request, $db){

        $satuan_konversi = explode("|", $request->satuan_produk);
        $edit_tbs_penjualan = $db::find($request->id_tbs);
        $harga_beli = Barang::select('harga_beli')->find($request->id_produk)->first()->harga_beli;

        $harga_produk = $harga_beli * ($satuan_konversi[3] * $satuan_konversi[4]);
        $subtotal = ($edit_tbs_penjualan->jumlah_produk * $harga_produk) - $edit_tbs_penjualan->potongan;

        $edit_tbs_penjualan->update(['satuan_id' => $satuan_konversi[0], 'harga_produk' => $harga_produk, 'subtotal' => $subtotal]);

        $respons['harga_produk'] = $harga_produk;
        $respons['nama_satuan']     = $satuan_konversi[1];
        $respons['satuan_id']     = $satuan_konversi[0];
        $respons['subtotal']     = $subtotal;

        return $respons;
    }


    public function editSatuanTbs(Request $request){

        $db = 'App\TbsReturPembelian';
        $respons = $this->editSatuan($request, $db);

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
