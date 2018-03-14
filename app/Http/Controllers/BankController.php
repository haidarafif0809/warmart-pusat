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
        return view('bank.index')->with(compact('html'));
    }


    public function view(){
        $bank = Bank::paginate(10);
        return response()->json($bank);
    }

    public function pencarian(Request $request){

        $bank = Bank::where('nama_bank','LIKE',"%$request->search%")->orWhere('atas_nama','LIKE',"%$request->search%")->orWhere('no_rek','LIKE',"%$request->search%")->paginate(10);
        return response()->json($bank);
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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        return Bank::find($id);
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
            'no_rek'        => 'required|unique:banks,no_rek,'. $id,
        ]);

        Bank::where('id', $id)->update([
            'nama_bank' =>$request->nama_bank,
            'atas_nama' =>$request->atas_nama,
            'no_rek'    =>$request->no_rek,
            'tampil_customer'    =>$request->tampil_customer,
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
       $bank =  Bank::destroy($id);  
       return response(200);
   }

}// END CLASS BankController
