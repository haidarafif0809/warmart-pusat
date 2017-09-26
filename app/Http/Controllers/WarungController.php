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
         //
        if ($request->ajax()) {
            # code...
            $master_warung = Warung::with(['kelurahan','role'])->where('tipe_user',2)->get();
            return Datatables::of($master_warung)
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
        ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email']) 
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Warung']) 
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat']) 
        ->addColumn(['data' => 'kelurahan.nama', 'name' => 'kelurahan.nama', 'title' => 'Wilayah']) 
        ->addColumn(['data' => 'link', 'name' => 'link', 'title' => 'Link Afiliasi']) 
        ->addColumn(['data' => 'no_telp', 'name' => 'no_telp', 'title' => 'No Telp']) 
        ->addColumn(['data' => 'nama_bank', 'name' => 'nama_bank', 'title' => 'Nama Bank']) 
        ->addColumn(['data' => 'no_rekening', 'name' => 'no_rekening', 'title' => 'No Rekening']) 
        ->addColumn(['data' => 'an_rekening', 'name' => 'an_rekening', 'title' => 'A.N Rekening'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        return view('master_warung.index')->with(compact('html'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('master_warung.create');
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
            'email'     => 'required',
            'name'      => 'required',
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|numeric',
            'nama_bank' => 'required',
            'no_rekening' => 'required',
            'an_rekening' => 'required',

            ]);

         $warung = Warung::create([
            'email' =>$request->email,
            'password' => bcrypt('rahasia'),
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'no_telp' =>$request->no_telp,
            'nama_bank' =>$request->nama_bank,
            'no_rekening' =>$request->no_rekening,
            'an_rekening' =>$request->an_rekening,
            'tipe_user'=> 2,
            'status_konfirmasi'=>0
            ]);


        $warung->attachRole(4);

          $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Menambah Warung '.$request->name.' </b>
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
        //
          $warung = Warung::with(['kelurahan'])->find($id);
            return view('master_warung.edit')->with(compact('warung'));
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
            'email'     => 'required|unique:users,email,'. $id,
            'name'      => 'required|unique:users,name,'. $id,
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|numeric',
            'nama_bank' => 'required',
            'no_rekening' => 'required|unique:users,no_rekening,'. $id,
            'an_rekening' => 'required',
            ]);

         //insert
        $barang = Warung::where('id',$id)->update([
            'email' =>$request->email,
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'no_telp' =>$request->no_telp,
            'nama_bank' =>$request->nama_bank,
            'no_rekening' =>$request->no_rekening,
            'an_rekening' =>$request->an_rekening
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
