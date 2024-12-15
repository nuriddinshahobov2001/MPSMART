<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleUser\RoleUserRequest;
use App\Http\Requests\RoleUser\RoleUserUpdateRequest;
use App\Http\Resources\RoleUsersResource;
use App\Models\RoleUserModel;
use Illuminate\Http\Request;

class RoleUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = RoleUserModel::all();
        return RoleUsersResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleUserRequest $request)
    {
        $data = $request->validated();

        $userRole = RoleUserModel::where('user_id', $data['user_id'])
            ->where('role_id', $data['role_id'])
            ->exists();

        if ($userRole) {
            return response()->json([
                'message' => 'User already has this role'
            ]);
        }

        try {
            $role = RoleUserModel::create([
                'user_id' => $data['user_id'],
                'role_id' => $data['role_id']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign role',
                'error' => $e->getMessage()
            ], 500);
        }

        return new RoleUsersResource($role);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = RoleUserModel::find($id);
        if (is_null($role)){
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }
        return new RoleUsersResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUserUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $role = RoleUserModel::find($id);
        if (is_null($role)){
            return response()->json([
                'message' => 'Role not found',
            ],404);
        }
        $role->update([
            'user_id' => $data['user_id'],
            'role_id' => $data['role_id']
        ]);
        return new RoleUsersResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = RoleUserModel::find($id);
        if (is_null($role)){
            return response()->json([
                'message' => 'Role not found',
            ]);
        }
        $role->delete();
        return response()->json([
            'message' => 'Role deleted!'
        ]);
    }
}
