<?php

namespace App\Http\Middleware;

use Cookie;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Login
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
        
        if(session('bo_id')) {
            return $next($request);
        } else {
            session()->flush();
            return redirect('/login?url='.base64_encode($request->fullUrl()));
        }
    }
}
