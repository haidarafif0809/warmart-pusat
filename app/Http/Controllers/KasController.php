<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Kas;
use Auth;
use Session;
use Laratrust;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
     //index KAS
         if ($request->ajax()) {
            # code...
            $kas = Kas::with(['user_buat','user_edit']);
            return Datatables::of($kas)
            ->addColumn('action', function($kas){
                    return view('datatable._action', [
                        'model'     => $kas,
                        'form_url'  => route('kas.destroy', $kas->id),
                        'edit_url'  => route('kas.edit', $kas->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Kas ' . $kas->nama_kas . '?'
                   
                        ]);
                });
      
            }
            $html = $htmlBuilder
            ->addColumn(['data' => 'kode_kas', 'name' => 'kode_kas', 'title' => 'Kode'])
            ->addColumn(['data' => 'nama_kas', 'name' => 'nama_kas', 'title' => 'Nama'])  
            ->addColumn(['data' => 'status_kas', 'name' => 'status_kas', 'title' => 'Status', 'orderable' => false])
            ->addColumn(['data' => 'default_kas', 'name' => 'default_kas', 'title' => 'Default', 'orderable' => false, 'searchable'=>false])
            ->addColumn(['data' => 'user_buat.name', 'name' => 'user_buat.name', 'title' => 'User Buat', 'orderable' => false, 'searchable'=>false])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'order  able' => false, 'searchable'=>false]);

        return view('kas.index')->with(compact('html'));
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
        //
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
}
