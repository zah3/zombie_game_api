<?php

namespace App\Http\Resources;

use App\Character;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CharacterCollection extends ResourceCollection
{
    public $collects = Character::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        foreach ($this->collection as $item) {
            $data[] = new CharacterResource($item);
        }
        return $data;
    }
}
