<?php

namespace App\Http\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;

trait NotificationTrait
{
    function pushNotification($notification_data) {

		$firebase_token = $notification_data['device_token'];
        $title = $notification_data['title'];
        $notifications = $notification_data['notification'];
		//server key
        $your_project_id_as_key = 'AAAAcsjec0w:APA91bH7EFy5uBHYsSGqPq9wRDTxVdFlSUgNHqwzCuvjP_TNNLMuEUm9aroesnJBC9jCCuCq_ylm9NF9Vg_VrqKF2E2R5r-WMlBV2p090MM4jHa8FTGp-ok6rJDnnXKzgOA7cfIgoutz';
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
        'authorization: key=' . $your_project_id_as_key,
        'content-type: application/json'
        ];

        $postdata = '{
                "to" : "'.$firebase_token.'",
                    "notification" : {
                        "title":"'.$title.'",
                        "text" : "'.strip_tags($notifications).'"
                    },
                "data" : {
                    "id" : 1,
                    "title":"'.$title.'",
                    "description" : "'.strip_tags($notifications).'"
                }
            }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);

        //var_dump($result) ;
    }

}
