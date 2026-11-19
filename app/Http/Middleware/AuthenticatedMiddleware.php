<?php

namespace App\Http\Middleware;

use App\users;
use Closure;
use Illuminate\Support\Facades\Auth;

class   AuthenticatedMiddleware
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
        if (Auth::check()) {
            if(Auth::user()->role == "USER"){
                return redirect()->route('dashboard');
            }
            return $next($request);
        }
        return route('login');
    }
}
