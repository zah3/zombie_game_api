<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'experience' => $this->experience,
            'strength' => $this->strength,
            'stamina' => $this->stamina,
            'speed' => $this->speed,
            'ability_points' => $this->ability_points,
            'user' => UserResource::make($this->whenLoaded('user')),
            'fraction' => FractionResource::make($this->whenLoaded('fraction')),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
