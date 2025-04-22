<?php

namespace App\Modules\Project\Controller;

use App\Common\Controller\BaseController;
use App\Modules\Project\Service\ProjectService;
use App\Modules\User\Service\UserService;
use Illuminate\Http\Request;


/**
 * ViewController for rendering and displaying data on the Project.
 *
 * This controller is responsible for:
 * - Rendering Blade views
 * - Coordinating with various Services from other modules to retrieve and display data
 *
 * Responsibilities:
 * - Should remain thin and focused only on view-related logic
 * - Must delegate business logic to Service layers
 */
class ProjectViewController extends BaseController {
    private ProjectService $project_service;
    private UserService $user_service;

    public function __construct(ProjectService $project_service, UserService $user_service) {
        $this->project_service = $project_service;
        $this->user_service = $user_service;
    }


    /**
     *  Display and render index page
    */
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


    /**
     *  Display and render create Project page
    */
    public function create() {
        $users = $this->user_service->findAll();

        return view('project.create', compact('users'));
    }

    /**
     *  Display and render show Project page
    */
    public function show(string $id) {
        $project = $this->project_service->show($id);
        $users = $this->user_service->findAll();

        return view('project.show', compact('project', 'users'));
    }
}
