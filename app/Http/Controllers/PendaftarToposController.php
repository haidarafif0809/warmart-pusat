<?php

namespace App\Http\Controllers;

use App\PendaftarTopos;
use App\Warung;
use App\Bank;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class PendaftarToposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name'    => 'required',
            'no_telpon' => 'required|without_spaces|unique:pendaftar_topos,no_telp,',
            'email'   => 'nullable|unique:users,email',
            'alamat'  => 'required',
            'lama_berlangganan' => 'required',
            'berlaku_hingga' => 'required',
            'pembayaran' => 'required',
            'total' => 'required',
            'tujuan_transfer' => 'required',
            'no_rek_transfer' => 'required',
            'atas_nama' => 'required'
        ]);

        $bank     = explode("|", $request->tujuan_transfer);
        $bank_id  = $bank[0];
        
        $pendaftar_topos = PendaftarTopos::create([
            'name'      => $request->name,
            'no_telp'   => $request->no_telpon,
            'email'     => $request->email,
            'lama_berlangganan'    => $request->lama_berlangganan,
            'berlaku_hingga'    => $request->berlaku_hingga,
            'jenis_pembayaran'    => $request->pembayaran,
            'total'    => $request->total,
            'bank_id'    => $bank_id,
            'no_rekening'    => $request->no_rek_transfer,
            'atas_nama'    => $request->atas_nama,
            'warung_id'    => Auth::user()->id_warung

        ]);

        return response(200);
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
    }


    public function dataWarung(){

     return Warung::find(Auth::user()->id_warung);
 }
 public function dataBank(){
    $bank = Bank::all();
    return response()->json($bank);
}
}
