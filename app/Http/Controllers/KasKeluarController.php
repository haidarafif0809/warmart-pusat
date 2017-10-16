<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\KasKeluar;
use Session;
use App\TransaksiKas;
use Auth;

class KasKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

        public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {

            $kas_keluar = KasKeluar::with(['kas','kategori'])->where('warung_id', Auth::user()->id_warung);
            return Datatables::of($kas_keluar)

            ->addColumn('action', function($master_kas_keluar){
                return view('kas_keluar._action', [
                    'model'     => $master_kas_keluar,
                    'form_url'  => route('kas_keluar.destroy', $master_kas_keluar->id),
                    'edit_url'  => route('kas_keluar.edit', $master_kas_keluar->id),
                    'confirm_message'   => 'Yakin Mau Menghapus kas keluar ' . $master_kas_keluar->no_faktur . '?'
                ]); 
            })
            ->addColumn('jumlah_keluar', function($jumlah_keluar){
                $data_keluar = number_format($jumlah_keluar->jumlah,0,',','.');

                return $data_keluar;
            })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No Faktur'])
        ->addColumn(['data' => 'kas.nama_kas', 'name' => 'kas.nama_kas', 'title' => 'Kas'])
        ->addColumn(['data' => 'kategori.nama_kategori_transaksi', 'name' => 'kategori.nama_kategori_transaksi', 'title' => 'Kategori'])
        ->addColumn(['data' => 'jumlah_keluar', 'name' => 'jumlah_keluar', 'title' => 'Jumlah'])
        ->addColumn(['data' => 'keterangan', 'name' => 'jumlah', 'title' => 'Keterangan'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable'=>false]);

        return view('kas_keluar.index')->with(compact('html'));


    }
 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas','id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi','id');

        return view('kas_keluar.create', ['data_kategori_transaksi'=> $data_kategori_transaksi, 'data_kas'=> $data_kas]);
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
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150',
        ]);

        if (Auth::user()->id_warung != "") {
            $no_faktur = KasKeluar::no_faktur(); 
            $kas = KasKeluar::create(['no_faktur' => $no_faktur,'kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $request->keterangan, 'warung_id' => Auth::user()->id_warung]);

            $pesan_alert = 
            '<div class="container-fluid">
                <div class="alert-icon">
                    <i class="material-icons">check</i>
                </div>
                <b>Sukses : Berhasil Menambah Transaksi Kas Keluar Sebesar "'.$request->jumlah.'"</b>
             </div>';

            return redirect()->route('kas_keluar.index');
        }
        else{
            return response()->view('error.403');            
        }
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
        $kas_keluar = KasKeluar::find($id);
        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas','id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi','id');
       
        if ($id_warung == $kas_keluar->warung_id) {
            return view('kas_keluar.edit', ['data_kategori_transaksi'=> $data_kategori_transaksi, 'data_kas'=> $data_kas])->with(compact('kas_keluar'));
        }
        else{
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
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150', 
            
            ]);

            $id_warung = Auth::user()->id_warung;
            $kas_keluar = KasKeluar::find($id);
       
        if ($id_warung == $kas_keluar->warung_id) {

            $kas_keluar->kas = $request->kas;
            $kas_keluar->kategori = $request->kategori;
            $kas_keluar->jumlah = $request->jumlah;
            $kas_keluar->keterangan = $request->keterangan;

            if (!$kas_keluar->save()) {
                return redirect()->back();
            }
            else{

                $pesan_alert = 
                '<div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Berhasil Mengubah Transaksi Kas Keluar "'.$kas_keluar->no_faktur.'"</b>
                 </div>';

                 Session::flash("flash_notification", [
                    "level"=>"success",
                    "message"=> $pesan_alert
                 ]);

            return redirect()->route('kas_keluar.index');

            }
        }
        else{
            return response()->view('error.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kas_keluar = KasKeluar::find($id);
        $id_warung = Auth::user()->id_warung; 
       
        if ($id_warung == $kas_keluar->warung_id) {
            // jika gagal hapus
            if (!KasKeluar::destroy($id)) {
                return redirect()->back();
            }
            else{                

                $pesan_alert = 
                '<div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Berhasil Menghapus Transaksi Kas Keluar "'.$kas_keluar->no_faktur.'"</b>
                 </div>';

                 Session::flash("flash_notification", [
                    "level"=>"success",
                    "message"=> $pesan_alert
                 ]);
                
                return redirect()->route('kas_keluar.index');
            }
        }else{
            return response()->view('error.403');
        }
    }

    //HITUNG TOTAL KAS
    public function hitung_total_kas(Request $request){
        $total_kas = TransaksiKas::total_kas($request);
        return $total_kas;
    }
    
}
