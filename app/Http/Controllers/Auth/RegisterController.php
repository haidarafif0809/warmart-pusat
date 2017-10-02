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
        if ($data['id_register'] == 1) {
            //Customer
            return Validator::make($data, [
                'name'      => 'required',
                'email'     => 'required|numeric|without_spaces|unique:users,email',
                'alamat'    => 'required',
                'tgl_lahir' => 'required',
                'kelurahan' => 'required',
                'password' => 'required|string|min:6|confirmed',
            ]);
        }elseif ($data['id_register'] == 2) { 
            //Komunitas
            return Validator::make($data, [
                'email'     => 'required|numeric|without_spaces|unique:users,email',
                'name'      => 'required',
                'password' => 'required|string|min:6|confirmed',
                'alamat'    => 'required',
                'kelurahan' => 'required',
                'nama_bank' => 'required',
                'no_rekening' => 'required',
                'an_rekening' => 'required',
                'id_warung' => 'required',
            ]);
        }elseif ($data['id_register'] == 3) {
           
              //Warung
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {  

        if ($data['id_register'] == 1) {
        //Customer
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'alamat' => $data['alamat'],    
            'no_telp' => $data['no_telp'],  
            'wilayah' => $data['kelurahan'],  
            'tgl_lahir' => $data['tgl_lahir'],    
            'password' => bcrypt($data['password']),
            'tipe_user'=> 3,
            'status_konfirmasi'=>0
        ]);

        $customerRole = Role::where('name', 'customer')->first();
        $user->attachRole($customerRole);
        return $user;

        }elseif ($data['id_register'] == 2) { 
        //Komunitas 
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'alamat' => $data['alamat'],  
            'id_warung' => $data['id_warung'],  
            'wilayah' => $data['kelurahan'],   
            'no_telp' => $data['no_telp'],     
            'nama_bank' => $data['nama_bank'],  
            'no_rekening' => $data['no_rekening'], 
            'an_rekening' => $data['an_rekening'],  
            'tipe_user'=> 2,
            'status_konfirmasi'=>0
        ]);

        $warungRole = Role::where('name', 'komunitas')->first();
        $user->attachRole($warungRole);
        return $user;

        }elseif ($data['id_register'] == 3) {
           
              //Warung
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

    

    }
 

    protected function register_customer()
    { 
        return view('auth.register_customer');    
    }
 
}
