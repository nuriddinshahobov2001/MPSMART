<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="RoleResource",
     *     type="object",
     *     title="Role Resource",
     *     description="Role representation",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Role ID"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Role name"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date",
     *         description="Creation date in d-m-Y format"
     *     )
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->format('d-m-Y')
        ];
    }
}
