<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Warung;
use Session;
use Laratrust;

class WarungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    { 
        if ($request->ajax()) {
            # code...
            $warung = Warung::with(['kelurahan'])->get();
            return Datatables::of($warung)
                ->addColumn('action', function($warung){
                    return view('datatable._action', [
                        'model'     => $warung,
                        'form_url'  => route('warung.destroy', $warung->id),
                        'edit_url'  => route('warung.edit', $warung->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Warung ' . $warung->name . '?',
                        'permission_ubah' => Laratrust::can('edit_warung'),
                        'permission_hapus' => Laratrust::can('hapus_warung'),

                        ]);
                })
                ->addColumn('link', function($link){ 
                    return $link->link_afiliasi;
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama']) 
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat']) 
        ->addColumn(['data' => 'kelurahan.nama', 'name' => 'kelurahan.nama', 'title' => 'Wilayah'])  
        ->addColumn(['data' => 'url_api', 'name' => 'url_api', 'title' => 'URL API']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        return view('warung.index')->with(compact('html'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view('warung.create');
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
            'name'      => 'required|unique:warungs,name,',
            'alamat'    => 'required',
            'kelurahan' => 'required', 
            'url_api'   => 'required||unique:warungs,url_api,',

            ]);

         $warung = Warung::create([
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan, 
            'url_api' =>$request->url_api,

            ]);

          $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Menambah Stoking Center '.$request->name.' </b>
                </div>';


        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);
        return redirect()->route('warung.index');
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
          $warung = Warung::with(['kelurahan'])->find($id);
            return view('warung.edit')->with(compact('warung'));
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
            'name'      => 'required|unique:warungs,name,'.$id,
            'alamat'    => 'required',
            'kelurahan' => 'required', 
            'url_api'   => 'required||unique:warungs,url_api,'.$id,

            ]);


         //insert
        $warung = Warung::where('id',$id)->update([
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan, 
            'url_api' =>$request->url_api,
        ]);

        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Mengubah Warung '.$request->name.' </b>
                </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);

        return redirect()->route('warung.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // jika gagal hapus
        if (!Warung::destroy($id)) {
            // redirect back
            return redirect()->back();
        }else{
            
        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Menghapus Warung </b>
                </div>';

        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return redirect()->route('warung.index');
        }
    }
}
