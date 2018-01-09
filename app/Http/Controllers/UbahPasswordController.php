<?php

namespace App\Http\Controllers;

use App\KeranjangBelanja;
use App\SettingAplikasi;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class UbahPasswordController extends Controller
{
    //
    public function ubah_password()
    {
        //
        $user = Auth::user();
        return view('ubah_password', ['user' => $user]);
    }

    public function proses_ubah_password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $user                  = Auth::user();
        $update_user           = User::find($user->id);
        $update_user->password = bcrypt($request->password);
        $update_user->save();

    }

    //USER PELANGGAN

    public function ubah_password_pelanggan()
    {
        $user                = Auth::user();
        $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->get();
        $cek_belanjaan       = $keranjang_belanjaan->count();
        //FOTO WARMART
        $logo_warmart = "" . asset('/assets/img/examples/warmart_logo.png') . "";
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        return view('ubah_password_pelanggan', ['user' => $user, 'cek_belanjaan' => $cek_belanjaan, 'logo_warmart' => $logo_warmart, 'setting_aplikasi' => $setting_aplikasi]);
    }

    public function proses_ubah_password_pelanggan(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $user                  = Auth::user();
        $update_user           = User::find($user->id);
        $update_user->password = bcrypt($request->password);
        $update_user->save();

        Session::flash("flash_notification", [
            "level"   => "success",
            "message" => "Password Berhasil Di Ubah",
        ]);

        return redirect()->route('daftar_produk.index');
    }
}
