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
     * @OA\Get(
     *     path="/api/roles",
     *     operationId="getRoles",
     *     tags={"Roles"},
     *     summary="Retrieve all roles",
     *     description="Returns a collection of roles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RoleResource")
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
        $roles = RoleModel::all();
        return RoleResource::collection($roles);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     operationId="storeRole",
     *     tags={"Roles"},
     *     summary="Create a new role",
     *     description="Creates a new role and returns the created role",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Admin",
     *                 description="The name of the role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
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
     *                     "name": {"The name field is required."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     operationId="getRoleById",
     *     tags={"Roles"},
     *     summary="Retrieve a role by ID",
     *     description="Returns a single role resource by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the role to retrieve",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     operationId="updateRole",
     *     tags={"Roles"},
     *     summary="Update a role by ID",
     *     description="Updates the name of an existing role and returns the updated resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the role to update",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Manager",
     *                 description="The new name of the role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
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
     *                     "name": {"The name field is required."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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


    public function destroy(string $id)
    {
        //
    }
}
