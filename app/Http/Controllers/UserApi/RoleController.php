<?php

namespace App\Http\Controllers\UserApi;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $role = RoleResource::collection(Role::all());
        return $this->apiResponse($role);
    }
    public function store(Request $request)
    {
        // $validated = $request->validated();
        // if ($validated) {
        //     return $this->apiResponse(null, $validated->errors(), 400);
        // }
        $role = Role::create([
            'role_name' => $request->role_name,
        ]);

        if ($role) {
            return $this->successResponse(new RoleResource($role), 'the role  Save');
        }
        return $this->errorResponse('the role Not Save');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = role::find($id);

        if ($role) {
            return $this->successResponse(new RoleResource($role), null);
        }
        return $this->errorResponse('the role Not Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if ($request->fails()) {
        //     return $this->apiResponse(null, $request->errors(), 400);
        // }
        $role = role::find($id);
        if (!$role) {
            return $this->errorResponse('the role Not Found', 404);
        }
        if ($role->user_id === Auth::id()) {

            $role->update([
                'role_name' => $request->role_name,

            ]);

            if ($role) {
                return $this->successResponse(new roleResource($role), 'the role update');
            }
        }
        return $this->errorResponse('you con not updet the role Because you are not authorized', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = role::find($id);

        if (!$role) {
            return $this->errorResponse('the role Not Found', 404);
        }


        $role->delete($id);
        if ($role) {
            return $this->apiResponse(null, 'the role deleted', 200);
        }
        return $this->errorResponse('you con not delete ', 404);
    }
}
