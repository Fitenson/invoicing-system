<?php

namespace App\Modules\Project\Service;

use App\Common\Service\BaseService;
use App\Modules\Project\Repository\ProjectRepository;
use App\Modules\Project\Model\Project;



class ProjectService extends BaseService {
    private ProjectRepository $project_repository;


    public function __construct(ProjectRepository $project_repository) {
        $this->project_repository = $project_repository;
    }


    public function getPaginated(array $params)
    {
        $selects = [
            '*'
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


    public function update(string $id, array $data)
    {
        $project = $this->project_repository->findById(Project::class, $id);
        return $this->project_repository->update($project, $data);
    }


    public function destroy(string $id)
    {
        return $this->project_repository->destroy(Project::class, $id);
    }
}
