<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class UserMustWarung
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


        if (Auth::check() && Auth::user()->tipe_user != 4) {
             Auth::logout();

        }

         return response()->view('error.403');;
    }
}
