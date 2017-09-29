<?php

namespace App\Http\Middleware;

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
       $response = $next($request);
        if (Auth::check() && !Auth::user()->status_konfirmasi) {

        Auth::logout();

        Session::flash("flash_notification", [ 
        "message" => "Anda Tidak Bisa Login Di Karenakan Belum DI Konfirmasi Oleh Admin."
        ]);

        return redirect('/login');
        }

        return $response;

    }
}
