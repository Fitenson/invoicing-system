<?php

namespace App\Modules\Auth\Controller;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class AuthViewController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }


    public function register()
    {
        return view('auth.register');
    }
}
