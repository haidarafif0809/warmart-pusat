<?php

namespace App\Http\Controllers;

use App\DetailItemKeluar;
use App\EditTbsItemKeluar;
use App\ItemKeluar;
use App\TbsItemKeluar;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Html\Builder;
use Laratrust;

class ItemKeluarController extends Controller
{
    //MENAMPILKAN DATA YG ADA DI ITEM KELUAR
    public function index(Request $request, Builder $htmlBuilder)
    {

    }

    public function paginationData($item_keluar, $array, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $item_keluar->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']      = $this->otoritasItemKeluar();
        $respons['first_page_url'] = url($url . '?page=' . $item_keluar->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $item_keluar->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $item_keluar->lastPage());
        $respons['next_page_url']  = $item_keluar->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $item_keluar->perPage();
        $respons['prev_page_url']  = $item_keluar->previousPageUrl();
        $respons['to']             = $item_keluar->perPage();
        $respons['total']          = $item_keluar->total();
        //DATA PAGINATION

        return $respons;
    }
    public function paginationPencarianData($item_keluar, $array, $url, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $item_keluar->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']      = $this->otoritasItemKeluar();
        $respons['first_page_url'] = url($url . '?page=' . $item_keluar->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $item_keluar->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $item_keluar->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $item_keluar->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $item_keluar->perPage();
        $respons['prev_page_url']  = $item_keluar->previousPageUrl();
        $respons['to']             = $item_keluar->perPage();
        $respons['total']          = $item_keluar->total();
        //DATA PAGINATION

        return $respons;
    }

    public function view()
    {
        $item_keluar = ItemKeluar::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
        $array       = array();

        foreach ($item_keluar as $item_keluars) {
            array_push($array, [
                'id'         => $item_keluars->id,
                'no_faktur'  => $item_keluars->no_faktur,
                'total'      => $item_keluars->TotalKeluar,
                'keterangan' => $item_keluars->keterangan,
                'waktu'      => $item_keluars->Waktu,
                'waktu_edit' => $item_keluars->WaktuEdit]);
        }
        $url     = '/item-keluar/view';
        $respons = $this->paginationData($item_keluar, $array, $url);

        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $item_keluar = ItemKeluar::where('warung_id', Auth::user()->id_warung)->where(function ($query) use ($request) {

            $query->orWhere('no_faktur', 'LIKE', $request->search . '%')
            ->orWhere('keterangan', 'LIKE', $request->search . '%')
            ->orWhere('created_at', 'LIKE', $request->search . '%')
            ->orWhere('updated_at', 'LIKE', $request->search . '%');

        })->orderBy('id', 'desc')->paginate(10);
        $array = array();

        foreach ($item_keluar as $item_keluars) {
            array_push($array, [
                'id'         => $item_keluars->id,
                'no_faktur'  => $item_keluars->no_faktur,
                'total'      => $item_keluars->TotalKeluar,
                'keterangan' => $item_keluars->keterangan,
                'waktu'      => $item_keluars->Waktu,
                'waktu_edit' => $item_keluars->WaktuEdit]);
        }

        $url    = '/item-keluar/pencarian';
        $search = $request->search;

        $respons = $this->paginationPencarianData($item_keluar, $array, $url, $search);

        return response()->json($respons);
    }

    public function viewTbsItemKeluar()
    {
        $session_id      = session()->getId();
        $user_warung     = Auth::user()->id_warung;
        $tbs_item_keluar = TbsItemKeluar::with(['produk'])->where('warung_id', $user_warung)->where('session_id', $session_id)->orderBy('id_tbs_item_keluar', 'desc')->paginate(10);
        $array           = array();

        foreach ($tbs_item_keluar as $tbs_item_keluars) {
            array_push($array, [
                'id_tbs_item_keluar' => $tbs_item_keluars->id_tbs_item_keluar,
                'nama_produk'        => $tbs_item_keluars->TitleCaseProduk,
                'kode_produk'        => $tbs_item_keluars->produk->kode_barang,
                'jumlah_produk'      => $tbs_item_keluars->jumlah_produk]);
        }

        $url     = '/item-keluar/view-tbs-item-keluar';
        $respons = $this->paginationData($tbs_item_keluar, $array, $url);

        return response()->json($respons);
    }

    public function pencarianTbsItemKeluar(Request $request)
    {
        $session_id      = session()->getId();
        $user_warung     = Auth::user()->id_warung;
        $tbs_item_keluar = TbsItemKeluar::select('tbs_item_keluars.id_tbs_item_keluar AS id_tbs_item_keluar', 'tbs_item_keluars.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_item_keluars.id_produk AS id_produk')->leftJoin('barangs', 'barangs.id', '=', 'tbs_item_keluars.id_produk')->where('warung_id', $user_warung)->where('session_id', $session_id)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%');

        })->orderBy('tbs_item_keluars.id_tbs_item_keluar', 'desc')->paginate(10);

        $array = array();
        foreach ($tbs_item_keluar as $tbs_item_keluars) {
            array_push($array, [
                'id_tbs_item_keluar' => $tbs_item_keluars['id_tbs_item_keluar'],
                'nama_produk'        => title_case($tbs_item_keluars['nama_barang']),
                'kode_produk'        => $tbs_item_keluars['kode_barang'],
                'jumlah_produk'      => $tbs_item_keluars['jumlah_produk']]);
        }

        $url    = '/item-keluar/pencarian-tbs-item-keluar';
        $search = $request->search;

        $respons = $this->paginationPencarianData($tbs_item_keluar, $array, $url, $search);

        return response()->json($respons);
    }

    public function viewEditTbsItemKeluar($id)
    {
        $item_keluar     = ItemKeluar::find($id);
        $user_warung     = Auth::user()->id_warung;
        $tbs_item_keluar = EditTbsItemKeluar::with(['produk'])->where('warung_id', $user_warung)->where('no_faktur', $item_keluar->no_faktur)->orderBy('id_edit_tbs_item_keluar', 'desc')->paginate(10);
        $array           = array();

        foreach ($tbs_item_keluar as $tbs_item_keluars) {
            array_push($array, [
                'id_item_keluar'          => $id,
                'id_edit_tbs_item_keluar' => $tbs_item_keluars->id_edit_tbs_item_keluar,
                'nama_produk'             => $tbs_item_keluars->TitleCaseProduk,
                'kode_produk'             => $tbs_item_keluars->produk->kode_barang,
                'jumlah_produk'           => $tbs_item_keluars->jumlah_produk]);
        }
        $url     = '/item-keluar/view-edit-tbs-item-keluar/' . $id;
        $respons = $this->paginationData($tbs_item_keluar, $array, $url);

        return response()->json($respons);
    }

    public function pencarianEditTbsItemKeluar(Request $request, $id)
    {
        $item_keluar     = ItemKeluar::find($id);
        $user_warung     = Auth::user()->id_warung;
        $tbs_item_keluar = EditTbsItemKeluar::select('edit_tbs_item_keluars.id_edit_tbs_item_keluar AS id_edit_tbs_item_keluar', 'edit_tbs_item_keluars.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_item_keluars.id_produk AS id_produk')->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_item_keluars.id_produk')
        ->where('warung_id', $user_warung)->where('no_faktur', $item_keluar->no_faktur)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%');

        })->orderBy('edit_tbs_item_keluars.id_edit_tbs_item_keluar', 'desc')->paginate(10);

        $array = array();
        foreach ($tbs_item_keluar as $tbs_item_keluars) {
            array_push($array, [
                'id_item_keluar'          => $id,
                'id_edit_tbs_item_keluar' => $tbs_item_keluars['id_edit_tbs_item_keluar'],
                'nama_produk'             => title_case($tbs_item_keluars['nama_barang']),
                'kode_produk'             => $tbs_item_keluars['kode_barang'],
                'jumlah_produk'           => $tbs_item_keluars['jumlah_produk']]);
        }

        $url    = '/item-keluar/pencarian-edit-tbs-item-keluar/' . $id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($tbs_item_keluar, $array, $url, $search);

        return response()->json($respons);
    }

//MENAMPILKAN DATA YG ADA DI TBS ITEM KELUAR
    public function create(Request $request, Builder $htmlBuilder)
    {

    }

    //PROSES TAMBAH TBS ITEM KELUAR
    public function proses_tambah_tbs_item_keluar(Request $request)
    {

        $produk     = explode("|", $request->produk);
        $id_produk  = $produk[0];
        $session_id = session()->getId();

        $data_tbs = TbsItemKeluar::where('id_produk', $id_produk)
        ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)
        ->count();

//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            return 0;

        } else {

            $tbsitemkeluar = TbsItemKeluar::create([
                'id_produk'     => $id_produk,
                'session_id'    => $session_id,
                'jumlah_produk' => $request->jumlah_produk,
                'warung_id'     => Auth::user()->id_warung,
            ]);

            return response(200);
        }
    }

//PROSES TAMBAH EDIT TBS ITEM keluar
    public function proses_tambah_edit_tbs_item_keluar(Request $request)
    {

        $produk     = explode("|", $request->produk);
        $id_produk  = $produk[0];
        $session_id = session()->getId();

        $data_tbs = EditTbsItemKeluar::select('id_produk')
        ->where('id_produk', $id_produk)
        ->where('no_faktur', $request->no_faktur)
        ->where('session_id', $session_id)
        ->where('warung_id', Auth::user()->id_warung)
        ->count();

        //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            return 0;

        } else {

            $tbsitemkeluar = EditTbsItemkeluar::create([
                'id_produk'     => $id_produk,
                'no_faktur'     => $request->no_faktur,
                'session_id'    => $session_id,
                'jumlah_produk' => $request->jumlah_produk,
                'warung_id'     => Auth::user()->id_warung,
            ]);

            return response(200);

        }
    }

//PROSES HAPUS TBS ITEM KELUAR
    public function proses_hapus_tbs_item_keluar($id)
    {

        if (!TbsItemKeluar::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }

//PROSES HAPUS EDIT TBS ITEM KELUAR
    public function proses_hapus_edit_tbs_item_keluar($id)
    {

        EditTbsItemKeluar::destroy($id);

        return response(200);
    }

//PROSES BATAL TBS ITEM KELUAR
    public function proses_hapus_semua_tbs_item_keluar()
    {
        $session_id           = session()->getId();
        $data_tbs_item_keluar = TbsItemKeluar::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }

//PROSES BATAL EDIT ITEM KELUAR
    public function proses_hapus_semua_edit_tbs_item_keluar(Request $request)
    {

        //PROSES MENGHAPUS SEMUA EDTI TBS SESUAI NO FAKTUR YANG DI AMBIL
        $data_tbs_item_keluar = EditTbsItemKeluar::where('no_faktur', $request->no_faktur)
        ->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }

    /**
     * Store a newly created resource in strage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //START TRANSAKSI
        DB::beginTransaction();
        $warung_id  = Auth::user()->id_warung;
        $session_id = session()->getId();
        $user       = Auth::user()->id;
        $no_faktur  = ItemKeluar::no_faktur($warung_id);

        //INSERT DETAIL ITEM KELUAR
        $data_produk_item_keluar = TbsItemKeluar::with('produk')->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung);

        if ($data_produk_item_keluar->count() == 0) {

            return $data_produk_item_keluar->count();

        } else {

            foreach ($data_produk_item_keluar->get() as $data_tbs) {

                $detail_item_keluar = new DetailItemKeluar();
                $stok_produk        = $detail_item_keluar->stok_produk($data_tbs->id_produk);
                $sisa               = $stok_produk - $data_tbs->jumlah_produk;

                if ($sisa < 0) {
                    //DI BATALKAN PROSES NYA

                    $respons['respons']     = 1;
                    $respons['nama_produk'] = title_case($data_tbs->produk->nama_barang);
                    $respons['stok_produk'] = $stok_produk;
                    DB::rollBack();
                    return response()->json($respons);

                } else {

                    $detail_item_keluar = DetailItemKeluar::create([
                        'id_produk'     => $data_tbs->id_produk,
                        'no_faktur'     => $no_faktur,
                        'jumlah_produk' => $data_tbs->jumlah_produk,
                        'warung_id'     => Auth::user()->id_warung,
                    ]);

                }
            }

            //INSERT ITEM KELUAR
            if ($request->keterangan == "") {
                $keterangan = "-";
            } else {
                $keterangan = $request->keterangan;
            }

            $itemkeluar = ItemKeluar::create([
                'no_faktur'  => $no_faktur,
                'keterangan' => $keterangan,
                'warung_id'  => Auth::user()->id_warung,
            ]);

            //HAPUS TBS ITEM KELUAR
            $data_produk_item_keluar->delete();

            DB::commit();
            return response(200);

        }
    }

    //PROSES SELESAI TRANSAKSI EDIT ITEM KELUAR
    public function proses_edit_item_keluar(Request $request, $id)
    {
        //START TRANSAKSI
        DB::beginTransaction();

        $data_item_keluar = ItemKeluar::find($id);
        $session_id       = session()->getId();
        $user             = Auth::user()->id;

        $data_detail_item_keluar = DetailItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung)->get();

//HAPUS DETAIL ITEM KELUAR
        foreach ($data_detail_item_keluar as $data_detail) {

            if (!$hapus_detail = DetailItemKeluar::destroy($data_detail->id_detail_item_keluar)) {
                //DI BATALKAN PROSES NYA
                DB::rollBack();
                return 2;
            }
        }

//INSERT DETAIL ITEM KELUAR
        $data_produk_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung);

        if ($data_produk_item_keluar->count() == 0) {

            DB::rollBack();
            return 0;

        } else {

            foreach ($data_produk_item_keluar->get() as $data_tbs) {

                $detail_item_keluar = new DetailItemKeluar();
                $stok_produk        = $detail_item_keluar->stok_produk($data_tbs->id_produk);
                $sisa               = $stok_produk - $data_tbs->jumlah_produk;

                if ($sisa < 0) {
                    //DI BATALKAN PROSES NYA

                    $respons['respons']     = 1;
                    $respons['nama_produk'] = title_case($data_tbs->produk->nama_barang);
                    $respons['stok_produk'] = $stok_produk;
                    DB::rollBack();
                    return response()->json($respons);

                } else {

                    $detail_item_keluar = DetailItemKeluar::create([
                        'id_produk'     => $data_tbs->id_produk,
                        'no_faktur'     => $data_item_keluar->no_faktur,
                        'jumlah_produk' => $data_tbs->jumlah_produk,
                        'warung_id'     => Auth::user()->id_warung,
                    ]);

                }

            }

            //INSERT ITEM KELUAR
            if ($request->keterangan == "") {
                $keterangan = "-";
            } else {
                $keterangan = $request->keterangan;
            }

            $data_item_keluar->update([
                'keterangan' => $keterangan,
            ]);

            $hapus_edit_tbs_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();
            DB::commit();
            return response(200);
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
        //
    }

    public function proses_form_edit($id)
    {
        $session_id              = session()->getId();
        $data_item_keluar        = ItemKeluar::find($id);
        $data_produk_item_keluar = DetailItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung);

        $hapus_semua_edit_tbs_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung)
        ->delete();

        foreach ($data_produk_item_keluar->get() as $data_tbs) {
            $detail_item_keluar = EditTbsItemKeluar::create([
                'id_produk'     => $data_tbs->id_produk,
                'no_faktur'     => $data_tbs->no_faktur,
                'jumlah_produk' => $data_tbs->jumlah_produk,
                'warung_id'     => Auth::user()->id_warung,
                'session_id'    => $session_id,
            ]);
        }

        return response(200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //MENAMPILKAN DATA DI TBS ITEM KELUAR
    public function edit(Request $request, Builder $htmlBuilder, $id)
    {

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

        if (!ItemKeluar::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }

    }

//PROSE EDIT JUMLAH TBS ITEM KELUAR
    public function proses_edit_jumlah_tbs_item_keluar(Request $request)
    {
        $tbs_item_keluar = TbsItemKeluar::find($request->id_tbs)->update(['jumlah_produk' => $request->jumlah_produk]);

        return response(200);
    }

//PROSE EDIT JUMLAH EDIT TBS ITEM KELUAR
    public function proses_edit_jumlah_edit_tbs_item_keluar(Request $request)
    {
        $tbs_item_keluar = EditTbsItemKeluar::find($request->id_tbs)->update(['jumlah_produk' => $request->jumlah_produk]);

        return response(200);
    }

    public function ambilFakturItemKeluar($id)
    {
        //
        return ItemKeluar::find($id);
    }
    public function detailItemKeluar($id)
    {
        //
        $item_keluar        = ItemKeluar::find($id);
        $user_warung        = Auth::user()->id_warung;
        $detail_item_keluar = DetailItemKeluar::with(['produk'])->where('warung_id', $user_warung)->where('no_faktur', $item_keluar->no_faktur)->orderBy('id_detail_item_keluar', 'desc')->paginate(10);
        $array              = array();

        foreach ($detail_item_keluar as $detail_item_keluars) {
            array_push($array, [
                'id_item_keluar'        => $id,
                'no_faktur'             => $detail_item_keluars->no_faktur,
                'id_detail_item_keluar' => $detail_item_keluars->id_detail_item_keluar,
                'nama_produk'           => title_case($detail_item_keluars->produk->nama_barang),
                'kode_produk'           => $detail_item_keluars->produk->kode_barang,
                'jumlah_produk'         => $detail_item_keluars->jumlah_produk]);
        }

        $url     = '/item-keluar/detail-item-keluar/' . $id;
        $respons = $this->paginationData($detail_item_keluar, $array, $url);

        return response()->json($respons);
    }

    public function pencarianDetailItemKeluar($id, Request $request)
    {
        $item_keluar        = ItemKeluar::find($id);
        $user_warung        = Auth::user()->id_warung;
        $detail_item_keluar = DetailItemKeluar::select('detail_item_keluars.id_detail_item_keluar AS id_detail_item_keluar', 'detail_item_keluars.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'detail_item_keluars.id_produk AS id_produk', 'detail_item_keluars.no_faktur AS no_faktur')->leftJoin('barangs', 'barangs.id', '=', 'detail_item_keluars.id_produk')
        ->where('detail_item_keluars.warung_id', $user_warung)->where('detail_item_keluars.no_faktur', $item_keluar->no_faktur)
        ->where(function ($query) use ($request) {

            $query->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
            ->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%');

        })->orderBy('detail_item_keluars.id_detail_item_keluar', 'desc')->paginate(10);

        $array = array();
        foreach ($detail_item_keluar as $detail_item_keluars) {
            array_push($array, [
                'id_item_keluar'        => $id,
                'no_faktur'             => $detail_item_keluars['no_faktur'],
                'id_detail_item_keluar' => $detail_item_keluars['id_detail_item_keluar'],
                'nama_produk'           => title_case($detail_item_keluars['nama_barang']),
                'kode_produk'           => $detail_item_keluars['kode_barang'],
                'jumlah_produk'         => $detail_item_keluars['jumlah_produk']]);
        }

        $url    = '/item-keluar/pencarian-detail-item-keluar/' . $id;
        $search = $request->search;

        $respons = $this->paginationPencarianData($detail_item_keluar, $array, $url, $search);

        return response()->json($respons);
    }
    public function otoritasItemKeluar(){

        if (Laratrust::can('tambah_item_keluar')) {
            $tambah_item_keluar = 1;
        }else{
            $tambah_item_keluar = 0;            
        }
        if (Laratrust::can('edit_item_keluar')) {
            $edit_item_keluar = 1;
        }else{
            $edit_item_keluar = 0;            
        }
        if (Laratrust::can('hapus_item_keluar')) {
            $hapus_item_keluar = 1;
        }else{
            $hapus_item_keluar = 0;            
        }
        $respons['tambah_item_keluar'] = $tambah_item_keluar;
        $respons['edit_item_keluar'] = $edit_item_keluar;
        $respons['hapus_item_keluar'] = $hapus_item_keluar;

        return response()->json($respons);
    }
}
