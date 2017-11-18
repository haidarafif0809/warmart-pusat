<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Warung;
use App\UserWarung;
use App\BankWarung;
use App\Kelurahan;
use Session;
use Laratrust;

class WarungController extends Controller
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
        return view('warung.index')->with(compact('html'));
    }


     public function view(){
            $warung = Warung::leftJoin('kelurahans','warungs.wilayah','=','kelurahans.id')
            ->leftJoin('bank_warungs','warungs.id','=','bank_warungs.warung_id')
            ->paginate(10);
            return response()->json($warung);  
    }

    public function pencarian(Request $request){

        $warung = Warung::leftJoin('kelurahans','warungs.wilayah','=','kelurahans.id')
        ->leftJoin('bank_warungs','warungs.id','=','bank_warungs.warung_id')
        ->where('name','LIKE',"%$request->search%")
        ->orWhere('alamat','LIKE',"%$request->search%")
        ->orWhere('no_telpon','LIKE',"%$request->search%")
        ->orWhere('bank_warungs.nama_bank','LIKE',"%$request->search%")
        ->orWhere('bank_warungs.atas_nama','LIKE',"%$request->search%")
        ->orWhere('bank_warungs.no_rek','LIKE',"%$request->search%")
        ->orWhere('kelurahans.nama','LIKE',"%$request->search%")
        ->paginate(10);

        return response()->json($warung);
    }

    public function pilih_kelurahan(){
        $kelurahan = Kelurahan::select('id','nama')->get();
        return response()->json($kelurahan);
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
            'no_telpon' => 'required|without_spaces|max:15|unique:users,no_telp,',
            'email'     => 'required|without_spaces|unique:users,email,',

            ]);

    //INSERT MASTER DATA WARUNG
         $warung = Warung::create([
            'name'      =>$request->name,
            'alamat'    =>$request->alamat,
            'wilayah'   =>$request->kelurahan,
            'no_telpon' =>$request->no_telpon, 
            'email'     =>$request->email, 
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
         $warung = Warung::with(['bank_warung'])->find($id);
         $warung['kelurahan'] =  $warung->wilayah;
         $warung['nama_bank'] = $warung->bank_warung->nama_bank;
         $warung['atas_nama'] = $warung->bank_warung->atas_nama;
         $warung['no_rek'] = $warung->bank_warung->no_rek;

        return $warung;
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
        return url('warung#/edit_warung/'.$id);
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
            'no_telpon' => 'required|max:15',
            ]);

         //UPDATE MASTER DATA WARUNG
        $warung = Warung::where('id',$id)->update([
            'name' =>$request->name,
            'alamat' =>$request->alamat,
            'wilayah' =>$request->kelurahan,
            'no_telpon' =>$request->no_telpon, 
            'email' =>$request->email,
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
            $warung = Warung::destroy($id);
            return response(200);
    }
}
