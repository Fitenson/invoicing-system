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


    public function login(array $post_data)
    {
        $User = User::where('name', $post_data['name'])->first();

        if(empty($User) || !Hash::check($post_data['password'], $User->password)) {
            return false;
        }

        Auth::login($User); // For frontend blade session
        $token = $User->createToken('web')->plainTextToken;

        session(['sanctum_token' => $token]);

        return true;
    }


    public function register(array $post_data)
    {
        $User = $this->auth_repository->create(User::class, [
            'name' => $post_data['name'],
            'email' => $post_data['email'],
            'password' => Hash::make($post_data['password']),
        ]);

        Auth::login($User);
        $token = $User->createToken('web')->plainTextToken;

        session(['sanctum_token' => $token]);


        return $User;
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return true;
    }
}
