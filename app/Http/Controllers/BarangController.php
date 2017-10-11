<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Barang;
use Laratrust;
use Auth;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        
        // datatable
        if ($request->ajax()) {
            # code...
            $data_barang = Barang::with(['satuan','kategori_barang'])->where('id_warung',Auth::user()->id_warung)->get();
            return Datatables::of($data_barang)
            ->addColumn('action', function($barang){ 
                return view('datatable._action',[

                        'model'             => $barang,
                        'form_url'          => route('barang.destroy',$barang->id),
                        'edit_url'          => route('barang.edit',$barang->id),
                        'confirm_message'   => 'Anda Yakin Mau Menghapus ' .$barang->nama_barang .' ?' 

                    ]);
            })
            ->addColumn('status_aktif', function($status){

                if ($status->status_aktif == 1) {
                    return "Aktif";
                }else{
                    return "Tidak Aktif";
                }

            })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'kode_barcode', 'name' => 'kode_barcode', 'title' => 'Barcode'])
        ->addColumn(['data' => 'kode_barang', 'name' => 'kode_barang', 'title' => 'Kode'])
        ->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama'])
        ->addColumn(['data' => 'harga_beli', 'name' => 'harga_beli', 'title' => 'Harga Beli'])
        ->addColumn(['data' => 'harga_jual', 'name' => 'harga_jual', 'title' => 'Harga Jual']) 
        ->addColumn(['data' => 'satuan.nama_satuan', 'name' => 'satuan.nama_satuan', 'title' => 'Satuan']) 
        ->addColumn(['data' => 'status_aktif', 'name' => 'status_aktif', 'title' => 'Status']) 
        ->addColumn(['data' => 'kategori_barang.nama_kategori_barang', 'name' => 'kategori_barang.nama_kategori_barang', 'title' => 'Kategori'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Ubah | Hapus', 'orderable' => false, 'searchable'=>false]);

        return view('barang.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //validate
        $this->validate($request, [
          'kode_barcode'        => 'unique:barangs,kode_barcode,NULL,id,id_warung,'.Auth::user()->id_warung.'|max:191', 
          'kode_barang'         => 'required|unique:barangs,kode_barang,NULL,id,id_warung,'.Auth::user()->id_warung.'|max:191',
          'nama_barang'         => 'required|max:191',
          'harga_beli'          => 'required|numeric|digits_between:1,11',
          'harga_jual'          => 'numeric|digits_between:1,11',
          'hitung_stok'         => 'required',
          'kategori_barang_id'  => 'required|exists:kategori_barangs,id',
          'satuan_id'           => 'required|exists:satuans,id',
          'status_aktif'        => 'required'
        ]);

        $insert_barang = Barang::create([
            'kode_barang'       => $request->kode_barang ,
            'kode_barcode'      => $request->kode_barcode, 
            'nama_barang'       => $request->nama_barang, 
            'harga_beli'        => $request->harga_beli, 
            'harga_jual'        => $request->harga_jual, 
            'satuan_id'         => $request->satuan_id, 
            'kategori_barang_id'=> $request->kategori_barang_id, 
            'status_aktif'      => $request->status_aktif, 
            'hitung_stok'       => $request->hitung_stok, 
            'id_warung'         => Auth::user()->id_warung]);

        $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <b><i class="material-icons">check</i></b>
                    </div>
                    <b>BERHASIL:</b> Menambahkan Produk <b>'.$request->nama_barang.'</b>
                </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);

        return redirect()->route('barang.index');
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

        $barang = Barang::find($id);
        return view('barang.edit')->with(compact('barang'));
    
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
        
                //validate
        $this->validate($request, [
          'kode_barcode'        => 'max:191|unique:barangs,kode_barcode,'. $id.',id,id_warung,'.Auth::user()->id_warung,
          'kode_barang'         => 'required|max:191|unique:barangs,kode_barang,'. $id.',id,id_warung,'.Auth::user()->id_warung,
          'nama_barang'         => 'required|max:191',
          'harga_beli'          => 'required|numeric|digits_between:1,11',
          'harga_jual'          => 'numeric|digits_between:1,11',
          'hitung_stok'         => 'required',
          'kategori_barang_id'  => 'required|exists:kategori_barangs,id',
          'satuan_id'           => 'required|exists:satuans,id',
          'status_aktif'        => 'required'

        ]);


        //update
        $barang = Barang::where('id',$id)->update([
            'kode_barang'       => $request->kode_barang ,
            'kode_barcode'      => $request->kode_barcode, 
            'nama_barang'       => $request->nama_barang, 
            'harga_beli'        => $request->harga_beli, 
            'harga_jual'        => $request->harga_jual, 
            'satuan_id'         => $request->satuan_id, 
            'kategori_barang_id'=> $request->kategori_barang_id, 
            'status_aktif'      => $request->status_aktif, 
            'hitung_stok'       => $request->hitung_stok, 
            'id_warung'         => Auth::user()->id_warung
        ]);
        


        $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <b><i class="material-icons">check</i></b>
                    </div>
                    <b>BERHASIL:</b> Mengubah Produk <b>'.$request->nama_barang.'</b>
                </div>';

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>$pesan_alert
            ]);

        return redirect()->route('barang.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
                // hapus 
        $barang = Barang::find($id);
        // jika gagal hapus
        if (!Barang::destroy($id)) {
            // redirect back
            return redirect()->back();
        }else{


        $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <b><i class="material-icons">check</i></b>
                    </div>
                    <b>BERHASIL:</b> Menghapus Produk <b>'.$barang->nama_barang.'</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "danger",
                "message"   => $pesan_alert
            ]);
        return redirect()->route('barang.index');
        }
    }
}
