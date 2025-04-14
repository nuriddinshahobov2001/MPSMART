<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreUsersResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="StoreUsersResource",
     *     type="object",
     *     title="Store Users Resource",
     *     description="Resource representation of a store user",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         example=1,
     *         description="ID of the store user"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         example="John Doe",
     *         description="Name of the store user"
     *     ),
     *     @OA\Property(
     *         property="api_key",
     *         type="string",
     *         example="abc123xyz",
     *         description="API key for the store user"
     *     ),
     *     @OA\Property(
     *         property="store_type",
     *         ref="#/components/schemas/StoreResource",
     *         description="Details of the associated store"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date",
     *         example="17-12-2024",
     *         description="Creation date of the store user"
     *     )
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'api_key' => $this->api_key,
            'client_id' => $this->client_id,
            'nalog_type' => $this->nalog_type,
            'nalog_percent' => $this->nalog_percent,
            'store_type' => new StoreResource($this->store),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
