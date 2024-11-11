<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Profil',
            'content'       => 'profile',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function updatePassword(Request $request,$code){
        $data = User::where('nik',session('bo_nik'))->first();

        if($data){
            
            $data->update([
                'password'  => bcrypt($request->new_password),
            ]);

            $response = [
                'status'  => 200,
                'message' => 'Password berhasil dirubah.'
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }
        return response()->json($response);
    }
}
