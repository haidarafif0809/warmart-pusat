<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Komunitas;
use App\BankKomunitas;
use App\Kelurahan;
use App\KomunitasPenggiat;
use App\Warung;
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

  public function view (){

    $komunitas = Komunitas::with(['kelurahan','warung'])->where('tipe_user',2)->orderBy('id','desc')->paginate(10);
    $komunitas_array = array();
    foreach ($komunitas as $comunitas) {

     if ($comunitas->id_warung == "") {
       $warung = "-";
     }else{
      $warung = $comunitas->warung->name;
    }


    if ($comunitas->wilayah == "") {
      $wilayah =  "-";
    }else{
      $wilayah = $comunitas->kelurahan->nama;
    }
    array_push($komunitas_array,[
      'id' => $comunitas->id,
      'no_telp' => $comunitas->no_telp,
      'nama_komunitas'    => $comunitas->name,
      'alamat_komunitas'  => $comunitas->alamat,
      'warung'            => $warung,
      'email'             =>  $comunitas->email,
      'wilayah'             =>  $wilayah,
      'link_afiliasi' => $comunitas->link_afiliasi,
      'konfirmasi_admin' => $comunitas->konfirmasi_admin,]);
  }
                  //DATA PAGINATION 
  $respons['current_page'] = $komunitas->currentPage();
  $respons['data'] = $komunitas_array; 
  $respons['first_page_url'] = url('/komunitas/view?page='.$komunitas->firstItem());
  $respons['from'] = 1;
  $respons['last_page'] = $komunitas->lastPage();
  $respons['last_page_url'] = url('/komunitas/view?page='.$komunitas->lastPage());
  $respons['next_page_url'] = $komunitas->nextPageUrl();
  $respons['path'] = url('/komunitas/view');
  $respons['per_page'] = $komunitas->perPage();
  $respons['prev_page_url'] = $komunitas->previousPageUrl();
  $respons['to'] = $komunitas->perPage();
  $respons['total'] = $komunitas->total();
                  //DATA PAGINATION 
  return response()->json($respons);  
}

public function pencarian(Request $request){
  $search = $request->search;
  $komunitas = Komunitas::with(['kelurahan','warung'])->where('tipe_user',2)
  ->where(function($query) use ($search){
    $query->orWhere('no_telp','LIKE',$search.'%')
    ->orWhere('name','LIKE',$search.'%')
    ->orWhere('alamat','LIKE',$search.'%')
    ->orWhere('email','LIKE',$search.'%');
  })->orderBy('id','desc')->paginate(10);
  $komunitas_array = array();
  foreach ($komunitas as $comunitas) {

   if ($comunitas->id_warung == "") {
     $warung = "-";
   }else{
    $warung = $comunitas->warung->name;
  }


  if ($comunitas->wilayah == "") {
    $wilayah =  "-";
  }else{
    $wilayah = $comunitas->kelurahan->nama;
  }
  array_push($komunitas_array,[
    'id' => $comunitas->id,
    'no_telp' => $comunitas->no_telp,
    'nama_komunitas'    => $comunitas->name,
    'alamat_komunitas'  => $comunitas->alamat,
    'warung'            => $warung,
    'email'             =>  $comunitas->email,
    'wilayah'             =>  $wilayah,
    'link_afiliasi' => $comunitas->link_afiliasi,
    'konfirmasi_admin' => $comunitas->konfirmasi_admin]);
}
                  //DATA PAGINATION 
$respons['current_page'] = $komunitas->currentPage();
$respons['data'] = $komunitas_array; 
$respons['first_page_url'] = url('/komunitas/view?page='.$komunitas->firstItem());
$respons['from'] = 1;
$respons['last_page'] = $komunitas->lastPage();
$respons['last_page_url'] = url('/komunitas/view?page='.$komunitas->lastPage());
$respons['next_page_url'] = $komunitas->nextPageUrl();
$respons['path'] = url('/komunitas/view');
$respons['per_page'] = $komunitas->perPage();
$respons['prev_page_url'] = $komunitas->previousPageUrl();
$respons['to'] = $komunitas->perPage();
$respons['total'] = $komunitas->total();
                  //DATA PAGINATION 

return response()->json($respons);  

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
      'no_telp'   => 'required|numeric|without_spaces|unique:users,no_telp,',
      'nama_bank' => 'required',
      'no_rekening' => 'required|numeric|unique:bank_komunitas,no_rek,',
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
        //end masukan data bank komunitas

    $komunitas->attachRole(4);

  }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

      $komunitas = Komunitas::with(['komunitas_penggiat','bank_komunitas'])->find($id);
      $komunitas['id_bank'] = $komunitas->bank_komunitas->id;
      $komunitas['nama_bank'] = $komunitas->bank_komunitas->nama_bank;
      $komunitas['no_rekening'] = $komunitas->bank_komunitas->no_rek;
      $komunitas['an_rekening'] = $komunitas->bank_komunitas->atas_nama;
      $komunitas['name_penggiat'] = $komunitas->komunitas_penggiat->nama_penggiat;
      $komunitas['alamat_penggiat'] = $komunitas->komunitas_penggiat->alamat_penggiat;
      $komunitas['kelurahan'] = $komunitas->wilayah;

      return $komunitas;

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
     return $komunitas = Komunitas::with(['komunitas_penggiat','bank_komunitas'])->find($id);
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
        'no_rekening' => 'required|numeric|unique:bank_komunitas,no_rek,'. $request->id_bank,
        'name_penggiat' => 'required',
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

         //masukan data bank komunitas
      if ($request->nama_bank != "" AND $request->no_rekening != "" AND $request->an_rekening != "" ){
        $bankkomunitas = BankKomunitas::where('komunitas_id',$id)->update([
          'nama_bank' =>$request->nama_bank,
          'no_rek'  =>$request->no_rekening,
          'atas_nama'=>$request->an_rekening              
        ]);
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

      Komunitas::destroy($id);
      KomunitasPenggiat::where('komunitas_id',$id)->delete();
      BankKomunitas::where('komunitas_id',$id)->delete();

      return response(200);


    }

    public function konfirmasi(Request $request){
        // konfirmasi komunitas

      $user_komunitas = Komunitas::where('id',$request->confirm)->update(['konfirmasi_admin' => '1']);

    }

    public function no_konfirmasi(Request $request){
        // no_konfirmasi komunitas

      $user_komunitas = Komunitas::where('id',$request->confirm)->update(['konfirmasi_admin' => '0']);

    }
    public function warungKomunitas(){
      $warung = Warung::all();
      return response()->json($warung);
    }
    public function keluarahanKomunitas(){
      $kelurahan = Kelurahan::all();
      return response()->json($kelurahan);
    }

  }
