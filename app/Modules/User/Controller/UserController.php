<?php

namespace App\Modules\User\Controller;

use App\Common\Controller\BaseController;
use App\Modules\User\Model\User;
use App\Modules\User\Service\UserService;
use Illuminate\Http\Request;


class UserController extends BaseController {
    private UserService $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }


    public function index(Request $request)
    {
        // $params = $request->only('param');
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ];

        $users = $this->user_service->getPaginated($params);
        return view('user.index', compact('users'));
    }


    public function store(Request $request)
    {
        $post_data = $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $this->user_service->create($post_data);

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }



    public function update(string $id, Request $request)
    {
        $post_data = $request->validate([
            'name' => 'required|string|max:100|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $update_user = $this->user_service->update($id, $post_data);

        if ($update_user) {
            return redirect()->route('users.show', ['id' => $id])->with('success', 'User updated successfully.');
        }


        return redirect()->back()->with('error', 'Failed to update user.');
    }



    public function destroy(string $id)
    {
        $result = $this->user_service->destroy($id);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        }

        // If deletion failed
        return redirect()->back()->with('error', 'Failed to delete user');
    }
}
