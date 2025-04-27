<?php

namespace App\Modules\Project\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Common\Repository\BaseRepository;
use App\Modules\Project\Model\Project;

/**
 * Repository layer for the Project module.
 *
 * Responsible for interacting with data sources (e.g., Eloquent models or raw DB queries).
 * This layer abstracts data retrieval and persistence logic, keeping it separate from business logic.
 */
class ProjectRepository extends BaseRepository {
    /**
     *  @param string $class_name       Class name of a model. Example: Project::class
     *  @param array $params            Parameter for the backend to perform server-side filtering
     *  @param array $selects           Selects query for the backend to perform in order to display the necessary data.
     *  @param array $extra_filters     Additional condition of filter, if any
     *
     *  @return LengthAwarePaginator $data
    */
    public function getPaginated(string $class_name = Project::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }
}
