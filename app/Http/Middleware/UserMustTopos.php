<?php

namespace App\Http\Middleware;

use Closure;

class UserMustTopos
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

        return $next($request);

        if (Auth::check() && (Auth::user()->tipe_user != 4 || Auth::user()->tipe_user != 1)) {
             Auth::logout();
            return response()->view('error.403');
        }
        else if(Auth::user()->konfirmasi_admin == 0){
            return redirect()->route('home.dashboard');
        }

        return $response;
    }
}
