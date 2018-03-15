<?php

namespace App\Http\Controllers;

use App\SettingJasaPengiriman;
use App\SettingTransferBank;
use Auth;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

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
}
