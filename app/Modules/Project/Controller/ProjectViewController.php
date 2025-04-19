<?php

namespace App\Modules\Project\Controller;

use App\Common\Controller\BaseController;
use App\Modules\Project\Service\ProjectService;
use Illuminate\Support\Facades\Request;


class ProjectViewController extends BaseController {
    private ProjectService $project_service;

    public function __construct(ProjectService $project_service) {
        $this->project_service = $project_service;
    }


    public function index(Request $request)
    {
        // $params = $request->only('param');
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ];

        $projects = $this->project_service->getPaginated($params);
        return view('project.index', compact('projects'));
    }


    public function create() {
        return view('project.create');
    }


    public function show(string $id) {
        $project = $this->project_service->show($id);

        return view('project.show', compact('project'));
    }
}
