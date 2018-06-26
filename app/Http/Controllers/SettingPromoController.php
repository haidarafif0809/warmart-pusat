<?php

namespace App\Http\Controllers;

use App\SettingPromo;
use App\FilterSettingPromo;
use App\WaktuSettingPromo;
use App\FilterSettingPromoRole;
use Auth;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Laratrust;
use File;

class SettingPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function view()
    {
        $settingpromo = SettingPromo::select(['barangs.nama_barang','barangs.kode_barang','setting_promos.baner_promo','setting_promos.harga_coret','setting_promos.id_setting_promo','setting_promos.jenis_promo','setting_promos.status','setting_promos.dari_tanggal','setting_promos.sampai_tanggal'])->leftJoin('barangs', 'barangs.id', '=', 'setting_promos.id_produk')->where('setting_promos.id_warung', Auth::user()->id_warung)->orderBy('setting_promos.id_setting_promo', 'desc')->paginate(10);
        $array             = array();

        foreach ($settingpromo as $settingpromos) {
            array_push($array, ['settingpromo' => $settingpromos]);
        }

        $url     = '/setting-promo/view';
        $respons = $this->paginationData($settingpromo, $array, $url);

        return response()->json($respons);
    }


    public function pencarian(Request $request)
    {
        $settingpromo = SettingPromo::select(['barangs.nama_barang','barangs.kode_barang','setting_promos.baner_promo','setting_promos.harga_coret','setting_promos.id_setting_promo'])->leftJoin('barangs', 'barangs.id', '=', 'setting_promos.id_produk')->where('setting_promos.id_warung', Auth::user()->id_warung)->where(function ($settingpromo) use ($request) {
            $settingpromo->orwhere('barangs.nama_barang', 'LIKE', '%' . $request->search . '%');
        })->orderBy('setting_promos.id_setting_promo', 'desc')->paginate(10);

        $array = array();
        foreach ($settingpromo as $settingpromos) {
            array_push($array, ['settingpromo' => $settingpromos]);
        }

        $url     = '/setting-promo/pencarian';
        //DATA PAGINATION
        $respons = $this->paginationData($settingpromo, $array,$url);

        return response()->json($respons);
    }

    public function paginationData($settingpromo, $array, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $settingpromo->currentPage();
        $respons['data']           = $array;
        $respons['otoritas']           = $this->otoritasSettingPromo();
        $respons['first_page_url'] = url($url . '?page=' . $settingpromo->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $settingpromo->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $settingpromo->lastPage());
        $respons['next_page_url']  = $settingpromo->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $settingpromo->perPage();
        $respons['prev_page_url']  = $settingpromo->previousPageUrl();
        $respons['to']             = $settingpromo->perPage();
        $respons['total']          = $settingpromo->total();
        //DATA PAGINATION

        return $respons;
    }


    public function dataFilter()
    {
     $filter_hari = FilterSettingPromo::where('grup','hari')->get();
     $filter_jam = FilterSettingPromo::where('grup','jam')->get(); 

     return response()->json([
      "filter_hari"         => $filter_hari,
      "filter_jam"     => $filter_jam,
  ]);

 }


 public function dataFilterEdit($id)
 {
     $filter_hari = FilterSettingPromo::where('grup','hari')->get();
     $filter_jam = FilterSettingPromo::where('grup','jam')->get(); 

     $arrayFilterHari = array();
     $arrayFilterJam = array();

     $waktu_setting_promo = WaktuSettingPromo::with('filter_setting_promo')->where('id_setting_promo',$id)->get();
     foreach ($waktu_setting_promo as $waktu_setting_promos) {
      if ($waktu_setting_promos->filter_setting_promo->grup == "hari") {
       array_push($arrayFilterHari, $waktu_setting_promos->waktu_promo);
   }
   if ($waktu_setting_promos->filter_setting_promo->grup == "jam") {
       array_push($arrayFilterJam, $waktu_setting_promos->waktu_promo);
   }
}

return response()->json([
  "filter_hari"         => $filter_hari,
  "filter_jam"     => $filter_jam,
  "data_filter_hari"     => $arrayFilterHari,
  "data_filter_jam"     => $arrayFilterJam,
]);

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
        //
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $produk    = explode("|", $request->produk);
            $id_produk = $produk[0];


            if ($request->status_aktif == "true" || $request->status_aktif == 1) {
               $status_aktif = 1;
           }else if($request->status_aktif == "false" || $request->status_aktif == 0) {
               $status_aktif = 0;
           }

           $data_promo = SettingPromo::leftJoin('barangs', 'barangs.id', '=', 'setting_promos.id_produk')->where('setting_promos.id_produk', $id_produk)->where('setting_promos.id_warung', Auth::user()->id_warung);

           if ($data_promo->count() > 0) {
              $respons['data_promo'] = 1;
              $respons['nama_barang'] = $data_promo->first()->nama_barang;
              return response()->json($respons);
          }
          else{

                $this->validate($request, [
                'harga_promo'         => 'required|numeric|digits_between:1,11',
                'baner_promo'         => 'image|max:3072',
                'dari_tanggal'        => 'required',
                'sampai_tanggal'      => 'required',
                'jenis_promo'         => 'required'
                ]);

                    $insert_setting = SettingPromo::create([
                        'harga_coret'        => $request->harga_promo,
                        'id_produk'          => $id_produk,
                        'id_warung'          => Auth::user()->id_warung,
                        'dari_tanggal'       => $request->dari_tanggal,
                        'sampai_tanggal'     => $request->sampai_tanggal,
                        'jenis_promo'        => $request->jenis_promo,
                        'status'             => $status_aktif]);

            if ($request->hasFile('baner_promo')) {
                $baner_promo = $request->file('baner_promo');

                if (is_array($baner_promo) || is_object($baner_promo)) {
                            // Mengambil file yang diupload
                    $uploaded_baner_promo = $baner_promo;
                            // mengambil extension file
                    $extension = $uploaded_baner_promo->getClientOriginalExtension();
                            // membuat nama file random berikut extension
                    $filename     = str_random(40) . '.' . $extension;
                    $image_resize = Image::make($baner_promo->getRealPath());
                    $image_resize->fit(2100, 370);
                    $image_resize->save(public_path('baner_setting_promo/' . $filename));
                    $insert_setting->baner_promo = $filename;
                            // menyimpan field baner_promo di database setting_promo dengan filename yang baru dibuat
                    $insert_setting->save();
                }

            }

            return $insert_setting->id_setting_promo;
        }
    }
}



public function tambahWaktu(Request $request,$id)
{
            //Insert data waktu setting promo
 foreach ($request->hari as $setting_hari) {
    $insert_setting = WaktuSettingPromo::create([
        'id_setting_promo'   => $id,
        'waktu_promo'        => $setting_hari,
        'id_warung'          => Auth::user()->id_warung]);
}

           //Insert data waktu setting promo
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $settingpromo = SettingPromo::select(['barangs.nama_barang','barangs.kode_barang','setting_promos.baner_promo','setting_promos.harga_coret as harga_promo','setting_promos.id_setting_promo','barangs.harga_jual as harga_produk','setting_promos.id_produk as produk','setting_promos.dari_tanggal','setting_promos.sampai_tanggal','setting_promos.jenis_promo','setting_promos.status as status_aktif','setting_promos.dari_tanggal as dari_tanggal_lama','setting_promos.sampai_tanggal as sampai_tanggal_lama'])->leftJoin('barangs', 'barangs.id', '=', 'setting_promos.id_produk')->where('setting_promos.id_warung', Auth::user()->id_warung)->where('setting_promos.id_setting_promo', $id)->first();

        return $settingpromo;
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
        $update_setting = SettingPromo::find($id);
        if ($update_setting->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {

            $produk    = explode("|", $request->produk);
            $id_produk = $produk[0];

            if ($request->status_aktif == "true" || $request->status_aktif == 1) {
               $status_aktif = 1;
           }else if($request->status_aktif == "false" || $request->status_aktif == 0) {
               $status_aktif = 0;
           }

                  $this->validate($request, [
                        'harga_promo'         => 'required|numeric|digits_between:1,11',
                        'baner_promo'         => 'image|max:3072',
                        'dari_tanggal'        => 'required',
                        'sampai_tanggal'      => 'required',
                        'jenis_promo'         => 'required'
                  ]);

                    $update_setting->update([
                        'harga_coret'        => $request->harga_promo,
                        'id_produk'          => $id_produk,
                        'id_warung'          => Auth::user()->id_warung,
                        'dari_tanggal'       => $request->dari_tanggal,
                        'sampai_tanggal'     => $request->sampai_tanggal,
                        'jenis_promo'        => $request->jenis_promo,
                        'status'             => $status_aktif
                    ]);


           if ($request->hasFile('baner_promo')) {
                        // Mengambil file yang diupload
            $baner_promo          = $request->file('baner_promo');
            $uploaded_baner_promo = $baner_promo;
                        // mengambil extension file
            $extension = $uploaded_baner_promo->getClientOriginalExtension();
                        // membuat nama file random berikut extension
            $filename     = str_random(40) . '.' . $extension;
            $image_resize = Image::make($baner_promo->getRealPath());
            $image_resize->fit(2100, 370);
            $image_resize->save(public_path('baner_setting_promo/' . $filename));
                        // hapus baner_promo_home lama, jika ada
            if ($update_setting->baner_promo) {
                $old_baner_promo = $update_setting->baner_promo;
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'baner_setting_promo'
                . DIRECTORY_SEPARATOR . $update_setting->baner_promo;
                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                                // File sudah dihapus/tidak ada
                }
            }
            $update_setting->baner_promo = $filename;
            $update_setting->save();
        }
    }
}

public function tambahWaktuEdit(Request $request,$id)
{
    WaktuSettingPromo::where('id_setting_promo',$id)->delete();

            //Insert data waktu setting promo
    foreach ($request->hari as $setting_hari) {
        $insert_setting = WaktuSettingPromo::create([
            'id_setting_promo'   => $id,
            'waktu_promo'        => $setting_hari,
            'id_warung'          => Auth::user()->id_warung]);
    }

           //Insert data waktu setting promo
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
        $barang = SettingPromo::find($id);

        if ($barang->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            SettingPromo::destroy($id);
            WaktuSettingPromo::where('id_setting_promo',$id)->delete();
        }
    }


    public function otoritasSettingPromo(){

        if (Laratrust::can('tambah_setting_promo')) {
            $tambah_setting_promo = 1;
        }else{
            $tambah_setting_promo = 0;            
        }
        if (Laratrust::can('edit_setting_promo')) {
            $edit_setting_promo = 1;
        }else{
            $edit_setting_promo = 0;            
        }
        if (Laratrust::can('hapus_setting_promo')) {
            $hapus_setting_promo = 1;
        }else{
            $hapus_setting_promo = 0;            
        }
        $respons['tambah_setting_promo'] = $tambah_setting_promo;
        $respons['edit_setting_promo'] = $edit_setting_promo;
        $respons['hapus_setting_promo'] = $hapus_setting_promo;

        return response()->json($respons);
    }

}
