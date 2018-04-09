<?php

namespace App\Http\Controllers;

use App\BankWarung;
use App\SettingTransferBank;
use Auth;
use Illuminate\Http\Request;
use Laratrust;

class BankWarungController extends Controller
{

    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function queryBank()
    {
        $data_bank = BankWarung::select(['bank_warungs.atas_nama', 'bank_warungs.nama_tampil', 'bank_warungs.no_rek', 'bank_warungs.id', 'setting_transfer_banks.nama_bank'])
        ->leftJoin('setting_transfer_banks', 'setting_transfer_banks.id', '=', 'bank_warungs.nama_bank')
        ->where('bank_warungs.warung_id', Auth::user()->id_warung);
        return $data_bank;
    }

    public function view()
    {
        $data_bank = $this->queryBank()->paginate(10);
        $otoritas = $this->otoritasBank();
        return response()->json([
            "bank" => $data_bank,
            "otoritas"     => $otoritas,
            ]);
    }

    public function pencarian(Request $request)
    {
        $data_bank = $this->queryBank()
        ->where(function ($query) use ($request) {
            $query->orwhere('bank_warungs.atas_nama', 'LIKE', '%' . $request->search . '%')
            ->orwhere('bank_warungs.no_rek', 'LIKE', '%' . $request->search . '%')
            ->orwhere('bank_warungs.nama_tampil', 'LIKE', '%' . $request->search . '%')
            ->orwhere('setting_transfer_banks.nama_bank', 'LIKE', '%' . $request->search . '%');
        })->paginate(10);
        $otoritas = $this->otoritasBank();
        return response()->json([
            "bank" => $data_bank,
            "otoritas"     => $otoritas,
            ]);
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
            'nama_tampil' => 'required',
            'atas_nama' => 'required',
            'no_rek'    => 'required|unique:bank_warungs,no_rek,',
            ]);

        $master_bank = BankWarung::create([
            'nama_bank' => $request->nama_bank,
            'nama_tampil' => $request->nama_tampil,
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
            'nama_tampil' => 'required',
            'atas_nama' => 'required',
            'no_rek'    => 'required|unique:bank_warungs,no_rek,' . $id,
            ]);

        BankWarung::where('id', $id)->update([
            'nama_bank' => $request->nama_bank,
            'nama_tampil' => $request->nama_tampil,
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



    public function otoritasBank(){

        if (Laratrust::can('tambah_bank')) {
            $tambah_bank = 1;
        }else{
            $tambah_bank = 0;            
        }
        if (Laratrust::can('edit_bank')) {
            $edit_bank = 1;
        }else{
            $edit_bank = 0;            
        }
        if (Laratrust::can('hapus_bank')) {
            $hapus_bank = 1;
        }else{
            $hapus_bank = 0;            
        }
        $respons['tambah_bank'] = $tambah_bank;
        $respons['edit_bank'] = $edit_bank;
        $respons['hapus_bank'] = $hapus_bank;

        return response()->json($respons);
    }
}
