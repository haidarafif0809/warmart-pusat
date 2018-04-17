<?php

namespace App\Http\Controllers;

use App\SettingJasaPengiriman;
use App\SettingTransferBank;
use App\SettingDefaultAlamatPelanggan;
use Auth;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Indonesia;

class SettingPengirimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function view()
    {
        $data_settings = SettingJasaPengiriman::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'asc')->paginate(10);

        $data_agent = new Agent();
        if ($data_agent->isMobile()) {
            $agent = 0;
        } else {
            $agent = 1;
        }

        $array = [];
        foreach ($data_settings as $data_setting) {
            array_push($array, ['setting' => $data_setting, 'agent' => $agent]);
        }

        return response()->json($array);
    }

    public function viewBank()
    {
        $data_settings = SettingTransferBank::where('warung_id', Auth::user()->id_warung)->orderBy('id', 'asc')->paginate(10);

        $data_agent = new Agent();
        if ($data_agent->isMobile()) {
            $agent = 0;
        } else {
            $agent = 1;
        }

        $array = [];
        foreach ($data_settings as $data_setting) {
            array_push($array, ['setting' => $data_setting, 'agent' => $agent]);
        }

        return response()->json($array);
    }

    public function viewDefaultAlamatPengriman()
    {   
        $defaultAlamatPelanggan = SettingDefaultAlamatPelanggan::select('provinsi','kabupaten','status_aktif')->where('warung_id', Auth::user()->id_warung);
        $provinsi = Indonesia::allProvinces();

        if ($defaultAlamatPelanggan->count() > 0) {
           $kabupaten = Indonesia::allCities()->where('province_id',$defaultAlamatPelanggan->first()->provinsi);

       }else{
        $kabupaten = "{}";
    }

    $response['provinsi'] = $provinsi;
    $response['kabupaten'] = $kabupaten;
    $response['defaultAlamatPelanggan'] = $defaultAlamatPelanggan;
    return response()->json($response);
}

public function simpanSetting(Request $request)
{
    $warung_id = Auth::user()->id_warung;
    foreach ($request->data as $key => $value) {
        $update_setting = SettingJasaPengiriman::find($value['setting']['id']);
        $update_setting->update([
            'tampil_jasa_pengiriman'  => $value['setting']['tampil_jasa_pengiriman'],
            'default_jasa_pengiriman' => $value['setting']['default_jasa_pengiriman'],
        ]);
    }
}

public function simpanSettingBank(Request $request)
{
    $warung_id = Auth::user()->id_warung;
    foreach ($request->data as $key => $value) {
        $update_setting_bank = SettingTransferBank::find($value['setting']['id']);
        $update_setting_bank->update([
            'tampil_bank'  => $value['setting']['tampil_bank'],
            'default_bank' => $value['setting']['default_bank'],
        ]);
    }
}

public function simpanSettingDefaultAlamatPengiriman(Request $request){
                //VALIDASI WARUNG
    $this->validate($request, [
        'provinsi'      => 'required',
        'kabupaten'    => 'required',
        'status_aktif'  => 'required'
    ]);
    SettingDefaultAlamatPelanggan::where('warung_id', Auth::user()->id_warung)->delete();
    SettingDefaultAlamatPelanggan::create(['provinsi' => $request->provinsi, 'kabupaten' => $request->kabupaten ,'status_aktif' => $request->status_aktif,'warung_id' => Auth::user()->id_warung]);
}
}
