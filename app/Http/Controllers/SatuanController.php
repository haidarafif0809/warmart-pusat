<?php

namespace App\Http\Controllers;

use App\Satuan;
use App\SettingAplikasi;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Laratrust;

class SatuanController extends Controller
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
        //
        return view('satuan.index')->with(compact('html'));
    }

    public function view()
    {
        $satuan = Satuan::orderBy('id', 'DESC')->paginate(10);
        $otoritas = $this->otoritasSatuan();
        return response()->json([
            "satuan" => $satuan,
            "otoritas"     => $otoritas,
        ]);
    }

    public function pencarian(Request $request)
    {

        $satuan = Satuan::where('nama_satuan', 'LIKE', "%$request->search%")->paginate(10);
        $otoritas = $this->otoritasSatuan();
        return response()->json([
            "satuan" => $satuan,
            "otoritas"     => $otoritas,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return Satuan::paginate(10);
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
        $this->validate($request, [
            'nama_satuan' => 'required|unique:satuans,nama_satuan',

        ]);

        $master_satuan = Satuan::create([
            'nama_satuan' => $request->nama_satuan,
        ]);
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
        return Satuan::find($id);
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
        $satuan = Satuan::find($id);
        return url('satuan#/edit_satuan/' . $id);
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
        $this->validate($request, [
            'nama_satuan' => 'required|unique:satuans,nama_satuan,' . $id,
        ]);

        Satuan::where('id', $id)->update([
            'nama_satuan' => $request->nama_satuan,
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
        //
        $satuan = Satuan::destroy($id);
        return response(200);
    }

    public function otoritasSatuan(){

        if (Laratrust::can('tambah_satuan')) {
            $tambah_satuan = 1;
        }else{
            $tambah_satuan = 0;            
        }
        if (Laratrust::can('edit_satuan')) {
            $edit_satuan = 1;
        }else{
            $edit_satuan = 0;            
        }
        if (Laratrust::can('hapus_satuan')) {
            $hapus_satuan = 1;
        }else{
            $hapus_satuan = 0;            
        }
        $respons['tambah_satuan'] = $tambah_satuan;
        $respons['edit_satuan'] = $edit_satuan;
        $respons['hapus_satuan'] = $hapus_satuan;

        return response()->json($respons);
    }
}
