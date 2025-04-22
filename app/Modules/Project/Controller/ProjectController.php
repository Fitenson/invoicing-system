<?php

namespace App\Modules\Project\Controller;

use App\Common\Controller\BaseController;
use App\Modules\Project\Service\ProjectService;
use Illuminate\Http\Request;


/**
 * Controller for handling API requests related to Project moduie.
 *
 * This layer is responsible for receiving HTTP requests,
 * passing input data to the Service layer, and returning appropriate responses.
 * Should remain thin and free of business logic.
 */
class ProjectController extends BaseController {
    private ProjectService $project_service;


    public function __construct(ProjectService $project_service) {
        $this->project_service = $project_service;
    }

    /**
     *  Index API
     *  Display all Projects data
     *
     *  Note: Currently notbeing used
    */
    public function index(Request $request)
    {
        // $params = $request->only('param');
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ];

        $users = $this->project_service->getPaginated($params);
        return view('project.index', compact('projects'));
    }


    /**
     *  Create new Project API
     *  @param Request $request     POST request containing the FormData of the Project data
    */
    public function store(Request $request)
    {
        $post_data = $request->validate([
            'name' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'client' => 'nullable|exists:users,id',
            'rate_per_hour' => 'nullable|string',
            'total_hours' => 'nullable|string',
        ]);

        $this->project_service->create($post_data);

        return redirect()->route('projects.index')->with('success', 'User deleted successfully');
    }


    /**
     * Update Project record
     *
     * @param string $id       Pass the id of the selected Project record to be updated
     * @param Request $request     POST request containing the FormData of the fields to be updated
    */
    public function update(string $id, Request $request)
    {
        $post_data = $request->validate([
            'name' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'client' => 'nullable|exists:users,id',
            'rate_per_hour' => 'nullable|string',
            'total_hours' => 'nullable|string',
        ]);

        $update_project = $this->project_service->update($id, $post_data);

        if ($update_project) {
            return redirect()->route('projects.show', ['id' => $id])->with('success', 'Project updated successfully.');
        }


        return redirect()->back()->with('error', 'Failed to update project.');
    }


    /**
     *  Delete selected Project record
     *  @param string $id       Pass the id of the selected Project record to be deleted
    */
    public function destroy(string $id)
    {
        $result = $this->project_service->destroy($id);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            return redirect()->route('projects.index')->with('success', 'User deleted successfully');
        }

        // If deletion failed
        return redirect()->back()->with('error', 'Failed to delete user');
    }
}
