<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
//custom import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;


class LoginRegisterController extends Controller
{

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            'user_id' => 'required',

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
                Session::put('admin_data', $user);
            }

            if($mobile_set != NULL)
            {
                $userid = $mobile_set;
                Session::put('userid', $userid);
                $user_data = auth()->user()->userData($userid);
                return redirect('adminfimihub/dashboard');
            }
            else
            {
                $userid = $email_set;
                Session::put('userid', $userid);
                $user_data = auth()->user()->userData($userid);
                return redirect('adminfimihub/dashboard');
            }
        }
        else{
        	return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush(['admin_data','userid']);
        return redirect('/adminfimihub/login');
    }


}
