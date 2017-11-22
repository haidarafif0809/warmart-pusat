<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\KategoriBarang;
use App\Barang;
use Session;
use Laratrust;

class KelompokProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
      $this->middleware('user-must-admin');
    }
    public function index()
    {
        //
    }


    public function view (){
     $kelompok_produk = KategoriBarang::orderBy('id','desc')->paginate(10);
     $array = array();
     foreach ($kelompok_produk as $kelompok_produks) {
      $barang = Barang::where('kategori_barang_id',$kelompok_produks->id)->count();
      if ($barang > 0) {
        $status_kelompok_produk = 1;
      }else{
        $status_kelompok_produk = 0;
      }

      array_push($array,[
        'id' => $kelompok_produks->id,
        'nama_kategori_barang' => $kelompok_produks->nama_kategori_barang,
        'kategori_icon' => $kelompok_produks->kategori_icon,
        'status_kelompok_produk' => $status_kelompok_produk]);
    }

     //DATA PAGINATION 
    $respons['current_page'] = $kelompok_produk->currentPage();
    $respons['data'] = $array; 
    $respons['first_page_url'] = url('/kelompok-produk/view?page='.$kelompok_produk->firstItem());
    $respons['from'] = 1;
    $respons['last_page'] = $kelompok_produk->lastPage();
    $respons['last_page_url'] = url('/kelompok-produk/view?page='.$kelompok_produk->lastPage());
    $respons['next_page_url'] = $kelompok_produk->nextPageUrl();
    $respons['path'] = url('/kelompok-produk/view');
    $respons['per_page'] = $kelompok_produk->perPage();
    $respons['prev_page_url'] = $kelompok_produk->previousPageUrl();
    $respons['to'] = $kelompok_produk->perPage();
    $respons['total'] = $kelompok_produk->total();
                  //DATA PAGINATION 
    return response()->json($respons);  
  }

  public function pencarian (Request $request){
   $kelompok_produk = KategoriBarang::where('nama_kategori_barang','LIKE',$request->search.'%')->orderBy('id','desc')->paginate(10);
   $array = array();
   foreach ($kelompok_produk as $kelompok_produks) {
    $barang = Barang::where('kategori_barang_id',$kelompok_produks->id)->count();
    if ($barang > 0) {
      $status_kelompok_produk = 1;
    }else{
      $status_kelompok_produk = 0;
    }

    array_push($array,[
      'id' => $kelompok_produks->id,
      'nama_kategori_barang' => $kelompok_produks->nama_kategori_barang,
      'kategori_icon' => $kelompok_produks->kategori_icon,
      'status_kelompok_produk' => $status_kelompok_produk]);
  }

     //DATA PAGINATION 
  $respons['current_page'] = $kelompok_produk->currentPage();
  $respons['data'] = $array; 
  $respons['first_page_url'] = url('/kelompok-produk/view?page='.$kelompok_produk->firstItem());
  $respons['from'] = 1;
  $respons['last_page'] = $kelompok_produk->lastPage();
  $respons['last_page_url'] = url('/kelompok-produk/view?page='.$kelompok_produk->lastPage());
  $respons['next_page_url'] = $kelompok_produk->nextPageUrl();
  $respons['path'] = url('/kelompok-produk/view');
  $respons['per_page'] = $kelompok_produk->perPage();
  $respons['prev_page_url'] = $kelompok_produk->previousPageUrl();
  $respons['to'] = $kelompok_produk->perPage();
  $respons['total'] = $kelompok_produk->total();
                  //DATA PAGINATION 
  return response()->json($respons); 
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        'nama_kelompok'     => 'required|unique:kategori_barangs,nama_kategori_barang',
        'icon_kelompok'      => 'required']);

      $kelompok_produk = KategoriBarang::create([
        'nama_kategori_barang' => $request->nama_kelompok,
        'kategori_icon' => $request->icon_kelompok
      ]);
      return response(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $kelompok_produk = KategoriBarang::find($id);
      $kelompok_produk['nama_kelompok'] = $kelompok_produk->nama_kategori_barang;        
      $kelompok_produk['icon_kelompok'] = $kelompok_produk->kategori_icon;
      return $kelompok_produk;
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
        'nama_kelompok'     => 'required|unique:kategori_barangs,nama_kategori_barang,'.$id,
        'icon_kelompok'      => 'required']);

      $kelompok_produk = KategoriBarang::find($id)->update([
        'nama_kategori_barang' => $request->nama_kelompok,
        'kategori_icon' => $request->icon_kelompok
      ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      KategoriBarang::destroy($id);

      return response(200);
    }
  }
