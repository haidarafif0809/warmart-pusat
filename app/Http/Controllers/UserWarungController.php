<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\UserWarung;
use Session;
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
        $this->middleware('user-must-admin');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            # code...
            $user_warung = UserWarung::with(['kelurahan', 'warung'])->where('tipe_user', 4)->get();
            return Datatables::of($user_warung)
            ->addColumn('action', function($user_warung){
                return view('datatable._action', [
                    'model'     => $user_warung,
                    'form_url'  => route('user_warung.destroy', $user_warung->id),
                    'edit_url'  => route('user_warung.edit', $user_warung->id),
                    'confirm_message'   => 'Yakin Mau Menghapus Warung ' . $user_warung->name . '?',
                    'permission_ubah' => Laratrust::can('edit_warung'),
                    'permission_hapus' => Laratrust::can('hapus_warung'),

                ]);
            })
            ->addColumn('kelurahan', function($wilayah){
                if ($wilayah->wilayah == NULL) {
                    $wilayah = "-";
                }
                else{
                    $wilayah = $wilayah->kelurahan->nama;
                }
                return $wilayah;
            })
            ->addColumn('email', function($email){
                if ($email->email == NULL AND $email->email == "") {
                    $email = "-";
                }
                else{
                    $email = $email->email;
                }
                return $email;
            })
            ->addColumn('warung', function($warung){
                if ($warung->warung == NULL AND $warung->warung == "") {
                    $warung = "-";
                }
                else{
                    $warung = $warung->warung->name;
                }
                return $warung;
            })
            ->addColumn('konfirmasi', function($user_konfirmasi){
                return view('user_warung._action', [
                    'model'     => $user_konfirmasi,
                    'confirm_ya'   => 'confirm-ya-'.$user_konfirmasi->id,
                    'confirm_no'   => 'confirm-no-'.$user_konfirmasi->id,
                    'confirm_message'   => 'Apakah Anda Yakin Ingin Meng Konfirmasi User Warung ' . $user_konfirmasi->name . '?',
                    'no_confirm_message'   => 'Apakah Anda Yakin Tidak Meng Konfirmasi User Warung ' . $user_konfirmasi->name . '?',
                    'konfirmasi_url' => route('user_warung.konfirmasi', $user_konfirmasi->id),
                    'no_konfirmasi_url' => route('user_warung.no_konfirmasi', $user_konfirmasi->id),
                    'konfirmasi_user' => Laratrust::can('konfirmasi_user'), 
                ]);
                })//Konfirmasi User Warung Apabila Bila Status User Warung 1 Maka User Warung sudah di konfirmasi oleh admin dan apabila status user 0 maka user belum di konfirmasi oleh admin
            ->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email']) 
        ->addColumn(['data' => 'no_telp', 'name' => 'no_telp', 'title' => 'No. Telpon']) 
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama']) 
        ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat']) 
        ->addColumn(['data' => 'kelurahan', 'name' => 'kelurahan', 'title' => 'Wilayah'])  
        ->addColumn(['data' => 'warung', 'name' => 'warung', 'title' => 'Warung', 'orderable' => false, 'searchable'=>false])  
        ->addColumn(['data' => 'konfirmasi', 'name' => 'konfirmasi', 'title' => 'Konfirmasi', 'searchable'=>false])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        return view('user_warung.index')->with(compact('html'));
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
        $user_warung = UserWarung::with(['kelurahan', 'warung'])->find($id);
        return view('user_warung.edit')->with(compact('user_warung'));
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
        'name'      => 'required',
        'alamat'    => 'required',
        'kelurahan' => 'required', 
        'email'     => 'required|without_spaces|unique:users,email,'.$id,
        'no_telp'   => 'required|without_spaces|unique:users,no_telp,'.$id,
    ]);

         //UPDATE USER WARUNG
       $user_warung = UserWarung::where('id',$id)->update([
        'name'      => $request->name,
        'email'     => $request->email, 
        'no_telp'     => $request->no_telp, 
        'alamat'    => $request->alamat,
        'wilayah'   => $request->kelurahan,
        'id_warung' => $request->id_warung,
    ]);

       $pesan_alert = '
       <div class="container-fluid">
       <div class="alert-icon">
       <i class="material-icons">check</i>
       </div>
       <b>Sukses : Berhasil Mengubah User Warung '.$request->name.' </b>
       </div>';

       Session::flash("flash_notification", [
        "level"=>"success",
        "message"=> $pesan_alert
    ]);

       return redirect()->route('user_warung.index');
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
        $user_warung = UserWarung::select('id_warung')->where('id',$id)->first();
        $warung_user = UserWarung::where('id_warung', $user_warung->id)->count();

        if ($warung_user > 1) {

            if (!UserWarung::destroy($id)) {
                return redirect()->back();
            }
            else{
                $pesan_alert = '
                <div class="container-fluid">
                <div class="alert-icon">
                <i class="material-icons">check</i>
                </div>
                <b>Sukses : Berhasil Menghapus User Warung </b>
                </div>';

                Session:: flash("flash_notification", [
                    "level"=>"success",
                    "message"=> $pesan_alert
                ]);

                return redirect()->route('user_warung.index');
            }
        }else{
            $pesan_alert = '
            <div class="container-fluid">
            <div class="alert-icon">
            <i class="material-icons">check</i>
            </div>
            <b>Gagal : User Warung Tidak Bisa Dihapus </b>
            </div>';

            Session:: flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
            ]);

            return redirect()->route('user_warung.index');
        }

    }

    public function konfirmasi($id){
        // konfirmasi komunitas
        $username = UserWarung::select('name')->where('id',$id)->first();
        $user_warung = UserWarung::where('id',$id)->update(['konfirmasi_admin' => '1']);

        $pesan_alert = '
        <div class="container-fluid">
        <div class="alert-icon">
        <i class="material-icons">check</i>
        </div>
        <b>Sukses : User Warung '. $username->name .' Berhasil Di Konfirmasi </b>
        </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
        ]);

        return redirect()->route('user_warung.index');

    }

    public function no_konfirmasi($id){
        // no_konfirmasi user_warung
        $username = UserWarung::select('name')->where('id',$id)->first();
        $user_warung = UserWarung::where('id',$id)->update(['konfirmasi_admin' => '0']);

        $pesan_alert = '
        <div class="container-fluid">
        <div class="alert-icon">
        <i class="material-icons">check</i>
        </div>
        <b>Sukses : User Warung '. $username->name .' Tidak Di Konfirmasi </b>
        </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
        ]);

        return redirect()->route('user_warung.index');
    }
}
