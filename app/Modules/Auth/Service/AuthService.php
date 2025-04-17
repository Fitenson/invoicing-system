<?php

namespace App\Modules\Auth\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Common\Service\BaseService;
use App\Modules\User\Model\User;
use App\Modules\Auth\Repository\AuthRepository;


class AuthService extends BaseService {
    private AuthRepository $auth_repository;

    public function __construct(AuthRepository $auth_repository)
    {
        $this->auth_repository = $auth_repository;
    }


    public function login(array $post_data, Request $request)
    {
        if (Auth::attempt($post_data)) {
            $request->session()->regenerate();
            return true;
        }

        return false;
    }


    public function register(array $post_data)
    {
        $User = $this->auth_repository->create(User::class, [
            'name' => $post_data['name'],
            'email' => $post_data['email'],
            'password' => Hash::make($post_data['password']),
        ]);


        return $User;
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return true;
    }
}
