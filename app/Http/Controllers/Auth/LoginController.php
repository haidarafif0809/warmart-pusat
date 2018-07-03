<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use App\KeranjangBelanja;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('user-should-verified');

    }

    protected function authenticated(Request $request, $user)
    {
        //
        if ($user->tipe_user == 4) {
            return redirect()->intended('/dashboard');
        } else {
            $session = Session::get('session_id');
            $keranjang_belanja = KeranjangBelanja::where('session_id',$session)->update(['id_pelanggan' => $user->id]);
            if ($request->status_login == 0) {
                return redirect()->intended('/'); 
            }else{
               return redirect()->intended('/selesaikan-pemesanan');
           }
       }
   }

   public function username()
   {
    return 'no_telp';
}
}
