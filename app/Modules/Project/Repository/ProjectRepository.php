<?php

namespace App\Modules\Project\Repository;

use App\Common\Repository\BaseRepository;
use App\Models\Project;



class ProjectRepository extends BaseRepository {
    public function getPaginated(string $class_name = Project::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }
}
