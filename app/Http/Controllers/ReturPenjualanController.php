<?php

namespace App\Http\Controllers;

use App\DetailReturPenjualan;
use App\Kas;
use App\ReturPenjualan;
use App\PenjualanPos;
use App\SettingAplikasi;
use App\TbsReturPenjualan;
use App\TransaksiKas;
use App\Warung;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Html\Builder;
use Laratrust;


class ReturPenjualanController extends Controller
{
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

        public function dataPagination($retur_penjualan, $array_retur_penjualan, $link_view)
    {

        $respons['current_page']   = $retur_penjualan->currentPage();
        $respons['data']           = $array_retur_penjualan;
        $respons['first_page_url'] = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $retur_penjualan->lastPage();
        $respons['last_page_url']  = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->lastPage());
        $respons['next_page_url']  = $retur_penjualan->nextPageUrl();
        $respons['path']           = url('/retur-penjualan/' . $link_view . '');
        $respons['per_page']       = $retur_penjualan->perPage();
        $respons['prev_page_url']  = $retur_penjualan->previousPageUrl();
        $respons['to']             = $retur_penjualan->perPage();
        $respons['total']          = $retur_penjualan->total();

        return $respons;
    }

        public function dataPaginationPencarianData($retur_penjualan, $array, $link_view, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $retur_penjualan->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $retur_penjualan->lastPage();
        $respons['last_page_url']  = url('/retur-penjualan/' . $link_view . '?page=' . $retur_penjualan->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $retur_penjualan->nextPageUrl();
        $respons['path']           = url('/retur-penjualan/'. $link_view);
        $respons['per_page']       = $retur_penjualan->perPage();
        $respons['prev_page_url']  = $retur_penjualan->previousPageUrl();
        $respons['to']             = $retur_penjualan->perPage();
        $respons['total']          = $retur_penjualan->total();
        //DATA PAGINATION
        return $respons;
    }


        public function view()
    {
        $retur_penjualan = ReturPenjualan::dataReturPenjualan()->paginate(10);

        $array_retur_penjualan = $this->foreachReturPenjualan($retur_penjualan);
        $link_view                = 'view';

        //DATA PAGINATION
        $respons = $this->dataPagination($retur_penjualan, $array_retur_penjualan, $link_view);
        return response()->json($respons);
    }

        public function foreachReturPenjualan($retur_penjualan)
    {
        $array_retur_penjualan = array();
        foreach ($retur_penjualan as $retur_penjualans) {
            array_push($array_retur_penjualan, [
                'id'         => $retur_penjualans->id,
                'no_faktur'  => $retur_penjualans->no_faktur,
                'pelanggan'  => $retur_penjualans->pelanggan,
                'waktu'      => $retur_penjualans->Waktu,
                'waktu_edit' => $retur_penjualans->WaktuEdit,
                'total'      => $retur_penjualans->getTotalSeparator(),
                'kas'        => $retur_penjualans->nama_kas,
                'keterangan' => $retur_penjualans->keterangan,
                'user_buat'  => $retur_penjualans->petugas,
            ]);
        }

        return $array_retur_penjualan;
    }

        public function foreachTbs($tbs_retur_penjualan, $jenis_tbs)
    {
        $array_retur_penjualan = array();

        foreach ($tbs_retur_penjualan as $tbs_retur_penjualans) {

            if ($jenis_tbs == 1) {
                // JIKA JENIS TBS == 1, MAKA ambil "id_tbs_retur_penjualan"
                $id_tbs = $tbs_retur_penjualans->id_tbs_retur_penjualan;
            } else {
                // JIKA JENIS TBS == 2, MAKA ambil "id_edit_tbs_retur_penjualan"
                $id_tbs = $tbs_retur_penjualans->id_edit_tbs_retur_penjualan;
            }

            array_push($array_retur_penjualan, [
                'id'                  => $id_tbs,
                'no_faktur_penjualan' => $tbs_retur_penjualans->no_faktur_penjualan,
                'kode_barang'         => $tbs_retur_penjualans->kode_barang,
                'nama_barang'         => $tbs_retur_penjualans->nama_barang,
                'jumlah_jual'         => $tbs_retur_penjualans->jumlah_jual,
                'jumlah_retur'        => $tbs_retur_penjualans->jumlah_retur,
                'satuan'              => $tbs_retur_penjualans->satuan,
                'harga_produk'        => $tbs_retur_penjualans->harga_produk,
                'potongan'            => $tbs_retur_penjualans->potongan,
                'subtotal'            => $tbs_retur_penjualans->subtotal,
            ]);
        }

        return $array_retur_penjualan;
    }

    //VIEW DAN PENCARIAN Tbs retur Penjualan
    public function viewTbsReturPenjualan()
    {
        $session_id            = session()->getId();
        $user_warung           = Auth::user()->id_warung;
        $tbs_retur_penjualan   = TbsReturPenjualan::dataTbsReturPenjualan($session_id)->paginate(10);
        $jenis_tbs             = 1;

        $array = $this->foreachTbs($tbs_retur_penjualan, $jenis_tbs);

        $url     = 'view-tbs-retur-penjualan';
        $respons = $this->dataPagination($tbs_retur_penjualan, $array, $url);
        return response()->json($respons);
    }

        public function pencarianTbsReturPenjualan(Request $request)
    {
        $session_id            = session()->getId();
        $user_warung           = Auth::user()->id_warung;
        $search                = $request->search;
        $tbs_retur_penjualan   = TbsReturPenjualan::cariTbsReturPenjualan($request, $session_id)->paginate(10);
        $jenis_tbs             = 1;

        $array = $this->foreachTbs($tbs_retur_penjualan, $jenis_tbs);

        $url     = 'view-tbs-retur-penjualan';
        $respons = $this->dataPaginationPencarianData($tbs_retur_penjualan, $array, $url, $search);
        return response()->json($respons);
    }

    //VIEW DAN PENCARIAN dataSupplierHutang
    public function dataPelangganRetur($id)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;
        $id_pelanggan  = $id;

        $data_retur_penjualan     = PenjualanPos::getDataPenjualanRetur($id_pelanggan)->paginate(10);

        $array = array();
        foreach ($data_retur_penjualan as $data_retur_penjualans) {
            array_push($array, [
                'id_penjualan'        => $data_retur_penjualans->id_penjualan,
                'id_produk'           => $data_retur_penjualans->id_produk, 
                'kode_barang'         => $data_retur_penjualans->kode_barang,
                'nama_barang'         => $data_retur_penjualans->nama_barang,
                'jumlah_jual'         => $data_retur_penjualans->jumlah_jual,
                'jumlah_produk'       => $data_retur_penjualans->jumlah_produk,
                'satuan'              => $data_retur_penjualans->nama_satuan,
                'harga_produk'        => $data_retur_penjualans->harga_produk,
                'subtotal'            => $data_retur_penjualans->subtotal,
                'waktu'               => $data_retur_penjualans->Waktu,
            ]);
        }
        $url     = 'data-pelanggan-retur/'.$id_pelanggan;
        $respons = $this->dataPagination($data_retur_penjualan, $array, $url);
        return response()->json($respons);
    }
        public function pencarianPelangganRetur(Request $request, $id)
    {
        $session_id  = session()->getId();
        $user_warung = Auth::user()->id_warung;
        $id_pelanggan  = $id;
        $search = $request->search;
        $data_retur_penjualan     = PenjualanPos::getDataCariPenjualanRetur($id_pelanggan,$request)->paginate(10);

        $array = array();
        foreach ($data_retur_penjualan as $data_retur_penjualans) {
            array_push($array, [
                'id_penjualan'        => $data_retur_penjualans->id_penjualan,
                'kode_barang'         => $data_retur_penjualans->kode_barang,
                'nama_barang'         => $data_retur_penjualans->nama_barang,
                'jumlah_jual'         => $data_retur_penjualans->jumlah_jual,
                'jumlah_produk'       => $data_retur_penjualans->jumlah_produk,
                'satuan'              => $data_retur_penjualans->nama_satuan,
                'harga_produk'        => $data_retur_penjualans->harga_produk,
                'subtotal'            => $data_retur_penjualans->subtotal,
                'waktu'               => $data_retur_penjualans->Waktu,
            ]);
        }
        $url     = 'data-pelanggan-retur/'.$id_pelanggan;
        $respons = $this->dataPaginationPencarianData($data_retur_penjualan, $array, $url, $search);
        return response()->json($respons);
    }
        //INSERT TBS
    public function prosesTbsReturPenjualan(Request $request)
    {
        $session_id = session()->getId();
        $data_satuan = explode("|", $request->satuan); 
        $data_tbs   = TbsReturPenjualan::where('no_faktur_penjualan', $request->id_penjualan)->where('id_produk', $request->id_produk)
        ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);


        $subtotal = $request->jumlah_retur * $request->harga_produk;
        //JIKA FAKTUR YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs->count() > 0) {
            return 0;
        } else {
            $tbs_pembayaran_hutang = TbsReturPenjualan::create([
                'session_id'          => $session_id,
                'no_faktur_penjualan' => $request->id_penjualan,
                'id_produk'           => $request->id_produk,
                'harga_produk'        => $request->harga_produk,
                'jumlah_retur'        => $request->jumlah_retur,
                'jumlah_jual'         => $request->jumlah_jual,
                'subtotal'            => $subtotal,
                'id_satuan_jual'           => $data_satuan[0], 
                'id_satuan'        => $data_satuan[2],
                'warung_id'           => Auth::user()->id_warung,
            ]);
            $respons['subtotal'] = $subtotal;
            return response()->json($respons);
        }
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
