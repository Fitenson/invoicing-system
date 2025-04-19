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
            'email' => 'max:100',
            'address' => 'nullable|string',
        ]);

        $user = $this->project_service->create($post_data);

        return redirect("/user/{$user->id}")->with('success', 'User created successfully!');
    }



    public function update(string $id, Request $request)
    {
        $post_data = $request->validate([
            'name' => 'required|string|max:100|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $update_user = $this->project_service->update($id, $post_data);

        if ($update_user) {
            return redirect()->route('users.show', ['id' => $id])->with('success', 'User updated successfully.');
        }


        return redirect()->back()->with('error', 'Failed to update user.');
    }



    public function destroy(string $id)
    {
        $result = $this->project_service->destroy($id);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        }

        // If deletion failed
        return redirect()->back()->with('error', 'Failed to delete user');
    }
}
