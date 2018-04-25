<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Kas;
use App\KomunitasCustomer;
use App\Notifications\PendaftarWarung;
use App\Role;
use App\SettingAplikasi;
use App\SettingFooter;
use App\SettingVerifikasi;
use App\User;
use App\UserWarung;
use App\Warung;
use App\BankWarung;
use App\SettingJasaPengiriman;
use App\SettingTransferBank;
use App\SettingPembedaAplikasi;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Notification;

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
    protected $redirectTo = '/';

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
                'name'     => 'required',
                'email'    => 'required|without_spaces|unique:users,email|email',
                'alamat'   => 'required',
                'no_telp'  => 'required|numeric|without_spaces|unique:users,no_telp',
                'password' => 'required|string|min:6|confirmed',
            ]);
        } elseif ($data['id_register'] == 2) {
            //Komunitas
            return Validator::make($data, [
                'email'    => 'nullable|without_spaces|unique:users,email|email',
                'name'     => 'required',
                'password' => 'required|string|min:6|confirmed',
                'no_telp'  => 'required|numeric|without_spaces|unique:users,no_telp',
                'alamat'   => 'required',
            ]);
        } elseif ($data['id_register'] == 3) {
            //USER WARUNG
            return Validator::make($data, [
                'email'       => 'nullable|without_spaces|unique:users,email|email',
                'name'        => 'required',
                'nama_warung' => 'required',
                'password'    => 'required|string|min:6|confirmed',
                'no_telp'     => 'required|numeric|without_spaces|unique:users,no_telp',
                'alamat'      => 'required',
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
        $warung_id = $this->getIdWarung();
        $kode_verifikasi = rand(1111, 9999);
        if ($data['id_register'] == 1) {
            //Customer
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'alamat'            => $data['alamat'],
                'no_telp'           => $data['no_telp'],
                'password'          => bcrypt($data['password']),
                'tipe_user'         => 3,
                'status_konfirmasi' => 0,
                'kode_verifikasi'   => $kode_verifikasi,
                'id_warung'         => $warung_id
            ]);

            $customerRole = Role::where('name', 'customer')->first();
            $user->attachRole($customerRole);
            $user->sendVerification();

            // registrasi berasal dari link affiliasi atau memilih komunitas saat registrasi
            if (isset($data['komunitas_id'])) {
                //kaitkan customer dengan komunitas yang berasal dari link affiliasi
                KomunitasCustomer::create(['komunitas_id' => $data['komunitas_id'], 'user_id' => $user->id]);
            } elseif ($data['komunitas'] != "") {
                KomunitasCustomer::create(['komunitas_id' => $data['komunitas'], 'user_id' => $user->id]);
            }

            $userkey      = env('USERKEY');
            $passkey      = env('PASSKEY');
            $nomor_tujuan = $data['no_telp'];
            //SETTING APLIKASI
            $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
            if ($setting_aplikasi->tipe_aplikasi == 0) {
                $isi_pesan = urlencode($kode_verifikasi . ', masukkan angka tersebut untuk Verifikasi User Warmart, Terima Kasih Telah Mendaftar Sebagai Customer Warmart. ');
            } else {
                $isi_pesan = urlencode($kode_verifikasi . ', masukkan angka tersebut untuk Verifikasi User , Terima Kasih Telah Mendaftar Sebagai Customer . ');
            }
            $setting_verifikasi = SettingVerifikasi::select()->first();
            if ($setting_verifikasi->no_telp == 1) {
                if (env('STATUS_SMS') == 1) {
                    $client = new Client(); //GuzzleHttp\Client
                    $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $isi_pesan . '');
                }
                # code...
            }

            return $user;

        } elseif ($data['id_register'] == 2) {
            //Komunitas
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => bcrypt($data['password']),
                'alamat'            => $data['alamat'],
                'no_telp'           => $data['no_telp'],
                'tipe_user'         => 2,
                'status_konfirmasi' => 0,
                'kode_verifikasi'   => $kode_verifikasi,
            ]);

            $warungRole = Role::where('name', 'komunitas')->first();
            $user->attachRole($warungRole);

            $userkey      = env('USERKEY');
            $passkey      = env('PASSKEY');
            $nomor_tujuan = $data['no_telp'];
            $isi_pesan    = urlencode($kode_verifikasi . ', masukkan angka tersebut untuk Verifikasi User Warmart, Terima Kasih Telah Mendaftar Sebagai Komunitas Warmart.');

            if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $isi_pesan . '');

            }

            return $user;

        } elseif ($data['id_register'] == 3) {
            //MASTER WARUNG
            $warung = Warung::create([
                'name'      => $data['nama_warung'],
                'alamat'    => $data['alamat'],
                'no_telpon' => $data['no_telp'],
                'wilayah'   => "-",
            ]);


            // //INSERT BANK WARUNG
            // $bank_warung = BankWarung::create([
            //     'nama_bank' => 1,
            //     'nama_tampil' => "BCA",
            //     'atas_nama' => "Andaglos",
            //     'no_rek'    => "1234567890",
            //     'warung_id' => $warung->id,
            //     ]);


            // SETTING FOOTER
            $sfDef = SettingFooter::defaultData();
            SettingFooter::create([
                'warung_id'    => $warung->id,
                'judul_warung' => $sfDef->judul_warung,
                'support_link' => $sfDef->support_link,
                'about_link'   => $sfDef->about_link,
                'about_us'     => $sfDef->about_us,
                'no_telp'      => $sfDef->no_telp,
                'alamat'       => $sfDef->alamat,
                'email'        => $sfDef->email,
                'whatsapp'     => $sfDef->whatsapp,
                'facebook'     => $sfDef->facebook,
                'twitter'      => $sfDef->twitter,
                'instagram'    => $sfDef->instagram,
                'google_plus'  => $sfDef->google_plus,
                'play_store'   => $sfDef->play_store,
            ]);
            $setting_pengiriman  = SettingJasaPengiriman::daftar($warung->id);
            $setting_bank_transfer  = SettingTransferBank::daftar($warung->id);
            //SETTING APLIKASI
            $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

            //APP WARMART == 1
            if ($setting_aplikasi->tipe_aplikasi == 0) {
                $konfirmasi_admin = 0;
                $nama_toko        = "Warung Warmart";
            } else {
                $konfirmasi_admin = 1;
                $nama_toko        = "Toko Topos";
            }

            //USER WARUNG
            $user = UserWarung::create([
                'name'              => $data['name'],
                'password'          => bcrypt($data['password']),
                'alamat'            => $data['alamat'],
                'no_telp'           => $data['no_telp'],
                'id_warung'         => $warung->id,
                'tipe_user'         => 4,
                'status_konfirmasi' => 0,
                'kode_verifikasi'   => $kode_verifikasi,
                'konfirmasi_admin'  => $konfirmasi_admin,
            ]);

            // KAS WARUNG

            Kas::create(['kode_kas' => 'K01', 'nama_kas' => 'Kas Warung', 'status_kas' => 1, 'default_kas' => 1, 'warung_id' => $warung->id]);

            $userWarungRole = Role::where('name', 'warung')->first();
            $user->attachRole($userWarungRole);

            // Notification::send(User::first(), new PendaftarWarung($user));

            $userkey      = env('USERKEY');
            $passkey      = env('PASSKEY');
            $nomor_tujuan = $data['no_telp'];
            $isi_pesan    = urlencode($kode_verifikasi . ', masukkan angka tersebut untuk Verifikasi User ' . $nama_toko . ', Terima Kasih Telah Mendaftar Sebagai ' . $nama_toko . '.');

            if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                // $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $isi_pesan . '');

            }

            return $user;

        }
    }


    public function getIdWarung(){
        //Cek Address Aplikasi yg di Jalankan
    $address_current = url('/');

    $address_app = SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();

    return $address_app->warung_id;
    }

    protected function kirim_kode_verifikasi(Request $request)
    {
        $nomor_hp = $request->nomor;
        $status   = $request->status;
        $user     = User::where('no_telp', $request->nomor)->first();
        return view('auth.verifikasi_register', ['nomor_hp' => $nomor_hp, 'user' => $user, 'status' => $status]);
    }

    protected function proses_kirim_kode_verifikasi(Request $request, $nomor_hp)
    {
        $user = User::where('no_telp', $nomor_hp)->first();
        if ($request->kode_verifikasi != $user->kode_verifikasi) {

            Session::flash("flash_notification", [
                "alert"   => 'danger',
                "icon"    => 'error_outline',
                "judul"   => 'FAILED',
                "message" => 'Mohon Maaf Nomor Verifikasi Yang Anda Isi Tidak Sama']);
            return back();
        } else {

            User::where('id', $user->id)->update(['status_konfirmasi' => '1']);
            $user = User::find($user->id);
            Auth::login($user);
            //warung
            if ($request->status == 0) {
                if (Auth::user()->tipe_user == 4) {
                    return redirect('/dashboard#/ubah-profil-user-warung');
                } else {
                    return redirect('/');
                }

            } elseif ($request->status == 1) {
                return redirect('/ubah-password');
            }
        }
    }

    protected function kirim_ulang_kode_verifikasi($id)
    {
        $kode_verifikasi = rand(1111, 9999);
        User::where('id', $id)->update(['kode_verifikasi' => $kode_verifikasi]);
        $user = User::where('id', $id)->first();

        $userkey      = env('USERKEY');
        $passkey      = env('PASSKEY');
        $nomor_tujuan = $user->no_telp;
        $isi_pesan    = urlencode($kode_verifikasi . ', masukkan angka tersebut untuk memverifikasi User');

        if (env('STATUS_SMS') == 1) {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get("https://reguler.zenziva.net/apps/smsapi.php?userkey=$userkey&passkey=$passkey&nohp=$nomor_tujuan&pesan=$isi_pesan");
        }

        Session::flash("flash_notification", [
            "alert"   => 'warning',
            "icon"    => 'warning',
            "judul"   => 'PERHATIAN',
            "message" => 'Silakan input Nomor verifikasi yang terkirim melalui SMS ke no ' . $user->no_telp]);
        return back();

    }

    protected function register_customer()
    {
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        return view('auth.register_customer', ['setting_aplikasi' => $setting_aplikasi->tipe_aplikasi]);
    }

    protected function syarat_ketentuan()
    {
        return view('auth.syarat_ketentuan');
    }

    public function caraMemesan()
    {
        return view('auth.cara_memesan');
    }
    protected function syarat_ketentuan_topos()
    {
        return view('auth.syarat_ketentuan_topos');
    }

    protected function lupa_password()
    {
        return view('auth.lupa_password');
    }

    protected function proses_lupa_password(Request $request)
    {
        $kode_verifikasi = rand(1111, 9999);
        $userkey         = env('USERKEY');
        $passkey         = env('PASSKEY');
        $nomor_tujuan    = $request->no_telp;
        $user            = User::where('no_telp', $nomor_tujuan)->first();
        User::where('no_telp', $nomor_tujuan)->update(['kode_verifikasi' => $kode_verifikasi]);
        $isi_pesan = $kode_verifikasi . ', masukan angka tersebut untuk memverifikasi perubahan password Anda';

        if (env('STATUS_SMS') == 1) {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get("https://reguler.zenziva.net/apps/smsapi.php?userkey=$userkey&passkey=$passkey&nohp=$nomor_tujuan&pesan=" . urlencode($isi_pesan));

            Session::flash("flash_notification", [
                "alert"   => 'warning',
                "icon"    => 'done',
                "judul"   => 'INFO',
                "message" => 'Silahkan periksa ponsel anda, kami mengirim sms nomor verifikasi ke : ' . $nomor_tujuan . '',
            ]);

        }
        return redirect('/kirim-kode-verifikasi?nomor=' . $nomor_tujuan . '&status=1');
    }

    //USER WARUNG

    protected function register_warung()
    {
//SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();
        return view('auth.register_warung', ['setting_aplikasi' => $setting_aplikasi]);
    }

    public function verifyEmail(Request $request, $token)
    {
        $email = $request->get('email');
        $user  = User::where('email', $email)->first();
        if ($user->status_konfirmasi == 1) {
            Auth::login($user);
            return redirect('/');
        } else {
            $user = User::where('verification_token', $token)->where('email', $email)->first();
            if ($user) {
                $user->verifyEmail();
                Session::flash("flash_notification", [
                    "level"   => "success",
                    "message" => "Berhasil melakukan verifikasi.",
                ]);
                Auth::login($user);
            }
            return redirect('/');
        }
    }

}
