<?php

namespace App\Http\Controllers;

use App\BankWarung;
use App\SettingTransferBank;
use Auth;
use Illuminate\Http\Request;

class BankWarungController extends Controller
{

    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index()
    {
        //
    }

    public function view()
    {
        $data_bank = BankWarung::where('warung_id', Auth::user()->id_warung)->paginate(10);
        return response()->json($data_bank);
    }

    public function pencarian(Request $request)
    {

        $data_bank = BankWarung::where('nama_bank', 'LIKE', "%$request->search%")->orWhere('atas_nama', 'LIKE', "%$request->search%")->orWhere('no_rek', 'LIKE', "%$request->search%")->paginate(10);
        return response()->json($data_bank);
    }

    public function dataBank()
    {
        $bank_transfer = SettingTransferBank::select(['id', 'nama_bank'])->where('tampil_bank', 1)->get();
        return response()->json($bank_transfer);
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_bank' => 'required',
            'atas_nama' => 'required',
            'no_rek'    => 'required|unique:bank_warungs,no_rek,',
        ]);

        $master_bank = BankWarung::create([
            'nama_bank' => $request->nama_bank,
            'atas_nama' => $request->atas_nama,
            'no_rek'    => $request->no_rek,
            'warung_id' => Auth::user()->id_warung,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return BankWarung::find($id);
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
        $this->validate($request, [
            'nama_bank' => 'required',
            'atas_nama' => 'required',
            'no_rek'    => 'required|unique:bank_warungs,no_rek,' . $id,
        ]);

        BankWarung::where('id', $id)->update([
            'nama_bank' => $request->nama_bank,
            'atas_nama' => $request->atas_nama,
            'no_rek'    => $request->no_rek,
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
        $bank = BankWarung::destroy($id);
        return response(200);
    }
}
