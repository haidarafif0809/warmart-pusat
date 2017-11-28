<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Warung;
use App\UserWarung;
use App\BankWarung;
use App\Kelurahan;
use Indonesia;
use Session;
use Laratrust;
use Auth;

class WarungProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder) 
    {  
        return view('warung_profil.index')->with(compact('html')); 
    } 

    public function view(){
        $warung = Warung::with(['bank_warung'])->where('id', Auth::user()->id_warung)->paginate(10);

        $warung_array = array();
        foreach ($warung as $warungs) {
            $no_rek = $warungs->bank_warung->no_rek;
            $nama_bank = $warungs->bank_warung->nama_bank;
            $atas_nama = $warungs->bank_warung->atas_nama;
            $provinsi = Indonesia::findProvince($warungs->provinsi);
            $kabupaten = Indonesia::findCity($warungs->kabupaten);
            $kecamatan = Indonesia::findDistrict($warungs->kecamatan);
            $kelurahan = Indonesia::findVillage($warungs->wilayah);

            array_push($warung_array, ['warung'=>$warungs, 'no_rek'=>$no_rek, 'nama_bank'=>$nama_bank, 'atas_nama'=>$atas_nama, 'provinsi'=>$provinsi, 'kabupaten'=>$kabupaten, 'kecamatan'=>$kecamatan, 'kelurahan'=>$kelurahan]);
        }

        //DATA PAGINATION 
        $respons['current_page'] = $warung->currentPage();
        $respons['data'] = $warung_array;
        $respons['first_page_url'] = url('/profil-warung/view?page='.$warung->firstItem());
        $respons['from'] = 1;
        $respons['last_page'] = $warung->lastPage();
        $respons['last_page_url'] = url('/profil-warung/view?page='.$warung->lastPage());
        $respons['next_page_url'] = $warung->nextPageUrl();
        $respons['path'] = url('/profil-warung/view');
        $respons['per_page'] = $warung->perPage();
        $respons['prev_page_url'] = $warung->previousPageUrl();
        $respons['to'] = $warung->perPage();
        $respons['total'] = $warung->total();
        //DATA PAGINATION

        return response()->json($respons);
    }

    public function pilih_provinsi(){
        $provinsi = Indonesia::allProvinces();
        return response()->json($provinsi);
    }

    public function pilih_kabupaten($id){
        $kabupaten = Indonesia::allCities()->where('province_id', $id);
        return response()->json($kabupaten);
    }

    public function pilih_kecamatan($id){
        $kecamatan = Indonesia::allDistricts()->where('city_id', $id);
        return response()->json($kecamatan);
    }

    public function pilih_kelurahan($id){
        $kelurahan = Indonesia::allVillages()->where('district_id', $id);
        return response()->json($kelurahan);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     $warung = Warung::with(['bank_warung'])->find($id);
     $warung['provinsi'] =  $warung->provinsi;
     $warung['nama_bank'] = $warung->bank_warung->nama_bank;
     $warung['atas_nama'] = $warung->bank_warung->atas_nama;
     $warung['no_rek'] = $warung->bank_warung->no_rek;

     return $warung;
 }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warung_profil = Warung::with(['bank_warung'])->find($id);
        $provinsi = Indonesia::allProvinces()->pluck('name','id');

        return view('warung_profil.edit', ['provinsi'=>$provinsi])->with(compact('warung_profil'));
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
    //VALIDASI WARUNG
       $this->validate($request, [
        'name'      => 'required|unique:warungs,name,'.$id,
        'alamat'    => 'required',
        'provinsi'  => 'required',
        'kabupaten' => 'required',
        'kecamatan' => 'required',
        'kelurahan' => 'required',
        'no_telpon' => 'required|max:15',
        ]);

         //UPDATE MASTER DATA WARUNG
       $warung = Warung::where('id',$id)->update([
        'name'      =>$request->name,
        'alamat'    =>$request->alamat,
        'provinsi'  =>$request->provinsi,
        'kabupaten' =>$request->kabupaten,
        'kecamatan' =>$request->kecamatan,
        'wilayah'   =>$request->kelurahan,
        'no_telpon' =>$request->no_telpon, 
        'email'     =>$request->email,
        ]);

       $bank_warung_id = BankWarung::select('id')->where('warung_id', $id)->first();

        //VALIDASI BANK WARUNG
       $this->validate($request, [
        'nama_bank' => 'required',
        'atas_nama' => 'required', 
        'no_rek'    => 'required|numeric|unique:bank_warungs,no_rek,'.$bank_warung_id->id, 
        ]);

         //UPDATE BANK WARUNG
       $bank_warung = BankWarung::where('warung_id',$id)->update([
        'nama_bank' =>$request->nama_bank,
        'atas_nama' =>$request->atas_nama,
        'no_rek' =>$request->no_rek,
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
        //
    }
}
