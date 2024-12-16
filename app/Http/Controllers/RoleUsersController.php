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
     * @OA\Get(
     *     path="/api/role-users",
     *     operationId="getRoleUsers",
     *     tags={"Role Users"},
     *     summary="Retrieve all role-user assignments",
     *     description="Returns a collection of role-user assignments",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RoleUsersResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        $roles = RoleUserModel::all();
        return RoleUsersResource::collection($roles);
    }

    /**
     * @OA\Post(
     *     path="/api/role-users",
     *     operationId="assignRoleToUser",
     *     tags={"Role Users"},
     *     summary="Assign a role to a user",
     *     description="Creates a new role-user assignment if it doesn't already exist",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "role_id"},
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1,
     *                 description="The ID of the user to whom the role is assigned"
     *             ),
     *             @OA\Property(
     *                 property="role_id",
     *                 type="integer",
     *                 example=2,
     *                 description="The ID of the role to assign to the user"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role assigned successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoleUsersResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User already has this role",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User already has this role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to assign role",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to assign role"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry..."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "user_id": {"The user_id field is required."},
     *                     "role_id": {"The role_id field is required."}
     *                 }
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/role-users/{id}",
     *     operationId="getRoleUserById",
     *     tags={"Role Users"},
     *     summary="Retrieve a specific role-user assignment",
     *     description="Returns a single role-user assignment by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the role-user assignment",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role-User assignment found",
     *         @OA\JsonContent(ref="#/components/schemas/RoleUsersResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role-User assignment not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role not found"
     *             )
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/role-users/{id}",
     *     operationId="updateRoleUser",
     *     tags={"Role Users"},
     *     summary="Update an existing role-user assignment",
     *     description="Updates a role-user assignment by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the role-user assignment to update",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "role_id"},
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1,
     *                 description="The ID of the user to update"
     *             ),
     *             @OA\Property(
     *                 property="role_id",
     *                 type="integer",
     *                 example=2,
     *                 description="The ID of the role to assign to the user"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role-User assignment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoleUsersResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role-User assignment not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "user_id": {"The user_id field is required."},
     *                     "role_id": {"The role_id field is required."}
     *                 }
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/role-users/{id}",
     *     operationId="deleteRoleUser",
     *     tags={"Role Users"},
     *     summary="Delete a role-user assignment",
     *     description="Deletes a role-user assignment by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the role-user assignment to delete",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role-User assignment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role deleted!"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role-User assignment not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role not found"
     *             )
     *         )
     *     )
     * )
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
