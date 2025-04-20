<?php

namespace App\Modules\Project\Service;

use App\Common\Service\BaseService;
use App\Modules\Project\Repository\ProjectRepository;
use App\Modules\Project\Model\Project;
use App\Modules\User\Model\User;


class ProjectService extends BaseService {
    private ProjectRepository $project_repository;


    public function __construct(ProjectRepository $project_repository) {
        $this->project_repository = $project_repository;
    }


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


    public function create(array $data)
    {
        $project = $this->project_repository->create(Project::class, $data);

        return $project;
    }


    public function show(string $id)
    {
        $project = $this->project_repository->show(Project::class, $id);
        return $project;
    }


    public function findAll()
    {
        return $this->project_repository->findAll(Project::class);
    }


    public function update(string $id, array $data)
    {
        $project = $this->project_repository->findById(Project::class, $id);
        return $this->project_repository->update($project, $data);
    }


    public function destroy(string $id)
    {
        return $this->project_repository->destroy(Project::class, $id);
    }


    public function getTotalProject()
    {
        return $this->project_repository->getTotalRecord(Project::class);
    }
}
