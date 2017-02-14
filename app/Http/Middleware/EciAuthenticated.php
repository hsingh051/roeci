<?php

namespace App\Http\Middleware;
use Session;
use Closure;
use Illuminate\Support\Facades\Auth;
use Config;

class EciAuthenticated
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
       // die('here');    
       if (Auth::user()->role == 1) {
            
            return $next($request)->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        }
        
        return redirect('/');
    }
}
