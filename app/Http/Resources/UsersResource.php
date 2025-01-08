<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{

    /**
     * @OA\Schema(
     *     schema="UserResource",
     *     type="object",
     *     title="User",
     *     description="Модель данных пользователя",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="phone", type="string", example="+123456789"),
     *     @OA\Property(property="plan", ref="#/components/schemas/SubscribePlansResource"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-13T12:34:56Z"),
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name'  => $this->name,
            'phone' => $this->phone,
            'role' => $this->roles->isNotEmpty() ? $this->roles->first()->name : null,
            'plan' => new SubscribePlansResource($this->plan) ,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
