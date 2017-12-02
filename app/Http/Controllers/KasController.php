<?php

namespace App\Http\Controllers;

use App\Kas;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;

class KasController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('kas.index')->with(compact('html'));
    }

    public function view()
    {
        $kas       = Kas::where('warung_id', Auth::user()->id_warung)->paginate(10);
        $kas_array = array();
        foreach ($kas as $kass) {
            $total_kas = $kass->totalKas;
            array_push($kas_array, ['total_kas' => $total_kas, 'kas' => $kass]);
        }
        //DATA PAGINATION
        $respons['current_page']   = $kas->currentPage();
        $respons['data']           = $kas_array;
        $respons['first_page_url'] = url('/kas/view?page=' . $kas->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $kas->lastPage();
        $respons['last_page_url']  = url('/kas/view?page=' . $kas->lastPage());
        $respons['next_page_url']  = $kas->nextPageUrl();
        $respons['path']           = url('/kas/view');
        $respons['per_page']       = $kas->perPage();
        $respons['prev_page_url']  = $kas->previousPageUrl();
        $respons['to']             = $kas->perPage();
        $respons['total']          = $kas->total();
        //DATA PAGINATION

        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $search = $request->search; // REQUEST SEARCH
        //query pencarian
        $kas = Kas::where('warung_id', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
// search
                $query->orwhere('nama_kas', 'LIKE', $search . '%')
                    ->orWhere('kode_kas', 'LIKE', $search . '%');
            })->paginate(10);

        $kas_array = array();

        foreach ($kas as $kass) {
            $total_kas = $kass->totalKas;
            array_push($kas_array, ['total_kas' => $total_kas, 'kas' => $kass]);
        }
        //DATA PAGINATION
        $respons['current_page']   = $kas->currentPage();
        $respons['data']           = $kas_array;
        $respons['first_page_url'] = url('/kas/view?page=' . $kas->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $kas->lastPage();
        $respons['last_page_url']  = url('/kas/view?page=' . $kas->lastPage());
        $respons['next_page_url']  = $kas->nextPageUrl();
        $respons['path']           = url('/kas/view');
        $respons['per_page']       = $kas->perPage();
        $respons['prev_page_url']  = $kas->previousPageUrl();
        $respons['to']             = $kas->perPage();
        $respons['total']          = $kas->total();
        //DATA PAGINATION

        return response()->json($respons);
    }

    public function cekDefaultKas(Request $request)
    {
        //JIKA KAS DEFAULT, DIUBAH MENJADI TIDAK DEFAULT (MAKA MUNCUL PERINGATAN)
        $data_kas = Kas::select('default_kas')->where('id', $request->id)->first();
        return $data_kas->default_kas;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kas.create');
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
            'kode_kas' => 'required|unique:kas,kode_kas,NULL,id,warung_id,' . Auth::user()->id_warung . '',
            'nama_kas' => 'required',
        ]);

        if ($request->status_kas == '') {
            $status_kas = 0;
        } else {
            $status_kas = $request->status_kas;
        }

        if ($request->default_kas == '') {
            $default_kas = 0;
        } else {
            $default_kas = $request->default_kas;
        }

        if (Auth::user()->id_warung != "") {
            //JIKA BUAT KAS BARU DENGAN DEFAULT KAS = "YA", TETAPI SUDAH ADA YG DEFAULT
            if ($request->default_kas == 1) {
                //UPDATE MASTER DATA KAS WARUNG, JADI TIDAK DEFAULT KAS
                $kas_default = Kas::where('default_kas', $request->default_kas)
                    ->where('warung_id', Auth::user()->id_warung)->update([
                    'default_kas' => 0,
                ]);

                //INSERT MASTER DATA KAS WARUNG, JADI DEFAULT KAS
                $kas = Kas::create([
                    'kode_kas'    => $request->kode_kas,
                    'nama_kas'    => $request->nama_kas,
                    'status_kas'  => $status_kas,
                    'default_kas' => $default_kas,
                    'warung_id'   => Auth::user()->id_warung,
                ]);
            } else {
                //INSERT MASTER DATA KAS WARUNG
                $kas = Kas::create([
                    'kode_kas'    => $request->kode_kas,
                    'nama_kas'    => $request->nama_kas,
                    'status_kas'  => $status_kas,
                    'default_kas' => $default_kas,
                    'warung_id'   => Auth::user()->id_warung,
                ]);
            }
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

        $id_warung = Auth::user()->id_warung;
        $kas       = Kas::find($id);

        if ($id_warung == $kas->warung_id) {
            return $kas;
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id_warung = Auth::user()->id_warung;
        $kas       = Kas::find($id);

        if ($id_warung == $kas->warung_id) {
            return view('kas.edit', ['user_warung' => $id_warung])->with(compact('kas'));
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
        $this->validate($request, [
            'kode_kas' => 'required|unique:kas,kode_kas,' . $id . ',id,warung_id,' . Auth::user()->id_warung,
            'nama_kas' => 'required',
        ]);

        $id_warung = Auth::user()->id_warung;
        $kas       = Kas::find($id);

        if ($request->status_kas == false) {
            $status_kas = 0;
        } else {
            $status_kas = $request->status_kas;
        }

        if ($request->default_kas == false) {
            $default_kas = 0;
        } else {
            $default_kas = $request->default_kas;
        }

        if ($id_warung == $kas->warung_id) {
            //JIKA BUAT KAS BARU DENGAN DEFAULT KAS = "YA", TETAPI SUDAH ADA YG DEFAULT
            //UPDATE MASTER DATA KAS WARUNG, JADI TIDAK DEFAULT KAS
            if ($request->default_kas == 1) {
                $kas_default = Kas::where('default_kas', $request->default_kas)
                    ->where('warung_id', Auth::user()->id_warung)->update([
                    'default_kas' => 0,
                ]);
                //UPDATE MASTER DATA KAS WARUNG
                Kas::where('id', $id)->update([
                    'kode_kas'    => $request->kode_kas,
                    'nama_kas'    => $request->nama_kas,
                    'status_kas'  => $status_kas,
                    'default_kas' => $default_kas,
                ]);
            } else {
                Kas::where('id', $id)
                    ->where('warung_id', Auth::user()->id_warung)
                    ->update([
                        'kode_kas'    => $request->kode_kas,
                        'nama_kas'    => $request->nama_kas,
                        'status_kas'  => $status_kas,
                        'default_kas' => $default_kas,
                    ]);
            }

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
        $id_warung = Auth::user()->id_warung;
        $kas       = Kas::find($id);

        if ($id_warung == $kas->warung_id) {
            Kas::destroy($id);
        } else {
            Auth::logout();
            return response()->view('error.403');
        }

    }

    public function cekKasWarung()
    {
        $kas = Kas::where('warung_id', Auth::user()->id_warung);

        return response()->json([
            "cek_kas" => $kas->count(),
            "kas"     => $kas->get()->toArray(),
        ]);

    }
}
