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
        $arrayAccess = ['1','2','3','4','5','6','7','8'];
        if($role1){
            if(in_array($role1,$arrayAccess)){
                $passed = true;
            }
        }
        if($role2){
            if(in_array($role2,$arrayAccess)){
                $passed = true;
            }
        }
        if($role3){
            if(in_array($role3,$arrayAccess)){
                $passed = true;
            }
        }
        if($role4){
            if(in_array($role4,$arrayAccess)){
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
