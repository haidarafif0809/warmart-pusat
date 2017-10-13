<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\KasMasuk;
use Session;
use App\TransaksiKas;


class KasMasukController extends Controller
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
            $kas_masuk = KasMasuk::with(['kas','kategori']);
            return Datatables::of($kas_masuk)->addColumn('action', function($master_kas_masuk){
                    return view('kas_masuk._action', [
                        'model'     => $master_kas_masuk,
                        'form_url'  => route('kas_masuk.destroy', $master_kas_masuk->id),
                        'edit_url'  => route('kas_masuk.edit', $master_kas_masuk->id),
                        'confirm_message'   => 'Yakin Mau Menghapus kas masuk ' . $master_kas_masuk->no_faktur . '?'
                   
                        ]); 
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No Faktur'])
        ->addColumn(['data' => 'kas.nama_kas', 'name' => 'kas.nama_kas', 'title' => 'Kas'])
        ->addColumn(['data' => 'kategori.nama_kategori_transaksi', 'name' => 'kategori.nama_kategori_transaksi', 'title' => 'Kategori'])
        ->addColumn(['data' => 'jumlah', 'name' => 'jumlah', 'title' => 'Jumlah'])
        ->addColumn(['data' => 'keterangan', 'name' => 'jumlah', 'title' => 'Keterangan'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable'=>false]);

        return view('kas_masuk.index')->with(compact('html'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('kas_masuk.create');
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

          // // proses tambah user
         $this->validate($request, [
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150', 
            
            ]);


         $no_faktur = KasMasuk::no_faktur();
 
         $kas = KasMasuk::create(['no_faktur' => $no_faktur,'kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $request->keterangan]);

         TransaksiKas::create(['no_faktur' => $no_faktur,'jenis_transaksi'=>'kas_masuk' ,'jumlah_masuk' => $request->jumlah,'kas' => $request->kas] );

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>" <b>BERHASIL:</b> Memasukkan Kas Sejumlah $request->jumlah  </b>"
            ]);

        return redirect()->route('kas_masuk.index');
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

                   $kas_masuk = KasMasuk::find($id);
       

        return view('kas_masuk.edit')->with(compact('kas_masuk'));
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


           // // proses tambah user
         $this->validate($request, [
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150', 
            
            ]);

         $kas = KasMasuk::find($id)->update(['kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $request->keterangan]);

         $kas_masuk = KasMasuk::find($id);


        TransaksiKas::where('no_faktur' , $kas_masuk->no_faktur)->update(['jumlah_masuk' => $request->jumlah,'kas' => $request->kas] );
    

         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"BERHASIL:</b> Mengubah Kas Masuk $kas_masuk->no_faktur"
            ]);

        return redirect()->route('kas_masuk.index');

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

          $kas = KasMasuk::find($id);
          
          TransaksiKas::where('no_faktur',$kas->no_faktur())->delete();
  

        // jika gagal hapus
        if (!KasMasuk::destroy($id)) {
            // redirect back
            return redirect()->back();
        }else{
            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => "Kas Masuk ". $kas->no_faktur ." Berhasil Di Hapus"
            ]);
        return redirect()->route('kas_masuk.index');
        }
    }
}
