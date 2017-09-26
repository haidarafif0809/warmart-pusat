<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB; 
use Session;
use Laratrust;
use App\KategoriHarga;

class KategoriHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //MENAMPILKAN DATA YG ADA DI ITEM KELUAR
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $master_bank = KategoriHarga::select(['id','nama_kategori_harga']);
            return Datatables::of($master_bank)->addColumn('action', function($kategori_harga){
                    return view('datatable._action', [
                        'model'     => $kategori_harga,
                        'form_url'  => route('kategori-harga.destroy', $kategori_harga->id),
                        'edit_url'  => route('kategori-harga.edit', $kategori_harga->id),
                        'confirm_message'   => 'Anda Yakin Ingin Menghapus Kategori Harga ' .$kategori_harga->nama_kategori_harga . ' ?',
                        'permission_ubah' => Laratrust::can('edit_satuan'),
                        'permission_hapus' => Laratrust::can('hapus_satuan'),

                        ]);
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_kategori_harga', 'name' => 'nama_kategori_harga', 'title' => 'Nama Kategori Harga'])  
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('kategori_harga.index')->with(compact('html'));

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
