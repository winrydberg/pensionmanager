<?php

namespace App\Repository\Eloquent;

use App\Models\Notification;
use App\Repository\Interfaces\NotificationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class NotificationRepository implements NotificationRepositoryInterface
{
    public function getNotifications(): Collection
    {
        return Notification::where('user_id', Auth::user()->id)->where('read', true)->get();       
    }


    public function getReadNotifications(): Collection
    {
        return Notification::where('user_id', Auth::user()->id)->where('read', false)->get();
    }


    public function getUserNotifCount(): array
    {
        $user = Auth::user();
        $unReadCount = Notification::where('user_id', $user->id)->where('read', false)->count();
        return [
            'status' => 'success',
            'unreadcount' => $unReadCount,
        ];
    }

}