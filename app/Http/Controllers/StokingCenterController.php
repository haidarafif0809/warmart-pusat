<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\StokingCenter;
use Session;
use Laratrust;

class StokingCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        //
         //
        if ($request->ajax()) {
            # code...
            $stokingcenter = StokingCenter::with(['kelurahan','kategori_harga'])->get();
            return Datatables::of($stokingcenter)
                ->addColumn('action', function($stokingcenter){
                    return view('datatable._action', [
                        'model'     => $stokingcenter,
                        'form_url'  => route('stoking-center.destroy', $stokingcenter->id),
                        'edit_url'  => route('stoking-center.edit', $stokingcenter->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Stoking Center ' . $stokingcenter->name . '?',
                        'permission_ubah' => Laratrust::can('edit_stokingcenter'),
                        'permission_hapus' => Laratrust::can('hapus_stokingcenter'),

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
        ->addColumn(['data' => 'kategori_harga.nama_kategori_harga', 'name' => 'kategori_harga.nama_kategori_harga', 'title' => 'Nama Kategori Harga']) 
        ->addColumn(['data' => 'url_api', 'name' => 'url_api', 'title' => 'URL API']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        return view('stoking_center.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('stoking_center.create');
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
            'name'      => 'required|unique:stoking_centers,name,',
            'alamat'    => 'required',
            'kelurahan' => 'required|unique:stoking_centers,wilayah,',
            'kategoriharga' => 'required',
            'url_api'   => 'required||unique:stoking_centers,url_api,',

            ]);

         $warung = StokingCenter::create([
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'kategori_harga' =>$request->kategoriharga,
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
        return redirect()->route('stoking-center.index');
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
          $stokingcenter = StokingCenter::with(['kelurahan','kategori_harga'])->find($id);
            return view('stoking_center.edit')->with(compact('stokingcenter'));
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
            'name'      => 'required|unique:stoking_centers,name,'.$id,
            'alamat'    => 'required',
            'kelurahan' => 'required|unique:stoking_centers,wilayah,'.$id,
            'kategoriharga' => 'required',
            'url_api'   => 'required||unique:stoking_centers,url_api,'.$id,

            ]);


         //insert
        $stoking_center = StokingCenter::where('id',$id)->update([
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'kategori_harga' =>$request->kategoriharga,
            'url_api' =>$request->url_api,
        ]);

        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Mengubah Stoking Center '.$request->name.' </b>
                </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);

        return redirect()->route('stoking-center.index');
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
        // jika gagal hapus
        if (!StokingCenter::destroy($id)) {
            // redirect back
            return redirect()->back();
        }else{
            
        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Menghapus Stoking Center </b>
                </div>';

        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return redirect()->route('stoking-center.index');
        }
    }
}
