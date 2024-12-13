<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribePlansRequest\SubscribePlansRequest;
use App\Http\Requests\SubscribePlansRequest\SubscribePlansUpdateRequest;
use App\Http\Resources\SubscribePlansResource;
use App\Models\SubscribePlansModel;

class SubscribePlansController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subscribe-plans",
     *     summary="Get list of subscription plans",
     *     tags={"Subscribe Plans"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SubscribePlansResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearer_token":{}}}
     * )
     */

    public function index()
    {
        $subscribePlans = SubscribePlansModel::all();
        return  SubscribePlansResource::collection($subscribePlans);
    }

    /**
     * @OA\Post(
     *     path="/api/subscribe-plans",
     *     summary="Create a new subscription plan",
     *     tags={"Subscribe Plans"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Basic Plan"),
     *             @OA\Property(property="price", type="number", format="float", example=9.99),
     *             @OA\Property(property="description", type="string", example="This is a basic subscription plan."),
     *             @OA\Property(property="month", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subscription plan created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SubscribePlansResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation error")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearer_token":{}}}
     * )
     */

    public function store(SubscribePlansRequest $request)
    {
        $data = $request->validated();
        $plan = SubscribePlansModel::create([
            'title' => $data['title'],
            'price' => $data['price'],
            'description' => $data['description'],
            'month' => $data['month'],
        ]);

        return new SubscribePlansResource($plan);
    }

    /**
     * @OA\Get(
     *     path="/api/subscribe-plans/{id}",
     *     summary="Get details of a specific subscription plan",
     *     tags={"Subscribe Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the subscription plan to retrieve",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SubscribePlansResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plan not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearer_token":{}}}
     * )
     */

    public function show(string $id)
    {
        $plan = SubscribePlansModel::find($id);
        if ($plan){
            return new SubscribePlansResource($plan);
        }
        return response()->json([
            'message' => 'Plan not found'
        ],404);
    }

    /**
     * @OA\Put(
     *     path="/api/subscribe-plans/{id}",
     *     summary="Update an existing subscription plan",
     *     tags={"Subscribe Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the subscription plan to update",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Updated Plan"),
     *             @OA\Property(property="price", type="number", format="float", example=14.99),
     *             @OA\Property(property="description", type="string", example="Updated description for the plan."),
     *             @OA\Property(property="month", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription plan updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SubscribePlansResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Plan not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation error")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearer_token":{}}}
     * )
     */

    public function update(SubscribePlansUpdateRequest $request, string $id)
    {
//        return 111;
        $data = $request->validated();
        $plan = SubscribePlansModel::find($id);
        if ($plan){
            $plan->title = $data['title'];
            $plan->price = $data['price'];
            $plan->description = $data['description'] ?? "(NULL)";
            $plan->month = $data['month'];
            $plan->save();
            return new SubscribePlansResource($plan);
        }
        return response()->json([
            'message' => 'Plan not found'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
