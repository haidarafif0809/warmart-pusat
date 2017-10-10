<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Warung;
use App\UserWarung;
use App\BankWarung;
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
            $warung = Warung::with(['kelurahan', 'bank_warung'])->get();
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
                ->addColumn('kelurahan', function($wilayah){
                    if ($wilayah->wilayah == "" OR $wilayah->wilayah == "-") {
                        $wilayah = "-";
                    }
                    else{
                        $wilayah = $wilayah->kelurahan->nama;
                    }
                    return $wilayah;
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama']) 
        ->addColumn(['data' => 'no_telpon', 'name' => 'no_telpon', 'title' => 'No. Telpon']) 
        ->addColumn(['data' => 'bank_warung.nama_bank', 'name' => 'bank_warung.nama_bank', 'title' => 'Nama Bank']) 
        ->addColumn(['data' => 'bank_warung.atas_nama', 'name' => 'bank_warung.atas_nama', 'title' => 'Nama Rekening']) 
        ->addColumn(['data' => 'bank_warung.no_rek', 'name' => 'bank_warung.no_rek', 'title' => 'No. Rekening']) 
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat']) 
        ->addColumn(['data' => 'kelurahan', 'name' => 'kelurahan', 'title' => 'Wilayah'])  
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
            'nama_bank' => 'required',
            'atas_nama' => 'required', 
            'no_rek'    => 'required|numeric|unique:bank_warungs,no_rek,',
            'no_telpon' => 'required|without_spaces|unique:users,no_telp,',
            'email'     => 'required|without_spaces|unique:users,email,',

            ]);

    //INSERT MASTER DATA WARUNG
         $warung = Warung::create([
            'name'      =>$request->name,
            'alamat'    =>$request->alamat,
            'wilayah'   =>$request->kelurahan,
            'no_telpon' =>$request->no_telpon, 
            'email'     =>"-", 
            ]);

    //INSERT BANK WARUNG
         $bank_warung = BankWarung::create([
            'nama_bank' =>$request->nama_bank,              
            'atas_nama' => $request->atas_nama,
            'no_rek' =>$request->no_rek,
            'warung_id' =>$warung->id,
            ]);

    //INSERT USER WARUNG
         $user_warung = UserWarung::create([ 
            'name'              => $request->name,
            'email'             => $request->email, 
            'no_telp'           => $request->no_telpon, 
            'alamat'            => $request->alamat,
            'wilayah'           => $request->kelurahan,
            'id_warung'         => $warung->id,
            'tipe_user'         => 4,
            'status_konfirmasi' => 1,
            'password'          => bcrypt('123456')
            ]);

    //INSERT OTORITAS USER WARUNG
        $user_warung->attachRole(4);

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
          $warung = Warung::with(['kelurahan', 'bank_warung'])->find($id);
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

        //VALIDASI WARUNG
           $this->validate($request, [
            'name'      => 'required|unique:warungs,name,'.$id,
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telpon' => 'required',
            ]);

         //UPDATE MASTER DATA WARUNG
        $warung = Warung::where('id',$id)->update([
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'no_telpon' =>$request->no_telpon, 
        ]);

        $bank_warung_id = BankWarung::select('id')->where('warung_id', $id)->first();

        //VALIDASI BANK WARUNG
           $this->validate($request, [
            'nama_bank' => 'required',
            'atas_nama' => 'required', 
            'no_rek'    => 'required|numeric|unique:bank_warungs,no_rek,'.$bank_warung_id->id, 
            ]);

         //UPDATE BANK WARUNG
        $bank_warung = BankWarung::where('warung_id',$id)->update([
            'nama_bank' =>$request->nama_bank,
            'atas_nama' =>$request->atas_nama,
            'no_rek' =>$request->no_rek,
        ]);

        $pesan_alert = '
                <div class="container-fluid">
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
            return redirect()->back();
        }
        else{
            
        $pesan_alert = '
                <div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">check</i>
                    </div>
                        <b>Success : Berhasil Menghapus Warung </b>
                </div>';

        Session:: flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);
        return redirect()->route('warung.index');
        }
    }
}
