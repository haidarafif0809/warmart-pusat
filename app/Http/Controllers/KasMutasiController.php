<?php 
 
namespace App\Http\Controllers; 
 
use Illuminate\Http\Request; 
use Yajra\Datatables\Html\Builder; 
use Yajra\Datatables\Datatables; 
use Illuminate\Support\Facades\DB; 
use App\Kas; 
use App\KasMutasi; 
use Session; 
use App\TransaksiKas; 
use Auth; 
 
class KasMutasiController extends Controller 
{ 
    /** 
     * Display a listing of the resource. 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }
 
    public function index(Request $request, Builder $htmlBuilder) 
    { 

        if (Auth::user()->id_warung == '') {
                Auth::logout();
                return response()->view('error.403');
        }else{

                // index kas mutasi 
                 if ($request->ajax()) { 
                    # code... 
                 $kas_mutasi = KasMutasi::with(['dari_kas','ke_kas'])->where('id_warung',Auth::user()->id_warung)->get(); 
                 return Datatables::of($kas_mutasi) 
                    ->addColumn('action', function($master_kas_mutasi){ 
                            return view('datatable._action', [ 
                                'model'     => $master_kas_mutasi, 
                                'form_url'  => route('kas_mutasi.destroy', $master_kas_mutasi->id), 
                                'edit_url'  => route('kas_mutasi.edit', $master_kas_mutasi->id), 
                                'confirm_message'   => 'Yakin Mau Menghapus Kas Mutasi ' . $master_kas_mutasi->no_faktur . '?' 
                            
                                ]);  
                        })

                    ->addColumn('jumlah', function($kas_mutasi){
                    $jumlah_keluar = number_format($kas_mutasi->jumlah,0,',','.');

                    return $jumlah_keluar;

                    })->make(true); 

                } 
                $html = $htmlBuilder 
                ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No Faktur']) 
                ->addColumn(['data' => 'dari_kas.nama_kas', 'name' => 'dari_kas.nama_kas', 'title' => 'Dari Kas']) 
                ->addColumn(['data' => 'ke_kas.nama_kas', 'name' => 'ke_kas.nama_kas', 'title' => 'Ke Kas']) 
                ->addColumn(['data' => 'jumlah', 'name' => 'jumlah', 'title' => 'Jumlah']) 
                ->addColumn(['data' => 'keterangan', 'name' => 'jumlah', 'title' => 'Keterangan']) 
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Ubah | Hapus', 'orderable' => false, 'searchable'=>false]); 
         
                return view('kas_mutasi.index')->with(compact('html')); 
        }
    } 
 
    /** 
     * Show the form for creating a new resource. 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create() 
    {   
        if (Auth::user()->id_warung == '') {
                Auth::logout();
                return response()->view('error.403');
        }else{

         //MENAMPILKAN KAS BERDASARKAN ID WARUNG 
          $kas = Kas::where('warung_id',Auth::user()->id_warung)->pluck('nama_kas','id'); 
 
          return view('kas_mutasi.create',['kas'=>$kas]); 
        }
    } 
 
    /** 
     * Store a newly created resource in storage. 
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @return \Illuminate\Http\Response 
     */ 
    public function store(Request $request) 
    { 
        if (Auth::user()->id_warung == '') {
                Auth::logout();
                return response()->view('error.403');
        }else{

             // // proses tambah kas mutasi 
             $this->validate($request, [ 
                'dari_kas'      => 'required', 
                'ke_kas'        => 'required', 
                'jumlah'        => 'required|numeric|digits_between:1,9', 
                'keterangan'    => 'nullable|max:150' 
                 
                ]); 
     
     
             $no_faktur = KasMutasi::no_faktur(); 
      
             $kas_mutasi = KasMutasi::create([ 
                'no_faktur'     => $no_faktur, 
                'dari_kas'      => $request->dari_kas, 
                'ke_kas'        => $request->ke_kas, 
                'jumlah'        => $request->jumlah, 
                'keterangan'    => $request->keterangan, 
                'id_warung'     => Auth::user()->id_warung]); 
     
            Session::flash("flash_notification", [ 
                "level"=>"success", 
                "message"=>" <b>BERHASIL:</b> Memutasikan Kas Sejumlah <b>$request->jumlah</b>" 
                ]); 
     
            return redirect()->route('kas_mutasi.index'); 
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
        $kas_mutasi = KasMutasi::find($id);         
         //MENAMPILKAN KAS BERDASARKAN ID WARUNG 
        $kas = Kas::where('warung_id',Auth::user()->id_warung)->pluck('nama_kas','id'); 

        if ($kas_mutasi->id_warung != Auth::user()->id_warung) {
                Auth::logout();
                return response()->view('error.403');
        }else{

        return view('kas_mutasi.edit',['kas'=>$kas])->with(compact('kas_mutasi')); 
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
        $kas_mutasi = KasMutasi::find($id); 

        if ($kas_mutasi->id_warung != Auth::user()->id_warung) {
                Auth::logout();
                return response()->view('error.403');
        }else{

            
             $this->validate($request, [ 
                'dari_kas'   => 'required', 
                'ke_kas'   => 'required', 
                'jumlah'   => 'required|numeric', 
                'keterangan'   => 'max:150',  
                 
                ]); 
     
             $kas = KasMutasi::find($id)->update(['dari_kas' => $request->dari_kas,'ke_kas' => $request->ke_kas,'jumlah' => $request->jumlah,'keterangan' => $request->keterangan,'id_warung'     => Auth::user()->id_warung]); 
              
     
             Session::flash("flash_notification", [ 
                "level"=>"success", 
                "message"=>"<b>BERHASIL:</b> Mengubah Kas Mutasi $kas_mutasi->no_faktur" 
                ]); 
     
            return redirect()->route('kas_mutasi.index'); 

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
          // 
        $kas_mutasi = KasMutasi::find($id); 

        if ($kas_mutasi->id_warung != Auth::user()->id_warung) {
                Auth::logout();
                return response()->view('error.403');
        }else{

                    // hitung kas 
                    $sisa_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))
                                ->where('kas', $kas_mutasi->ke_kas)
                                ->where('warung_id',Auth::user()->id_warung)
                                ->where('no_faktur','!=',$kas_mutasi->no_faktur)
                                ->first();

                    if ($sisa_kas->total_kas < 0) {

                            Session::flash("flash_notification", [ 
                                    "level"     => "danger", 
                                    "message"   => "Mohon Maaf, Kas Mutasi ". $kas_mutasi->no_faktur ." Tidak bisa Di Hapus, Jika Dihapus Kas akan Minus " 
                                ]); 
                            return redirect()->route('kas_mutasi.index'); 
                    }else{
            
                            // jika gagal hapus 
                            if (!KasMutasi::destroy($id)) { 
                                // redirect back 
                                return redirect()->back(); 
                            }else{ 
                                Session::flash("flash_notification", [ 
                                    "level"     => "danger", 
                                    "message"   => "Kas Mutasi ". $kas_mutasi->no_faktur ." Berhasil Di Hapus" 
                                ]); 
                            return redirect()->route('kas_mutasi.index'); 
                            } 
                    }
                   

        }
    } 
 
 
} 