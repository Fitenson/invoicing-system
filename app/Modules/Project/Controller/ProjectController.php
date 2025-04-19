<?php

namespace App\Modules\Project\Controller;

use App\Common\Controller\BaseController;
use App\Modules\Project\Service\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends BaseController {
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

        $users = $this->project_service->getPaginated($params);
        return view('project.index', compact('projects'));
    }


    public function store(Request $request)
    {
        $post_data = $request->validate([
            'name' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'client' => 'nullable|string',
            'rate_per_hour' => 'nullable|string',
            'total_hours' => 'nullable|string',
        ]);

        $project = $this->project_service->create($post_data);

        return redirect("/project/{$project->id}")->with('success', 'Project created successfully!');
    }



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
