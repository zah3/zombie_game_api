<?php

namespace App\Http\Resources;

use App\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'is_active' => $this->when(Auth::user()->hasRole(Role::ROLE_ADMIN),'is_active'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'roles' => RoleResource::collection( $this->whenLoaded('roles')),
        ];
    }
}
