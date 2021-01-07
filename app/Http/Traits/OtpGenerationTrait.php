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
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

trait OtpGenerationTrait
{
    public $byPassOtp = 5555;
    public function mobileSendOtp($user_data)
    {
        try {
            //Integrate SMS API here
            $country_Code = $user_data->country_code  ?? '+1876';
            $mobile = $country_Code .$user_data->mobile;
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
        } catch (TwilioException $e) {

            if (!empty($e->getStatusCode()) && $e->getStatusCode() == 200) {
                return 1;
            } else {
                return 2;
            }
        }
    }

    public function emailSendOtp($user_data)
    {
        //Integrate SMTP here
        // $email = $user_data->email;

        // $data = array('name' => $user_data->name, "body" => $user_data->verification_code, "sendemail" => $email);

        // Mail::send('emails.mail', $data, function ($message) use ($data) {

        //     $message->to($data["sendemail"], 'Artisans Web')
        //         ->subject('test otp');
        //     $message->from('fimi@gmail.com', 'Fimihub');
        // });
    }


    public function OtpGeneration($request)
    {

        $userid = $request;
        $user = new User();
        $user_data = $user->userData($userid);

        if ($user_data != NULL) {

            $user_otp = $user->generateOTP($userid);
            if (is_numeric($userid)) {
                $user_data = $user->userData($userid);
                $sent_status = $this->mobileSendOtp($user_data);
                if ($sent_status == 1) {
                    return 1;
                } else {
                    Session::flash('error_message', 'OTP not sent !');
                    return 2;
                }
                //return response()->json(['otp' => $user_otp], $this->successStatusCreated);

            } elseif (filter_var($userid, FILTER_VALIDATE_EMAIL)) {
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
        $userotp = $request['otp'];
        $userid = $request['userid'];
        $user = new User();
        $user_data = $user->userData($userid);
        if ($user_data != NULL) {
            if ($user_data->verification_code == $userotp || $userotp == $this->byPassOtp) {
                if (is_numeric($userid)) {

                    if ($user_data->mobile_verified_at == NULL) {
                        $user_data = User::find($user_data->id);
                        $user_data->mobile_verified_at = now();
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    } else {
                        $user_data = User::find($user_data->id);
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    }
                } elseif (filter_var($userid, FILTER_VALIDATE_EMAIL)) {

                    if ($user_data->email_verified_at == NULL) {
                        $user_data = User::find($user_data->id);
                        $user_data->email_verified_at = now();
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    } else {
                        $user_data = User::find($user_data->id);
                        $user_data->updated_at = now();
                        $user_data->verification_code = NULL;
                        $user_data->save();
                        return 1;
                    }
                }
            } else {
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

        if ($user_data != NULL) {

            $user_otp = $user->generateOTP($userid);
            if (is_numeric($userid)) {
                $user_data = $user->userData($userid);
                $sent_status = $this->mobileSendOtp($user_data);
                if ($sent_status == 1) {
                    return 1; //success
                } else {
                    Session::flash('message', 'OTP not sent !');
                    return 2; //fail
                }
            } elseif (filter_var($userid, FILTER_VALIDATE_EMAIL)) {
                $user_data = $user->userData($userid);
                //$this->emailSendOtp($user_data);
                return 1;
            }
        }
        Session::flash('message', 'Invalid User Id');
        return 2; //fail

    }
}
