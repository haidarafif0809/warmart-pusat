<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\KasKeluar;
use Session;
use App\TransaksiKas;


class KasKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request, Builder $htmlBuilder)
    {
        //


         if ($request->ajax()) {
            # code...
            $kas_keluar = KasKeluar::with(['kas','kategori']);
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
        //
                return view('kas_keluar.create');
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

        $no_faktur = KasKeluar::no_faktur();

        $kas = KasKeluar::create(['no_faktur' => $no_faktur,'kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $request->keterangan]);

        TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => $request->jumlah, 'kas' => $request->kas] );

        $pesan_alert = 
        '<div class="container-fluid">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <b>Sukses : Berhasil Menambah Transaksi Kas Keluar Sebesar "'.$request->jumlah.'"</b>
         </div>';

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=> $pesan_alert
         ]);

        return redirect()->route('kas_keluar.index');
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


                   $kas_keluar = KasKeluar::find($id);
       

        return view('kas_keluar.edit')->with(compact('kas_keluar'));
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

        $kas = KasKeluar::find($id)->update(['kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $request->keterangan]);

        $kas_keluar = KasKeluar::find($id);


        TransaksiKas::where('no_faktur' , $kas_keluar->no_faktur)->update(['jumlah_keluar' => $request->jumlah,'kas' => $request->kas] );

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kas_keluar = KasKeluar::find($id);

        // jika gagal hapus
        if (!KasKeluar::destroy($id)) {
            return redirect()->back();
        }
        else{

            TransaksiKas::where('no_faktur',$kas_keluar->no_faktur)->delete();

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
    }

    //HITUNG TOTAL KAS
    public function hitung_total_kas(Request $request){
        $total_kas = TransaksiKas::total_kas($request);
        return $total_kas;
    }
    
}
