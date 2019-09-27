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
            'name' => $this->name,
            'username' => $this->user->username,
            'character_id' => $this->id,
            'experience' => $this->experience,
            'strength' => $this->strength,
            'stamina' => $this->stamina,
            'speed' => $this->speed,
            'ability_points' => $this->ability_points,
            'fraction' => FractionResource::make($this->fraction),
            'coordinates' => CoordinateResource::make($this->whenLoaded('coordinate')),
            'abilities' => AbilityResource::collection($this->whenLoaded('abilities')),
        ];
    }
}
