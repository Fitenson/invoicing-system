<?php

namespace App\Modules\Auth\Repository;

use Illuminate\Support\Facades\Hash;

use App\Common\Repository\BaseRepository;
use App\Modules\User\Model\User;

class AuthRepository extends BaseRepository {
    public function findByName(string $name)
    {
        return User::where('name', $name)->first();
    }
}
