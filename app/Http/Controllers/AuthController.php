<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Redirect;

class AuthController extends Controller
{
    public function login()
    {
        if(session('bo_id')) {
            return redirect('dashboard');
        }

        return view('login');
    }

    public function auth(Request $request){
        $user = User::where('username', $request->username)->where('status','1')->first();
		if($user) {
            if(Hash::check($request->password, $user->password)) {
                session([
                    'bo_id'             => $user->id,
                    'bo_username'       => $user->username,
                    'bo_name'           => $user->name,
                    'bo_type'           => $user->type,
                    'bo_typename'       => $user->type(),
                ]);

                $response = [
                    'status' 	=> 200,
                    'message'	=> 'Successfull logged in. Please wait!'
                ];

            } else {
                $response = [
                    'status' 	=> 422,
                    'message'	=> 'Account not found'
                ];
            }
		} else {
			$response = [
				'status' 	=> 422,
				'message'	=> 'Account not found'
			];
		}

        return response()->json($response);
    }

    public function logout(){
        session()->flush();
        Auth::logout();
        return redirect('login');
    }
}