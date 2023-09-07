<?php

namespace App\Http\Controllers\UserApi;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    use ApiResponse;
    public function index()
    {

        $users = User::with('roles')->get();

        $array = [];

        foreach ($users as $user) {
            $roles = [];
            foreach ($user->roles as $role) {
                $roles[] = $role->role_name;
            }
            $array[] = [
                "name" => $user->name,
                "roles" => $roles
            ];
        }

        return $this->apiResponse($array);
    }
    public function show($id)
    {
        $user = User::with('roles')->find($id);

        if ($user) {
            $roles = [];
            foreach ($user->roles as $role) {
                $roles[] = $role->role_name;
            }
            $array = [
                "name" => $user->name,
                "roles" => $roles
            ];
            return $this->apiResponse($array);
        } else {
            return $this->errorResponse('User not found');
        }
    }
    public function store(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $roleName = $request->input('role_name');
            $roles = Role::where('role_name', $roleName)->first();

            $user->roles()->attach($roles->id);
            $array = [
                "name" => $user->name,
                "roles" => $roles->role_name
            ];
            return $this->successResponse($array, 'Roles assigned successfully');
        } else {
            // Return an error message if the user is not found
            return $this->errorResponse('User not found');
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $roleName = $request->input('role_name');
            $roles = Role::where('role_name', $roleName)->first();

            $user->roles()->sync($roles->id);
            $array = [
                "name" => $user->name,
                "roles" => $roles->role_name
            ];
            return $this->successResponse($array, 'Roles assigned successfully');
        } else {
            // Return an error message if the user is not found
            return $this->errorResponse('User not found');
        }
    }
    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $role_name = $request->input('role_name');
            $role = Role::where('role_name', $role_name)->first();

            if ($role) {
                $user->roles()->detach($role->id);
                return $this->successResponse([], 'role removed successfully');
            } else {
                // Return an error message if the role is not found
                return $this->errorResponse('role not found');
            }
        } else {
            // Return an error message if the user is not found
            return $this->errorResponse('User not found');
        }
    }
}
