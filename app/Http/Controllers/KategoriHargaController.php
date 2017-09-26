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
        return view('kategori_harga.create');
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
            'nama_kategori_harga'     => 'required|unique:kategori_hargas,nama_kategori_harga,', 
        ]);
        
        $pesan_alert = 
             '<div class="container-fluid">
                  <div class="alert-icon">
                  <i class="material-icons">check</i>
                  </div>
                  <b>Sukses : Berhasil Menambah Kategori Harga "'.$request->nama_kategori_harga.'"</b>
              </div>';

            $kategori_harga = KategoriHarga::create([
                'nama_kategori_harga' =>$request->nama_kategori_harga, 
            ]);

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
            ]);
            
            return redirect()->route('kategori-harga.index');
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
        $kategori_harga = KategoriHarga::find($id);
        return view('kategori_harga.edit')->with(compact('kategori_harga'));
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
            'nama_kategori_harga'     => 'required|unique:kategori_hargas,nama_kategori_harga,'. $id, 
        ]);

        KategoriHarga::where('id', $id)->update([
                'nama_kategori_harga' =>$request->nama_kategori_harga, 
            ]);

        $pesan_alert = 
             '<div class="container-fluid">
                  <div class="alert-icon">
                  <i class="material-icons">check</i>
                  </div>
                  <b>Sukses : Berhasil Mengubah Kategori Harga "'.$request->nama_kategori_harga.'"</b>
              </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);

        return redirect()->route('kategori-harga.index');    
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
                    <b>Sukses : Kategori Harga Berhasil Dihapus</b>
                </div>';

        KategoriHarga::destroy($id);  

        Session:: flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
            ]);


        return redirect()->route('kategori-harga.index');
    }
}
