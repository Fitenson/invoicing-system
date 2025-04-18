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

    // Show login form
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('/dashboard');
        }

        return view('auth.login');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Login and return Sanctum token
    public function login(Request $request)
    {
        $post_data = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $login = $this->auth_service->login($post_data);

        if($login) {
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'name' => 'Incorrect name or password.',
        ])->withInput();
    }


    // Register and return Sanctum token
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $this->auth_service->register($data);

        return redirect('/dashboard');
    }

    // Logout and revoke tokens
    public function logout(Request $request)
    {
        $this->auth_service->logout($request);
        return redirect('/login');
    }
}
