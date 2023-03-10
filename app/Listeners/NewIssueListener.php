<?php

namespace App\Listeners;

use App\Events\NewIssueRaised;
use App\Events\NewPushNotificationEvent;
use App\Models\Company;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class NewIssueListener
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
     * @param  \App\Events\NewIssueRaised  $event
     * @return void
     */
    public function handle(NewIssueRaised $event)
    {
        $issue = $event->issue;

        if($issue){
            $claim = $issue->claim;
            $company = Company::select('id', 'name')->where('id',$claim->company_id)->first();
            activity()
                    ->causedBy(Auth::user())
                    ->performedOn($claim)
                    ->withProperties(['company' => $company])
                    ->log(config('enums.ISSUE_WITH_CLAIM'), config('enums.ISSUE_WITH_CLAIM'));
            //send push notification to users
            event(new NewPushNotificationEvent("Issue On Claim", "A new issue has been reported on claim with Claim ID: " . $claim->claimid, $claim->id));

        }
    }
}