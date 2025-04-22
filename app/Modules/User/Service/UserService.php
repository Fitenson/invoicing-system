<?php

namespace App\Modules\User\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Common\Service\BaseService;
use App\Modules\User\Repository\UserRepository;
use App\Modules\User\Model\User;


class UserService extends BaseService {
    private UserRepository $user_repository;

    public function __construct(UserRepository $user_repository) {
        $this->user_repository = $user_repository;
    }


    public function getPaginated(array $params)
    {
        $selects = [
            '*',
            DB::raw('(SELECT name FROM users AS u1 WHERE u1.id = users.created_by LIMIT 1) as created_by_name'),
            DB::raw('(SELECT name FROM users AS u2 WHERE u2.id = users.updated_by LIMIT 1) as updated_by_name'),
        ];

        return $this->user_repository->getPaginated(User::class, $params, $selects);
    }


    public function show(string $id)
    {
        $user = $this->user_repository->show(User::class, $id);
        return $user;
    }


    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            //  Default password
            $password = '88888888';
            $data['password'] = Hash::make($password);

            $user = $this->user_repository->create(User::class, $data);
            DB::commit();

            return $user;
        } catch(\Exception $error) {
            DB::rollBack();
            throw new HttpException(422, 'Failed to create user: ' . $error->getMessage());
        }
    }


    public function findAll()
    {
        return $this->user_repository->findAll(User::class);
    }


    public function update(string $id, array $data)
    {
        try {
            DB::beginTransaction();
            $user = $this->user_repository->findById(User::class, $id);
            DB::commit();

            return $this->user_repository->update($user, $data);
        } catch(\Exception $error) {
            DB::rollBack();
            throw new HttpException(422, 'Failed to update user: ' . $error->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $this->user_repository->destroy(User::class, $id);
            DB::commit();
            return true;
        } catch(\Exception $error) {
            throw new HttpException(422, 'Failed to delete user: ' . $error->getMessage());
        }
    }


    public function getTotalUser()
    {
        return $this->user_repository->getTotalRecord(User::class);
    }
}
