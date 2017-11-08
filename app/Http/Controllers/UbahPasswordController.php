<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Session;
use App\KeranjangBelanja; 

class UbahPasswordController extends Controller
{
    //
    public function ubah_password()
    {
        //
        $user = Auth::user();
        return view('ubah_password',['user'=>$user]);
    }

    public function proses_ubah_password(Request $request)
    {
        $this->validate($request, [ 
            'password' => 'required|min:6|confirmed'
        ]);
        
        
        $user = Auth::user();
        $update_user = User::find($user->id);   
        $update_user->password = bcrypt($request->password);
        $update_user->save();  

        Session::flash("flash_notification", [
         "level"     => "success",
         "message"   => "Password Berhasil Di Ubah"
     ]);
        
        return back();
    } 	

    //USER PELANGGAN

    public function ubah_password_pelanggan()
    {
        $user = Auth::user();
        $keranjang_belanjaan = KeranjangBelanja::with(['produk','pelanggan'])->where('id_pelanggan',Auth::user()->id)->get();
        $cek_belanjaan = $keranjang_belanjaan->count();

        return view('ubah_password_pelanggan',['user'=>$user, 'cek_belanjaan'=>$cek_belanjaan]);
    }

    public function proses_ubah_password_pelanggan(Request $request, $id)
    {
        $this->validate($request, [ 
            'password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();
        $update_user = User::find($user->id);   
        $update_user->password = bcrypt($request->password);
        $update_user->save();  

        Session::flash("flash_notification", [
           "level"     => "success",
           "message"   => "Password Berhasil Di Ubah"
       ]);
        
        return redirect()->route('daftar_produk.index');
    } 
}
