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
}
