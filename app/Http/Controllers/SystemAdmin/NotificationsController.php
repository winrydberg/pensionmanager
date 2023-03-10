<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Repository\Interfaces\NotificationRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    // private NotificationRepositoryInterface $notifRepo;
    // public function __construct(NotificationRepositoryInterface $notifRepo)
    // {
    //     $this->notifRepo = $notifRepo;   
    // }


    public function getUnreadNotifications() {
        try{
            $title ='Unread Notifications';
            $notifications =  Notification::where('user_id', Auth::user()->id)->where('read', false)->with('issue')->get();
            return view('systemadmins.notifications', compact('notifications', 'title'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Oops, something went wrong. Unable to get notifications');
        }
    }

    public function getReadNotifications() {
        try{
            $title = "Read Notifications";
            $notifications =  Notification::where('user_id', Auth::user()->id)->where('read', true)->get();
            return view('systemadmins.notifications', compact('notifications', 'title'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Oops, something went wrong. Unable to get notifications');
        }
    }

    public function markeAsRead(Request $request){
        $id = $request->id;

        $notification = Notification::find($id);

        if($notification){
            $notification->read = true;
            $notification->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Notification read'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to mark as read'
            ]);
        }
    }


    // public function getNotificationCount(){
    //     try{
    //         $result = $this->notifRepo->getUserNotifCount();
    //         return response()->json($result);
    //     }catch(Exception $e){
    //         return response()->json()
    //     }
    // }
}