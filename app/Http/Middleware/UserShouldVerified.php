<?php

namespace App\Http\Middleware;

use App\SettingVerifikasi;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserShouldVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response           = $next($request);
        $setting_verifikasi = SettingVerifikasi::select()->first();
        if ($setting_verifikasi->email != 1 && $setting_verifikasi->no_telp == 1) {
            if (Auth::check() && !Auth::user()->status_konfirmasi) {

                Auth::logout();

                Session::flash("flash_notification", [
                    "alert"   => 'warning',
                    "icon"    => 'error_outline',
                    "judul"   => 'PERHATIAN',
                    "message" => 'Silakan input nomor verifikasi yang terkirim melalui SMS ke no ' . $request['no_telp'] . '',
                ]);
                return redirect('/kirim-kode-verifikasi?nomor=' . $request['no_telp'] . '&status=0');
                # code...
            }
        } elseif ($setting_verifikasi->email == 1 && $setting_verifikasi->no_telp == 1) {
            if (Auth::check() && !Auth::user()->status_konfirmasi) {

                Auth::logout();

                Session::flash("flash_notification", [
                    "alert"   => 'warning',
                    "icon"    => 'error_outline',
                    "judul"   => 'PERHATIAN',
                    "message" => 'Silakan input nomor verifikasi yang terkirim melalui SMS ke no ' . $request['no_telp'] . ' . Atau verifikasi Melalui link yang di kirim ke email : ' . $request['email'] . ' .',
                ]);
                return redirect('/kirim-kode-verifikasi?nomor=' . $request['no_telp'] . '&status=0');
                # code...
            }
        } elseif ($setting_verifikasi->email == 1 && $setting_verifikasi->no_telp != 1) {
            if (Auth::check() && !Auth::user()->status_konfirmasi) {
                Auth::logout();
                Session::flash("flash_notification", [
                    "alert"   => "warning",
                    "icon"    => 'error_outline',
                    "judul"   => 'PERHATIAN',
                    "message" => 'Kami mengirimkan link verifikasi melalui Email ke alamat email ' . $request['email'] . ' . Periksa email Anda sekarang .',
                ]);
                return redirect('/login');
                # code...
            }
        }

        return $response;

    }
}
