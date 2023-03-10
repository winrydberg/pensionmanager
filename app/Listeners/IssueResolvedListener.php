<?php

namespace App\Listeners;

use App\Events\IssueResolved;
use App\Events\NewPushNotificationEvent;
use App\Models\Claim;
use App\Models\Company;
use App\Models\Notification;
use App\Models\User;
use App\Repository\Interfaces\IssueRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class IssueResolvedListener
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
     * @param  \App\Events\IssueResolved  $event
     * @return void
     */
    public function handle(IssueResolved $event)
    {
        $issue = $event->issue;

        if($issue){
            $claim = $issue->claim;
            if($claim){
                $company = Company::select('id', 'name')->where('id',$claim->company_id)->first();
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($claim)
                    ->withProperties(['company' => $company])
                    ->log(config('enums.ISSUE_RESOLVED'), config('enums.ISSUE_RESOLVED'));
                $users = User::all();
                foreach($users as $user){
                    Notification::create([
                        'message' => $issue->resolve_message,
                        'user_id' => $user->id,
                        'read' => false,
                        'type' => 'Issue resolved with ID '.$claim->claimid.', '. $issue->updated_at->diffForHumans(),
                        'url' => 'claim?claimid='.$claim->id,
                        'issue_id' => $issue->id,
                        'claim_id' => $claim->id,
                        'issue_ticket' => $issue->issue_ticket,
                    ]);
                }
                $claim->update([
                    'has_issue' => false
                ]);
                //send push notification to users
                event(new NewPushNotificationEvent("Issue Resolved", "An issue  has been resolved on claim with Claim ID: " . $claim->claimid, $claim->id));

            }
        }
    }
}