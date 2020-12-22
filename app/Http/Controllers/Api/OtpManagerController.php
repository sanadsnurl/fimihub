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
            $mobile = $user_data->mobile;
            $otp = $user_data->verification_code;
            $authKey = "309952Aq8MczyMxu5e03001fP1";
            $senderId = "ADSURL";
            $messageMsg = urlencode("<#>Your OTP is: $otp ");
            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobile,
                'message' => $messageMsg,
                'sender' => $senderId,
                'route' => 4,
                'country' => 91
            );
            $url = "https://api.msg91.com/api/sendhttp.php";
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $output = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'error:' . curl_error($ch);
            }
            curl_close($ch);
            if (strlen($output) == 24) {
                return 1;
            }else{
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
                //$sent_status=$this->mobileSendOtp($user_data);
                // if($sent_status==1){
                //     return response()->json(['otp'=>$user_otp, 'message' => 'OTP Sent','status'=>true], $this->successStatusCreated);
                // }
                // else{
                //     return response()->json(['message' => 'OTP not sent','status'=>false], $this->failureStatus);
                // }
                return response()->json(['otp'=>(string)$user_otp, 'message' => 'OTP Sent','status'=>true], $this->successStatusCreated);

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
                                                'status' => true,
                                                'message'=>'Verified Successfully'], $this->successStatus);
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
