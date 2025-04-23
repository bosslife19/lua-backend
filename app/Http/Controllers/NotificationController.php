<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function createNotification(Request $request){
        $user = $request->user();
      $request->validate(['title'=>'required', 'type'=>'required', 'message'=>'required']);
      Notification::create([
        'title'=>$request->title,
        'type'=>$request->type,
        'message'=>$request->message,
        'user_id'=>$user->id,
      ]);

      return response()->json(['status'=>true], 200);


    }
    public function getUnreadNotifications(Request $request){
        $user = $request->user();
        $notifications = Notification::where('user_id', $user->id)->where('read', false)->get();
        return response()->json(['status'=>true, 'notifications'=>$notifications], 200);
    }

    public function markNotificationsAsRead(Request $request) {
        $user = $request->user();
    
        Notification::where('user_id', $user->id)
                    ->where('read', false)
                    ->update(['read' => true]);
    
        return response()->json(['status' => true]);
    }
}
