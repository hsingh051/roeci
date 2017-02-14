<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DeoAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
       if (Auth::user()->role == 3) {

            return $next($request)->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        }
        
        return redirect('/');
    }
}
