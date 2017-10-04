<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Komunitas;
use Session;
use Laratrust;

class KomunitasController extends Controller
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
            $komunitas = komunitas::with(['kelurahan','role','warung'])->where('tipe_user',2)->get();
            return Datatables::of($komunitas)
                ->addColumn('action', function($komunitas){
                    return view('datatable._action', [
                        'model'     => $komunitas,
                        'form_url'  => route('komunitas.destroy', $komunitas->id),
                        'edit_url'  => route('komunitas.edit', $komunitas->id),
                        'confirm_message'   => 'Yakin Mau Menghapus komunitas ' . $komunitas->name . '?',
                        'permission_ubah' => Laratrust::can('edit_komunitas'),
                        'permission_hapus' => Laratrust::can('hapus_komunitas'),

                        ]);
                })
                ->addColumn('link', function($link){ 
                    return $link->link_afiliasi;
                })
                ->addColumn('warung', function($warung){ 
                   if ($warung->id_warung == "") {
                        return "-";
                   }else{
                        return $warung->warung->name;
                   }
                })
                ->addColumn('kelurahan', function($kelurahan){ 
                   if ($kelurahan->wilayah == "") {
                        return "-";
                   }else{
                        return $kelurahan->kelurahan->nama;
                   }
                })
                ->addColumn('nama_bank', function($nama_bank){ 
                   if ($nama_bank->nama_bank == "") {
                        return "-";
                   }else{
                        return $nama_bank->nama_bank;
                   }
                })
                ->addColumn('no_rekening', function($no_rekening){ 
                   if ($no_rekening->no_rekening == "") {
                        return "-";
                   }else{
                        return $no_rekening->no_rekening;
                   }
                })
                ->addColumn('an_rekening', function($an_rekening){ 
                   if ($an_rekening->an_rekening == "") {
                        return "-";
                   }else{
                        return $an_rekening->an_rekening;
                   }
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'No Telp']) 
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Komunitas'])
        ->addColumn(['data' => 'warung', 'name' => 'warung', 'title' => 'Warung'])  
        ->addColumn(['data' => 'no_telp', 'name' => 'no_telp', 'title' => 'Email'])  
        ->addColumn(['data' => 'kelurahan', 'name' => 'kelurahan', 'title' => 'Wilayah']) 
        ->addColumn(['data' => 'link', 'name' => 'link', 'title' => 'Link Afiliasi']) 
        ->addColumn(['data' => 'nama_bank', 'name' => 'nama_bank', 'title' => 'Nama Bank']) 
        ->addColumn(['data' => 'no_rekening', 'name' => 'no_rekening', 'title' => 'No Rekening']) 
        ->addColumn(['data' => 'an_rekening', 'name' => 'an_rekening', 'title' => 'A.N Rekening'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        return view('komunitas.index')->with(compact('html'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('komunitas.create');
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
            'email'     => 'required|without_spaces|numeric|unique:users,email,',
            'name'      => 'required|unique:users,name,',
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|without_spaces|unique:users,no_telp,',
            'nama_bank' => 'required',
            'no_rekening' => 'required|unique:users,no_rekening,',
            'an_rekening' => 'required',
            'id_warung' => 'required',

            ]);

         $komunitas = Komunitas::create([
            'email' =>$request->email,
            'password' => bcrypt('rahasia'),
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'no_telp' =>$request->no_telp,
            'nama_bank' =>$request->nama_bank,
            'no_rekening' =>$request->no_rekening,
            'an_rekening' =>$request->an_rekening,
            'id_warung' =>$request->id_warung,
            'tipe_user'=> 2,
            'status_konfirmasi'=>0
            ]);


        $komunitas->attachRole(4);

          $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Menambah Komunitas '.$request->name.' </b>
                </div>';


        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);
        return redirect()->route('komunitas.index');
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
          $komunitas = Komunitas::with(['kelurahan','warung'])->find($id);
            return view('komunitas.edit')->with(compact('komunitas'));
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
            'email'     => 'required|without_spaces|numeric|unique:users,email,'. $id,
            'name'      => 'required|unique:users,name,'. $id,
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|without_spaces|unique:users,no_telp,'. $id,
            'nama_bank' => 'required',
            'no_rekening' => 'required|unique:users,no_rekening,'. $id,
            'an_rekening' => 'required',
            'id_warung' => 'required',
            ]);

         //insert
        $komunitas = Komunitas::where('id',$id)->update([
            'email' =>$request->email,
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'no_telp' =>$request->no_telp,
            'nama_bank' =>$request->nama_bank,
            'no_rekening' =>$request->no_rekening,
            'an_rekening' =>$request->an_rekening,
            'id_warung' =>$request->id_warung,
        ]);

        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Mengubah Komunitas '.$request->name.' </b>
                </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);

        return redirect()->route('komunitas.index');
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
        if (!Komunitas::destroy($id)) {
            // redirect back
            return redirect()->back();
        }else{
            
        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Success : Berhasil Menghapus Komunitas </b>
                </div>';

        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return redirect()->route('komunitas.index');
        }

    }
}
