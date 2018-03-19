<?php

namespace App\Http\Controllers;

use App\Kelurahan;
use App\SettingAplikasi;
use App\Warung;
use Auth;
use Illuminate\Http\Request;
use Indonesia;
use Yajra\Datatables\Html\Builder;

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

    public function view()
    {
        $warung           = Warung::with(['bank_warung'])->where('id', Auth::user()->id_warung)->paginate(10);
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $warung_array = array();
        foreach ($warung as $warungs) {
            $no_rek    = $warungs->bank_warung->no_rek;
            $nama_bank = $warungs->bank_warung->nama_bank;
            $atas_nama = $warungs->bank_warung->atas_nama;
            if ($warungs->provinsi == null) {
                $provinsi  = "";
                $kabupaten = "";
                $kecamatan = "";
                $kelurahan = "";
            } else {
                $provinsi  = Indonesia::findProvince($warungs->provinsi);
                $kabupaten = Indonesia::findCity($warungs->kabupaten);
                $kecamatan = Indonesia::findDistrict($warungs->kecamatan);
                $kelurahan = Indonesia::findVillage($warungs->wilayah);
            }

            array_push($warung_array, ['warung' => $warungs, 'no_rek' => $no_rek, 'nama_bank' => $nama_bank, 'atas_nama' => $atas_nama, 'provinsi' => $provinsi, 'kabupaten' => $kabupaten, 'kecamatan' => $kecamatan, 'kelurahan' => $kelurahan, 'setting_aplikasi' => $setting_aplikasi]);
        }

        //DATA PAGINATION
        $respons['current_page']   = $warung->currentPage();
        $respons['data']           = $warung_array;
        $respons['first_page_url'] = url('/profil-warung/view?page=' . $warung->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $warung->lastPage();
        $respons['last_page_url']  = url('/profil-warung/view?page=' . $warung->lastPage());
        $respons['next_page_url']  = $warung->nextPageUrl();
        $respons['path']           = url('/profil-warung/view');
        $respons['per_page']       = $warung->perPage();
        $respons['prev_page_url']  = $warung->previousPageUrl();
        $respons['to']             = $warung->perPage();
        $respons['total']          = $warung->total();
        //DATA PAGINATION

        return response()->json($respons);
    }

    public function pilih_provinsi()
    {
        $wilayah = Indonesia::allProvinces();
        return response()->json($wilayah);
    }

    //PILIH WILAYAH
    public function pilih_wilayah($id, $type)
    {
        # Tarik ID_wilayah & tipe_wilayah
        $id_wilayah   = $id;
        $type_wilayah = $type;

        # Buat pilihan "Switch Case" berdasarkan variabel "type" dari dari data yg dikirim
        switch ($type_wilayah):
    # untuk kasus "kabupaten"
    case 'kabupaten':
        $kabupaten = Indonesia::allCities()->where('province_id', $id);
        return response()->json($kabupaten);
        break;
    # untuk kasus "kecamatan"
    case 'kecamatan':
        $kecamatan = Indonesia::allDistricts()->where('city_id', $id);
        return response()->json($kecamatan);
        break;
    # untuk kasus "kelurahan"
    case 'kelurahan':
        $kelurahan = Indonesia::allVillages()->where('district_id', $id);
        return response()->json($kelurahan);
        break;
        # pilihan berakhir
        endswitch;

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
        $warung                     = Warung::with(['bank_warung'])->find($id);
        $warung['provinsi']         = $warung->provinsi;
        $warung['nama_bank']        = $warung->bank_warung->nama_bank;
        $warung['atas_nama']        = $warung->bank_warung->atas_nama;
        $warung['no_rek']           = $warung->bank_warung->no_rek;
        $warung['setting_aplikasi'] = $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

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
        $provinsi      = Indonesia::allProvinces()->pluck('name', 'id');

        return view('warung_profil.edit', ['provinsi' => $provinsi])->with(compact('warung_profil'));
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
            'name'      => 'required|unique:warungs,name,' . $id,
            'alamat'    => 'required',
            'provinsi'  => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'no_telpon' => 'required|max:15|unique:warungs,no_telpon,' . $id,
        ]);

        //UPDATE MASTER DATA WARUNG
        $warung = Warung::where('id', $id)->update([
            'name'         => $request->name,
            'alamat'       => $request->alamat,
            'provinsi'     => $request->provinsi,
            'kabupaten'    => $request->kabupaten,
            'kecamatan'    => $request->kecamatan,
            'wilayah'      => $request->kelurahan,
            'no_telpon'    => $request->no_telpon,
            'email'        => $request->email,
            'footer_struk' => $request->footer_struk,
        ]);

        // $bank_warung_id = BankWarung::select('id')->where('warung_id', $id)->first();

        // //VALIDASI BANK WARUNG
        // $this->validate($request, [
        //     'nama_bank' => 'required',
        //     'atas_nama' => 'required',
        //     'no_rek'    => 'required|unique:bank_warungs,no_rek,' . $bank_warung_id->id,
        // ]);

        // //UPDATE BANK WARUNG
        // $bank_warung = BankWarung::where('warung_id', $id)->update([
        //     'nama_bank' => $request->nama_bank,
        //     'atas_nama' => $request->atas_nama,
        //     'no_rek'    => $request->no_rek,
        // ]);
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
