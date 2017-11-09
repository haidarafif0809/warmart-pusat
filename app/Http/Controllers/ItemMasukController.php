<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\ItemMasuk;  
use App\TbsItemMasuk;  
use App\DetailItemMasuk;
use App\EditTbsItemMasuk;  
use App\Barang;  
use Session;
use Auth;
use Laratrust;


class ItemMasukController extends Controller
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
      if ($request->ajax()) { 
        $item_masuk = ItemMasuk::where('warung_id',Auth::user()->id_warung);
        return Datatables::of($item_masuk)->addColumn('action', function($itemmasuk){
          $detail_item_masuk = DetailItemMasuk::with(['produk'])->where('warung_id',Auth::user()->id_warung)->where('no_faktur',$itemmasuk->no_faktur)->get();
          return view('item_masuk._action', [
            'model'     => $itemmasuk,
            'id_item_masuk'     => $itemmasuk->id,
            'data_detail_item_masuk'     => $detail_item_masuk,
            'form_url'  => route('item-masuk.destroy', $itemmasuk->id),
            'edit_url'  => route('item-masuk.proses_form_edit', $itemmasuk->id),
            'confirm_message'   => 'Anda Yakin Ingin Menghapus Item Keluar Faktur "'.$itemmasuk->no_faktur.'" ?',
            ]);
        }) ->make(true);
      }
      $html = $htmlBuilder
      ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No. Faktur'])  
      ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan']) 
      ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu']) 
      ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Waktu Edit'])  
      ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]); 
      return view('item_masuk.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Builder $htmlBuilder)
    {
      if ($request->ajax()) { 
        $session_id = session()->getId();
        $user_warung = Auth::user()->id_warung;
        $tbs_item_masuk = TbsItemMasuk::with(['produk'])->where('warung_id',$user_warung)->where('session_id', $session_id)->get();
        return Datatables::of($tbs_item_masuk)->addColumn('action', function($tbsitemmasuk){
          return view('item_masuk._hapus_produk', [
            'model'     => $tbsitemmasuk,
            'form_url'  => route('item-masuk.proses_hapus_tbs_item_masuk', $tbsitemmasuk->id_tbs_item_masuk),  
            'confirm_message'   => 'Yakin Mau Menghapus Produk ?'
            ]);
        })        
        ->editColumn('jumlah_produk', function($produk){
          return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk->id_tbs_item_masuk' data-nama='$produk->TitleCaseProduk' >$produk->jumlah_produk</a>"; 
        })
        ->addColumn('data_produk_tbs', function($data_produk_tbs){ 
          $produk = Barang::find($data_produk_tbs->id_produk);
          $data_produk = $produk->kode_barang ." - ". $produk->nama_barang;          
          return $data_produk;   
        })->make(true);
      }

      $html = $htmlBuilder 
      ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'orderable' => false, 'searchable'=>false ]) 
      ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
      ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable'=>false]);

      return view('item_masuk.create')->with(compact('html'));
    }

     //PROSES TAMBAH TBS ITEM MASUK
    public function proses_tambah_tbs_item_masuk(Request $request)
    { 

      $this->validate($request, [
        'id_produk_tbs'     => 'required|max:11|numeric',
        'jumlah_produk' => 'required|digits_between:1,15|numeric',
        ]);

      $session_id = session()->getId();

      $data_tbs = TbsItemMasuk::select('id_produk')
      ->where('id_produk', $request->id_produk_tbs)
      ->where('session_id', $session_id)
      ->count();



      $data_produk = Barang::select('nama_barang')->where('id', $request->id_produk)->first();
      $pesan_alert = "Produk '".$data_produk->nama_barang."' Sudah Ada, Silakan Pilih Produk Lain !";


      //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
      if ($data_tbs > 0) {

        $pesan_alert = 
        '<div class="container-fluid">
        <div class="alert-icon">
          <i class="material-icons">warning</i>
        </div>
        <b>Warning : Produk "'.$data_produk->nama_barang.'" Sudah Ada, Silakan Pilih Produk Lain !</b>
      </div>';

      Session::flash("flash_notification", [
        "level"=>"warning",
        "message"=> $pesan_alert
        ]); 

      return back();
    }
    else{

      $pesan_alert = 
      '<div class="container-fluid">
      <div class="alert-icon">
        <i class="material-icons">check</i>
      </div>
      <b>Sukses : Berhasil Menambah Produk "'.$data_produk->nama_barang.'"</b>
    </div>';

    $tbsitemmasuk = TbsItemMasuk::create([
      'id_produk' =>$request->id_produk_tbs,            
      'session_id' => $session_id,
      'jumlah_produk' =>$request->jumlah_produk,
      'warung_id' => Auth::user()->id_warung,
      ]);

    Session::flash("flash_notification", [
      "level"=>"success",
      "message"=> $pesan_alert
      ]);
    return back();

  }
}

     //PROSES HAPUS TBS ITEM MASUK
public function proses_hapus_tbs_item_masuk($id)
{ 
  if (!TbsItemMasuk::destroy($id)) {
    $pesan_alert = 
    '<div class="container-fluid">
    <div class="alert-icon">
      <i class="material-icons">check</i>
    </div>
    <b>Gagal : Menghapus Produk</b>
  </div>';

  Session::flash("flash_notification", [
    "level"     => "danger",
    "message"   => $pesan_alert
    ]);
  return back();
}
else{
  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Menghapus Produk</b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);
return back();
}
}

     //PROSES HAPUS EDIT TBS ITEM MASUK
public function proses_hapus_edit_tbs_item_masuk($id)
{ 
  EditTbsItemMasuk::where('id_edit_tbs_item_masuk',$id)->delete();

  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Menghapus Produk</b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);

return back();
}


        //PROSES BATAL ITEM MASUK
public function proses_hapus_semua_tbs_item_masuk()
{ 
  $session_id = session()->getId();
  $data_tbs_item_masuk = TbsItemMasuk::where('session_id', $session_id)->delete(); 
  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Membatalkan Item Masuk</b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);
return redirect()->route('item-masuk.create');
}

        //PROSES BATAL EDIT ITEM MASUK
public function proses_hapus_semua_edit_tbs_item_masuk($id)
{   
        //MENGAMBIL ID ITEM MASUK
  $data_item_masuk = ItemMasuk::find($id); 
        //PROSES MENGHAPUS SEMUA EDTI TBS SESUAI NO FAKTUR YANG DI AMBIL 
  $data_tbs_item_masuk = EditTbsItemMasuk::where('no_faktur', $data_item_masuk->no_faktur)->delete(); 
  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Membatalkan Edit Item Masuk</b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);
return redirect()->route('item-masuk.index');
}

    //PROSES SELESAI TRANSAKSI EDIT ITEM MASUK
public function proses_edit_item_masuk(Request $request,$id) {

  $data_item_masuk = ItemMasuk::find($id);  
  $session_id = session()->getId();
  $user = Auth::user()->id; 

//INSERT DETAIL ITEM MASUK
  $data_produk_item_masuk = EditTbsItemMasuk::where('no_faktur', $data_item_masuk->no_faktur)->where('warung_id', Auth::user()->id_warung);

  if ($data_produk_item_masuk->count() == 0) {

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
}
else{
  $data_detail_item_masuk = DetailItemMasuk::where('no_faktur', $data_item_masuk->no_faktur)->where('warung_id', Auth::user()->id_warung)->get();

    //HAPUS DETAIL ITEM MASUK
  foreach ($data_detail_item_masuk as $data_detail) {            

    if (!$hapus_detail = DetailItemMasuk::destroy($data_detail->id_detail_item_masuk)) {
                    //DI BATALKAN PROSES NYA
      DB::rollBack();
      return redirect()->back();
    }
  }


  foreach ($data_produk_item_masuk->get() as $data_tbs) {

    $detail_item_masuk = DetailItemMasuk::create([
      'id_produk'     => $data_tbs->id_produk,              
      'no_faktur'     => $data_item_masuk->no_faktur,
      'jumlah_produk' => $data_tbs->jumlah_produk,
      'warung_id'     => Auth::user()->id_warung
      ]);   

  }

    //INSERT ITEM MASUK
  if ($request->keterangan == "") {
    $keterangan = "-";
  }
  else{
    $keterangan = $request->keterangan;
  }

  $itemmasuk = ItemMasuk::find($id)->update([ 
    'keterangan' =>$keterangan
    ]);

  $hapus_edit_tbs_item_masuk = EditTbsItemMasuk::where('no_faktur', $data_item_masuk->no_faktur)->where('warung_id', Auth::user()->id_warung)->delete(); 

  if (!$itemmasuk) {
    return back();
  }

  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Melakukan Edit Transaksi Item Masuk Faktur "'.$data_item_masuk->no_faktur.'"</b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);

return redirect()->route('item-masuk.index');
}        
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    //PROSES SELESAI TRANSAKSI ITEM MASUK
    public function store(Request $request) {

    //START TRANSAKSI
      DB::beginTransaction();
      $warung_id = Auth::user()->id_warung;
      $session_id = session()->getId();
      $user = Auth::user()->id;
      $no_faktur = ItemMasuk::no_faktur($warung_id);

      //INSERT DETAIL ITEM MASUK
      $data_produk_item_masuk = TbsItemMasuk::where('session_id', $session_id)->where('warung_id',$warung_id);

        //jika belum ada produk yang di inputkan 
      if ($data_produk_item_masuk->count() == 0) {

       $pesan_alert = 
       '<div class="container-fluid">
       <div class="alert-icon">
        <i class="material-icons">error</i>
      </div>
      <b>Gagal : Belum ada Produk Yang Di inputkan</b>
    </div>';

    Session::flash("flash_notification", [
      "level"     => "danger",
      "message"   => $pesan_alert
      ]);

    
    return redirect()->back();
  } 

        //INSERT ITEM MASUK
  if ($request->keterangan == "") {
    $keterangan = "-";
  }
  else{
    $keterangan = $request->keterangan;
  }

  foreach ($data_produk_item_masuk->get() as $data_tbs) {
    $detail_item_masuk = DetailItemMasuk::create([
      'id_produk' =>$data_tbs->id_produk,              
      'no_faktur' => $no_faktur,
      'jumlah_produk' =>$data_tbs->jumlah_produk,
      'warung_id' => $warung_id,
      ]);
  }

  $itemmasuk = ItemMasuk::create([
    'no_faktur' => $no_faktur,
    'keterangan' =>$keterangan,
    'warung_id' => $warung_id,
    ]);

  if (!$itemmasuk) {
    return back();
  }

        //HAPUS TBS ITEM MASUK
  $data_produk_item_masuk->delete();

  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Melakukan Transaksi Item Masuk Faktur "'.$no_faktur.'"</b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);

DB::commit(); 
return redirect()->route('item-masuk.index');
}

    /**                                                                                                                                                                                                                                                                                                                           
     * Display the specified resource.
     *
     * @param  \App\ItemMasukController  $itemMasukController
     * @return \Illuminate\Http\Response
     */
    public function show(ItemMasukController $itemMasukController)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ItemMasukController  $itemMasukController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemMasukController $itemMasukController)
    {
        //
    }


    public function proses_form_edit($id)
    {
        //
      $session_id = session()->getId();
      $data_item_masuk = ItemMasuk::find($id);  
      $data_produk_item_masuk = DetailItemMasuk::where('no_faktur', $data_item_masuk->no_faktur);

      $hapus_semua_edit_tbs_item_masuk = EditTbsItemMasuk::where('no_faktur', $data_item_masuk->no_faktur)->delete();
      foreach ($data_produk_item_masuk->get() as $data_tbs) {
        $detail_item_masuk = EditTbsItemMasuk::create([
          'id_produk' =>$data_tbs->id_produk,              
          'no_faktur' => $data_tbs->no_faktur,
          'jumlah_produk' =>$data_tbs->jumlah_produk,          
          'session_id' => $session_id,
          'warung_id' =>Auth::user()->id_warung
          ]);
      }

      return redirect()->route('item-masuk.edit',$id);
    }

      //MENAMPILKAN DATA DI TBS ITEM MASUK
    public function edit(Request $request, Builder $htmlBuilder,$id)
    {   
      if ($request->ajax()) {  
        $item_masuk = ItemMasuk::find($id); 
        $tbs_item_masuk = EditTbsItemMasuk::with(['produk'])->where('warung_id',Auth::user()->id_warung)->where('no_faktur', $item_masuk->no_faktur)->get();
        return Datatables::of($tbs_item_masuk)->addColumn('action', function($tbsitemmasuk){
          return view('item_masuk._hapus_produk', [
            'model'     => $tbsitemmasuk,
            'form_url'  => route('item-masuk.proses_hapus_edit_tbs_item_masuk', $tbsitemmasuk->id_edit_tbs_item_masuk),  
            'confirm_message'   => 'Yakin Mau Menghapus Produk ?'
            ]);
        })      
        ->editColumn('jumlah_produk', function($produk){
          return "<a href='#edit-jumlah' id='edit_jumlah_produk' class='edit-jumlah' data-id='$produk->id_edit_tbs_item_masuk' data-nama='$produk->TitleCaseProduk' >$produk->jumlah_produk</a>"; 
        })->addColumn('data_produk_tbs', function($data_produk_tbs){ 
          $produk = Barang::find($data_produk_tbs->id_produk);
          $data_produk = $produk->kode_barang ." - ". $produk->nama_barang;          
          return $data_produk;   
        })->make(true);
      }

      $html = $htmlBuilder 
      ->addColumn(['data' => 'data_produk_tbs', 'name' => 'data_produk_tbs', 'title' => 'Produk', 'searchable'=>false ]) 
      ->addColumn(['data' => 'jumlah_produk', 'name' => 'jumlah_produk', 'title' => 'Jumlah'])
      ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable'=>false]);

      $item_masuk = ItemMasuk::find($id); 
      return view('item_masuk.edit')->with(compact('html','item_masuk'));
    }

        //PROSES TAMBAH EDIT TBS ITEM MASUK
    public function proses_tambah_edit_tbs_item_masuk(Request $request,$id)
    { 
      $this->validate($request, [
        'id_produk_tbs'     => 'required|max:11|numeric',
        'jumlah_produk' => 'required|max:8|numeric',
        ]);

      $data_item_masuk = ItemMasuk::find($id);    
      $session_id = session()->getId();

      $data_tbs = EditTbsItemMasuk::select('id_produk')
      ->where('id_produk', $request->id_produk_tbs)
      ->where('no_faktur', $data_item_masuk->no_faktur)
      ->where('session_id', $session_id)
      ->where('warung_id', Auth::user()->id_warung)
      ->count();

      $data_produk = Barang::select('nama_barang')->where('id', $request->id_produk)->first();
      $pesan_alert = "Produk '".$data_produk->nama_barang."' Sudah Ada, Silakan Pilih Produk Lain !";


      //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
      if ($data_tbs > 0) {

        $pesan_alert = 
        '<div class="container-fluid">
        <div class="alert-icon">
          <i class="material-icons">warning</i>
        </div>
        <b>Warning : Produk "'.$data_produk->nama_barang.'" Sudah Ada, Silakan Pilih Produk Lain !</b>
      </div>';

      Session::flash("flash_notification", [
        "level"=>"warning",
        "message"=> $pesan_alert
        ]); 

      return back();
    }
    else{

     $pesan_alert = 
     '<div class="container-fluid">
     <div class="alert-icon">
      <i class="material-icons">check</i>
    </div>
    <b>Sukses : Berhasil Menambah Produk "'.$data_produk->nama_barang.'"</b>
  </div>';

  $tbsitemmasuk = EditTbsItemMasuk::create([
    'id_produk' =>$request->id_produk_tbs,    
    'no_faktur' =>$data_item_masuk->no_faktur,                    
    'session_id' => $session_id,
    'jumlah_produk' =>$request->jumlah_produk,
    'warung_id' => Auth::user()->id_warung,
    ]);

  Session::flash("flash_notification", [
    "level"=>"success",
    "message"=> $pesan_alert
    ]);
  return back();

}
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ItemMasukController  $itemMasukController
     * @return \Illuminate\Http\Response
     */
        //PROSES HAPUS ITEM MASUK
    public function destroy($id)
    { 
      $pesan_alert = 
      '<div class="container-fluid">
      <div class="alert-icon">
        <i class="material-icons">check</i>
      </div>
      <b>Sukses : Item Masuk Berhasil Dihapus</b>
    </div>';

    if (!ItemMasuk::destroy($id)) {
      return redirect()->back();
    }

    Session:: flash("flash_notification", [
      "level"=>"danger",
      "message"=> $pesan_alert
      ]);
    return redirect()->route('item-masuk.index');
  }

  public function proses_edit_jumlah(Request $request){

    $tbs_item_masuk = TbsItemMasuk::find($request->id_tbs_item_masuk);

    $tbs_item_masuk->update(['jumlah_produk' => $request->jumlah_beli_baru]);
    $nama_barang = $tbs_item_masuk->produk->nama_barang;

    $pesan_alert = 
    '<div class="container-fluid">
    <div class="alert-icon">
      <i class="material-icons">check</i>
    </div>
    <b>Sukses : Berhasil Mengubah Jumlah Item Masuk "'.$nama_barang.'"  </b>
  </div>';

  Session::flash("flash_notification", [
    "level"     => "success",
    "message"   => $pesan_alert
    ]);

  return redirect()->back();

} 

public function proses_edit_jumlah_edit(Request $request){ 
  $tbs_item_masuk = EditTbsItemMasuk::find($request->id_edit_tbs_item_masuk);

  $tbs_item_masuk->update(['jumlah_produk' => $request->jumlah_beli_baru]);
  $nama_barang = $tbs_item_masuk->produk->nama_barang;

  $pesan_alert = 
  '<div class="container-fluid">
  <div class="alert-icon">
    <i class="material-icons">check</i>
  </div>
  <b>Sukses : Berhasil Mengubah Jumlah Item Masuk "'.$nama_barang.'"  </b>
</div>';

Session::flash("flash_notification", [
  "level"     => "success",
  "message"   => $pesan_alert
  ]);

return redirect()->back();

} 
}
