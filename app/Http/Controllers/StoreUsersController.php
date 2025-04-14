<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreUpdateRequest;
use App\Http\Requests\StoreUser\StoreUsersRequest;
use App\Http\Requests\StoreUser\StoreUsersUpdateRequest;
use App\Http\Resources\StoreUsersResource;
use App\Models\StoreModel;
use App\Models\StoreUserModel;
use Illuminate\Http\Request;

class StoreUsersController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/store-users",
     *     summary="Get a list of all store users",
     *     tags={"Store Users"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of store users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/StoreUsersResource")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $stores = StoreUserModel::all();
        return StoreUsersResource::collection($stores);
    }

    /**
     * @OA\Post(
     *     path="/api/store-users",
     *     summary="Create a new store user",
     *     tags={"Store Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "api_key", "store_id", "client_id", "nalog_type", "nalog_percent"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe",
     *                 description="Name of the store user"
     *             ),
     *             @OA\Property(
     *                 property="api_key",
     *                 type="string",
     *                 example="abc123xyz",
     *                 description="API key for the store user"
     *             ),
     *             @OA\Property(
     *                 property="store_id",
     *                 type="integer",
     *                 example=1,
     *                 description="ID of the associated store"
     *             ),
     *             @OA\Property(
     *                 property="client_id",
     *                 type="string",
     *                 example="casdadadasdqw",
     *                 description="ID of the associated client"
     *             ),
     *             @OA\Property(
     *                 property="nalog_type",
     *                 type="string",
     *                 example="Asdfbg",
     *                 description="Nalog type"
     *             ),
     *             @OA\Property(
     *                 property="nalog_percent",
     *                 type="string",
     *                 example="2.4",
     *                 description="Nalog percent"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Store user created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StoreUsersResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     )
     * )
     */

    public function store(StoreUsersRequest $request)
    {
        $data = $request->validated();
        $store =  StoreUserModel::create([
            'name' => $data['name'],
            'api_key' => $data['api_key'],
            'store_id' => $data['store_id'],
        ]);
        return new StoreUsersResource($store);
    }


    /**
     * @OA\Get(
     *     path="/api/store-users/{id}",
     *     summary="Retrieve a store user by ID",
     *     tags={"Store Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the store user",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store user data retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StoreUsersResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store not found")
     *         )
     *     )
     * )
     */

    public function show(string $id)
    {
        $store = StoreUserModel::find($id);
        if (is_null($store)) {
            return response()->json(['message' => 'Store not found'], 404);
        }
        return new StoreUsersResource($store);
    }

    /**
     * @OA\Put(
     *     path="/api/store-users/{id}",
     *     summary="Update an existing store user",
     *     tags={"Store Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the store user to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "api_key", "store_id"},
     *             @OA\Property(property="name", type="string", example="Updated Store User"),
     *             @OA\Property(property="api_key", type="string", example="newApiKey123"),
     *             @OA\Property(property="store_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store user updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/StoreUsersResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store not found")
     *         )
     *     )
     * )
     */

    public function update(StoreUsersUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $store = StoreUserModel::find($id);
        if (is_null($store)) {
            return response()->json(['message'=>'Store not found'], 404);
        }
        $store->name = $data['name'];
        $store->api_key = $data['api_key'];
        $store->store_id = $data['store_id'];
        $store->save();
        return new StoreUsersResource($store);
    }

    /**
     * @OA\Delete(
     *     path="/api/store-users/{id}",
     *     summary="Delete a store user by ID",
     *     tags={"Store Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the store user to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store user deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store not found")
     *         )
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $store = StoreUserModel::find($id);
        if (is_null($store)) {
            return response()->json(['message'=>'Store not found'], 404);
        }
        $store->delete();
        return response()->json(['message' => 'Store deleted successfully']);
    }
}
