<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Suplier;
use Laratrust;
use File;
use Auth;


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
        //
            if ($request->ajax()) {
            $master_suplier = Suplier::select(['id','nama_suplier','no_telp','alamat','warung_id'])->where('warung_id',Auth::user()->id_warung)->get();
            return Datatables::of($master_suplier)->addColumn('action', function($suplier){
                    return view('datatable._action', [
                        'model'     => $suplier,
                        'form_url'  => route('suplier.destroy', $suplier->id),
                        'edit_url'  => route('suplier.edit', $suplier->id),
                        'confirm_message'   => 'Anda Yakin Ingin Menghapus ' .$suplier->nama_suplier . ' ?',
                        ]);
              })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_suplier', 'name' => 'nama_suplier', 'title' => 'Nama']) 
        ->addColumn(['data' => 'no_telp', 'name' => 'no_telp', 'title' => 'No. Telpon']) 
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat'])  
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('suplier.index')->with(compact('html'));
        
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
        //
        $this->validate($request, [
            'nama_suplier' => 'required|unique:supliers,nama_suplier,NULL,id,warung_id,'.Auth::user()->id_warung.'',
            'alamat'=>'required',
            'no_telp'=>'required',
        ]);

            if (Auth::user()->id_warung != "") {
            $suplier = Suplier::create([
                'nama_suplier' =>$request->nama_suplier,
                'alamat'       =>$request->alamat,
                'no_telp'      =>$request->no_telp,
                'warung_id'    =>Auth::user()->id_warung]); 
            }else{
                  Auth::logout();
                return response()->view('error.403');
            }

        $pesan_alert = '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Menambah Suplier "'.$request->nama_suplier.'"</b>
         </div>';

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
         ]);

        return redirect()->route('suplier.index');
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
        $warung_id = Auth::user()->id_warung;
        $suplier = Suplier::find($id);

            if ($warung_id == $suplier->warung_id) {
                return view('suplier.edit')->with(compact('suplier')); 
            }else{
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
            'nama_suplier' => 'required|unique:supliers,nama_suplier,'. $id.',id,warung_id,'.Auth::user()->id_warung.'',
            'alamat'=>'required',
            'no_telp'=>'required',
        ]);

        $suplier = Suplier::find($id);
        if ($id_warung == $suplier->warung_id) {
            $suplier = Suplier::find($id)->update([
                'nama_suplier' =>$request->nama_suplier,
                'alamat'       =>$request->alamat,
                'no_telp'      =>$request->no_telp,
            ]);
        }else{
              Auth::logout();
                return response()->view('error.403'); 
        }

        $pesan_alert = 
        '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Mengubah Suplier "'.$request->nama_suplier.'"</b>
         </div>';

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
         ]);

        return redirect()->route('suplier.index');
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
        $id_warung = Auth::user()->id_warung;
        $suplier = Suplier::find($id);

        if ($id_warung == $suplier->warung_id) {
            // JIKA GAGAL MENGHAPUD
            if (!Suplier::destroy($id)) {
                return redirect()->back();
            }
            else{
         $pesan_alert = '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Menghapus Suplier </b>
         </div>';

         Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
         ]);
                return redirect()->route('suplier.index');
            }
        }else{
              Auth::logout();
                return response()->view('error.403'); 
        }
    }
}
