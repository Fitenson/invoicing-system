<?php

namespace App\Modules\User\Service;

use App\Common\Service\BaseService;
use App\Modules\User\Repository\UserRepository;
use App\Modules\User\Model\User;


class UserService extends BaseService {
    private UserRepository $user_repository;

    public function __construct(UserRepository $user_repository) {
        $this->user_repository = $user_repository;
    }


    public function getPaginated($params)
    {
        $selects = [
            '*'
        ];

        return $this->user_repository->getPaginated(User::class, $params, $selects);
    }
}
