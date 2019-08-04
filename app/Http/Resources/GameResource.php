<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'character_id' => $this->id,
            'experience' => $this->experience,
            'agility' => $this->agility,
            'strength' => $this->strength,
            'fraction' => FractionResource::make($this->fraction),
            'coordinates' => CoordinateResource::make($this->whenLoaded('coordinate')),
            'abilities' => AbilityResource::collection($this->whenLoaded('abilities')),
        ];
    }
}
