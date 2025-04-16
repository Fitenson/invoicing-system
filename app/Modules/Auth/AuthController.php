<?php

namespace App\Modules\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\Model\User;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    //  Login function
    public function login(Request $request)
    {
        $postData = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        //  If success, go to dashboard
        if (Auth::attempt($postData)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }


        //  Else, return errors
        return back()->withErrors([
            'username' => 'Incorrect username or password.',
        ])->withInput();
    }


    //  Register/create new user
    public function register(Request $request)
    {
        $postData = $request->validate([
            'username' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $User = User::create([
            'username' => $postData['username'],
            'email' => $postData['email'],
            'password' => Hash::make($postData['password']),
        ]);

        Auth::login($User);

        return redirect('/dashboard');
    }

    //  Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
