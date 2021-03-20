<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Notification;
use Auth;

class NotificationController extends Controller
{
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function getAllNotifications($type = false)
    {
        $userId = Auth::id();
        $data = $this->notification->getAllNotifications($userId, $type);

        return response()->json(['message'=> 'Success','status' =>true, 'data' => $data], $this->successStatus);
    }

    public function getNotificationById($id)
    {
        $userId = Auth::id();
        $data = $this->notification->getNotificationById($userId, $id);
        return response()->json(['message'=> 'Success','status' =>true, 'data' => $data], $this->successStatus);
    }

    public function markAsRead($id = false)
    {
        $userId = Auth::id();
        $data = $this->notification->markAsRead($userId, $id);
        return response()->json(['message'=> 'Success','status' => true, 'data' => $data], $this->successStatus);
    }
}
