<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribePlansResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="SubscribePlansResource",
     *     type="object",
     *     title="Subscribe Plan Resource",
     *     description="Representation of a single subscription plan",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="Updated Plan"),
     *     @OA\Property(property="price", type="number", format="float", example=14.99),
     *     @OA\Property(property="description", type="string", example="Updated description for the plan."),
     *     @OA\Property(property="month", type="integer", example=3),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-13T12:00:00Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-13T12:30:00Z")
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'month' => $this->month,
            'price' => $this->price,
            'description' => ($this->description === "(NULL)") ? null :$this->description,
            'created_at' => $this->created_at,
        ];
    }
}
