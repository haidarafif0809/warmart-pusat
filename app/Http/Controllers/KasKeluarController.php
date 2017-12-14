<?php

namespace App\Http\Controllers;

use App\Kas;
use App\KasKeluar;
use App\KategoriTransaksi;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Html\Builder;

class KasKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {

            $kas_keluar = KasKeluar::with(['kas', 'kategori'])->where('warung_id', Auth::user()->id_warung);
            return Datatables::of($kas_keluar)

                ->addColumn('action', function ($master_kas_keluar) {
                    return view('kas_keluar._action', [
                        'model'           => $master_kas_keluar,
                        'form_url'        => route('kas_keluar.destroy', $master_kas_keluar->id),
                        'edit_url'        => route('kas_keluar.edit', $master_kas_keluar->id),
                        'confirm_message' => 'Yakin Mau Menghapus kas keluar ' . $master_kas_keluar->no_faktur . '?',
                    ]);
                })
                ->addColumn('jumlah_keluar', function ($jumlah_keluar) {
                    $data_keluar = number_format($jumlah_keluar->jumlah, 0, ',', '.');

                    return $data_keluar;
                })
                ->addColumn('waktu', function ($waktu) {
                    $waktu = $waktu->created_at;

                    return $waktu;
                })->make(true);
            $html = $htmlBuilder
                ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No Faktur'])
                ->addColumn(['data' => 'kas.nama_kas', 'name' => 'kas.nama_kas', 'title' => 'Kas'])
                ->addColumn(['data' => 'kategori.nama_kategori_transaksi', 'name' => 'kategori.nama_kategori_transaksi', 'title' => 'Kategori'])
                ->addColumn(['data' => 'jumlah_keluar', 'name' => 'jumlah_keluar', 'title' => 'Jumlah'])
                ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'])
                ->addColumn(['data' => 'waktu', 'name' => 'waktu', 'title' => 'Waktu'])
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]);
            return view('kas_keluar.index')->with(compact('html'));
        }
    }

    public function tandaPemisahTitik($angka)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function dataPagination($data_kas_keluar, $array_kas_keluar)
    {

        $respons['current_page']   = $data_kas_keluar->currentPage();
        $respons['data']           = $array_kas_keluar;
        $respons['first_page_url'] = url('/kas-keluar/view?page=' . $data_kas_keluar->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_kas_keluar->lastPage();
        $respons['last_page_url']  = url('/kas-keluar/view?page=' . $data_kas_keluar->lastPage());
        $respons['next_page_url']  = $data_kas_keluar->nextPageUrl();
        $respons['path']           = url('/kas-keluar/view');
        $respons['per_page']       = $data_kas_keluar->perPage();
        $respons['prev_page_url']  = $data_kas_keluar->previousPageUrl();
        $respons['to']             = $data_kas_keluar->perPage();
        $respons['total']          = $data_kas_keluar->total();

        return $respons;
    }

    public function view()
    {
        $data_kas_keluar = KasKeluar::select(['kas_keluars.id', 'kas_keluars.no_faktur', 'kas_keluars.jumlah', 'kas_keluars.keterangan', 'kas_keluars.created_at', 'kas_keluars.warung_id', 'kas.nama_kas', 'kategori_transaksis.nama_kategori_transaksi'])->leftJoin('kas', 'kas_keluars.kas', '=', 'kas.id')
            ->leftJoin('kategori_transaksis', 'kas_keluars.kategori', '=', 'kategori_transaksis.id')
            ->where('kas_keluars.warung_id', Auth::user()->id_warung)->orderBy('kas_keluars.id', 'desc')->paginate(10);

        $array_kas_keluar = array();
        foreach ($data_kas_keluar as $kas_keluar) {
            array_push($array_kas_keluar, ['kas_keluar' => $kas_keluar, 'jumlah' => $this->tandaPemisahTitik($kas_keluar->jumlah)]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kas_keluar, $array_kas_keluar);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $search          = $request->search;
        $data_kas_keluar = KasKeluar::select(['kas_keluars.id', 'kas_keluars.no_faktur', 'kas_keluars.jumlah', 'kas_keluars.keterangan', 'kas_keluars.created_at', 'kas_keluars.warung_id', 'kas.nama_kas', 'kategori_transaksis.nama_kategori_transaksi'])->leftJoin('kas', 'kas_keluars.kas', '=', 'kas.id')
            ->leftJoin('kategori_transaksis', 'kas_keluars.kategori', '=', 'kategori_transaksis.id')
            ->where('kas_keluars.warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('kas.nama_kas', 'LIKE', $search . '%')
                    ->orwhere('kas_keluars.no_faktur', 'LIKE', $search . '%')
                    ->orWhere('kategori_transaksis.nama_kategori_transaksi', 'LIKE', $search . '%');
            })->orderBy('kas_keluars.id', 'desc')->paginate(10);

        $array_kas_keluar = array();
        foreach ($data_kas_keluar as $kas_keluar) {
            array_push($array_kas_keluar, ['kas_keluar' => $kas_keluar, 'jumlah' => $this->tandaPemisahTitik($kas_keluar->jumlah)]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kas_keluar, $array_kas_keluar);
        return response()->json($respons);
    }

    public function pilih_kas()
    {
        $kas = Kas::select('id', 'nama_kas')->where('kas.warung_id', Auth::user()->id_warung)->get();
        return response()->json($kas);
    }

    public function pilih_kategori()
    {
        $kategori_transaksi = KategoriTransaksi::select('id', 'nama_kategori_transaksi')->where('kategori_transaksis.id_warung', Auth::user()->id_warung)->get();
        return response()->json($kategori_transaksi);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas', 'id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi', 'id');

        return view('kas_keluar.create', ['data_kategori_transaksi' => $data_kategori_transaksi, 'data_kas' => $data_kas]);
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
            'kas'        => 'required',
            'kategori'   => 'required',
            'jumlah'     => 'required|numeric',
            'keterangan' => 'max:150',
        ]);

        if (Auth::user()->id_warung != "") {

            $total_kas = TransaksiKas::total_kas($request);
            $sisa_kas  = $total_kas - $request->jumlah;

            if ($sisa_kas < 0) {
                return $sisa_kas;
            } else {

                $no_faktur = KasKeluar::no_faktur();
                $kas       = KasKeluar::create([
                    'no_faktur'  => $no_faktur,
                    'kas'        => $request->kas,
                    'kategori'   => $request->kategori,
                    'jumlah'     => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'warung_id'  => Auth::user()->id_warung,
                ]);

                return $sisa_kas;
            }

        } else {
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

        $id_warung  = Auth::user()->id_warung;
        $kas_keluar = KasKeluar::find($id);
        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas', 'id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi', 'id');

        if ($id_warung == $kas_keluar->warung_id) {
            return view('kas_keluar.edit', ['data_kategori_transaksi' => $data_kategori_transaksi, 'data_kas' => $data_kas])->with(compact('kas_keluar'));
        } else {
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
        $this->validate($request, [
            'kas'        => 'required',
            'kategori'   => 'required',
            'jumlah'     => 'required|numeric',
            'keterangan' => 'max:150',

        ]);

        $id_warung  = Auth::user()->id_warung;
        $kas_keluar = KasKeluar::find($id);

        if ($id_warung == $kas_keluar->warung_id) {

            $kas_keluar->kas        = $request->kas;
            $kas_keluar->kategori   = $request->kategori;
            $kas_keluar->jumlah     = $request->jumlah;
            $kas_keluar->keterangan = $request->keterangan;

            if (!$kas_keluar->save()) {
                return redirect()->back();
            } else {

                $pesan_alert =
                '<div class="container-fluid">
                <div class="alert-icon">
                    <i class="material-icons">check</i>
                </div>
                <b>Sukses : Berhasil Mengubah Transaksi Kas Keluar "' . $kas_keluar->no_faktur . '"</b>
            </div>';

                Session::flash("flash_notification", [
                    "level"   => "success",
                    "message" => $pesan_alert,
                ]);

                return redirect()->route('kas-keluar.index');

            }
        } else {
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
        $kas_keluar = KasKeluar::find($id);
        $id_warung  = Auth::user()->id_warung;

        if ($id_warung == $kas_keluar->warung_id) {
            KasKeluar::destroy($id);
        } else {
            return response()->view('error.403');
        }
    }

}
