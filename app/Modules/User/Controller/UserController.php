<?php

namespace App\Modules\User\Controller;

use App\Common\Controller\BaseController;
use App\Modules\User\Model\User;
use App\Modules\User\Service\UserService;
use Illuminate\Support\Facades\Request;

class UserController extends BaseController {
    private UserService $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }


    public function index(Request $request)
    {
        // $params = $request->only('param');
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ];

        $users = $this->user_service->getPaginated($params);
        return view('user.index', compact('users'));
    }


    public function show(User $user)
    {
        return view('user.show');
    }


    public function create(User $user)
    {
        return view('user.create');
    }


    public function update(User $user)
    {
        return view('user.update');
    }
}
