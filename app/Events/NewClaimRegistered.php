<?php

namespace App\Events;

use App\Models\Claim;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewClaimRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Claim $claim;
    public string $title;
    public string $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Claim $claim, $title, $message)
    {
        $this->claim = $claim;
        $this->title = $title;
        $this->message = $message;
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
