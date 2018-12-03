<?php

namespace App\Http\Resources;

use App\Fraction;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FractionCollection extends ResourceCollection
{
    public $collects = Fraction::class;
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
            $data[] = new FractionResource($item);
        }
        return $data;
    }
}
