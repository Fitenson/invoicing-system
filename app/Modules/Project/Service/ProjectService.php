<?php

namespace App\Modules\Project\Service;

use App\Common\Service\BaseService;
use App\Modules\Project\Repository\ProjectRepository;
use App\Modules\Project\Model\Project;
use App\Modules\User\Model\User;


/**
 * Service Layer for Project module.
 *
 * This layer is responsible for handling application-level logic,
 * separating complex business operations from the data layer (e.g., repositories or models).
 *
 * Typically used to coordinate saving/updating data, validations, or calling other services,
 * while keeping controllers and models clean from business logic.
 */
class ProjectService extends BaseService {
    private ProjectRepository $project_repository;


    public function __construct(ProjectRepository $project_repository) {
        $this->project_repository = $project_repository;
    }

    /**
     *  Use to implement server side pagination, filtering and sorting
     *  @param array $params        Parameter that determine which page to render
    */
    public function getPaginated(array $params)
    {
        $selects = [
            '*',
            'client_name' => User::select(['name'])->whereColumn('users.id', 'projects.client'),
            'created_by_name' => User::select(['name'])->whereColumn('users.id', 'projects.created_by'),
            'updated_by_name' => User::select(['name'])->whereColumn('users.id', 'projects.updated_by'),
        ];

        return $this->project_repository->getPaginated(Project::class, $params, $selects);
    }


    /**
     *  Create new Project data
     *  @param string $class_name       Pass class name of a model within Project module
     *  @param array $data              Pass data to be saved in database
     *
     *  @return Model $model            Return model of the saved data
    */
    public function create(array $data)
    {
        $project = $this->project_repository->create(Project::class, $data);

        return $project;
    }

    /**
     *  Return Project data
     *  @param string $id       Pass id of the selected Project
    */
    public function show(string $id)
    {
        $project = $this->project_repository->show(Project::class, $id);
        return $project;
    }

    /**
     *  Used for display project dropdown
    */
    public function findAll()
    {
        return $this->project_repository->findAll(Project::class);
    }

    /**
     *  Update a record based on class name of a model within Project module
     *  @param string $id               Pass id of the selected record
     *  @param array $data              Pass data to be saved on the database
    */
    public function update(string $id, array $data)
    {
        $project = $this->project_repository->findById(Project::class, $id);
        return $this->project_repository->update($project, $data);
    }

    /**
     *  Delete selected Project record
     *
     *  @param string $id               Pass the id of the selected record to be deleted
    */
    public function destroy(string $id)
    {
        return $this->project_repository->destroy(Project::class, $id);
    }

    /**
     *  Return the total number of projects in the system
    */
    public function getTotalProject()
    {
        return $this->project_repository->getTotalRecord(Project::class);
    }
}
