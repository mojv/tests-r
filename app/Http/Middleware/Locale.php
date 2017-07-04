<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Request;

class locale
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

        if (session()->has('locale')){
            App::setLocale(session('locale'));
            return $next($request);
        }else {
            $langServ=Request::server('HTTP_ACCEPT_LANGUAGE');
            $lang=$lang=substr($langServ, 0, 2);
            App::setLocale($lang);
            return $next($request);
        }

    }
}
