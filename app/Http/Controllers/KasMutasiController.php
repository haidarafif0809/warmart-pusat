<?php

namespace App\Http\Controllers;

use App\Kas;
use App\KasMutasi;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Html\Builder;

class KasMutasiController extends Controller
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

    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('kas_mutasi.index')->with(compact('html'));
    }

    public function tandaPemisahTitik($angka)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function dataPagination($data_kas_mutasi, $array_kas_mutasi)
    {

        $respons['current_page']   = $data_kas_mutasi->currentPage();
        $respons['data']           = $array_kas_mutasi;
        $respons['first_page_url'] = url('/kas-mutasi/view?page=' . $data_kas_mutasi->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_kas_mutasi->lastPage();
        $respons['last_page_url']  = url('/kas-mutasi/view?page=' . $data_kas_mutasi->lastPage());
        $respons['next_page_url']  = $data_kas_mutasi->nextPageUrl();
        $respons['path']           = url('/kas-mutasi/view');
        $respons['per_page']       = $data_kas_mutasi->perPage();
        $respons['prev_page_url']  = $data_kas_mutasi->previousPageUrl();
        $respons['to']             = $data_kas_mutasi->perPage();
        $respons['total']          = $data_kas_mutasi->total();

        return $respons;
    }

    public function view()
    {
        $data_kas_mutasi = KasMutasi::select(['kas_mutasis.id', 'kas_mutasis.no_faktur', 'kas_mutasis.jumlah', 'kas_mutasis.keterangan', 'kas_mutasis.id_warung', 'kas_pengirim.nama_kas as nama_dari_kas', 'kas_penerima.nama_kas as nama_ke_kas'])
            ->leftJoin('kas as kas_pengirim', 'kas_mutasis.dari_kas', '=', 'kas_pengirim.id')
            ->leftJoin('kas as kas_penerima', 'kas_mutasis.ke_kas', '=', 'kas_penerima.id')
            ->where('kas_mutasis.id_warung', Auth::user()->id_warung)->orderBy('kas_mutasis.id', 'desc')->paginate(10);

        $array_kas_mutasi = array();
        foreach ($data_kas_mutasi as $kas_mutasi) {
            array_push($array_kas_mutasi, ['kas_mutasi' => $kas_mutasi, 'jumlah' => $this->tandaPemisahTitik($kas_mutasi->jumlah)]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kas_mutasi, $array_kas_mutasi);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $search = $request->search;

        $data_kas_mutasi = KasMutasi::select(['kas_mutasis.id', 'kas_mutasis.no_faktur', 'kas_mutasis.jumlah', 'kas_mutasis.keterangan', 'kas_mutasis.id_warung', 'kas_pengirim.nama_kas as nama_dari_kas', 'kas_penerima.nama_kas as nama_ke_kas', 'kas_pengirim.nama_kas', 'kas_penerima.nama_kas'])
            ->leftJoin('kas as kas_pengirim', 'kas_mutasis.dari_kas', '=', 'kas_pengirim.id')
            ->leftJoin('kas as kas_penerima', 'kas_mutasis.ke_kas', '=', 'kas_penerima.id')
            ->where('kas_mutasis.id_warung', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
                $query->orwhere('kas_pengirim.nama_kas', 'LIKE', '%' . $search . '%')
                    ->orwhere('kas_penerima.nama_kas', 'LIKE', '%' . $search . '%')
                    ->orwhere('kas_mutasis.no_faktur', 'LIKE', '%' . $search . '%')
                    ->orwhere('kas_mutasis.keterangan', 'LIKE', '%' . $search . '%');
            })->orderBy('kas_mutasis.id', 'desc')->paginate(10);

        $array_kas_mutasi = array();
        foreach ($data_kas_mutasi as $kas_mutasi) {
            array_push($array_kas_mutasi, ['kas_mutasi' => $kas_mutasi, 'jumlah' => $this->tandaPemisahTitik($kas_mutasi->jumlah)]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kas_mutasi, $array_kas_mutasi);
        return response()->json($respons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            //MENAMPILKAN KAS BERDASARKAN ID WARUNG
            $kas = Kas::where('warung_id', Auth::user()->id_warung)->pluck('nama_kas', 'id');

            return view('kas_mutasi.create', ['kas' => $kas]);
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
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            // // proses tambah kas mutasi
            $this->validate($request, [
                'dari_kas'   => 'required',
                'ke_kas'     => 'required',
                'jumlah'     => 'required|numeric|digits_between:1,9',
                'keterangan' => 'nullable|max:150',

            ]);

            $no_faktur = KasMutasi::no_faktur();

            $kas_mutasi = KasMutasi::create([
                'no_faktur'  => $no_faktur,
                'dari_kas'   => $request->dari_kas,
                'ke_kas'     => $request->ke_kas,
                'jumlah'     => $request->jumlah,
                'keterangan' => $request->keterangan,
                'id_warung'  => Auth::user()->id_warung]);

            Session::flash("flash_notification", [
                "level"   => "success",
                "message" => " <b>BERHASIL:</b> Memutasikan Kas Sejumlah <b>$request->jumlah</b>",
            ]);

            return redirect()->route('kas_mutasi.index');
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
        $kas_mutasi = KasMutasi::find($id);
        //MENAMPILKAN KAS BERDASARKAN ID WARUNG
        $kas = Kas::where('warung_id', Auth::user()->id_warung)->pluck('nama_kas', 'id');

        if ($kas_mutasi->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {

            return view('kas_mutasi.edit', ['kas' => $kas])->with(compact('kas_mutasi'));
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
        $kas_mutasi = KasMutasi::find($id);

        if ($kas_mutasi->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $this->validate($request, [
                'dari_kas'   => 'required',
                'ke_kas'     => 'required',
                'jumlah'     => 'required|numeric',
                'keterangan' => 'max:150',

            ]);

            $kas = KasMutasi::find($id)->update(['dari_kas' => $request->dari_kas, 'ke_kas' => $request->ke_kas, 'jumlah' => $request->jumlah, 'keterangan' => $request->keterangan, 'id_warung' => Auth::user()->id_warung]);

            Session::flash("flash_notification", [
                "level"   => "success",
                "message" => "<b>BERHASIL:</b> Mengubah Kas Mutasi $kas_mutasi->no_faktur",
            ]);

            return redirect()->route('kas_mutasi.index');

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
        //
        $kas_mutasi = KasMutasi::find($id);

        if ($kas_mutasi->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {

            // hitung kas
            $sisa_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))
                ->where('kas', $kas_mutasi->ke_kas)
                ->where('warung_id', Auth::user()->id_warung)
                ->where('no_faktur', '!=', $kas_mutasi->no_faktur)
                ->first();

            if ($sisa_kas->total_kas < 0) {

                Session::flash("flash_notification", [
                    "level"   => "danger",
                    "message" => "Mohon Maaf, Kas Mutasi " . $kas_mutasi->no_faktur . " Tidak bisa Di Hapus, Jika Dihapus Kas akan Minus ",
                ]);
                return redirect()->route('kas_mutasi.index');
            } else {

                // jika gagal hapus
                if (!KasMutasi::destroy($id)) {
                    // redirect back
                    return redirect()->back();
                } else {
                    Session::flash("flash_notification", [
                        "level"   => "danger",
                        "message" => "Kas Mutasi " . $kas_mutasi->no_faktur . " Berhasil Di Hapus",
                    ]);
                    return redirect()->route('kas_mutasi.index');
                }
            }

        }
    }

}
