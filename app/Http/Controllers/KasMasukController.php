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
    //PROSES MENAMPILKAN KAS MASUK
     public function index(Request $request, Builder $htmlBuilder)
    { 
         if ($request->ajax()) {

            $kas_masuk = KasMasuk::with(['kas','kategori']);
            return Datatables::of($kas_masuk)->addColumn('action', function($master_kas_masuk){
                    return view('datatable._action', [
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
 
    public function create()
    { 
        return view('kas_masuk.create');
    }
 
    //PROSES TAMBAH KAS MASUK
    public function store(Request $request)
    { 
         $this->validate($request, [
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150', 
            
            ]);

         if ($request->keterangan == "") {
             $keterangan = "-";
         }else{ 
             $keterangan = $request->keterangan;
         }

         $no_faktur = KasMasuk::no_faktur();
         $kas = KasMasuk::create(['no_faktur' => $no_faktur,'kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $keterangan]);
         
         //PROSES MEMBUAT TRANSAKSI KAS
         TransaksiKas::create(['no_faktur' => $no_faktur,'jenis_transaksi'=>'kas_masuk' ,'jumlah_masuk' => $request->jumlah,'kas' => $request->kas] );

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>" <b>BERHASIL:</b> Memasukkan Kas Sejumlah $request->jumlah  </b>"
            ]);

        return redirect()->route('kas_masuk.index');
    }

    public function show($id)
    {
        //
    }

    //PROSES KE HALAMAN EDIT
    public function edit($id)
    { 
        $kas_masuk = KasMasuk::find($id);
        return view('kas_masuk.edit')->with(compact('kas_masuk'));
    }

    //PROSES EDIT KAS Masuk
    public function update(Request $request, $id)
    {  
         $this->validate($request, [
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150',  
            ]);

         if ($request->keterangan == "") {
             $keterangan = "-";
         }else{ 
             $keterangan = $request->keterangan;
         }

         $kas = KasMasuk::find($id)->update(['kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $keterangan]);
         $kas_masuk = KasMasuk::find($id);

         //PROSES UPDATE TRANSAKSI KAS
        TransaksiKas::where('no_faktur' , $kas_masuk->no_faktur)->update(['jumlah_masuk' => $request->jumlah,'kas' => $request->kas]);
    
         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"BERHASIL:</b> Mengubah Kas Masuk $kas_masuk->no_faktur"
            ]);

        return redirect()->route('kas_masuk.index');

    }
 
    public function destroy($id)
    { 
        $kas = KasMasuk::find($id); 
        // jika gagal hapus
        if (!KasMasuk::destroy($id)) {
            // redirect back
            return redirect()->back();
        }else{
            //MENGHAPUS TRANSAKSI KAS
           TransaksiKas::where('no_faktur',$kas->no_faktur())->delete();
            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => "Kas Masuk ". $kas->no_faktur ." Berhasil Di Hapus"
            ]);
        return redirect()->route('kas_masuk.index');
        }
    }
}
