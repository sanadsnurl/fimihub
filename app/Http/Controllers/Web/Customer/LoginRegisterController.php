<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\GetBasicPageDataTraits;
use Illuminate\Http\Request;
use App\User;
//custom import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use Response;
use Session;

class LoginRegisterController extends Controller
{
    use OtpGenerationTrait, GetBasicPageDataTraits;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'password' => 'required|confirmed|string|min:6',
            'mobile' => 'required|numeric|unique:users|digits:7',
            'country_code' => 'required|string',
            'terms'=> 'required',
        ]);
        if(!$validator->fails()){
            $data=$request->toArray();
            $data['user_type']=3;
            $user = User::create($data);
                if($user != NULL){
                    $userid = $data['mobile'];
                    Session::put('userid', $userid);
                    $this->OtpGeneration($userid);
                    Session::flash('message', 'Register Succesfully, Please Verify Now!');
                    Session::flash('modal_check', 'open');
                    return redirect('/register');

                }else{
                    Session::flash('message', 'Registration Failed , Please try again!');
                    return redirect()->back();
                }

        }
        else{
        	return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            // 'user_id' => 'required|numeric',
            'user_id' => 'required|numeric|digits:7',
            'country_code' => 'required|string',

        ]);
        if(!$validator->fails()){
            // dd($request->fcm_token);
            $user_id = $request->input('user_id');
            $password = $request->input('password');
            $country_code = $request->input('country_code');
            $mobile_set = "";
            $email_set = "";

            if(is_numeric($user_id))
            {
                $loginData =["mobile"=>$user_id,"password"=>$password,"country_code"=>$country_code];
                $mobile_set = $user_id;
            }
            else{
                $loginData =["email"=>$user_id,"password"=>$password];
                $email_set = $user_id;
            }

            if(!auth()->attempt($loginData))
            {
                Session::flash('message', 'Invalid Credentials !');
                return redirect()->back()->withInput();
            }
            else{
                $user = Auth::user();
                Session::put('user', $user);
            }

            if($mobile_set != NULL)
            {
                $userid = $mobile_set;
                Session::put('userid', $userid);
                $user_data = auth()->user()->userData($userid);
                if (isset($request->token)) {
                    $user_update_data['device_token'] = $device_token;
                    $user_dev_token = auth()->user()->UpdateLogin($user_update_data);
                }
                if($user_data->mobile_verified_at == NULL)
                {
                    Session::flash('error_message', 'Please verify your Mobile Number !');
                    $this->OtpGeneration($userid);
                    Session::flash('modal_check2', 'open');
                    return redirect()->back();
                }
                else
                {
                    return redirect('home');
                }
            }
            else
            {
                $userid = $email_set;
                Session::put('userid', $userid);
                $user_data = auth()->user()->userData($userid);
                if($user_data->email_verified_at == NULL)
                {
                    Session::flash('message', 'Please verify your Email-ID !');
                    $this->OtpGeneration($userid);
                    Session::flash('modal_check2', 'open');
                    return redirect()->back();

                }
                else
                {
                    return redirect('home');
                }
            }
        }
        else{
        	return redirect()->back()->withInput()->withErrors($validator);
        }
    }



    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }

    public function resendOtp(Request $request)
    {
        $userid = session('userid');
        Session::flash('message', 'Please verify your Account !');
        $this->OtpGeneration($userid);
        Session::flash('modal_check', 'open');
        return redirect()->back();
    }
    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'num1' => 'required|digits:1',
            'num2' => 'required|digits:1',
            'num3' => 'required|digits:1',
            'num4' => 'required|digits:1',

        ]);
        if(!$validator->fails()){
            $otp=$request->input('num1').$request->input('num2').$request->input('num3').$request->input('num4');
            $data['otp']=$otp;
            $data['userid']=session('userid');
            //dd($data);
            $otp_verified_status=$this->OtpVerification($data);
            if($otp_verified_status==2){
                Session::flash('modal_check', 'open');
                Session::flash('error_message', 'Invalid OTP');

                return redirect()->back();
            }
            elseif($otp_verified_status==1){
                Session::flash('message', 'Account Verified, Plaese Login');

                return redirect('/login');
            }else{
                return redirect('/logout');
            }

        }
        else{
            //dd($validator);
            Session::flash('modal_check', 'open');

        	return redirect()->back()->withErrors($validator);
        }

    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'=>'required|confirmed|min:6',

        ]);
        if(!$validator->fails()){
            $userid = session('userid');
            $password = $request->input('password');
            $user = new User();
            $user_data = $user->userData($userid);

            $data=['userid'=>$userid,'password'=>$password];
            if($user_data != NULL ){
                    $user = new User();
                    $user->changePassword($data);
                    $user_data = User::find($user_data->id);
                    $user_data->verification_code = NULL;
                    $user_data->save();
                    Session::flash('message', 'Password Changed ,Please Login');
                    return redirect('login');

            }
            Session::flash('message', 'Invalid User Id');
            return redirect('/login');
        }else{
            Session::flash('forget_pwd_modal_check', 'open');
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number'=>'required',
            'country_code'=>'required|string',

        ]);
        if(!$validator->fails()){
            $userid = $request->input('phone_number');
            Session::put('userid', $userid);

            $otp_verified_status=$this->OtpGeneration($userid);

            if($otp_verified_status==2){
                Session::flash('forget_pwd_snd_otp_modal_check', 'open');
                Session::flash('error_message', 'OTP Not Sent');

                return redirect()->back();
            }
            elseif($otp_verified_status==1){
                Session::flash('modal_check', 'open');
                Session::flash('error_message', 'Please verify your Account !');
                return redirect()->back();
            }else{
                Session::flash('message', 'Something went wrong !');
                return redirect('/login');
            }
        }else{
            Session::flash('forget_pwd_snd_otp_modal_check', 'open');

            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function verifyForgetPasswordOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'num1' => 'required|digits:1',
            'num2' => 'required|digits:1',
            'num3' => 'required|digits:1',
            'num4' => 'required|digits:1',

        ]);
        if(!$validator->fails()){
            $otp=$request->input('num1').$request->input('num2').$request->input('num3').$request->input('num4');
            $data['otp']=$otp;
            $data['userid']=session('userid');
            //dd($data);
            $otp_verified_status=$this->OtpVerification($data);
            if($otp_verified_status==2){
                Session::flash('modal_check', 'open');
                Session::flash('error_message', 'Invalid OTP');

                return redirect()->back();
            }
            elseif($otp_verified_status==1){
                Session::flash('forget_pwd_modal_check', 'open');
                // Session::flash('error_message', 'OTP Verified!');
                return redirect()->back();
            }else{
                Session::flash('message', 'Something went wrong !');
                return redirect('/login');
            }

        }
        else{
            //dd($validator);
            Session::flash('modal_check', 'open');

        	return redirect()->back()->withErrors($validator);
        }

    }

    public function verifyOtpLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'num1' => 'required|digits:1',
            'num2' => 'required|digits:1',
            'num3' => 'required|digits:1',
            'num4' => 'required|digits:1',

        ]);
        if(!$validator->fails()){
            $otp=$request->input('num1').$request->input('num2').$request->input('num3').$request->input('num4');
            $data['otp']=$otp;
            $data['userid']=session('userid');
            $otp_verified_status=$this->OtpVerification($data);


            if($otp_verified_status==2){
                Session::flash('modal_check2', 'open');
                Session::flash('error_message', 'Invalid OTP');

                return redirect()->back();
            }
            elseif($otp_verified_status==1){
                $user = Auth::user();
                Session::put('user', $user);
                Session::flash('message', 'Account Verified, Plaese Login');

                return redirect('/home');
            }else{
                return redirect('/logout');
            }

        }
        else{
            //dd($validator);
            Session::flash('modal_check2', 'open');
            return redirect()->back()->withErrors($validator);
        }

    }


}
