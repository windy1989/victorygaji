<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role1 = null, $role2 = null, $role3 = null, $role4 = null)
    {
        $passed = false;
        if($role1){
            if($role1 == session('bo_type')){
                $passed = true;
            }
        }
        if($role2){
            if($role2 == session('bo_type')){
                $passed = true;
            }
        }
        if($role3){
            if($role3 == session('bo_type')){
                $passed = true;
            }
        }
        if($role4){
            if($role4 == session('bo_type')){
                $passed = true;
            }
        }
        if($passed){
            return $next($request);
        }else{
            abort(401);
        }
    }
}
