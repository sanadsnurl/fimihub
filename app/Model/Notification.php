<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Exception;

class Notification extends Model
{
    public function makeNotifiaction($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();

        $query_data = DB::table('notifications')->insertGetId($data);


        return $query_data;
    }

    public function getNotificationById($user_id, $id)
    {
        try
        {
            $notification_data=DB::table('notifications')
                    ->where('id', $id)
                    ->where('user_id', $user_id)
                    ->orderBy('created_at','desc')
                    ->first();

            return $notification_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }
    public function getAllNotifications($user_id, $type)
    {
        try
        {
            if ($type == 1) {
                $notification_data=DB::table('notifications')
                    ->where('user_id', $user_id)
                    ->where('visibility',1)
                    ->whereNull('deleted_at')
                    ->orderBy('created_at','desc')
                    ->paginate(8);
            } else if ($type == 2) {
                    $notification_data=DB::table('notifications')
                    ->where('user_id', $user_id)
                    ->where('visibility',0)
                    ->whereNull('deleted_at')
                    ->orderBy('created_at','desc')
                    ->paginate(8);
            } else {
                $notification_data=DB::table('notifications')
                    ->where('user_id', $user_id)
                    ->whereNull('deleted_at')
                    ->orderBy('created_at','desc')
                    ->paginate(8);
            }

            return $notification_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function markAsRead($user_id, $id = false)
    {
        try
        {
            if ($id) {
                $notification_data=DB::table('notifications')
                        ->where('user_id', $user_id)
                        ->where('id', $id)
                        ->update(['visibility' => 1]);
            } else {
                $notification_data=DB::table('notifications')
                ->where('user_id', $user_id)
                ->update(['visibility' => 1]);
            }

            return $notification_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

}
