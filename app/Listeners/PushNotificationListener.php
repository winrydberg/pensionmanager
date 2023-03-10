<?php

namespace App\Listeners;

use App\Events\NewClaimRegistered;
use App\Events\NewPushNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\PushNotification;
use Exception;
use Illuminate\Support\Facades\Log;

class PushNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewPushNotificationEvent  $event
     * @return void
     */
    public function handle(NewPushNotificationEvent $event)
    {
        $title = $event->title;
        $message = $event->message;
        $claimid = $event->claimid;
        try{
            $notification = new PushNotification();
            $notification->sendPushNotification($title, $message, $claimid);
        }catch(Exception $e){
            Log::error('UNABLE TO SEND NOTIFICATION  => '. $e->getMessage());
        }
    }
}
