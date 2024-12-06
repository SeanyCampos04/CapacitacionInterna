<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    public function index(){
        if (Auth::check()) {
            return view('layouts.dashboard');
        }
    }
}
