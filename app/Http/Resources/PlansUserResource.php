<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class PlansUserResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="PlansUser",
     *     type="object",
     *     @OA\Property(property="user_id", type="integer", description="ID пользователя"),
     *     @OA\Property(property="plan_id", type="integer", description="ID плана"),
     *     @OA\Property(property="date_begin", type="string", format="date", description="Дата начала"),
     *     @OA\Property(property="date_end", type="string", format="date", description="Дата окончания")
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'date_begin' => $this->date_begin,
            'date_end' => $this->date_end,
        ];
    }
}
