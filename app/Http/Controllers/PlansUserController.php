<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersPlan\UsersPlanRequest;
use App\Http\Requests\UsersPlan\UsersPlanUpdateRequest;
use App\Http\Resources\PlansUserResource;
use App\Models\PlansUserModel;
use Illuminate\Http\Request;

class PlansUserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/users-plan",
     *     summary="Get a list of user plans",
     *     tags={"PlansUser"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PlansUser")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        $plans = PlansUserModel::all();
        return PlansUserResource::collection($plans);
    }

    /**
     * @OA\Post(
     *     path="/users-plan",
     *     summary="Create a new user plan",
     *     tags={"PlansUser"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data to create a new user plan",
     *         @OA\JsonContent(
     *             required={"plan_id", "user_id", "date_begin", "date_end"},
     *             @OA\Property(property="plan_id", type="integer", description="ID плана"),
     *             @OA\Property(property="user_id", type="integer", description="ID пользователя"),
     *             @OA\Property(property="date_begin", type="string", format="date", description="Дата начала"),
     *             @OA\Property(property="date_end", type="string", format="date", description="Дата окончания")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully created",
     *         @OA\JsonContent(ref="#/components/schemas/PlansUser")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid data provided"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(UsersPlanRequest $request)
    {
        $data = $request->validated();
        $plan = PlansUserModel::create([
            'plan_id' => $data['plan_id'],
            'user_id' => $data['user_id'],
            'date_begin' => $data['date_begin'],
            'date_end' => $data['date_end'],
        ]);
        return new PlansUserResource($plan);
    }

    /**
     * @OA\Get(
     *     path="/users-plan/{id}",
     *     summary="Get a specific user plan",
     *     tags={"PlansUser"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID плана пользователя",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the user plan",
     *         @OA\JsonContent(ref="#/components/schemas/PlansUser")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show(string $id)
    {
        $plan = PlansUserModel::find($id);
        if (is_null($plan)) {
            return response()->json('Plan not found', 404);
        }
        return new PlansUserResource($plan);
    }

    /**
     * @OA\Put(
     *     path="/users-plan/{id}",
     *     summary="Update a user plan",
     *     tags={"PlansUser"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID плана пользователя",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data for updating the user plan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="plan_id", type="integer", description="ID плана"),
     *             @OA\Property(property="user_id", type="integer", description="ID пользователя"),
     *             @OA\Property(property="date_begin", type="string", format="date", description="Дата начала"),
     *             @OA\Property(property="date_end", type="string", format="date", description="Дата окончания")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully updated the user plan",
     *         @OA\JsonContent(ref="#/components/schemas/PlansUser")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(UsersPlanUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $plan = PlansUserModel::find($id);
        if (is_null($plan)) {
            return response()->json('Plan not found', 404);
        }
        $plan->plan_id = $data['plan_id'];
        $plan->user_id = $data['user_id'];
        $plan->date_begin = $data['date_begin'];
        $plan->date_end = $data['date_end'];
        $plan->save();
        return new PlansUserResource($plan);
    }

    /**
     * @OA\Delete(
     *     path="/users-plan/{id}",
     *     summary="Delete a user plan",
     *     tags={"PlansUser"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID плана пользователя для удаления",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Plan deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $plan = PlansUserModel::find($id);
        if (is_null($plan)) {
            return response()->json('Plan not found', 404);
        }
        $plan->delete();
        return response()->json(['message', 'Plan deleted successfully']);
    }
}
