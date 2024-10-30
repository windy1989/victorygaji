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
    public function handle(Request $request, Closure $next, $role1 = null, $role2 = null, $role3 = null, $role4 = null, $role5 = null, $role6 = null, $role7 = null, $role8 = null, $role9 = null, $role10 = null): mixed
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
        if($role5){
            if($role5 == session('bo_type')){
                $passed = true;
            }
        }
        if($role6){
            if($role6 == session('bo_type')){
                $passed = true;
            }
        }
        if($role7){
            if($role7 == session('bo_type')){
                $passed = true;
            }
        }
        if($role8){
            if($role8 == session('bo_type')){
                $passed = true;
            }
        }
        if($role9){
            if($role9 == session('bo_type')){
                $passed = true;
            }
        }
        if($role10){
            if($role10 == session('bo_type')){
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
