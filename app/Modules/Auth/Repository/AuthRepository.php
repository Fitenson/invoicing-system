<?php

namespace App\Modules\Auth\Repository;

use Illuminate\Support\Facades\Hash;

use App\Common\Repository\BaseRepository;
use App\Modules\User\Model\User;


/**
 * Repository layer for the Auth module.
 *
 * Responsible for interacting with data sources (e.g., Eloquent models or raw DB queries).
 * This layer abstracts data retrieval and persistence logic, keeping it separate from business logic.
 */
class AuthRepository extends BaseRepository {
    public function findByName(string $name)
    {
        return User::where('name', $name)->first();
    }
}
