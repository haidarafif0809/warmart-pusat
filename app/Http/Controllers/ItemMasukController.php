<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailItemMasuk;
use App\EditTbsItemMasuk;
use App\ItemMasuk;
use App\TbsItemMasuk;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;

class ItemMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index()
    {

    }

    public function view()
    {
        $item_masuk = ItemMasuk::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
        $array      = array();

        foreach ($item_masuk as $item_masuks) {
            array_push($array, [
                'id'         => $item_masuks->id,
                'no_faktur'  => $item_masuks->no_faktur,
                'keterangan' => $item_masuks->keterangan,
                'waktu'      => $item_masuks->Waktu,
                'waktu_edit' => $item_masuks->WaktuEdit]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $item_masuk->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/item-masuk/view?page=' . $item_masuk->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $item_masuk->lastPage();
        $respons['last_page_url']  = url('/item-masuk/view?page=' . $item_masuk->lastPage());
        $respons['next_page_url']  = $item_masuk->nextPageUrl();
        $respons['path']           = url('/item-masuk/view');
        $respons['per_page']       = $item_masuk->perPage();
        $respons['prev_page_url']  = $item_masuk->previousPageUrl();
        $respons['to']             = $item_masuk->perPage();
        $respons['total']          = $item_masuk->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $item_masuk = ItemMasuk::where('warung_id', Auth::user()->id_warung)->where(function ($query) use ($request) {

            $query->orWhere('no_faktur', 'LIKE', $request->search . '%')
                ->orWhere('keterangan', 'LIKE', $request->search . '%')
                ->orWhere('created_at', 'LIKE', $request->search . '%')
                ->orWhere('updated_at', 'LIKE', $request->search . '%');

        })->orderBy('id', 'desc')->paginate(10);
        $array = array();

        foreach ($item_masuk as $item_masuks) {
            array_push($array, [
                'id'         => $item_masuks->id,
                'no_faktur'  => $item_masuks->no_faktur,
                'keterangan' => $item_masuks->keterangan,
                'waktu'      => $item_masuks->Waktu,
                'waktu_edit' => $item_masuks->WaktuEdit]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $item_masuk->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/item-masuk/pencarian?page=' . $item_masuk->firstItem() . '&search=' . $request->search);
        $respons['from']           = 1;
        $respons['last_page']      = $item_masuk->lastPage();
        $respons['last_page_url']  = url('/item-masuk/pencarian?page=' . $item_masuk->lastPage() . '&search=' . $request->search);
        $respons['next_page_url']  = $item_masuk->nextPageUrl();
        $respons['path']           = url('/item-masuk/pencarian');
        $respons['per_page']       = $item_masuk->perPage();
        $respons['prev_page_url']  = $item_masuk->previousPageUrl();
        $respons['to']             = $item_masuk->perPage();
        $respons['total']          = $item_masuk->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    public function viewTbsItemMasuk()
    {
        $session_id     = session()->getId();
        $user_warung    = Auth::user()->id_warung;
        $tbs_item_masuk = TbsItemMasuk::with(['produk'])->where('warung_id', $user_warung)->where('session_id', $session_id)->orderBy('id_tbs_item_masuk', 'desc')->paginate(10);
        $array          = array();

        foreach ($tbs_item_masuk as $tbs_item_masuks) {
            array_push($array, [
                'id_tbs_item_masuk' => $tbs_item_masuks->id_tbs_item_masuk,
                'nama_produk'       => $tbs_item_masuks->TitleCaseProduk,
                'kode_produk'       => $tbs_item_masuks->produk->kode_barang,
                'jumlah_produk'     => $tbs_item_masuks->jumlah_produk]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $tbs_item_masuk->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/item-masuk/view-tbs-item-masuk?page=' . $tbs_item_masuk->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $tbs_item_masuk->lastPage();
        $respons['last_page_url']  = url('/item-masuk/view-tbs-item-masuk?page=' . $tbs_item_masuk->lastPage());
        $respons['next_page_url']  = $tbs_item_masuk->nextPageUrl();
        $respons['path']           = url('/item-masuk/view-tbs-item-masuk');
        $respons['per_page']       = $tbs_item_masuk->perPage();
        $respons['prev_page_url']  = $tbs_item_masuk->previousPageUrl();
        $respons['to']             = $tbs_item_masuk->perPage();
        $respons['total']          = $tbs_item_masuk->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    public function pencarianTbsItemMasuk(Request $request)
    {
        $session_id     = session()->getId();
        $user_warung    = Auth::user()->id_warung;
        $tbs_item_masuk = TbsItemMasuk::select('tbs_item_masuks.id_tbs_item_masuk AS id_tbs_item_masuk', 'tbs_item_masuks.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'tbs_item_masuks.id_produk AS id_produk')->leftJoin('barangs', 'barangs.id', '=', 'tbs_item_masuks.id_produk')->where('warung_id', $user_warung)->where('session_id', $session_id)
            ->where(function ($query) use ($request) {

                $query->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%')
                    ->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%');

            })->orderBy('tbs_item_masuks.id_tbs_item_masuk', 'desc')->paginate(10);

        $array = array();
        foreach ($tbs_item_masuk as $tbs_item_masuks) {
            array_push($array, [
                'id_tbs_item_masuk' => $tbs_item_masuks['id_tbs_item_masuk'],
                'nama_produk'       => title_case($tbs_item_masuks['nama_barang']),
                'kode_produk'       => $tbs_item_masuks['kode_barang'],
                'jumlah_produk'     => $tbs_item_masuks['jumlah_produk']]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $tbs_item_masuk->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/item-masuk/pencarian-tbs-item-masuk?page=' . $tbs_item_masuk->firstItem() . '&search=' . $request->search);
        $respons['from']           = 1;
        $respons['last_page']      = $tbs_item_masuk->lastPage();
        $respons['last_page_url']  = url('/item-masuk/pencarian-tbs-item-masuk?page=' . $tbs_item_masuk->lastPage() . '&search=' . $request->search);
        $respons['next_page_url']  = $tbs_item_masuk->nextPageUrl();
        $respons['path']           = url('/item-masuk/pencarian-tbs-item-masuk');
        $respons['per_page']       = $tbs_item_masuk->perPage();
        $respons['prev_page_url']  = $tbs_item_masuk->previousPageUrl();
        $respons['to']             = $tbs_item_masuk->perPage();
        $respons['total']          = $tbs_item_masuk->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    public function viewEditTbsItemMasuk($id)
    {
        $item_masuk     = ItemMasuk::find($id);
        $user_warung    = Auth::user()->id_warung;
        $tbs_item_masuk = EditTbsItemMasuk::with(['produk'])->where('warung_id', $user_warung)->where('no_faktur', $item_masuk->no_faktur)->orderBy('id_edit_tbs_item_masuk', 'desc')->paginate(10);
        $array          = array();

        foreach ($tbs_item_masuk as $tbs_item_masuks) {
            array_push($array, [
                'id_item_masuk'          => $id,
                'id_edit_tbs_item_masuk' => $tbs_item_masuks->id_edit_tbs_item_masuk,
                'nama_produk'            => $tbs_item_masuks->TitleCaseProduk,
                'kode_produk'            => $tbs_item_masuks->produk->kode_barang,
                'jumlah_produk'          => $tbs_item_masuks->jumlah_produk]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $tbs_item_masuk->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/item-masuk/view-edit-tbs-item-masuk/' . $id . '?page=' . $tbs_item_masuk->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $tbs_item_masuk->lastPage();
        $respons['last_page_url']  = url('/item-masuk/view-edit-tbs-item-masuk/' . $id . '?page=' . $tbs_item_masuk->lastPage());
        $respons['next_page_url']  = $tbs_item_masuk->nextPageUrl();
        $respons['path']           = url('/item-masuk/view-edit-tbs-item-masuk/' . $id);
        $respons['per_page']       = $tbs_item_masuk->perPage();
        $respons['prev_page_url']  = $tbs_item_masuk->previousPageUrl();
        $respons['to']             = $tbs_item_masuk->perPage();
        $respons['total']          = $tbs_item_masuk->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    public function pencarianEditTbsItemMasuk(Request $request, $id)
    {
        $item_masuk     = ItemMasuk::find($id);
        $user_warung    = Auth::user()->id_warung;
        $tbs_item_masuk = EditTbsItemMasuk::select('edit_tbs_item_masuks.id_edit_tbs_item_masuk AS id_edit_tbs_item_masuk', 'edit_tbs_item_masuks.jumlah_produk AS jumlah_produk', 'barangs.nama_barang AS nama_barang', 'barangs.kode_barang AS kode_barang', 'edit_tbs_item_masuks.id_produk AS id_produk')->leftJoin('barangs', 'barangs.id', '=', 'edit_tbs_item_masuks.id_produk')
            ->where('warung_id', $user_warung)->where('no_faktur', $item_masuk->no_faktur)
            ->where(function ($query) use ($request) {

                $query->orWhere('barangs.nama_barang', 'LIKE', $request->search . '%')
                    ->orWhere('barangs.kode_barang', 'LIKE', $request->search . '%');

            })->orderBy('edit_tbs_item_masuks.id_edit_tbs_item_masuk', 'desc')->paginate(10);

        $array = array();
        foreach ($tbs_item_masuk as $tbs_item_masuks) {
            array_push($array, [
                'id_item_masuk'          => $id,
                'id_edit_tbs_item_masuk' => $tbs_item_masuks['id_edit_tbs_item_masuk'],
                'nama_produk'            => title_case($tbs_item_masuks['nama_barang']),
                'kode_produk'            => $tbs_item_masuks['kode_barang'],
                'jumlah_produk'          => $tbs_item_masuks['jumlah_produk']]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $tbs_item_masuk->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url('/item-masuk/pencarian-edit-tbs-item-masuk/' . $id . '?page=' . $tbs_item_masuk->firstItem() . '&search=' . $request->search);
        $respons['from']           = 1;
        $respons['last_page']      = $tbs_item_masuk->lastPage();
        $respons['last_page_url']  = url('/item-masuk/pencarian-edit-tbs-item-masuk/' . $id . '?page=' . $tbs_item_masuk->lastPage() . '&search=' . $request->search);
        $respons['next_page_url']  = $tbs_item_masuk->nextPageUrl();
        $respons['path']           = url('/item-masuk/pencarian-edit-tbs-item-masuk/' . $id);
        $respons['per_page']       = $tbs_item_masuk->perPage();
        $respons['prev_page_url']  = $tbs_item_masuk->previousPageUrl();
        $respons['to']             = $tbs_item_masuk->perPage();
        $respons['total']          = $tbs_item_masuk->total();
        //DATA PAGINATION
        return response()->json($respons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $session_id     = session()->getId();
            $user_warung    = Auth::user()->id_warung;
            $tbs_item_masuk = TbsItemMasuk::with(['produk'])->where('warung_id', $user_warung)->where('session_id', $session_id)->get();
            return Datatables::of($tbs_item_masuk)->addColumn('action', function ($tbsitemmasuk) {
                return view('item_masuk._hapus_produk', [
                    'model'           => $tbsitemmasuk,
                    'form_url'        => route('item-masuk.proses_hapus_tbs_item_masuk', $tbsitemmasuk->id_tbs_item_masuk),
                    'confirm_message' => 'Yakin Mau Menghapus Produk ?',
                ]);
            })
                ->editColumn('jumlah_produk', function ($produk) {
                    return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk->id_tbs_item_masuk' data-nama='$produk->TitleCaseProduk' >$produk->jumlah_produk</a>";
                })
                ->addColumn('data_produk_tbs', function ($data_produk_tbs) {
                    $produk      = Barang::find($data_produk_tbs->id_produk);
                    $data_produk = $produk->kode_barang . " - " . $produk->nama_barang;
                    return $data_produk;
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable' => false]);

        return view('item_masuk.create')->with(compact('html'));
    }

    //PROSES TAMBAH TBS ITEM MASUK
    public function proses_tambah_tbs_item_masuk(Request $request)
    {
        $produk     = explode("|", $request->produk);
        $id_produk  = $produk[0];
        $session_id = session()->getId();

        $data_tbs = TbsItemMasuk::where('id_produk', $id_produk)->where('warung_id', Auth::user()->id_warung)->where('session_id', $session_id);

        if ($data_tbs->count() > 0) {

            return 0;

        } else {

            TbsItemMasuk::create([
                'id_produk'     => $id_produk,
                'session_id'    => $session_id,
                'jumlah_produk' => $request->jumlah_produk,
                'warung_id'     => Auth::user()->id_warung,
            ]);
            return response(200);
        }

    }

    //PROSES HAPUS TBS ITEM MASUK
    public function proses_hapus_tbs_item_masuk($id)
    {
        TbsItemMasuk::destroy($id);

        return response(200);
    }

    //PROSES HAPUS EDIT TBS ITEM MASUK
    public function proses_hapus_edit_tbs_item_masuk($id)
    {
        EditTbsItemMasuk::where('id_edit_tbs_item_masuk', $id)->delete();

        return response(200);
    }

    //PROSES BATAL ITEM MASUK
    public function proses_hapus_semua_tbs_item_masuk()
    {
        $session_id          = session()->getId();
        $data_tbs_item_masuk = TbsItemMasuk::where('session_id', $session_id)->delete();

        return response(200);
    }

    //PROSES BATAL EDIT ITEM MASUK
    public function proses_hapus_semua_edit_tbs_item_masuk(Request $request)
    {

        //PROSES MENGHAPUS SEMUA EDTI TBS SESUAI NO FAKTUR YANG DI AMBIL
        $data_tbs_item_masuk = EditTbsItemMasuk::where('no_faktur', $request->no_faktur)->delete();

        return response(200);
    }

    //PROSES SELESAI TRANSAKSI EDIT ITEM MASUK
    public function proses_edit_item_masuk(Request $request, $id)
    {

        $session_id = session()->getId();
        $user       = Auth::user()->id;

//INSERT DETAIL ITEM MASUK
        $data_produk_item_masuk = EditTbsItemMasuk::where('no_faktur', $request->no_faktur)->where('warung_id', Auth::user()->id_warung);

        if ($data_produk_item_masuk->count() == 0) {

            return $data_produk_item_masuk->count();

        } else {

            $data_detail_item_masuk = DetailItemMasuk::where('no_faktur', $request->no_faktur)->where('warung_id', Auth::user()->id_warung)->get();

            //HAPUS DETAIL ITEM MASUK
            foreach ($data_detail_item_masuk as $data_detail) {

                DetailItemMasuk::destroy($data_detail->id_detail_item_masuk);
            }

            foreach ($data_produk_item_masuk->get() as $data_tbs) {

                $detail_item_masuk = DetailItemMasuk::create([
                    'id_produk'     => $data_tbs->id_produk,
                    'no_faktur'     => $data_tbs->no_faktur,
                    'jumlah_produk' => $data_tbs->jumlah_produk,
                    'warung_id'     => Auth::user()->id_warung,
                ]);

            }

            //INSERT ITEM MASUK
            if ($request->keterangan == "") {
                $keterangan = "-";
            } else {
                $keterangan = $request->keterangan;
            }

            $itemmasuk = ItemMasuk::find($id)->update([
                'keterangan' => $keterangan,
            ]);

            $hapus_edit_tbs_item_masuk = EditTbsItemMasuk::where('no_faktur', $request->no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();

            return response(200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //PROSES SELESAI TRANSAKSI ITEM MASUK
    public function store(Request $request)
    {

        //START TRANSAKSI
        DB::beginTransaction();
        $warung_id  = Auth::user()->id_warung;
        $session_id = session()->getId();
        $user       = Auth::user()->id;
        $no_faktur  = ItemMasuk::no_faktur($warung_id);

        //INSERT DETAIL ITEM MASUK
        $data_produk_item_masuk = TbsItemMasuk::where('session_id', $session_id)->where('warung_id', $warung_id);

        if ($data_produk_item_masuk->count() > 0) {

            //INSERT ITEM MASUK
            if ($request->keterangan == "") {
                $keterangan = "-";
            } else {
                $keterangan = $request->keterangan;
            }

            foreach ($data_produk_item_masuk->get() as $data_tbs) {
                $detail_item_masuk = DetailItemMasuk::create([
                    'id_produk'     => $data_tbs->id_produk,
                    'no_faktur'     => $no_faktur,
                    'jumlah_produk' => $data_tbs->jumlah_produk,
                    'warung_id'     => $warung_id,
                ]);
            }

            $itemmasuk = ItemMasuk::create([
                'no_faktur'  => $no_faktur,
                'keterangan' => $keterangan,
                'warung_id'  => $warung_id,
            ]);

            //HAPUS TBS ITEM MASUK
            $data_produk_item_masuk->delete();

            DB::commit();
            return response(200);

        } else {
            return $data_produk_item_masuk->count();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ItemMasukController  $itemMasukController
     * @return \Illuminate\Http\Response
     */
    public function show(ItemMasukController $itemMasukController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ItemMasukController  $itemMasukController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemMasukController $itemMasukController)
    {
        //
    }

    public function proses_form_edit($id)
    {
        //
        $session_id             = session()->getId();
        $data_item_masuk        = ItemMasuk::find($id);
        $data_produk_item_masuk = DetailItemMasuk::where('no_faktur', $data_item_masuk->no_faktur);

        $hapus_semua_edit_tbs_item_masuk = EditTbsItemMasuk::where('no_faktur', $data_item_masuk->no_faktur)->delete();
        foreach ($data_produk_item_masuk->get() as $data_tbs) {
            $detail_item_masuk = EditTbsItemMasuk::create([
                'id_produk'     => $data_tbs->id_produk,
                'no_faktur'     => $data_tbs->no_faktur,
                'jumlah_produk' => $data_tbs->jumlah_produk,
                'session_id'    => $session_id,
                'warung_id'     => Auth::user()->id_warung,
            ]);
        }

        return response(200);

    }

    //MENAMPILKAN DATA DI TBS ITEM MASUK
    public function edit(Request $request, Builder $htmlBuilder, $id)
    {
        if ($request->ajax()) {
            $item_masuk     = ItemMasuk::find($id);
            $tbs_item_masuk = EditTbsItemMasuk::with(['produk'])->where('warung_id', Auth::user()->id_warung)->where('no_faktur', $item_masuk->no_faktur)->get();
            return Datatables::of($tbs_item_masuk)->addColumn('action', function ($tbsitemmasuk) {
                return view('item_masuk._hapus_produk', [
                    'model'           => $tbsitemmasuk,
                    'form_url'        => route('item-masuk.proses_hapus_edit_tbs_item_masuk', $tbsitemmasuk->id_edit_tbs_item_masuk),
                    'confirm_message' => 'Yakin Mau Menghapus Produk ?',
                ]);
            })
                ->editColumn('jumlah_produk', function ($produk) {
                    return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk->id_edit_tbs_item_masuk' data-nama='$produk->TitleCaseProduk' >$produk->jumlah_produk</a>";
                })->addColumn('data_produk_tbs', function ($data_produk_tbs) {
                $produk      = Barang::find($data_produk_tbs->id_produk);
                $data_produk = $produk->kode_barang . " - " . $produk->nama_barang;
                return $data_produk;
            })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'searchable' => false])
            ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable' => false]);

        $item_masuk = ItemMasuk::find($id);
        return view('item_masuk.edit')->with(compact('html', 'item_masuk'));
    }

    //PROSES TAMBAH EDIT TBS ITEM MASUK
    public function proses_tambah_edit_tbs_item_masuk(Request $request)
    {
        $produk     = explode("|", $request->produk);
        $id_produk  = $produk[0];
        $session_id = session()->getId();

        $data_tbs = EditTbsItemMasuk::select('id_produk')
            ->where('id_produk', $id_produk)
            ->where('no_faktur', $request->no_faktur)
            ->where('session_id', $session_id)
            ->where('warung_id', Auth::user()->id_warung)
            ->count();

        //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            return 0;

        } else {

            $tbsitemmasuk = EditTbsItemMasuk::create([
                'id_produk'     => $id_produk,
                'no_faktur'     => $request->no_faktur,
                'session_id'    => $session_id,
                'jumlah_produk' => $request->jumlah_produk,
                'warung_id'     => Auth::user()->id_warung,
            ]);

            return response(200);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ItemMasukController  $itemMasukController
     * @return \Illuminate\Http\Response
     */
    //PROSES HAPUS ITEM MASUK
    public function destroy($id)
    {

        ItemMasuk::destroy($id);
        return response(200);
    }

    public function proses_edit_jumlah(Request $request)
    {
        $tbs_item_masuk = TbsItemMasuk::find($request->id_tbs)->update(['jumlah_produk' => $request->jumlah_produk]);

        return response(200);

    }

    public function proses_edit_jumlah_edit(Request $request)
    {
        $tbs_item_masuk = EditTbsItemMasuk::find($request->id_tbs)->update(['jumlah_produk' => $request->jumlah_produk]);

        return response(200);

    }

    public function ambilFakturItemMasuk($id)
    {
        //
        return ItemMasuk::find($id);
    }
}
