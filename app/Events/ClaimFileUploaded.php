<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClaimFileUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $files = [];
    public $userid;
    public $departmentid;
    public $claimid;
    public $isprocessedfile;
    
    public function __construct(array $files, $user, $department, $claimid, $isprocessedfile=0)
    {
        $this->files = $files;
        $this->userid = $user;
        $this->departmentid = $department;
        $this->claimid = $claimid ;
        $this->isprocessedfile = $isprocessedfile;
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