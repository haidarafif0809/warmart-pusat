<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB; 
use Session;
use Laratrust;
use App\KategoriTransaksi;
use Auth;

class KategoriTransaksiController extends Controller
{
  public function __construct()
    {
        $this->middleware('user-must-warung');
    }

  public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {

            $data_kategori_transaksi = KategoriTransaksi::select(['id','nama_kategori_transaksi','id_warung'])->where('id_warung',Auth::user()->id_warung)->get();
            return Datatables::of($data_kategori_transaksi)
                
                ->addColumn('action', function($kategori_transaksi){
                    return view('datatable._action', [
                        'model'             => $kategori_transaksi,
                        'form_url'          => route('kategori_transaksi.destroy', $kategori_transaksi->id),
                        'edit_url'          => route('kategori_transaksi.edit', $kategori_transaksi->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Kategori Transaksi ' . $kategori_transaksi->name . '?'
                   
                        ]); 
                })->make(true);
        }

        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_kategori_transaksi', 'name' => 'nama_kategori_transaksi', 'title' => 'Nama'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable'=>false]);

        return view('kategori_transaksi.index')->with(compact('html'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori_transaksi.create');
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
            'nama_kategori_transaksi' => 'required|unique:kategori_transaksis,nama_kategori_transaksi',
        ]);

            if (Auth::user()->id_warung != "") {
            $kategori_transaksi = KategoriTransaksi::create([
                'nama_kategori_transaksi' =>$request->nama_kategori_transaksi,
                'id_warung' =>Auth::user()->id_warung]); 
            }else{
                  Auth::logout();
                return response()->view('error.403');
            }

        $pesan_alert = 
        '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Menambah Kategori Transaksi "'.$request->nama_kategori_transaksi.'"</b>
         </div>';

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
         ]);

        return redirect()->route('kategori_transaksi.index');
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
        $id_warung = Auth::user()->id_warung;
        $kategori_transaksi = KategoriTransaksi::find($id);

            if ($id_warung == $kategori_transaksi->id_warung) {
                return view('kategori_transaksi.edit')->with(compact('kategori_transaksi')); 
            }else{
                  Auth::logout();
                return response()->view('error.403');
            }
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
            'nama_kategori_transaksi'     => 'required|unique:kategori_transaksis,nama_kategori_transaksi,'.$id,
        ]);

        $kategori_transaksi = KategoriTransaksi::find($id);

        if ($id_warung == $kategori_transaksi->id_warung) {
            $kategori_transaksi = KategoriTransaksi::find($id)->update([
                'nama_kategori_transaksi'  => $request->nama_kategori_transaksi
            ]);
        }else{
              Auth::logout();
                return response()->view('error.403'); 
        }

        $pesan_alert = 
        '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Mengubah Kategori Transaksi "'.$request->nama_kategori_transaksi.'"</b>
         </div>';

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
         ]);

        return redirect()->route('kategori_transaksi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $id_warung = Auth::user()->id_warung;
        $kategori_transaksi = KategoriTransaksi::find($id);

        if ($id_warung == $kategori_transaksi->id_warung) {
            // JIKA GAGAL MENGHAPUD
            if (!KategoriTransaksi::destroy($id)) {
                return redirect()->back();
            }
            else{
                return redirect()->route('kategori_transaksi.index');
            }
        }else{
              Auth::logout();
                return response()->view('error.403'); 
        }
    }
}
