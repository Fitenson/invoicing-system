<?php

namespace App\Modules\User\Controller;

use App\Http\Controllers\Controller;


class UserViewController extends Controller {
    public function index()
    {
        return view('user.index');
    }
}
