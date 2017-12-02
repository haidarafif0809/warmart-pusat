<?php

namespace App\Http\Controllers;

use App\Pembelian;
use App\Suplier;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;

class SuplierController extends Controller
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
        return view('suplier.index')->with(compact('html'));
    }

    public function statusSuplier($suplier)
    {
        $data_pembelian = Pembelian::where('suplier_id', $suplier->id)->where('warung_id', $suplier->warung_id)->count();
        if ($data_pembelian > 0) {
            $status_suplier = 1;
        } else {
            $status_suplier = 0;
        }

        return $status_suplier;
    }

    public function dataPagination($data_suplier, $array_suplier)
    {
        $respons['current_page']   = $data_suplier->currentPage();
        $respons['data']           = $array_suplier;
        $respons['first_page_url'] = url('/suplier/view?page=' . $data_suplier->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_suplier->lastPage();
        $respons['last_page_url']  = url('/suplier/view?page=' . $data_suplier->lastPage());
        $respons['next_page_url']  = $data_suplier->nextPageUrl();
        $respons['path']           = url('/suplier/view');
        $respons['per_page']       = $data_suplier->perPage();
        $respons['prev_page_url']  = $data_suplier->previousPageUrl();
        $respons['to']             = $data_suplier->perPage();
        $respons['total']          = $data_suplier->total();

        return $respons;
    }

    public function view()
    {
        $data_suplier  = Suplier::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
        $array_suplier = array();
        foreach ($data_suplier as $suplier) {

            $status_suplier = $this->statusSuplier($suplier);
            array_push($array_suplier, [
                'suplier'        => $suplier,
                'status_suplier' => $status_suplier,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_suplier, $array_suplier);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $data_suplier = Suplier::where('warung_id', Auth::user()->id_warung)
            ->where('nama_suplier', 'LIKE', "%$request->search%")
            ->orwhere('no_telp', 'LIKE', "%$request->search%")
            ->orwhere('alamat', 'LIKE', "%$request->search%")
            ->orwhere('contact_person', 'LIKE', "%$request->search%")
            ->orderBy('id', 'desc')->paginate(10);

        $array_suplier = array();
        foreach ($data_suplier as $suplier) {

            $status_suplier = $this->statusSuplier($suplier);
            array_push($array_suplier, [
                'suplier'        => $suplier,
                'status_suplier' => $status_suplier,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_suplier, $array_suplier);
        return response()->json($respons);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('suplier.create');
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
            'nama_suplier' => 'required|unique:supliers,nama_suplier,NULL,id,warung_id,' . Auth::user()->id_warung . '',
            'alamat'       => 'required',
            'no_telp'      => 'required|without_spaces',
        ]);

        if (Auth::user()->id_warung != "") {
            $suplier = Suplier::create([
                'nama_suplier'   => $request->nama_suplier,
                'alamat'         => $request->alamat,
                'no_telp'        => $request->no_telp,
                'contact_person' => $request->contact_person,
                'warung_id'      => Auth::user()->id_warung]);
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
        $suplier = Suplier::find($id);
        return $suplier;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warung_id = Auth::user()->id_warung;
        $suplier   = Suplier::find($id);

        if ($warung_id == $suplier->warung_id) {
            return view('suplier.edit')->with(compact('suplier'));
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
        //
        $id_warung = Auth::user()->id_warung;

        $this->validate($request, [
            'nama_suplier' => 'required|unique:supliers,nama_suplier,' . $id . ',id,warung_id,' . Auth::user()->id_warung . '',
            'alamat'       => 'required',
            'no_telp'      => 'required|without_spaces',
        ]);

        $suplier = Suplier::find($id);
        if ($id_warung == $suplier->warung_id) {
            $suplier = Suplier::find($id)->update([
                'nama_suplier'   => $request->nama_suplier,
                'alamat'         => $request->alamat,
                'no_telp'        => $request->no_telp,
                'contact_person' => $request->contact_person,
            ]);
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
        $suplier   = Suplier::find($id);

        if ($id_warung == $suplier->warung_id) {
            Suplier::destroy($id);
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }
}
