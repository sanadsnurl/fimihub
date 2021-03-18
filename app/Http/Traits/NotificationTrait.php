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
        $page_token = $notification_data['page_token'] ?? '-1';
        $page_url = $notification_data['page_url'] ?? '';
		//server key
        $your_project_id_as_key = 'AAAAS7OBtOE:APA91bGi-xdLFihiaQrTEmlXedX_QDWDxNZVVqrHaMxV82cpeK_wEP_lucI8HaCtCM9bSCJvMJ2JHUE9u-B6mRpj3aVkfGUi3-wqC3Y-cUPSg3h9avqCOqCHaS7xWW-m0xmz6OVwh8tL';
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
        'authorization: key=' . $your_project_id_as_key,
        'content-type: application/json'
        ];

        $postdata = '{
                "to" : "'.$firebase_token.'",
                    "notification" : {
                        "title":"'.$title.'",
                        "body" : "'.strip_tags($notifications).'",
                        "page_token" : "'.$page_token.'",
                        "icon" : "https://fimihub.com/public/asset/customer/assets/images/logo.png",
                        "url" : "'.$page_url.'",
                        "sound" : "default"
                    },
                "data" : {
                    "id" : 1,
                    "title":"'.$title.'",
                    "description" : "'.strip_tags($notifications).'",
                    "page_token" : "'.$page_token.'",
                    "icon" : "https://fimihub.com/public/asset/customer/assets/images/logo.png",
                    "url" : "'.$page_url.'",
                    "sound" : "default"
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

        // var_dump($result) ;
        // die();
    }

}
