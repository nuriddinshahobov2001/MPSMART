<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleUsersResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="RoleUsersResource",
     *     type="object",
     *     title="Role-User Assignment Resource",
     *     description="Role-User assignment details",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         example=1,
     *         description="The ID of the role-user assignment"
     *     ),
     *       @OA\Property(
     *            property="user",
     *            ref="#/components/schemas/UserResource"
     *     ),
     *     @OA\Property(
     *         property="role",
     *         ref="#/components/schemas/RoleResource",
     *         description="Role associated with the user"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date",
     *         example="16-12-2024",
     *         description="The creation date of the role-user assignment"
     *     )
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UsersResource($this->user),
            'role' => new RoleResource($this->role) ,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
