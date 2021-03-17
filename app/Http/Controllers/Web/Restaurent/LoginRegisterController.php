<?php

namespace App\Http\Controllers\Web\Restaurent;

use App\Http\Controllers\Controller;
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
    use OtpGenerationTrait;

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            'user_id' => 'required|numeric',

        ]);
        if(!$validator->fails()){
            $user_id = $request->input('user_id');
            $password = $request->input('password');
            $mobile_set = "";
            $email_set = "";

            if(is_numeric($user_id))
            {
                $loginData =["mobile"=>$user_id,"password"=>$password];
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
                Session::put('restaurent', $user);
            }

            if($mobile_set != NULL)
            {
                $userid = $mobile_set;
                Session::put('userid', $userid);
                $user_data = auth()->user()->userData($userid);
                if($user_data->mobile_verified_at == NULL)
                {
                    Session::flash('message', 'Please verify your Mobile Number !');
                    $this->OtpGeneration($userid);
                    return redirect('/resendOtp');

                }elseif($user_data->visibility == 3){
                    Session::flash('message', 'Account Disabled !');
                    return redirect()->back();
                }
                elseif($user_data->visibility != 0){
                    Session::flash('message', 'Account Deleted !');
                    return redirect()->back();
                }
                else
                {
                    return redirect('Restaurent/customerOrder');
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
        return redirect('Restaurent/login');
    }

    public function saveToken(Request $request)
    {
        $user = Auth::user();
        $user_update_data['device_token'] = $request->token;
        $user_update_data['id'] = $user->id;
        $users = new User();
        $user_dev_token = $users->UpdateLogin($user_update_data);
        // auth()->user()->update(['device_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function resendOtp(Request $request)
    {
        $userid = session('userid');
        Session::flash('message', 'Please verify your Account !');
        $this->OtpGeneration($userid);
        return view('restaurent.auth.verifyOtp');
    }

    public function setNewPassword(Request $request)
    {
        $userid = session('userid');
        return view('restaurent.auth.setNewPassword');
    }

    public function resendNewOtp(Request $request)
    {
        $userid = session('userid');
        Session::flash('message', 'Please verify your Account !');
        $this->OtpGeneration($userid);
        return redirect('Restaurent/setNewPassword');
    }

    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4',


        ]);
        if(!$validator->fails()){
            $otp=$request->input('otp');
            $data['otp']=$otp;
            $data['userid']=session('userid');
            //dd($data);
            $otp_verified_status=$this->OtpVerification($data);

            if($otp_verified_status==2){
                Session::flash('message', 'Invalid OTP');

                return view('restaurent.auth.verifyOtp');

            }
            elseif($otp_verified_status==1){
                $user = Auth::user();
                Session::put('restaurent', $user);
                Session::flash('message', 'Account verified successfully');
                return redirect('Restaurent/dashboard');
            }else{
                return redirect('Restaurent/login');
            }

        }
        else{
            //dd($validator);

        	return redirect()->back()->withErrors($validator);
        }

    }

    public function resetPassword(Request $request){
        $user = Auth::user();
        return view('restaurent.resetPassword')->with(['data' => $user]);
    }

    public function resetPasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6',
            'current_password' => 'required|string|min:6',
        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $data = $request->toarray();
            $data['userid'] = $user->mobile;
            if (Hash::check($data['current_password'], $user->password)) {
                $user = new User();
                $user->changePassword($data);
                Session::flash('message', 'Password Changed');


                return redirect()->back();
            } else {
                Session::flash('message', 'Invalid Current Password');
                return redirect()->back();
            }
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function forgetPassword(Request $request){
        return view('restaurent.auth.forgetPassword');
    }

    public function forgetPasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number'=>'required',

        ]);
        if(!$validator->fails()){
            $userid = $request->input('phone_number');
            Session::put('userid', $userid);
            $user_inst = new User();
            $user_data = $user_inst->userDataWithMobile($userid);
            if(!empty($user_data) &&  $user_data->user_type == 4){
                $otp_verified_status = $this->OtpGeneration($userid);

                if($otp_verified_status==2){
                    Session::flash('message', 'OTP Not Sent');
                    return redirect()->back();
                }
                elseif($otp_verified_status==1){
                    Session::flash('message', 'Please verify your Account !');
                    return redirect('Restaurent/setNewPassword');
                }else{
                    Session::flash('message', 'OTP Not Sent');
                    return redirect()->back();
                }
            }else{
                Session::flash('message', 'Invalid Contact Number');
                return redirect()->back();
            }

        }else{
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function verifyForgetPasswordOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4',
            'password' => 'required|string|min:6|confirmed',

        ]);
        if(!$validator->fails()){
            $data['otp']=request('otp');
            $data['password']=request('password');
            $data['userid']=session('userid');
            //dd($data);
            $otp_verified_status=$this->OtpVerification($data);
            if($otp_verified_status==2){
                Session::flash('message', 'Invalid OTP');
                return redirect()->back();
            }
            elseif($otp_verified_status==1){
                $user_inst = new User();
                $user_inst->changePassword($data);
                Session::flash('message', 'Password Changed Successfully');
                return redirect('Restaurent/login');
            }else{
                Session::flash('message', 'Something went wrong !');
                return redirect('Restaurent/login');
            }

        }
        else{
// dd("s");
        	return redirect()->back()->withErrors($validator);
        }

    }
}
