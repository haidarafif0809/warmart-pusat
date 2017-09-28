<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('user-should-verified');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|name|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'alamat'    => 'required',
            'kelurahan' => 'required',
            'no_telp'   => 'required|numeric',
            'nama_bank' => 'required',
            'no_rekening' => 'required',
            'an_rekening' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    { 
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'alamat' => $data['alamat'],  
            'wilayah' => $data['kelurahan'],   
            'no_telp' => $data['no_telp'],     
            'nama_bank' => $data['nama_bank'],  
            'no_rekening' => $data['no_rekening'], 
            'an_rekening' => $data['an_rekening'],  
            'tipe_user'=> 2,
            'status_konfirmasi'=>0
        ]);

        $warungRole = Role::where('name', 'warung')->first();
        $user->attachRole($warungRole);
        return $user;
    }

    protected function register_warung()
    { 
        return view('auth.register_warung');    
    }

    protected function register_customer()
    { 
        return view('auth.register_customer');    
    }
}
