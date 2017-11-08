<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request;use Yajra\Datatables\Html\Builder; 
use Yajra\Datatables\Datatables; 
use Illuminate\Support\Facades\DB;  
use App\Pembelian;  
use App\TbsPembelian; 
use App\DetailPembelian;   
use App\Barang;   
use App\EditTbsPembelian;
use App\TransaksiKas;
use App\Kas;   
use Session; 
use Auth; 
use Laratrust; 

class PembelianController extends Controller 
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
        //INDEX PEMBELIAN 
      if ($request->ajax()) { 
        $pembelian = Pembelian::with(['kas','suplier'])->where('warung_id',Auth::user()->id_warung)->orderBy('id')->get(); 
        return Datatables::of($pembelian)->addColumn('action', function($data_pembelian){ 
          return view('pembelian._action', [ 
            'model'     => $data_pembelian, 
            'form_url'  => route('pembelian.destroy', $data_pembelian->id), 
            'edit_url'  => route('pembelian.form_edit_beli', $data_pembelian->id), 
            'confirm_message'   => 'Anda Yakin Ingin Menghapus Pembelian ' .$data_pembelian->no_faktur . ' ?', 
          ]); 
        }) 
        ->addColumn('total', function($data_pembelian){ 
          return $data_pembelian->PemisahTotal; 
        }) 
        ->addColumn('potongan', function($data_pembelian){ 
          return $data_pembelian->PemisahPotongan; 
        }) 
        ->addColumn('tunai', function($data_pembelian){ 
          return $data_pembelian->PemisahTunai; 
        }) 
        ->addColumn('kembalian', function($data_pembelian){ 
          return $data_pembelian->PemisahKembalian; 
        }) 
        ->addColumn('kredit', function($data_pembelian){ 
          return $data_pembelian->PemisahKredit; 
        })->make(true); 
      } 

      $html = $htmlBuilder 
      ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No. Faktur']) 
      ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu']) 
      ->addColumn(['data' => 'suplier.nama_suplier', 'name' => 'suplier.nama_suplier', 'title' => 'Suplier', 'orderable' => false, 'searchable'=>false]) 
      ->addColumn(['data' => 'kas.nama_kas', 'name' => 'kas.nama_kas', 'title' => 'Cara Bayar', 'orderable' => false, 'searchable'=>false]) 
      ->addColumn(['data' => 'status_pembelian', 'name' => 'status_pembelian', 'title' => 'Status']) 
      ->addColumn(['data' => 'potongan', 'name' => 'potongan', 'title' => 'Potongan'])  
      ->addColumn(['data' => 'total', 'name' => 'total', 'title' => 'Total']) 
      ->addColumn(['data' => 'tunai', 'name' => 'tunai', 'title' => 'Tunai']) 
      ->addColumn(['data' => 'kembalian', 'name' => 'kembalian', 'title' => 'Kembalian']) 
      ->addColumn(['data' => 'kredit', 'name' => 'kredit', 'title' => 'Kredit']) 
      ->addColumn(['data' => 'tanggal_jt_tempo', 'name' => 'tanggal_jt_tempo', 'title' => 'Jatuh Tempo']) 
      ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]); 

      return view('pembelian.index')->with(compact('html')); 
    } 

    /** 
     * Show the form for creating a new resource. 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request, Builder $htmlBuilder) 
    { 
        // form pembelian 
      $session_id = session()->getId();   
      $sum_subtotal = TbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('session_id', $session_id)->where('warung_id',Auth::user()->id_warung)->first();   
      $subtotal = number_format($sum_subtotal->subtotal,2,',','.'); 
      if ($request->ajax()) { 

        $tbs_pembelian = TbsPembelian::with(['produk'])->where('session_id', $session_id)->where('warung_id',Auth::user()->id_warung)->get(); 
        return Datatables::of($tbs_pembelian)->addColumn('action', function($TbsPembelian){ 

          $pesan_alert = 'Anda Yakin Ingin Menghapus Produk "'.$TbsPembelian->TitleCaseBarang.'" ?'; 
          return view('pembelian._hapus_produk', [ 
            'model'             => $TbsPembelian, 
            'form_url'          => route('pembelian.hapus_tbs_pembelian', $TbsPembelian->id_tbs_pembelian),   
            'confirm_message'   => $pesan_alert 
          ]); 
        }) 
        ->editColumn('data_produk_tbs', function($data_produk_tbs){ 

          return $data_produk_tbs->produk->kode_barang .' - '.$data_produk_tbs->TitleCaseBarang;  
        }) 
        ->editColumn('jumlah_produk', function($produk_tbs){ 
          return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk_tbs->id_tbs_pembelian' data-nama='$produk_tbs->TitleCaseBarang'>$produk_tbs->jumlah_produk</a>";  
        }) 
        ->editColumn('harga_produk', function($produk){ 

         return "<a href='#edit-harga' id='edit_harga_produk' class='edit-harga' data-id='$produk->id_tbs_pembelian'  data-nama='$produk->TitleCaseBarang'>$produk->harga_produk</a>";  
       }) 
        ->editColumn('potongan', function($produk){ 

          $potongan_persen = ($produk->potongan / ($produk->jumlah_produk * $produk->harga_produk)) * 100; 
          return "<a href='#edit-potongan' id='edit_potongan' class='edit-potongan' data-id='$produk->id_tbs_pembelian' data-nama='$produk->TitleCaseBarang' data-jumlah='$produk->jumlah_produk' data-harga='$produk->harga_produk'>$produk->potongan"." | ".round($potongan_persen,2)."%</a>";  
        }) 
        ->editColumn('tax', function($produk)  use ($session_id) { 
          $ppn = TbsPembelian::select('ppn')->where('session_id', $session_id)->where('warung_id',Auth::user()->id_warung)->where('ppn','!=','')->limit(1); 
          if ($ppn->count() > 0) { 
           $ppn_produk = $ppn->first()->ppn; 
           $tax_persen = ($produk->tax * 100) / ($produk->jumlah_produk * $produk->harga_produk - $produk->potongan);
         }else{            
          $ppn_produk = ""; 
          $tax_persen = 0; 
        } 
        return "<a href='#edit-tax' id='edit_tax_produk' class='edit-tax' data-id='$produk->id_tbs_pembelian'  data-jumlah='$produk->jumlah_produk' data-potongan='$produk->potongan' data-harga='$produk->harga_produk' data-ppn='$ppn_produk' data-nama='$produk->TitleCaseBarang'>$produk->tax"." | ".round($tax_persen,2)."%</a>";  
      })->make(true); 
      } 

      $html = $htmlBuilder 
      ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'orderable' => false, 'searchable'=>false]) 
      ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah']) 
      ->addColumn(['data' => 'harga_produk', 'name' => 'harga_produk', 'title' => 'Harga']) 
      ->addColumn(['data' => 'potongan', 'name' => 'potongan', 'title' => 'Potongan']) 
      ->addColumn(['data' => 'tax', 'name' => 'tax', 'title' => 'Pajak']) 
      ->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Subtotal']) 
      ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable'=>false]); 

      $kas_default = Kas::where('warung_id',Auth::user()->id_warung)->where('default_kas',1); 

      return view('pembelian.create',['subtotal_tbs'=>$subtotal,'kas_default'=>$kas_default])->with(compact('html')); 
    } 

    //PROSES TAMBAH TBS PEMBELIAN 
    public function proses_tambah_tbs_pembelian(Request $request){ 

      //VALIDATE
      $this->validate($request, [ 
        'id_produk_tbs'     => 'required|numeric', 
        'jumlah_produk' => 'required|numeric|digits_between:1,15', 
      ]); 

      $session_id = session()->getId(); // SESSION ID
      // CEK TBS PEMBELIAN
      $data_tbs = TbsPembelian::where('id_produk', $request->id_produk_tbs) 
      ->where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung); 

      // SELECT PRODUK
      $barang = Barang::select('nama_barang','satuan_id')->where('id',$request->id_produk_tbs)->where('id_warung',Auth::user()->id_warung)->first(); 
//JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS 
      if ($data_tbs->count() > 0) { 

        $pesan_alert =  
        '<div class="container-fluid"> 
        <div class="alert-icon"> 
        <i class="material-icons">warning</i> 
        </div> 
        <b>Warning : Produk "'.$barang->nama_barang.'" Sudah Ada, Silakan Pilih Produk Lain !</b> 
        </div>'; 

        Session::flash("flash_notification", [ 
          "level"   =>"warning", 
          "message" => $pesan_alert 
        ]);  

        return redirect()->route('pembelian.create'); 
      } 
      else{ 

       $pesan_alert =  
       '<div class="container-fluid"> 
       <div class="alert-icon"> 
       <i class="material-icons">check</i> 
       </div> 
       <b>Berhasil Menambah Produk "'.$barang->nama_barang.'"</b> 
       </div>';

       // SUBTOTAL = JUMLAH * HARGA
       $subtotal = $request->jumlah_produk * $request->harga_produk; 

       // INSERT TBS PEMBELIAN
       $Insert_tbspembelian = TbsPembelian::create([ 
        'id_produk'     => $request->id_produk_tbs,               
        'session_id'    => $session_id, 
        'jumlah_produk' => $request->jumlah_produk, 
        'harga_produk'  => $request->harga_produk, 
        'subtotal'      => $subtotal, 
        'satuan_id'     => $barang->satuan_id, 
        'warung_id'     => Auth::user()->id_warung                                                                                                        
      ]); 

       Session::flash("flash_notification", [ 
        "level"     =>"success", 
        "message"   => $pesan_alert 
      ]); 
       return redirect()->route('pembelian.create'); 
     } 
   } 

//PROSES EDIT JUMLAH TBS PEMBELIAN 
   public function edit_jumlah_tbs_pembelian(Request $request){ 
        // SELECT  TBS PEMBELIAN
    $tbs_pembelian = TbsPembelian::find($request->id_tbs_pembelian);
    // JIKA TAX/ PAJAKK EDIT TBS PEMBELIAN == 0
    if ($tbs_pembelian->tax == 0) { 
      $tax_produk = 0; 
    }else{ 
            // TAX PERSEN = (TAX TBS PEMBELIAN * 100) / (JUMLAH PRODUK * HARGA - POTONGAN)
      $tax = ($tbs_pembelian->tax * 100) / ($request->jumlah_edit_produk * $tbs_pembelian->harga_produk - $tbs_pembelian->potongan); // TAX DALAM BENTUK PERSEN
      // TAX PRODUK = (HARGA * JUMLAH - POTONGAN) * TAX /100
      $tax_produk = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) * $tax / 100; 
    } 

    if ($tbs_pembelian->ppn == 'Include') { // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
      $subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan; 
    }elseif ($tbs_pembelian->ppn == 'Exclude') {  // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOT
     $subtotal = (($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan) + $tax_produk; 
   }else{ 
    $subtotal = ($tbs_pembelian->harga_produk * $request->jumlah_edit_produk) - $tbs_pembelian->potongan; 
  } 
// UPDATE JUMLAH PRODUK, SUBTOTAL, DAN TAX
  $tbs_pembelian->update(['jumlah_produk' => $request->jumlah_edit_produk,'subtotal'=>$subtotal,'tax'=>$tax_produk]); 
  $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

  $pesan_alert =  
  '<div class="container-fluid"> 
  <div class="alert-icon"> 
  <i class="material-icons">check</i> 
  </div> 
  <b>Berhasil Mengubah Jumlah Produk "'.$nama_barang.'"</b> 
  </div>'; 

  Session::flash("flash_notification", [ 
    "level"     => "success", 
    "message"   => $pesan_alert 
  ]); 

  return redirect()->back(); 
} 


//PROSES EDIT HARGA TBS PEMBELIAN 
public function edit_harga_tbs_pembelian(Request $request){ 
      // SELECT  TBS PEMBELIAN
  $tbs_pembelian = TbsPembelian::find($request->id_harga); 

// JIKA POTONGAN == 0
  if ($tbs_pembelian->potongan == 0) { 
    $potongan_produk = 0; 
  }else{ 
  // POTONGA PERSEN = POTONGAN / (JUMLAH * HARGA) * 100
    $potongan_persen = ($tbs_pembelian->potongan / ($tbs_pembelian->jumlah_produk * $request->harga_edit_produk)) * 100; 
    // POTONGAN PRODUK = HARGA * JUMLAH * POTONGAN PERSEN /100 
    $potongan_produk = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) * $potongan_persen / 100; 
  } 

// JIKA PAJAK == 0
  if ($tbs_pembelian->tax == 0) { 
    $tax_produk = 0; 
  }else{ 
// TAX PERSEN =  (TAX * 100) / (JUMLAH * HARGA - POTONGAN )
    $tax = ($tbs_pembelian->tax * 100) / ($tbs_pembelian->jumlah_produk * $request->harga_edit_produk - $potongan_produk); 
// TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
    $tax_produk = (($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) * $tax / 100; 
  } 

  if ($tbs_pembelian->ppn == 'Include') {  // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
    $subtotal = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
  }elseif ($tbs_pembelian->ppn == 'Exclude') { // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
   $subtotal = (($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) + $tax_produk; 
 }else{ 
  $subtotal = ($request->harga_edit_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
} 

// UPDATE HARGA, SUBTOTAL, POTONGAN, TAX
$tbs_pembelian->update(['harga_produk' => $request->harga_edit_produk,'subtotal'=>$subtotal,'potongan'=>$potongan_produk,'tax'=>$tax_produk]); 
$nama_barang = $tbs_pembelian->TitleCaseBarang;  // TITLE CASH

$pesan_alert =  
'<div class="container-fluid"> 
<div class="alert-icon"> 
<i class="material-icons">check</i> 
</div> 
<b>Berhasil Mengubah Harga Produk "'.$nama_barang.'"</b> 
</div>'; 

Session::flash("flash_notification", [ 
  "level"     => "success", 
  "message"   => $pesan_alert 
]); 

return redirect()->back(); 
} 


//PROSES EDIT HARGA TBS PEMBELIAN 
public function edit_potongan_tbs_pembelian(Request $request){ 
      // SELECT EDIT TBS PEMBELIAN
  $tbs_pembelian = TbsPembelian::find($request->id_potongan); 
    $potongan = substr_count($request->potongan_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%" 
        // JIKA TIDAK ADA
    if ($potongan == 0) { 
      $potongan_produk = $request->potongan_edit_produk; // POTONGAN TIDAK DALAM BENTUK NOMINAL
    }else{ // JIKA ADA

      $potongan_persen = explode('%',$request->potongan_edit_produk);  //  PISAH STRING BERDASRAKAN TANDA "%"
// POTONGA PRODUK =  (HARGA * JUMLAH ) * POTONGAN PERSEN / 100;
      $potongan_produk = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) * $potongan_persen[0] / 100; 
    } 
    // JIKA TIDAK ADA PAJAK 
    if ($tbs_pembelian->tax == 0) { 
      $tax_produk = 0; 
    }else{ 
// TAX PERSEN =  (TAX * 100) / (JUMLAH * HARGA - POTONGAN )
      $tax = ($tbs_pembelian->tax * 100) / ($tbs_pembelian->jumlah_produk * $tbs_pembelian->harga_produk - $potongan_produk); 
// TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
      $tax_produk = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) * $tax / 100; 
    } 

    if ($tbs_pembelian->ppn == 'Include') {  // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
      $subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
    }elseif ($tbs_pembelian->ppn == 'Exclude') { // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL
     $subtotal = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk) + $tax_produk; 
   }else{ 
    $subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $potongan_produk; 
  } 

// UPDATE POTONGAN, SUBTOTAL, TAX
  $tbs_pembelian->update(['potongan' => $potongan_produk,'subtotal'=>$subtotal,'tax'=>$tax_produk]); 
  $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

  $pesan_alert =  
  '<div class="container-fluid"> 
  <div class="alert-icon"> 
  <i class="material-icons">check</i> 
  </div> 
  <b>Berhasil Mengubah Potongan Produk "'.$nama_barang.'"</b> 
  </div>'; 

  Session::flash("flash_notification", [ 
    "level"     => "success", 
    "message"   => $pesan_alert 
  ]); 

  return redirect()->back(); 
} 

public function editTaxTbsPembelian(Request $request){ 
  // SELECT EDIT  TBS PEMBELIAN 
  $tbs_pembelian = TbsPembelian::find($request->id_tax); 
    $tax = substr_count($request->tax_edit_produk, '%'); // UNTUK CEK APAKAH ADA STRING "%" 
      // JIKA TIDAK ADA
    if ($tax == 0) { 
      $tax_produk = $request->tax_edit_produk; // TAX DAALAM BENTUK NOMINAL
    }else{  // JIKA ADA

      $tax_persen = explode('%',$request->tax_edit_produk); //  PISAH STRING BERDASRAKAN TANDA "%"
// TAX PRODUK = ((HARGA * JUMLAH) - POTONGAN) * TAX PERSEN / 100
      $tax_produk = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) * $tax_persen[0] / 100; 
    } 

    if ($request->ppn_produk == 'Include') { // JIKA PPN INCLUDE MAKA PAJAK TIDAK MEMPENGARUHI SUBTOTAL
      $subtotal = ($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan; 
    }elseif ($request->ppn_produk == 'Exclude') { // JIKA PPN EXCLUDE MAKA PAJAK MEMPENGARUHI SUBTOTAL 
     $subtotal = (($tbs_pembelian->harga_produk * $tbs_pembelian->jumlah_produk) - $tbs_pembelian->potongan) + $tax_produk; 
   } 
   // UPDATE SUBTOTAL, TAX, PPN
   $tbs_pembelian->update(['subtotal'=>$subtotal,'tax'=>$tax_produk,'ppn'=>$request->ppn_produk]); 
   $nama_barang = $tbs_pembelian->TitleCaseBarang; // TITLE CASH

   $pesan_alert =  
   '<div class="container-fluid"> 
   <div class="alert-icon"> 
   <i class="material-icons">check</i> 
   </div> 
   <b>Berhasil Mengubah Pajak Produk "'.$nama_barang.'"</b> 
   </div>'; 

   Session::flash("flash_notification", [ 
    "level"     => "success", 
    "message"   => $pesan_alert 
  ]); 

   return redirect()->back(); 
 } 
 
//PROSES HAPUS TBS PEMBELIAN 
 public function hapus_tbs_pembelian($id){ 

  if (!TbsPembelian::destroy($id)) { 
    return redirect()->route('pembelian.create'); 
  } 
  else{ 
    $pesan_alert =  
    '<div class="container-fluid"> 
    <div class="alert-icon"> 
    <i class="material-icons">check</i> 
    </div> 
    <b>Berhasil Menghapus Produk</b> 
    </div>'; 

    Session::flash("flash_notification", [ 
      "level"     => "danger", 
      "message"   => $pesan_alert 
    ]); 
    return redirect()->route('pembelian.create'); 
  } 
} 

//PROSES BATAL TBS PEMBELIAN 
public function proses_batal_transaksi_pembelian(){ 
  $session_id = session()->getId(); 
  $data_tbs_pembelian = TbsPembelian::where('session_id', $session_id)->where('warung_id', Auth::user()->id_warung)->delete(); 
  $pesan_alert =  
  '<div class="container-fluid"> 
  <div class="alert-icon"> 
  <i class="material-icons">check</i> 
  </div> 
  <b>Berhasil Membatalkan Pembelian</b> 
  </div>'; 

  Session::flash("flash_notification", [ 
    "level"     => "success", 
    "message"   => $pesan_alert 
  ]); 
  return redirect()->route('pembelian.create'); 
} 
    /** 
     * Store a newly created resource in storage. 
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @return \Illuminate\Http\Response 
     */ 
    public function store(Request $request) 
    { 
           //START TRANSAKSI 
      DB::beginTransaction(); 
      $warung_id = Auth::user()->id_warung; 
      $session_id = session()->getId(); 
      $user = Auth::user()->id; 
      $no_faktur = Pembelian::no_faktur($warung_id); 

    //INSERT DETAIL PEMBELIAN 
      $data_produk_pembelian = TbsPembelian::where('session_id', $session_id)->where('warung_id',Auth::user()->id_warung); 

      if ($data_produk_pembelian->count() == 0) { 

       $pesan_alert =  
       '<div class="container-fluid"> 
       <div class="alert-icon"> 
       <i class="material-icons">error</i> 
       </div> 
       <b>Gagal : Belum Ada Produk Yang Diinputkan</b> 
       </div>'; 

       Session::flash("flash_notification", [ 
        "level"     => "danger", 
        "message"   => $pesan_alert 
      ]); 

       return redirect()->back(); 
     }else{ 

                // INSERT DETAIL PEMBELIAN 
      foreach ($data_produk_pembelian->get() as $data_tbs_pembelian) { 
        $barang = Barang::select('harga_beli')->where('id',$data_tbs_pembelian->id_produk)->where('id_warung',Auth::user()->id_warung); 
        if ($barang->first()->harga_beli != $data_tbs_pembelian->harga_produk) {
          $barang->update(['harga_beli'=>$data_tbs_pembelian->harga_produk]); 
        }
        $detail_pembelian = DetailPembelian::create([ 
          'no_faktur'         => $no_faktur, 
          'satuan_id'         => $data_tbs_pembelian->satuan_id, 
          'id_produk'         => $data_tbs_pembelian->id_produk, 
          'jumlah_produk'     => $data_tbs_pembelian->jumlah_produk, 
          'harga_produk'      => $data_tbs_pembelian->harga_produk, 
          'subtotal'          => $data_tbs_pembelian->subtotal, 
          'tax'               => $data_tbs_pembelian->tax, 
          'potongan'          => $data_tbs_pembelian->potongan, 
          'ppn'               => $data_tbs_pembelian->ppn, 
          'warung_id'         => Auth::user()->id_warung 
        ]); 

      } 

          //INSERT PEMBELIAN 
      if ($request->keterangan == "") { 
        $keterangan = "-"; 
      } 
      else{ 
        $keterangan = $request->keterangan; 
      } 

      if ($request->pembayaran == '') { 
        $pembayaran = 0; 
      }else{ 
        $pembayaran = $request->pembayaran; 
      } 
      if ($request->kembalian == '') { 
        $kembalian = 0; 
      }else{ 
        $kembalian = $request->kembalian; 
      } 
      $pembelian = Pembelian::create([ 
        'no_faktur'         => $no_faktur, 
        'total'             => str_replace('.','',$request->total_akhir), 
        'suplier_id'        => $request->suplier_id, 
        'status_pembelian'  => $request->status_pembelian, 
        'potongan'          => $request->potongan, 
        'tunai'             => $pembayaran, 
        'kembalian'         => str_replace('.','',$kembalian), 
        'kredit'            => str_replace('.','',$request->kredit), 
        'nilai_kredit'      => str_replace('.','',$request->kredit), 
        'cara_bayar'        => $request->id_cara_bayar, 
        'status_beli_awal'  => $request->status_pembelian, 
        'tanggal_jt_tempo'  => $request->jatuh_tempo, 
        'keterangan'        => $request->keterangan, 
        'ppn'               => $request->ppn, 
        'warung_id'         => Auth::user()->id_warung 
      ]); 

          //HAPUS TBS PEMBELIAN 
      $data_produk_pembelian->delete(); 

      $pesan_alert =  
      '<div class="container-fluid"> 
      <div class="alert-icon"> 
      <i class="material-icons">check</i> 
      </div> 
      <b>Sukses : Berhasil Melakukan Transaksi PEMBELIAN Faktur "'.$no_faktur.'"</b> 
      </div>'; 

      Session::flash("flash_notification", [ 
        "level"     => "success", 
        "message"   => $pesan_alert 
      ]); 

      DB::commit(); 
      return redirect()->route('pembelian.index'); 

    } 
  } 

  public function datatableDetailPembelian(Request $request){ 

    $detail_pembelian = DetailPembelian::with(['produk'])->where('warung_id',Auth::user()->id_warung)->where('no_faktur',$request->no_faktur)->get(); 
    return Datatables::of($detail_pembelian)->addColumn('produk',function($data_pembelian){ 
      return $data_pembelian->TitleCaseBarang; 
    })->addColumn('jumlah_produk',function($data_pembelian){ 
      return $data_pembelian->PemisahJumlah; 
    })->addColumn('harga_produk',function($data_pembelian){ 
      return $data_pembelian->PemisahHarga; 
    })->addColumn('potongan',function($data_pembelian){ 
      return $data_pembelian->PemisahPotongan; 
    })->addColumn('tax',function($data_pembelian){ 
      return $data_pembelian->PemisahTax; 
    })->addColumn('subtotal',function($data_pembelian){ 
      return $data_pembelian->PemisahSubtotal; 
    })->make(true); 
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
    public function proses_form_edit($id)
    { 
      $session_id = session()->getId(); 
      $data_pembelian = Pembelian::find($id);   
      $data_produk_pembelian = DetailPembelian::where('no_faktur', $data_pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung); 

      $hapus_semua_edit_tbs_pembelian = EditTbsPembelian::where('no_faktur', $data_pembelian->no_faktur)->where('warung_id', Auth::user()->id_warung) 
      ->delete(); 

      foreach ($data_produk_pembelian->get() as $data_tbs) { 
        $detail_pembelian = EditTbsPembelian::create([ 
          'session_id'    => $session_id,              
          'no_faktur'     => $data_tbs->no_faktur, 
          'id_produk'     =>$data_tbs->id_produk,  
          'satuan_id'     =>$data_tbs->satuan_id,  
          'jumlah_produk' =>$data_tbs->jumlah_produk,      
          'harga_produk'  =>$data_tbs->harga_produk,     
          'subtotal'      =>$data_tbs->subtotal,    
          'tax'           =>$data_tbs->tax,   
          'potongan'       =>$data_tbs->potongan,     
          'ppn'           =>$data_tbs->ppn,      
          'warung_id'     => Auth::user()->id_warung, 
        ]); 
      } 
      return redirect()->route('pembelian.edit',$id);
    }

    public function edit(Request $request,$id, Builder $htmlBuilder) 
    { 
      $data_pembelian = Pembelian::find($id);  
      $no_faktur = $data_pembelian->no_faktur;
      $sum_subtotal = EditTbsPembelian::select(DB::raw('SUM(subtotal) as subtotal'))->where('no_faktur', $no_faktur)->where('warung_id',Auth::user()->id_warung)->first();   
      $subtotal = number_format($sum_subtotal->subtotal,2,',','.'); 
      $tbs_pembelian = EditTbsPembelian::with(['produk'])->where('no_faktur', $data_pembelian->no_faktur)->where('warung_id',Auth::user()->id_warung); 
      $jumlah_item =  $tbs_pembelian->count();
      $kas = TransaksiKas::select('jumlah_keluar')->where('no_faktur', $no_faktur)->where('warung_id',Auth::user()->id_warung); 
      if ($kas->count() == 0) {        
        $jumlah_kas_lama = 0;
      }else{
        $jumlah_kas_lama = $kas->first()->jumlah_keluar;
      }
      if ($request->ajax()) { 
        return Datatables::of($tbs_pembelian->get())->addColumn('action', function($TbsPembelian){ 

          $pesan_alert = 'Anda Yakin Ingin Menghapus Produk "'.$TbsPembelian->TitleCaseBarang.'" ?'; 
          return view('pembelian._hapus_produk', [ 
            'model'             => $TbsPembelian, 
            'form_url'          => route('editPembelian.hapus_tbs_pembelian', $TbsPembelian->id_edit_tbs_pembelians),   
            'confirm_message'   => $pesan_alert 
          ]); 
        }) 
        ->editColumn('data_produk_tbs', function($data_produk_tbs){ 

          return $data_produk_tbs->produk->kode_barang .' - '.$data_produk_tbs->TitleCaseBarang;  
        }) 
        ->editColumn('jumlah_produk', function($produk_tbs){ 
          return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk_tbs->id_edit_tbs_pembelians' data-nama='$produk_tbs->TitleCaseBarang'>$produk_tbs->jumlah_produk</a>";  
        }) 
        ->editColumn('harga_produk', function($produk){ 

         return "<a href='#edit-harga' id='edit_harga_produk' class='edit-harga' data-id='$produk->id_edit_tbs_pembelians'  data-nama='$produk->TitleCaseBarang'>$produk->harga_produk</a>";  
       }) 
        ->editColumn('potongan', function($produk){ 

          $potongan_persen = ($produk->potongan / ($produk->jumlah_produk * $produk->harga_produk)) * 100; 
          return "<a href='#edit-potongan' id='edit_potongan' class='edit-potongan' data-id='$produk->id_edit_tbs_pembelians' data-nama='$produk->TitleCaseBarang' data-jumlah='$produk->jumlah_produk' data-harga='$produk->harga_produk'>$produk->potongan"." | ".round($potongan_persen,2)."%</a>";  
        }) 
        ->editColumn('tax', function($produk)  use ($no_faktur) { 
          $ppn = EditTbsPembelian::select('ppn')->where('no_faktur', $no_faktur)->where('warung_id',Auth::user()->id_warung)->where('ppn','!=','')->limit(1); 
          if ($ppn->count() > 0) { 

           $ppn_produk = $ppn->first()->ppn; 
           $tax_persen = ($produk->tax * 100) / ($produk->jumlah_produk * $produk->harga_produk - $produk->potongan); 
         }else{ 
           $ppn_produk = ""; 
           $tax_persen = 0;  
         } 
         return "<a href='#edit-tax' id='edit_tax_produk' class='edit-tax' data-id='$produk->id_edit_tbs_pembelians'  data-jumlah='$produk->jumlah_produk' data-potongan='$produk->potongan' data-harga='$produk->harga_produk' data-ppn='$ppn_produk' data-nama='$produk->TitleCaseBarang'>$produk->tax"." | ".round($tax_persen,2)."%</a>";  
       })->make(true); 
      } 

      $html = $htmlBuilder 
      ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'orderable' => false, 'searchable'=>false]) 
      ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah']) 
      ->addColumn(['data' => 'harga_produk', 'name' => 'harga_produk', 'title' => 'Harga']) 
      ->addColumn(['data' => 'potongan', 'name' => 'potongan', 'title' => 'Potongan']) 
      ->addColumn(['data' => 'tax', 'name' => 'tax', 'title' => 'Pajak']) 
      ->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Subtotal']) 
      ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable'=>false]); 

      $kas_default = Kas::where('warung_id',Auth::user()->id_warung)->where('default_kas',1); 

      return view('pembelian.edit',[
        'subtotal_tbs'  => $subtotal,
        'kas_default'   =>$kas_default,
        'no_faktur'     =>$no_faktur,
        'pembelian'      => $data_pembelian,
        'jumlah_item'   => $jumlah_item,
        'jumlah_kas_lama'=> $jumlah_kas_lama] )->with(compact('html')); 

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
     $pesan_alert = 
     '<div class="container-fluid">
     <div class="alert-icon">
     <i class="material-icons">check</i>
     </div>
     <b>Pembelian Berhasil Dihapus</b>
     </div>';

     if (!Pembelian::destroy($id)) {
      return redirect()->back();
    }

    Session:: flash("flash_notification", [
      "level"=>"danger",
      "message"=> $pesan_alert
    ]);
    return redirect()->route('pembelian.index');
  } 
} 