<?php

namespace App\Modules\Auth\Controller;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

/**
 * ViewController for rendering login and register page
 *
 * This controller is responsible for:
 * - Rendering Blade views
 * - Coordinating with various Services from other modules to retrieve and display data
 *
 * Responsibilities:
 * - Should remain thin and focused only on view-related logic
 * - Must delegate business logic to Service layers
 */
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
