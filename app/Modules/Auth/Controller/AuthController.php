<?php

namespace App\Modules\Auth\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\Model\User;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Service\AuthService;

class AuthController extends Controller
{
    private AuthService $auth_service;


    public function __construct(AuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }


    //  Login function
    public function login(Request $request)
    {
        $post_data = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $login = $this->auth_service->login($post_data, $request);

        //  If success, go to dashboard
        if ($login) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }


        //  Else, return errors
        return back()->withErrors([
            'name' => 'Incorrect name or password.',
        ])->withInput();
    }


    //  Register/create new user
    public function register(Request $request)
    {
        $post_data = $request->validate([
            'name' => 'required|string|max:100|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $User = $this->auth_service->register($post_data);

        Auth::login($User);

        return redirect('/dashboard');
    }

    //  Logout
    public function logout(Request $request)
    {
        $this->auth_service->logout($request);
        return redirect('/login');
    }
}
