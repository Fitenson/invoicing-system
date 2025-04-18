<?php

namespace App\Modules\User\Repository;

use App\Common\Repository\BaseRepository;
use App\Modules\User\Model\User;


class UserRepository extends BaseRepository {
    public function getPaginated(string $class_name = User::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }
}
