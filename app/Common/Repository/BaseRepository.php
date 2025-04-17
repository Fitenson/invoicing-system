<?php

namespace App\Common\Repository;


abstract class BaseRepository {
    protected function create(string $class_name, array $data)
    {
        return $class_name::create($data);
    }
}
