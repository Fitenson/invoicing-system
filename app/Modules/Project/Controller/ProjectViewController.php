<?php

namespace App\Modules\Project\Controller;

use App\Common\Controller\BaseController;
use App\Modules\Project\Service\ProjectService;
use App\Modules\User\Service\UserService;
use Illuminate\Http\Request;


class ProjectViewController extends BaseController {
    private ProjectService $project_service;
    private UserService $user_service;

    public function __construct(ProjectService $project_service, UserService $user_service) {
        $this->project_service = $project_service;
        $this->user_service = $user_service;
    }


    public function index(Request $request)
    {
        $params = $request->only([
            'search',
            'status',
            'user_id',
            'sort_by',
            'sort_order',
            'per_page'
        ]);

        // Set defaults if not provided
        $params['sort_by'] ??= 'created_at';
        $params['sort_order'] ??= 'desc';


        $projects = $this->project_service->getPaginated($params);
        return view('project.index', compact('projects'));
    }


    public function create() {
        $users = $this->user_service->findAll();

        return view('project.create', compact('users'));
    }


    public function show(string $id) {
        $project = $this->project_service->show($id);
        $users = $this->user_service->findAll();

        return view('project.show', compact('project', 'users'));
    }
}
