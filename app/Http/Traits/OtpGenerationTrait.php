<?php

namespace App\Http\Traits;
use App\Http\Controllers\Web\Merchant\OtpManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Mail;
use Session;
use Twilio\Rest\Client;

trait OtpGenerationTrait {

    public function mobileSendOtp($user_data)
    {
        try {
            //Integrate SMS API here
            $mobile = $user_data->mobile;
            $otp = $user_data->verification_code;
            // $authKey = "309952Aq8MczyMxu5e03001fP1";
            // $senderId = "ADSURL";
            // $messageMsg = urlencode("<#>Your OTP is: $otp ");
            // $postData = array(
            //     'authkey' => $authKey,
            //     'mobiles' => $mobile,
            //     'message' => $messageMsg,
            //     'sender' => $senderId,
            //     'route' => 4,
            //     'country' => 91
            // );
            // $url = "https://api.msg91.com/api/sendhttp.php";
            // $ch = curl_init();
            // curl_setopt_array($ch, array(
            //     CURLOPT_URL => $url,
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_POST => true,
            //     CURLOPT_POSTFIELDS => $postData
            // ));
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            // $output = curl_exec($ch);
            // if (curl_errno($ch)) {
            //     echo 'error:' . curl_error($ch);
            // }
            // curl_close($ch);
            // if (strlen($output) == 24) {
            //     return 1;
            // }else{
            //     return 2;
            // }

            
            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = 'AC513ef57c0a0b86c0fca05473ad711c2a';
            $auth_token = '7caab498f7426d5185d2c89a7c34ac5b';
            // In production, these should be environment variables. E.g.:
            // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

            // A Twilio number you own with SMS capabilities
            $twilio_number = "+15017122661";
            $messageMsg = urlencode("<#>Your OTP is : $otp ");
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                8840212040,
                array(
                    'from' => $twilio_number,
                    'body' => $messageMsg
                )
            );

            if($client){
                return 1;
            }else{
                return 2;
            }

        } catch (Exception $e) {
            return response()->json(['custom_error'=> $e->getMessage()], $this->invalidStatus);

        }
        
        
    }

    public function emailSendOtp($user_data)
    {
        //Integrate SMTP here
        $email = $user_data->email;
       
        $data = array('name'=>$user_data->name , "body" => $user_data->verification_code ,"sendemail"=>$email);

        Mail::send('emails.mail' , $data , function($message) use ($data){
            
            $message->to($data["sendemail"]  , 'Artisans Web')
                    ->subject('test otp');
            $message->from('indianshaadiwala@gmail.com' , 'Indian Shadiwala');
        });

    }


    public function OtpGeneration($request)
    {
        
        $userid = $request;
        $user = new User();
        $user_data = $user->userData($userid);
        
        if($user_data != NULL)
        {
            
            $user_otp = $user->generateOTP($userid);
            if(is_numeric($userid))
            {
                $user_data = $user->userData($userid);
                $sent_status=$this->mobileSendOtp($user_data);
                if($sent_status==1){
                    return 1;
                }
                else{
                    Session::flash('error_message', 'OTP not sent !');
                    return 3; 
                }
                //return response()->json(['otp' => $user_otp], $this->successStatusCreated);
                
            }
            elseif (filter_var($userid, FILTER_VALIDATE_EMAIL)) 
            {
                $user_data = $user->userData($userid);
                //$this->emailSendOtp($user_data);
                return response()->json(['otp' => $user_otp], $this->successStatusCreated);
                
            }
        }
        Session::flash('error_message', 'Invalid Phone Number');
        return 2;
            
    }

    public function OtpVerification($request)
    {
        $userotp =$request['otp'];
        $userid = $request['userid'];
        $user = new User();
        $user_data = $user->userData($userid);
        if($user_data != NULL){
            if($user_data->verification_code == $userotp)
            {
                if(is_numeric($userid))
                {  
                
                    if($user_data->mobile_verified_at == NULL)
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->mobile_verified_at = now();
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    }
                    else
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    }
                }
                elseif (filter_var($userid, FILTER_VALIDATE_EMAIL)) 
                {
                
                    if($user_data->email_verified_at == NULL)
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->email_verified_at = now();
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    }
                    else
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                        
                    }
                }
            }else{ 
                Session::flash('modal_check', 'Invalid OTP');
                return 2;
            }
        }
        Session::flash('message', 'Verification Failed');
        return 2;
    }

    public function OtpGenerationForgetPassword($request)
    {
        
        $userid = session('user_id_temp');
        
        $user = new User();
        $user_data = $user->userData($userid);
        
        if($user_data != NULL)
        {
            
            $user_otp = $user->generateOTP($userid);
            if(is_numeric($userid))
            {
                $user_data = $user->userData($userid);
                $sent_status=$this->mobileSendOtp($user_data);
                if($sent_status==1){
                    return 1;//success
                }
                else{
                    Session::flash('message', 'OTP not sent !');
                    return 2; //fail
                }
                
            }
            elseif (filter_var($userid, FILTER_VALIDATE_EMAIL)) 
            {
                $user_data = $user->userData($userid);
                //$this->emailSendOtp($user_data);
            return 1;
                
            }
        }
        Session::flash('message', 'Invalid User Id');
        return 2;//fail
            
    }





}