<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPushNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $title;
    public string $message;
    public  $claimid;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($title, $message, $claimid)
    {
        $this->title = $title;
        $this->message = $message;
        $this->claimid = $claimid;
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
