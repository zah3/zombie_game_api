<?php

namespace App\Events;

use App\{
    User,
    Role
};
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * When user is created add to him User role.
     *
     * @param User $user
     *
     * @return $this
     */
    public function userCreated(User $user)
    {
        $user->roles()->attach(Role::withName(Role::USER)->first());
        return $this;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
