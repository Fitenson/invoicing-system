<?php

namespace App\Modules\User\Controller;

use App\Common\Controller\BaseController;
use App\Modules\User\Service\UserService;
use Illuminate\Support\Facades\Request;

class UserController extends BaseController {
    private UserService $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }


    public function index(Request $request)
    {
        $params = $request->all('param');
        $users = $this->user_service->getPaginated($params);
        return view('user.index', compact('users'));
    }
}
