<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = RoleModel::all();
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $role = RoleModel::create([
            'name' => $data['name']
        ]);

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = RoleModel::find($id);
        if (is_null($role)){
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }
        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $role = RoleModel::find($id);
        if (is_null($role)){
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }
        $role->name = $data['name'];
        $role->save();
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
