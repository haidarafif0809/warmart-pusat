<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB; 
use Session;
use Laratrust;
use App\Bank;
use Auth;
class BankController extends Controller
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


    //MENAMPILKAN DATA YG ADA DI ITEM KELUAR
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $master_bank = Bank::select(['id','nama_bank', 'atas_nama', 'no_rek']);
            return Datatables::of($master_bank)->addColumn('action', function($bank){
                    return view('datatable._action', [
                        'model'     => $bank,
                        'form_url'  => route('bank.destroy', $bank->id),
                        'edit_url'  => route('bank.edit', $bank->id),
                        'confirm_message'   => 'Anda Yakin Ingin Menghapus Bank ' .$bank->nama_bank . ' ?',
                        'permission_ubah' => Laratrust::can('edit_bank'),
                        'permission_hapus' => Laratrust::can('hapus_bank'),

                        ]);
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_bank', 'name' => 'nama_bank', 'title' => 'Nama Bank']) 
        ->addColumn(['data' => 'atas_nama', 'name' => 'atas_nama', 'title' => 'Nama Rekening']) 
        ->addColumn(['data' => 'no_rek', 'name' => 'no_rek', 'title' => 'No. Rekening']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('bank.index')->with(compact('html'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank.create');
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
            'nama_bank'     => 'required',
            'atas_nama'     => 'required',
            'no_rek'        => 'required|numeric|unique:banks,no_rek,',
            'tampil_customer'     => 'required',
        ]);
        
        $pesan_alert = 
             '<div class="container-fluid">
                  <div class="alert-icon">
                  <i class="material-icons">check</i>
                  </div>
                  <b>Sukses : Berhasil Menambah Bank "'.$request->nama_bank.'"</b>
              </div>';

            $master_bank = Bank::create([
                'nama_bank' =>$request->nama_bank,              
                'atas_nama' => $request->atas_nama,
                'no_rek' =>$request->no_rek,
                'tampil_customer' =>$request->tampil_customer,
            ]);

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
            ]);
            
            return redirect()->route('bank.index');
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
        $bank = Bank::find($id);
        return view('bank.edit')->with(compact('bank'));
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
            'nama_bank'     => 'required',
            'atas_nama'     => 'required',
            'no_rek'        => 'required|numeric|unique:banks,no_rek,'. $id,
            'tampil_customer'     => 'required',
        ]);

        Bank::where('id', $id)->update([
                'nama_bank' =>$request->nama_bank,
                'atas_nama' =>$request->atas_nama,
                'no_rek'    =>$request->no_rek,
                'tampil_customer'    =>$request->tampil_customer,
            ]);

        $pesan_alert = 
             '<div class="container-fluid">
                  <div class="alert-icon">
                  <i class="material-icons">check</i>
                  </div>
                  <b>Sukses : Berhasil Mengubah Bank "'.$request->nama_bank.'"</b>
              </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);

        return redirect()->route('bank.index');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      

      $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Bank Berhasil Dihapus</b>
                </div>';

        Bank::destroy($id);  

        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=> $pesan_alert
            ]);


        return redirect()->route('bank.index');
    }

}// END CLASS BankController
