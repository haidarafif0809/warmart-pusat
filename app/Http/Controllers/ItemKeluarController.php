<?php

namespace App\Http\Controllers;

use App\Barang;
use App\DetailItemKeluar;
use App\EditTbsItemKeluar;
use App\ItemKeluar;
use App\TbsItemKeluar;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;

class ItemKeluarController extends Controller
{
    //MENAMPILKAN DATA YG ADA DI ITEM KELUAR
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $item_keluar = ItemKeluar::all();
            return Datatables::of($item_keluar)->addColumn('action', function ($itemkeluar) {
                $detail_item_keluar = DetailItemKeluar::with(['produk'])->where('warung_id', Auth::user()->id_warung)->where('no_faktur', $itemkeluar->no_faktur)->get();
                return view('item_keluar._action', [
                    'model'                   => $itemkeluar,
                    'id_item_keluar'          => $itemkeluar->id,
                    'data_detail_item_keluar' => $detail_item_keluar,
                    'form_url'                => route('item-keluar.destroy', $itemkeluar->id),
                    'edit_url'                => route('item-keluar.proses_form_edit', $itemkeluar->id),
                    'confirm_message'         => 'Anda Yakin Ingin Menghapus Item Keluar Faktur "' . $itemkeluar->no_faktur . '" ?',
                ]);
            })
                ->addColumn('total_nilai_keluar', function ($total_keluar) {
                    $data_nilai_keluar = number_format($total_keluar->total, 0, ',', '.');

                    return $data_nilai_keluar;
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No. Faktur'])
            ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'])
            ->addColumn(['data' => 'total_nilai_keluar', 'name' => 'total_nilai_keluar', 'title' => 'Total'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu'])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Waktu Edit'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('item_keluar.index')->with(compact('html'));

    }

    public function paginationData($item_keluar, $array, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $item_keluar->currentPage();
        $respons['data']           = $array;
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

//MENAMPILKAN DATA YG ADA DI TBS ITEM KELUAR
    public function create(Request $request, Builder $htmlBuilder)
    {

        if ($request->ajax()) {

            $session_id      = session()->getId();
            $tbs_item_keluar = TbsItemKeluar::with(['produk'])->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->get();

            return Datatables::of($tbs_item_keluar)->addColumn('action', function ($tbsitemkeluar) {

                $pesan_alert = 'Anda Yakin Ingin Menghapus Produk "' . $tbsitemkeluar->produk->nama_barang . '" ?';
                return view('item_keluar._hapus_produk', [
                    'model'           => $tbsitemkeluar,
                    'form_url'        => route('item-keluar.proses_hapus_tbs_item_keluar', $tbsitemkeluar->id_tbs_item_keluar),
                    'confirm_message' => $pesan_alert,
                ]);
            })
                ->editColumn('data_produk_tbs', function ($data_produk_tbs) {

                    return $data_produk_tbs->produk->kode_barang . ' - ' . $data_produk_tbs->produk->nama_barang;
                })
                ->editColumn('jumlah_produk', function ($produk) {

                    return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk->id_tbs_item_keluar'>$produk->jumlah_produk</a>";
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk'])
            ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable' => false]);

        return view('item_keluar.create')->with(compact('html'));
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
    public function proses_tambah_edit_tbs_item_keluar(Request $request, $id)
    {
        $this->validate($request, [
            'id_produk'     => 'required|numeric',
            'jumlah_produk' => 'required|digits_between:1,15|numeric',
        ]);

        $data_item_keluar = ItemKeluar::find($id);
        $session_id       = session()->getId();

        $data_tbs = EditTbsItemKeluar::select('id_produk')
            ->where('id_produk', $request->id_produk)
            ->where('no_faktur', $data_item_keluar->no_faktur)
            ->where('session_id', $session_id)
            ->where('warung_id', Auth::user()->id_warung)
            ->count();

        $data_produk = Barang::select('nama_barang')->where('id', $request->id_produk)->where('id_warung', Auth::user()->id_warung)->first();

        //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {

            $pesan_alert =
            '<div class="container-fluid">
            <div class="alert-icon">
            <i class="material-icons">warning</i>
            </div>
            <b>Warning : Produk "' . $data_produk->nama_barang . '" Sudah Ada, Silakan Pilih Produk Lain !</b>
            </div>';

            Session::flash("flash_notification", [
                "level"   => "warning",
                "message" => $pesan_alert,
            ]);

            return back();
        } else {

            $pesan_alert =
            '<div class="container-fluid">
            <div class="alert-icon">
            <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Menambah Produk "' . $data_produk->nama_barang . '"</b>
            </div>';

            $tbsitemkeluar = EditTbsItemkeluar::create([
                'id_produk'     => $request->id_produk,
                'no_faktur'     => $data_item_keluar->no_faktur,
                'session_id'    => $session_id,
                'jumlah_produk' => $request->jumlah_produk,
                'warung_id'     => Auth::user()->id_warung,
            ]);

            Session::flash("flash_notification", [
                "level"   => "success",
                "message" => $pesan_alert,
            ]);
            return back();

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

        $pesan_alert =
            '<div class="container-fluid">
        <div class="alert-icon">
        <i class="material-icons">check</i>
        </div>
        <b>Sukses : Berhasil Menghapus Produk</b>
        </div>';

        Session::flash("flash_notification", [
            "level"   => "success",
            "message" => $pesan_alert,
        ]);

        return back();
    }

//PROSES BATAL TBS ITEM KELUAR
    public function proses_hapus_semua_tbs_item_keluar()
    {
        $session_id           = session()->getId();
        $data_tbs_item_keluar = TbsItemKeluar::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete();

        return response(200);
    }

//PROSES BATAL EDIT ITEM KELUAR
    public function proses_hapus_semua_edit_tbs_item_keluar($id)
    {
        //MENGAMBIL ID ITEM KELUAR
        $data_item_keluar = ItemKeluar::find($id);

        //PROSES MENGHAPUS SEMUA EDTI TBS SESUAI NO FAKTUR YANG DI AMBIL
        $data_tbs_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)
            ->where('warung_id', Auth::user()->id_warung)->delete();

        $pesan_alert =
        '<div class="container-fluid">
        <div class="alert-icon">
        <i class="material-icons">check</i>
        </div>
        <b>Sukses : Berhasil Membatalkan Edit Item Keluar Faktur "' . $data_item_keluar->no_faktur . '"</b>
        </div>';

        Session::flash("flash_notification", [
            "level"   => "success",
            "message" => $pesan_alert,
        ]);

        return redirect()->route('item-keluar.index');
    }

    /**
     * Store a newly created resource in storage.
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

        $data_item_keluar = ItemKeluar::find($id);
        $session_id       = session()->getId();
        $user             = Auth::user()->id;

        $data_detail_item_keluar = DetailItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung)->get();

//HAPUS DETAIL ITEM KELUAR
        foreach ($data_detail_item_keluar as $data_detail) {

            if (!$hapus_detail = DetailItemKeluar::destroy($data_detail->id_detail_item_keluar)) {
                //DI BATALKAN PROSES NYA
                DB::rollBack();
                return redirect()->back();
            }
        }

//INSERT DETAIL ITEM KELUAR
        $data_produk_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung);

        if ($data_produk_item_keluar->count() == 0) {

            $pesan_alert =
                '<div class="container-fluid">
            <div class="alert-icon">
            <i class="material-icons">error</i>
            </div>
            <b>Gagal : Belum Ada Produk Yang Diinputkan</b>
            </div>';

            Session::flash("flash_notification", [
                "level"   => "danger",
                "message" => $pesan_alert,
            ]);

            return redirect()->back();
        } else {
            foreach ($data_produk_item_keluar->get() as $data_tbs) {

                $detail_item_keluar = new DetailItemKeluar();
                if (!$detail_item_keluar->stok_produk($data_tbs->id_produk, $data_tbs->jumlah_produk)) {
                    //DI BATALKAN PROSES NYA
                    DB::rollBack();
                    return redirect()->back();
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

            $itemkeluar = ItemKeluar::find($id)->update([
                'keterangan' => $keterangan,
            ]);

            $hapus_edit_tbs_item_keluar = EditTbsItemKeluar::where('no_faktur', $data_item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung)->delete();

            if (!$itemkeluar) {
                return back();
            }

            $pesan_alert =
            '<div class="container-fluid">
            <div class="alert-icon">
            <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Melakukan Edit Transaksi Item Keluar Faktur "' . $data_item_keluar->no_faktur . '"</b>
            </div>';

            Session::flash("flash_notification", [
                "level"   => "success",
                "message" => $pesan_alert,
            ]);

            return redirect()->route('item-keluar.index');
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

        return redirect()->route('item-keluar.edit', $id);
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
        if ($request->ajax()) {
            $item_keluar     = ItemKeluar::find($id);
            $tbs_item_keluar = EditTbsItemKeluar::with(['produk'])->where('no_faktur', $item_keluar->no_faktur)->where('warung_id', Auth::user()->id_warung)
                ->get();
            return Datatables::of($tbs_item_keluar)->addColumn('action', function ($tbsitemkeluar) {
                return view('item_keluar._hapus_produk', [
                    'model'           => $tbsitemkeluar,
                    'form_url'        => route('item-keluar.proses_hapus_edit_tbs_item_keluar', $tbsitemkeluar->id_edit_tbs_item_keluar),
                    'confirm_message' => 'Yakin Mau Menghapus Produk "' . $tbsitemkeluar->produk->nama_barang . '" ?',
                ]);
            })->addColumn('data_produk_tbs', function ($data_produk_tbs) {
                $produk      = Barang::find($data_produk_tbs->id_produk);
                $data_produk = $produk->kode_barang . " - " . $produk->nama_barang;
                return $data_produk;
            })
                ->editColumn('jumlah_produk', function ($produk) {

                    return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah-edit-tbs' data-id='$produk->id_edit_tbs_item_keluar'>$produk->jumlah_produk</a>";
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'searchable' => false])
            ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable' => false]);

        $item_keluar = Itemkeluar::find($id);
        return view('item_keluar.edit')->with(compact('html', 'item_keluar'));
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
        $tbs_item_keluar = EditTbsItemKeluar::find($request->id_edit_tbs_item_keluar);

        $tbs_item_keluar->update(['jumlah_produk' => $request->jumlah_keluar]);
        $nama_barang = $tbs_item_keluar->produk->nama_barang;

        $pesan_alert =
            '<div class="container-fluid">
        <div class="alert-icon">
        <i class="material-icons">check</i>
        </div>
        <b>Sukses : Berhasil Mengubah Jumlah Item Keluar "' . $nama_barang . '"</b>
        </div>';

        Session::flash("flash_notification", [
            "level"   => "success",
            "message" => $pesan_alert,
        ]);

        return redirect()->back();
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
}
