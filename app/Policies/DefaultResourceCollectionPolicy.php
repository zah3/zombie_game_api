<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DefaultResourceCollectionPolicy
{
    use HandlesAuthorization;

    public function view(User $user,ResourceCollection $resourceCollection)
    {
        foreach($resourceCollection as $resource) {
            if ($this->can('view',$resource) === false) {
                return false;
            }
        }
        return true;
    }
}
