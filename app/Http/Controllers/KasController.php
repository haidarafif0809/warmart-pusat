<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Kas;
use Auth;
use Session;
use Laratrust;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {

            $kas = Kas::select(['id','kode_kas', 'nama_kas', 'status_kas', 'default_kas'])->where('warung_id', Auth::user()->id_warung);
            return Datatables::of($kas)
                ->addColumn('action', function($kas){
                    return view('datatable._action', [
                        'model'             => $kas,
                        'form_url'          => route('kas.destroy', $kas->id),
                        'edit_url'          => route('kas.edit', $kas->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Kas ' . $kas->nama_kas . ' ?',
                        'permission_ubah'   => Laratrust::can('edit_kas'),
                        'permission_hapus'  => Laratrust::can('hapus_kas'),

                        ]);
                })
                ->editColumn('default_kas', function($default){
                    if ($default->default_kas == 1) {
                        $default = '<i style="color:green" class="material-icons">check_circle</i>';
                    }
                    else{
                        $default = '<i style="color:red" class="material-icons">cancel</i>';
                    }
                    return $default;
                })
                ->editColumn('status_kas', function($status){
                    if ($status->status_kas == 1) {
                        $status = "Aktif";
                    }
                    else{
                        $status = "Tidak Aktif";
                    }                    
                    return $status;
                })
                ->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'kode_kas', 'name' => 'kode_kas', 'title' => 'Kode Kas']) 
        ->addColumn(['data' => 'nama_kas', 'name' => 'nama_kas', 'title' => 'Nama Kas']) 
        ->addColumn(['data' => 'status_kas', 'name' => 'status_kas', 'title' => 'Status Kas'])
        ->addColumn(['data' => 'default_kas', 'name' => 'default_kas', 'title' => 'Default Kas']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        return view('kas.index')->with(compact('html'));
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
            'kode_kas'   => 'required|unique:kas,kode_kas,',
            'nama_kas'   => 'required',
            'status_kas' => 'required', 
            'default_kas'=> 'required',
        ]);

        //JIKA BUAT KAS BARU DENGAN DEFAULT KAS = "YA", TETAPI SUDAH ADA YG DEFAULT
        if ($request->default_kas == 1) {
            //UPDATE MASTER DATA KAS WARUNG, JADI TIDAK DEFAULT KAS
            $kas_default = Kas::where('default_kas',$request->default_kas)->update([
                'default_kas' => 0, 
            ]);

            //INSERT MASTER DATA KAS WARUNG, JADI DEFAULT KAS
            $warung = Kas::create([
                'kode_kas'    =>$request->kode_kas,
                'nama_kas'    =>$request->nama_kas,
                'status_kas'  =>$request->status_kas,
                'default_kas' =>$request->default_kas,
                'default_kas' =>$request->default_kas,
                'warung_id'   =>Auth::user()->id_warung
            ]);
        }
        else{
            //INSERT MASTER DATA KAS WARUNG
            $warung = Kas::create([
                'kode_kas'    =>$request->kode_kas,
                'nama_kas'    =>$request->nama_kas,
                'status_kas'  =>$request->status_kas,
                'default_kas' =>$request->default_kas,
                'default_kas' =>$request->default_kas,
                'warung_id'   =>Auth::user()->id_warung 
            ]);
        }    

        $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Berhasil Menambah Kas '.$request->nama_kas.' </b>
                </div>';


        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);
        return redirect()->route('kas.index');
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
        $kas = Kas::find($id);
        return view('kas.edit')->with(compact('kas'));
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
            'kode_kas'   => 'required|unique:kas,kode_kas,'.$id,
            'nama_kas'   => 'required',
            'status_kas' => 'required', 
            'default_kas'=> 'required',
        ]);

        //JIKA BUAT KAS BARU DENGAN DEFAULT KAS = "YA", TETAPI SUDAH ADA YG DEFAULT
        if ($request->default_kas == 1) {
            //UPDATE MASTER DATA KAS WARUNG, JADI TIDAK DEFAULT KAS
            $kas_default = Kas::where('default_kas',$request->default_kas)->update([
                'default_kas' => 0, 
            ]);

            //UPDATE MASTER DATA KAS WARUNG
            Kas::where('id', $id)->update([
                'kode_kas'      =>$request->kode_kas,
                'nama_kas'      =>$request->nama_kas,
                'status_kas'    =>$request->status_kas,
                'default_kas'   =>$request->default_kas,
            ]);

            $pesan_alert = 
                     '<div class="container-fluid">
                          <div class="alert-icon">
                          <i class="material-icons">check</i>
                          </div>
                          <b>Sukses : Berhasil Mengubah Kas "'.$request->nama_kas.'"</b>
                      </div>';

                Session::flash("flash_notification", [
                    "level"=>"success",
                    "message"=>$pesan_alert
                    ]);

                return redirect()->route('kas.index');
        }
        else{

            //JIKA KAS DEFAULT, DIUBAH MENJADI TIDAK DEFAULT (MAKA MUNCUL PERINGATAN)
            $data_kas = Kas::select('default_kas')->where('id', $id)->first();

            if ($data_kas->default_kas != 1) {
                //UPDATE MASTER DATA KAS WARUNG
                Kas::where('id', $id)->update([
                    'kode_kas'      =>$request->kode_kas,
                    'nama_kas'      =>$request->nama_kas,
                    'status_kas'    =>$request->status_kas,
                    'default_kas'   =>$request->default_kas,
                ]);

                $pesan_alert = 
                     '<div class="container-fluid">
                          <div class="alert-icon">
                          <i class="material-icons">check</i>
                          </div>
                          <b>Sukses : Berhasil Mengubah Kas "'.$request->nama_kas.'"</b>
                      </div>';

                Session::flash("flash_notification", [
                    "level"=>"success",
                    "message"=>$pesan_alert
                    ]);

                return redirect()->route('kas.index');
        
            }
            else{
                $pesan_alert = 
                 '<div class="container-fluid">
                      <div class="alert-icon">
                      <i class="material-icons">warning</i>
                      </div>
                      <b>Peringatan : Harus Ada 1 Kas Yang Menjadi Default Kas.</b>
                  </div>';

                Session::flash("flash_notification", [
                    "level"=>"warning",
                    "message"=>$pesan_alert
                ]);

                return redirect()->back();
            }         
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
        // JIKA GAGAL MENGHAPUD
        if (!Kas::destroy($id)) {
            return redirect()->back();
        }
        else{
            return redirect()->route('kas.index');
        }
        
    }
}
