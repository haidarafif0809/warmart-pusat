<?php

namespace App\Http\Controllers;

use App\Kelurahan;
use App\SettingAplikasi;
use App\UserWarung;
use App\Warung;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Laratrust;

class UserWarungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        if ($setting_aplikasi->tipe_aplikasi == 0) {
            $this->middleware('user-must-admin');
        } else {
            $this->middleware('user-must-topos');
        }
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('user_warung.index')->with(compact('html'));
    }

    //VIEW USER WARUNG
    public function view()
    {
        $user_warung = UserWarung::with(['warung'])->where('tipe_user', 4)->orderBy('id', 'desc')->paginate(10);

        $user_warung_array = array();
        foreach ($user_warung as $user_warungs) {
            $data_kelurahan = Kelurahan::select(['nama'])->where('id', $user_warungs->wilayah)->first();
            if ($user_warungs->wilayah == "") {
                $wilayah = "-";
            } else {
                $wilayah = $user_warungs->kelurahan->nama;
            }
            $warung = $user_warungs->warung->name;
            array_push($user_warung_array, ['user_warung' => $user_warungs, 'wilayah' => $wilayah, 'warung' => $warung]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $user_warung->currentPage();
        $respons['data']           = $user_warung_array;
        $respons['otoritas']      = $this->otoritasUserWarung();
        $respons['first_page_url'] = url('/user-warung/view?page=' . $user_warung->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $user_warung->lastPage();
        $respons['last_page_url']  = url('/user-warung/view?page=' . $user_warung->lastPage());
        $respons['next_page_url']  = $user_warung->nextPageUrl();
        $respons['path']           = url('/user-warung/view');
        $respons['per_page']       = $user_warung->perPage();
        $respons['prev_page_url']  = $user_warung->previousPageUrl();
        $respons['to']             = $user_warung->perPage();
        $respons['total']          = $user_warung->total();
        //DATA PAGINATION

        return response()->json($respons);
    }

    //PENCARIAN USER WARUNG
    public function pencarian(Request $request)
    {
        $search = $request->search; // REQUEST SEARCH

        if ($search != "") {
            $user_warung = UserWarung::with(['warung', 'kelurahan'])->where('tipe_user', 4)
            ->where(function ($query) use ($search) {
// search
                $query->orwhere('email', 'LIKE', '%' . $search . '%')
                ->orWhere('no_telp', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')
                ->orWhere('alamat', 'LIKE', '%' . $search . '%');
            })->paginate(10);
        } else {
            $user_warung = UserWarung::with(['warung', 'kelurahan'])->where('tipe_user', 4)->orderBy('id', 'desc')->paginate(10);
        }

        $user_warung_array = array();
        foreach ($user_warung as $user_warungs) {
            if ($user_warungs->wilayah == "") {
                $wilayah = "-";
            } else {
                $wilayah = $user_warungs->kelurahan->nama;
            }

            $warung = $user_warungs->warung->name;
            array_push($user_warung_array, ['user_warung' => $user_warungs, 'wilayah' => $wilayah, 'warung' => $warung]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $user_warung->currentPage();
        $respons['data']           = $user_warung_array;
        $respons['otoritas']      = $this->otoritasUserWarung();
        $respons['first_page_url'] = url('/user-warung/view?page=' . $user_warung->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $user_warung->lastPage();
        $respons['last_page_url']  = url('/user-warung/view?page=' . $user_warung->lastPage());
        $respons['next_page_url']  = $user_warung->nextPageUrl();
        $respons['path']           = url('/user-warung/view');
        $respons['per_page']       = $user_warung->perPage();
        $respons['prev_page_url']  = $user_warung->previousPageUrl();
        $respons['to']             = $user_warung->perPage();
        $respons['total']          = $user_warung->total();
        //DATA PAGINATION

        return response()->json($respons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user_warung.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_warung    = UserWarung::with(['warung'])->find($id);
        $data_kelurahan = Kelurahan::select(['nama'])->where('id', $user_warung->wilayah)->first();
        if ($user_warung->wilayah == null) {
            $wilayah = "-";
        } else {
            $wilayah = $data_kelurahan->nama;
        }

        $warung = Warung::where('id', $user_warung->id_warung)->first();

        $user_warung['kelurahan'] = $wilayah;
        $user_warung['warung']    = $warung->name;

        return $user_warung;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_warung = UserWarung::with(['kelurahan', 'warung'])->find($id);

        $kelurahan = Kelurahan::where('id', $user_warung->wilayah)->first();
        $warung    = Warung::where('id', $user_warung->id_warung)->first();

        $user_warung['kelurahan'] = $kelurahan->name;
        $user_warung['warung']    = $warung->nama;

        return url('user-warung#/edit_user_warung/' . $id);
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
        //VALIDASI USER WARUNG
        $this->validate($request, [
            'email'     => 'required|without_spaces|unique:users,email,' . $id,
            'name'      => 'required',
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|without_spaces|unique:users,no_telp,' . $id,
            'id_warung' => 'required',
        ]);

        //UPDATE USER WARUNG
        $user_warung = UserWarung::where('id', $id)->update([
            'email'     => $request->email,
            'name'      => $request->name,
            'alamat'    => $request->alamat,
            'wilayah'   => $request->wilayah,
            'no_telp'   => $request->no_telp,
            'id_warung' => $request->id_warung,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // JIKA USER WARUNG GAGAL DIHAPUS
        $user_warung = UserWarung::select('id_warung')->where('id', $id)->first();
        $warung_user = UserWarung::where('id_warung', $user_warung->id_warung)->count();

        if ($warung_user > 1) {
            $user_warung = UserWarung::destroy($id);
            return response(200);
        } else {
            return response(422);
        }

    }

    public function konfirmasi(Request $request)
    {
        // konfirmasi user_warung
        $username    = UserWarung::select('name')->where('id', $request->confirm)->first();
        $user_warung = UserWarung::find($request->confirm)->update(['konfirmasi_admin' => 1]);
    }

    public function no_konfirmasi(Request $request)
    {
        // no_konfirmasi user_warung
        $username    = UserWarung::select('name')->where('id', $request->confirm)->first();
        $user_warung = UserWarung::find($request->confirm)->update(['konfirmasi_admin' => 0]);
    }

    //PROSES PILIH KELURAHAN
    public function pilih_kelurahan()
    {
        $kelurahan = Kelurahan::all();
        return response()->json($kelurahan);
    }

    //PROSES PILIH WARUNG
    public function pilih_warung()
    {
        $warung = Warung::all();
        return response()->json($warung);
    }

    public function otoritasUserWarung(){

        if (Laratrust::can('tambah_user')) {
            $tambah_user = 1;
        }else{
            $tambah_user = 0;            
        }
        if (Laratrust::can('edit_user')) {
            $edit_user = 1;
        }else{
            $edit_user = 0;            
        }
        if (Laratrust::can('hapus_user')) {
            $hapus_user = 1;
        }else{
            $hapus_user = 0;            
        }
        $respons['tambah_user'] = $tambah_user;
        $respons['edit_user'] = $edit_user;
        $respons['hapus_user'] = $hapus_user;

        return response()->json($respons);
    }
}
