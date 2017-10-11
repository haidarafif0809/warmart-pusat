<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserWarung;
use App\BankWarung;
use App\Warung;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use App\KomunitasCustomer;

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
                'email'     => 'nullable|without_spaces|unique:users,email',
                'alamat'    => 'required',
                'no_telp'   => 'required|numeric|without_spaces|unique:users,no_telp', 
                'password'  => 'required|string|min:6|confirmed',
            ]);
        }
        elseif ($data['id_register'] == 2) { 
            //Komunitas
            return Validator::make($data, [
                'email'     => 'nullable|without_spaces|unique:users,email',
                'name'      => 'required',
                'password'  => 'required|string|min:6|confirmed',
                'no_telp'   => 'required|numeric|without_spaces|unique:users,no_telp',
                'alamat'    => 'required', 
            ]);
        }
        elseif ($data['id_register'] == 3) { 
            //USER WARUNG
            return Validator::make($data, [
                'email'     => 'nullable|without_spaces|unique:users,email',
                'name'      => 'required',
                'password'  => 'required|string|min:6|confirmed',
                'no_telp'   => 'required|numeric|without_spaces|unique:users,no_telp',
                'alamat'    => 'required', 
            ]);
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
        $kode_verifikasi = rand(1111,9999);
        if ($data['id_register'] == 1) {
        //Customer
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'alamat' => $data['alamat'],    
                'no_telp' => $data['no_telp'],    
                'password' => bcrypt($data['password']),
                'tipe_user'=> 3,
                'status_konfirmasi'=>0,
                'kode_verifikasi'=> $kode_verifikasi,
            ]);



            $customerRole = Role::where('name', 'customer')->first();
            $user->attachRole($customerRole);


              // registrasi berasal dari link affiliasi
            if (isset($data['komunitas_id'])) {
                
                //kaitkan customer dengan komunitas yang berasal dari link affiliasi
                KomunitasCustomer::create(['komunitas_id' => $data['komunitas_id'],'user_id' => $user->id]);
            }

            $userkey = env('USERKEY');
            $passkey = env('PASSKEY');
            $nomor_tujuan = $data['no_telp'];
            $isi_pesan ='Terima Kasih Telah Mendaftar Sebagai Customer Warmart. Silakan Masukan Kode Verfikasi Warmart '.$kode_verifikasi.'';

            if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$nomor_tujuan.'&pesan='.$isi_pesan.''); 
               
            }

            return $user;

        }
        elseif ($data['id_register'] == 2) { 
        //Komunitas 
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'alamat' => $data['alamat'],   
                'no_telp' => $data['no_telp'],   
                'tipe_user'=> 2,
                'status_konfirmasi'=>0,
                'kode_verifikasi'=> $kode_verifikasi,
            ]);

            $warungRole = Role::where('name', 'komunitas')->first();
            $user->attachRole($warungRole);

            $userkey = env('USERKEY');
            $passkey = env('PASSKEY');
            $nomor_tujuan = $data['no_telp'];
            $isi_pesan ='Terima Kasih Telah Mendaftar Sebagai Komunitas Warmart. Silakan Masukan Kode Verfikasi Warmart '.$kode_verifikasi.'';

            if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$nomor_tujuan.'&pesan='.$isi_pesan.''); 
               
            }

            return $user;

        }
        elseif ($data['id_register'] == 3) { 

        //MASTER WARUNG
            $warung = Warung::create([
                'name'      =>$data['name'],
                'alamat'    =>$data['alamat'],
                'no_telpon' =>$data['no_telp'],
                'wilayah'     => "-", 
                'email'     => "-", 
            ]);

        //INSERT BANK WARUNG
         $bank_warung = BankWarung::create([
                'nama_bank' => "-",              
                'atas_nama' => "-",
                'no_rek'    => "-",
                'warung_id' => $warung->id,
            ]);

        //USER WARUNG 
            $user = UserWarung::create([
                'name'      => $data['name'],
                'email'     => "-",
                'password'  => bcrypt($data['password']),
                'alamat'    => $data['alamat'],   
                'no_telp'   => $data['no_telp'],  
                'id_warung' => $warung->id,   
                'tipe_user' => 4,
                'status_konfirmasi'=>0,
                'kode_verifikasi'=> $kode_verifikasi,
            ]);

            $userWarungRole = Role::where('name', 'warung')->first();
            $user->attachRole($userWarungRole);

            $userkey = env('USERKEY');
            $passkey = env('PASSKEY');
            $nomor_tujuan = $data['no_telp'];
            $isi_pesan ='Terima Kasih Telah Mendaftar Sebagai Warung Warmart. Silakan Masukan Kode Verfikasi Warmart '.$kode_verifikasi.'';

            if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$nomor_tujuan.'&pesan='.$isi_pesan.''); 
               
            }

            return $user;

        } 
    }
 
    protected function kirim_kode_verifikasi(Request $request)
    {  
        $nomor_hp = $request->nomor;
        $status = $request->status;
        $user = User::where('no_telp',$request->nomor)->first();
        return view('auth.verifikasi_register',['nomor_hp'=>$nomor_hp,'user'=>$user,'status'=>$status]);    
    } 

    protected function proses_kirim_kode_verifikasi(Request $request,$nomor_hp)
    {  
        $user = User::where('no_telp',$nomor_hp)->first();
        if ($request->kode_verifikasi != $user->kode_verifikasi) {
             
            Session::flash("flash_notification", [
                "alert" => 'danger',
                "icon" => 'error_outline',
                "judul" => 'FAILED',
                "message" => 'Mohon Maaf Kode Verfikasi Yang Anda Isi Tidak Sama']);
            return back();
          }else{

            User::where('id',$user->id)->update(['status_konfirmasi' => '1']); 
            $user = User::find($user->id);
            Auth::login($user);

            if ($request->status == 0) {
                return redirect('/home');
            }elseif($request->status == 1){
                return redirect('/ubah-password'); 
            }
          }
    }

    protected function kirim_ulang_kode_verifikasi($id)
    { 
        $kode_verifikasi = rand(1111,9999);
        User::where('id',$id)->update(['kode_verifikasi' => $kode_verifikasi]);
        $user = User::where('id',$id)->first();
           
        $userkey = env('USERKEY');
        $passkey = env('PASSKEY');
        $nomor_tujuan = $user->no_telp;
        $isi_pesan ='Terima Kasih Telah Mendaftar Sebagai Warmart. Silakan Masukan Kode Verfikasi Warmart '.$kode_verifikasi.'';

        if (env('STATUS_SMS') == 1) {
        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get("https://reguler.zenziva.net/apps/smsapi.php?userkey=$userkey&passkey=$passkey&nohp=$nomor_tujuan&pesan=$isi_pesan"); 
        }

            Session::flash("flash_notification", [
                "alert" => 'warning',
                "icon" => 'warning',
                "judul" => 'PERHATIAN',
                "message" => 'Silakan input nomor verifikasi yang terkirim melalui SMS ke no ' . $user->no_telp]);
            return back();

    }

    protected function register_customer()
    { 
        return view('auth.register_customer');    
    }
 
    protected function lupa_password()
    { 
        return view('auth.lupa_password');    
    }
 
    protected function proses_lupa_password(Request $request)
    {   
        $kode_verifikasi = rand(1111,9999);
        $userkey = env('USERKEY');
        $passkey = env('PASSKEY');
        $nomor_tujuan = $request->no_telp;
        $user = User::where('no_telp',$nomor_tujuan)->first();
        User::where('no_telp',$nomor_tujuan)->update(['kode_verifikasi' => $kode_verifikasi]);
        $isi_pesan ='Yth :  '.$user->name.', Masukan Kode Verfikasi Berikut Untuk Login Ke Warmart : '.$kode_verifikasi.'';

        if (env('STATUS_SMS') == 1) {
        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get("https://reguler.zenziva.net/apps/smsapi.php?userkey=$userkey&passkey=$passkey&nohp=$nomor_tujuan&pesan=$isi_pesan"); 


        Session::flash("flash_notification", [ 
        "alert" => 'warning',
        "icon" => 'done',
        "judul" => 'INFO',
        "message" => 'Silahkan Periksa Hp Anda Kami Mengirim Kode Verfikasi Ke : '.$nomor_tujuan.''
        ]);

        return redirect('/kirim-kode-verifikasi?nomor='.$nomor_tujuan.'&status=1');
        } 
    }

    //USER WARUNG


    protected function register_warung  ()
    { 
        return view('auth.register_warung   ');    
    }
}
