<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreRequest;
use App\Http\Requests\Store\StoreUpdateRequest;
use App\Http\Resources\StoreResource;
use App\Models\StoreModel;
use App\Models\StoreUserModel;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/stores",
     *     summary="Retrieve a list of all stores",
     *     tags={"Stores"},
     *     @OA\Response(
     *         response=200,
     *         description="List of stores",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/StoreResource")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $stores = StoreModel::all();
        return StoreResource::collection($stores);
    }

    /**
     * @OA\Post(
     *     path="/api/stores",
     *     summary="Create a new store",
     *     tags={"Stores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Store")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Store successfully created",
     *         @OA\JsonContent(ref="#/components/schemas/StoreResource")
     *     )
     * )
     */

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $store = StoreModel::create([
            'name' => $data['name']
        ]);
        return new StoreResource($store);
    }

    /**
     * @OA\Get(
     *     path="/api/stores/{id}",
     *     summary="Retrieve a store by ID",
     *     tags={"Stores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the store",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store data",
     *         @OA\JsonContent(ref="#/components/schemas/StoreResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */

    public function show(string $id)
    {
        $store = StoreModel::find($id);
        if (is_null($store)) {
            return response()->json('Store not found', 404);
        }
        return new StoreResource($store);

    }

    /**
     * @OA\Put(
     *     path="/api/stores/{id}",
     *     summary="Update a store by ID",
     *     tags={"Stores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the store",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Store Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store successfully updated",
     *         @OA\JsonContent(ref="#/components/schemas/StoreResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */

    public function update(StoreUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $store = StoreModel::find($id);
        if (is_null($store)) {
            return response()->json('Store not found', 404);
        }
        $store->name = $data['name'];
        $store->save();
        return new StoreResource($store);
    }

    /**
     * @OA\Delete(
     *     path="/api/stores/{id}",
     *     summary="Delete a store by ID",
     *     tags={"Stores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the store",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store successfully deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $store = StoreModel::find($id);
        if (is_null($store)) {
            return response()->json('Store not found', 404);
        }
        $stores = StoreUserModel::where('store_id', $store->id)->count();
        if ($stores > 0) {
            return response()->json('Cannot delete store: Users are associated with this store.', 400);
        }
        $store->delete();
        return response()->json(['message' => 'Store deleted successfully'], 200);

    }
}
