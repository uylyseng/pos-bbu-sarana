<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetDefaultLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set default locale to Khmer if not already set in session
        if (!Session::has('locale')) {
            Session::put('locale', 'km');
            App::setLocale('km');
        } else {
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
