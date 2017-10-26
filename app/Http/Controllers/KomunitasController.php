<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Komunitas;
use App\BankKomunitas;
use App\KomunitasPenggiat;
use Session;
use Laratrust;

class KomunitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('user-must-admin');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
         //
        if ($request->ajax()) {
            # code...
            $komunitas = Komunitas::with(['kelurahan','role','warung','komunitas_penggiat','bank_komunitas'])->where('tipe_user',2)->get();
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

                })->addColumn('link', function($link){ 

                    return $link->link_afiliasi;

                })
                ->addColumn('warung', function($warung){ 

                   if ($warung->id_warung == "") {
                        return "-";
                   }else{
                        return $warung->warung->name;
                   }

                })->addColumn('kelurahan', function($kelurahan){ 

                   if ($kelurahan->wilayah == "") {
                        return "-";
                   }else{
                        return $kelurahan->kelurahan->nama;
                   }

                })
                ->addColumn('konfirmasi', function($user_konfirmasi){
                    return view('komunitas._action', [
                        'model'     => $user_konfirmasi,
                        'confirm_ya'   => 'confirm-ya-'.$user_konfirmasi->id,
                        'confirm_no'   => 'confirm-no-'.$user_konfirmasi->id,
                        'confirm_message'   => 'Apakah Anda Yakin Ingin Meng Konfirmasi Komunitas ' . $user_konfirmasi->name . '?',
                        'no_confirm_message'   => 'Apakah Anda Yakin Tidak Meng Konfirmasi Komunitas ' . $user_konfirmasi->name . '?',
                        'konfirmasi_url' => route('komunitas.konfirmasi', $user_konfirmasi->id),
                        'no_konfirmasi_url' => route('komunitas.no_konfirmasi', $user_konfirmasi->id),
                        'konfirmasi_user' => Laratrust::can('konfirmasi_user'), 
                        ]);
                })//Konfirmasi Komunitas Apabila Bila Status Komunitas 1 Maka Komunitas sudah di konfirmasi oleh admin dan apabila status user 0 maka user belum di konfirmasi oleh admin
                ->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'no_telp', 'name' => 'no_telp', 'title' => 'No Telp']) 
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Komunitas'])
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat Komunitas'])
        ->addColumn(['data' => 'warung', 'name' => 'warung', 'title' => 'Warung'])  
        ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])  
        ->addColumn(['data' => 'kelurahan', 'name' => 'kelurahan', 'title' => 'Wilayah']) 
        ->addColumn(['data' => 'link', 'name' => 'link', 'title' => 'Link Afiliasi']) 
        ->addColumn(['data' => 'konfirmasi', 'name' => 'konfirmasi', 'title' => 'Konfirmasi', 'searchable'=>false])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '','orderable' => false, 'searchable'=>false]);
        
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
            'email'     => 'required|without_spaces|unique:users,email,',
            'name'      => 'required|unique:users,name,',
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|without_spaces|unique:users,no_telp,',
            'nama_bank' => 'required',
            'no_rekening' => 'required|unique:bank_komunitas,no_rek,',
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
            'id_warung' =>$request->id_warung,
            'tipe_user'=> 2,
            'status_konfirmasi'=>0
            ]);

         //masukan data komunitas komunitas
         if ($request->name_penggiat != "" AND $request->alamat_penggiat != ""){
            $komunitaspenggiat = KomunitasPenggiat::create([
            'nama_penggiat' =>$request->name_penggiat,
            'alamat_penggiat'  =>$request->alamat_penggiat,
            'komunitas_id'=>$komunitas->id      
            ]);
         }
         else{
            
         }
        //end masukan data komunitas komunitas

        //masukan data bank komunitas
         if ($request->nama_bank != "" AND $request->no_rekening != "" AND $request->an_rekening != "" ){
            $bankkomunitas = BankKomunitas::create([
            'nama_bank' =>$request->nama_bank,
            'no_rek'  =>$request->no_rekening,
            'atas_nama'=>$request->an_rekening,              
            'komunitas_id'=>$komunitas->id      
            ]);
         }
         else{
            
         }
        //end masukan data bank komunitas


        $komunitas->attachRole(4);

          $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Berhasil : Menambah Komunitas '.$request->name.' </b>
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


          $komunitas = Komunitas::with(['kelurahan','warung','komunitas_penggiat','bank_komunitas'])->find($id);
            return view('komunitas.edit')->with(compact('komunitas'));
    }



    public function detail_lihat_komunitas($id)
    {
          $komunitas = Komunitas::with(['kelurahan','warung','komunitas_penggiat','bank_komunitas'])->find($id);
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
            'email'     => 'required|without_spaces|unique:users,email,'. $id,
            'name'      => 'required|unique:users,name,'. $id,
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|without_spaces|unique:users,no_telp,'. $id,
            'nama_bank' => 'required',
            'no_rekening' => 'required|unique:bank_komunitas,no_rek,'. $id,
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
            'id_warung' =>$request->id_warung,
        ]);

        if ($request->name_penggiat != "" AND $request->alamat_penggiat != ""){
            $komunitaspenggiat = KomunitasPenggiat::where('komunitas_id',$id)->update([
            'nama_penggiat' =>$request->name_penggiat,
            'alamat_penggiat'  =>$request->alamat_penggiat  
            ]);
         }
         else{
            
         }

         //masukan data bank komunitas
         if ($request->nama_bank != "" AND $request->no_rekening != "" AND $request->an_rekening != "" ){
            $bankkomunitas = BankKomunitas::where('komunitas_id',$id)->update([
            'nama_bank' =>$request->nama_bank,
            'no_rek'  =>$request->no_rekening,
            'atas_nama'=>$request->an_rekening              
            ]);
         }
         else{
            
         }
        //end masukan data bank komunitas

        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Berhasil : Mengubah Komunitas '.$request->name.' </b>
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
            
             KomunitasPenggiat::where('komunitas_id',$id)->delete();
           BankKomunitas::where('komunitas_id',$id)->delete();


        $pesan_alert = '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Berhasil : Menghapus Komunitas </b>
                </div>';

        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);
        return redirect()->route('komunitas.index');
        }

    }

    public function konfirmasi($id){
        // konfirmasi komunitas
        $username = Komunitas::select('name')->where('id',$id)->first();
        $user_komunitas = Komunitas::where('id',$id)->update(['konfirmasi_admin' => '1']);

        $pesan_alert = '
            <div class="container-fluid">
                <div class="alert-icon">
                    <i class="material-icons">check</i>
                </div>
                    <b>Sukses : Komunitas '. $username->name .' Berhasil Di Konfirmasi </b>
            </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);
 
        return redirect()->route('komunitas.index');

    }

    public function no_konfirmasi($id){
        // no_konfirmasi komunitas
        $username = Komunitas::select('name')->where('id',$id)->first();
        $user_komunitas = Komunitas::where('id',$id)->update(['konfirmasi_admin' => '0']);

        $pesan_alert = '
            <div class="container-fluid">
                <div class="alert-icon">
                    <i class="material-icons">check</i>
                </div>
                    <b>Sukses : Komunitas '. $username->name .' Tidak Di Konfirmasi </b>
            </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);
 
        return redirect()->route('komunitas.index');
    }

}
