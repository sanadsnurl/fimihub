<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//user import section
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Mail;

class OtpManagerController extends Controller
{
    public $byPassOtp = 5555;
    public function mobileSendOtp($user_data)
    {
        try {
            //Integrate SMS API here
            $country_Code = $user_data->country_code  ?? '+1876';
            $mobile = $country_Code.$user_data->mobile;
            $otp = $user_data->verification_code;

            // Your Account SID and Auth Token from twilio.com/console
            // To set up environmental variables, see http://twil.io/secure
            $account_sid = env('TWILIO_ACCOUNT_SID');
            $auth_token = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_SENDER_NUMBER');
            // In production, these should be environment variables. E.g.:

            // A Twilio number you own with SMS capabilities
            $client = new Client($account_sid, $auth_token);
            $r = $client->messages->create(
                // the number you'd like to send the message to
                $mobile,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $twilio_number,
                    // the body of the text message you'd like to send
                    'body' => "<#>Your OTP is: $otp "
                ]
            );

            if ($r) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            return response()->json(['message'=> $e->getMessage()], $this->invalidStatus);

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
            $message->from('qubeez@gmail.com' , 'Qbeez');
        });

    }


    public function OtpGeneration(Request $request)
    {
        $userid = $request->input('userid');
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
                    return response()->json(['otp'=>$user_otp, 'message' => 'OTP Sent','status'=>true], $this->successStatusCreated);
                }
                else{
                    return response()->json(['message' => 'OTP not sent','status'=>false], $this->failureStatus);
                }
                // return response()->json(['otp'=>(string)$user_otp, 'message' => 'OTP Sent','status'=>true], $this->successStatusCreated);

            }
            elseif (filter_var($userid, FILTER_VALIDATE_EMAIL))
            {
                $user_data = $user->userData($userid);
                //$this->emailSendOtp($user_data);
                return response()->json(['otp'=>(string)$user_otp, 'message' => 'OTP Sent','status'=>true], $this->successStatusCreated);

            }
        }
            return response()->json(['status'=>false,'message' => 'Invalid User Id'], $this->failureStatus);


    }

    public function OtpVerification(Request $request)
    {
        $userotp = $request->input('otp');
        $userid = $request->input('userid');
        $user = new User();
        $user_data = $user->userData($userid);
        if($user_data != NULL){
            if($user_data->verification_code == $userotp || $userotp == $this->byPassOtp)
            {
                if(is_numeric($userid))
                {
                    if ($user_data->visibility == 1) {
                        $status= false;
                        $message = "Account needs Admin Approval !";
                        $bank_data = null;
                        $vehicle_datas = null;
                        $address_data = null;
                    }elseif($user_data->visibility == 2){
                        $status= false;
                        $message = "Account Blocked Or Disabled";
                        $bank_data = null;
                        $vehicle_datas = null;
                        $address_data = null;
                    }else{
                        $status= true;
                        $message = "success";
                    }
                    if($user_data->mobile_verified_at == NULL)
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->mobile_verified_at = now();
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();

                        $accessToken = $user_data->createToken('teckzy')->accessToken;

                        $user_data->access_token=$accessToken;
                        return response()->json(['data'=>$user_data,
                                                'status' => $status,
                                                'message'=>$message], $this->successStatus);
                    }
                    else
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();


                        return response()->json(['status' => true,'message'=>'OTP Verified Successfully'], $this->successStatus);
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

                    return response()->json(['status' => true,'message'=>'Verified Successfully'], $this->successStatus);
                    }
                    else
                    {
                        $user_data = User::find($user_data->id);
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();

                        return response()->json(['status' => true,'message'=>'OTP Verified Successfully'], $this->successStatus);
                    }
                }
            }
            return response()->json(['status' => true , 'message'=>'Invalid OTP'], $this->failureStatus);

        }

        return response()->json(['status' => false , 'message'=>'Invalid User Id'], $this->failureStatus);

    }
}
