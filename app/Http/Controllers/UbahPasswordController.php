<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Session;

class UbahPasswordController extends Controller
{
    //
    public function ubah_password()
    {
        //
        $user = Auth::user();
        return view('ubah_password',['user'=>$user]);
    }

    public function proses_ubah_password(Request $request, $id)
    {
        $this->validate($request, [
            'password_awal' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
 
        $cek_password = User::find($id);

		if (Auth::attempt(['email' => $cek_password->email, 'password' => $request->password_awal])) {
		         
            $update_user = User::find($id);   
            $update_user->password = bcrypt($request->password);
            $update_user->save();  

	        Session::flash("flash_notification", [
	            "level"     => "success",
	            "message"   => "Password Berhasil Di Ubah"
	        ]);

		 }
 
        else{ 
	        Session::flash("flash_notification", [
	            "level"     => "danger",
	            "message"   => "Mohon Maaf Password Awal Anda Belum Sama"
	        ]);
        }
 
       return back();
    } 	
}
