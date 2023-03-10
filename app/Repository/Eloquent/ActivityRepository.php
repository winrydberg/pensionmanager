<?php

namespace App\Repository\Eloquent;

use App\Repository\Interfaces\ActivityRepositoryInterface;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function getActivityByDate($date): Collection
    {
        // $activities = Activity::whereMonth('created_at', date('m', strtotime($date)))->with('causer')->get();
        $activities = Activity::orderBy('created_at')->with('causer', 'subject')->get()->groupBy(function($data) {
            return $data->created_at->format('Y-m-d');
        });

        return $activities;
    }


    public function getClaimActivities($claimid): Collection
    {
        return Activity::where('subject_id', $claimid)->orderBy('created_at', 'DESC')->get();
    }
}