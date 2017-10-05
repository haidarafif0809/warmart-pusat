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
            'password' => 'required|min:6|confirmed'
        ]);
  
		         
            $update_user = User::find($id);   
            $update_user->password = bcrypt($request->password);
            $update_user->save();  

	        Session::flash("flash_notification", [
	            "level"     => "success",
	            "message"   => "Password Berhasil Di Ubah"
	        ]);
  
       return back();
    } 	
}
