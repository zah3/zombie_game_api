<?php

namespace App\Http\Resources;

use App\Role;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    public $collects = Role::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [ 'data' => $this->collection ];
    }
}
