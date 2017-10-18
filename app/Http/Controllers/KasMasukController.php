<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\KasMasuk;
use Session;
use App\TransaksiKas;
use Auth;


class KasMasukController extends Controller
{
     
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }
    //PROSES MENAMPILKAN KAS MASUK
     public function index(Request $request, Builder $htmlBuilder)
    { 
         if ($request->ajax()) {

            $kas_masuk = KasMasuk::with(['kas','kategori'])->where('id_warung',Auth::user()->id_warung)->get();
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
        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas','id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi','id');

        return view('kas_masuk.create', ['data_kategori_transaksi'=> $data_kategori_transaksi, 'data_kas'=> $data_kas]);
    }
 
    //PROSES TAMBAH KAS MASUK
    public function store(Request $request)
    { 
         $this->validate($request, [
            'kas'   => 'required',
            'kategori'   => 'required',
            'jumlah'   => 'required|numeric',
            'keterangan'   => 'max:150'
            ]); 

         if ($request->keterangan == "") {
             $keterangan = "-";
         }else{ 
             $keterangan = $request->keterangan;
         }

        if (Auth::user()->id_warung != "") {
             $no_faktur = KasMasuk::no_faktur();
             $kas = KasMasuk::create(['no_faktur' => $no_faktur,'kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $keterangan,'id_warung'=> Auth::user()->id_warung]);
             
             //PROSES MEMBUAT TRANSAKSI KAS
             TransaksiKas::create(['no_faktur' => $no_faktur,'jenis_transaksi'=>'kas_masuk' ,'jumlah_masuk' => $request->jumlah,'kas' => $request->kas,'warung_id'=>Auth::user()->id_warung]);

            $pesan_alert = '<b>Sukses:</b> Berhasil Menambah Transaksi Kas Masuk Sebesar "'.$request->jumlah.'" </b>';

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
                ]);

            return redirect()->route('kas_masuk.index');
        }else{
          Auth::logout();
                return response()->view('error.403');            
        }
    }

    public function show($id)
    {
        //
    }

    //PROSES KE HALAMAN EDIT
    public function edit($id)
    { 
        $id_warung = Auth::user()->id_warung;
        $kas_masuk = KasMasuk::find($id);
        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas','id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi','id'); 

        if ($id_warung == $kas_masuk->id_warung) {
            return view('kas_masuk.edit',['data_kategori_transaksi'=> $data_kategori_transaksi, 'data_kas'=> $data_kas])->with(compact('kas_masuk')); 
        }else{
            Auth::logout();
            return response()->view('error.403');
        }
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

        $kas_masuk = KasMasuk::find($id);
        $id_warung = Auth::user()->id_warung;

        if ($id_warung == $kas_masuk->id_warung) {
             $kas = KasMasuk::find($id)->update(['kas' => $request->kas,'kategori' => $request->kategori,'jumlah' => $request->jumlah,'keterangan' => $keterangan]);

             //PROSES UPDATE TRANSAKSI KAS
            TransaksiKas::where('no_faktur' , $kas_masuk->no_faktur)->update(['jumlah_masuk' => $request->jumlah,'kas' => $request->kas]);
        
             Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>'<b>Sukses :</b>Berhasil Mengubah Transaksi Kas Keluar "'.$kas_masuk->no_faktur.'"'
                ]);

            return redirect()->route('kas_masuk.index');
        }else{
          Auth::logout();
                return response()->view('error.403'); 
        } 
    }
  

    public function destroy($id)
    { $kas_masuk = KasMasuk::find($id); 

       if ($kas_masuk->id_warung != Auth::user()->id_warung) {
                Auth::logout();
                return response()->view('error.403');
        }else{

                   // hitung kas
                    $sisa_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))
                                ->where('kas', $kas_masuk->kas)
                                ->where('warung_id',Auth::user()->id_warung)
                                ->where('no_faktur','!=',$kas_masuk->no_faktur)
                                ->first();

                   if ($sisa_kas->total_kas < 0) {

                           Session::flash("flash_notification", [
                                    "level"     => "danger",
                                    "message"   => "<b>Info :</b> Kas Masuk ". $kas_masuk->no_faktur ." Tidak Di Hapus, Jika Dihapus Kas akan Minus "
                                ]);
                            return redirect()->route('kas_masuk.index');
                    }else{

                           TransaksiKas::where('no_faktur',$kas_masuk->no_faktur)->delete();
            
                            // jika gagal hapus
                            if (!KasMasuk::destroy($id)) {
                                // redirect back
                                return redirect()->back();
                            }else{
                                Session::flash("flash_notification", [
                                    "level"     => "success",
                                    "message"   => "<b>Succes :</b> Kas Masuk ". $kas_masuk->no_faktur ." Berhasil Di Hapus"
                                ]);
                            return redirect()->route('kas_masuk.index');
                            }
                    } 

       } 
    }
}
