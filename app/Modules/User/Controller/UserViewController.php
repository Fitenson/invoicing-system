<?php

namespace App\Modules\User\Controller;

use App\Common\Controller\BaseController;
use App\Modules\User\Service\UserService;
use Illuminate\Support\Facades\Request;


class UserViewController extends BaseController {
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


    public function create() {
        return view('user.create');
    }


    public function show(string $id) {
        $user = $this->user_service->show($id);

        return view('user.show', compact('user'));
    }
}
