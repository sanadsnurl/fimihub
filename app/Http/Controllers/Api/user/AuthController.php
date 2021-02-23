<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\user\UserLoginRequest;
use App\Http\Requests\user\UserRegisterRequest;
use App\Http\Requests\user\UserForgetPasswordRequest;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\oauth_access_token;
use Response;
use File;

class AuthController extends Controller
{
    public $byPassOtp = 5555;

    use OtpGenerationTrait;
    public function userRegister(UserRegisterRequest $request)
    {

        try {
            $data = $request->toArray();

            $user_insert_data = array();
            $user_insert_data['mobile'] = $data['mobile'] ?? '';
            $user_insert_data['name'] = $data['name'] ?? '';
            $user_insert_data['password'] = $data['password'] ?? '';
            $user_insert_data['country_code'] = $data['country_code'] ?? '+91';
            $user_insert_data['device_token'] = $data['device_token'] ?? '';
            $user_insert_data['user_type'] = 3;
            $user_insert_data['visibility'] = 0;

            $user = User::create($user_insert_data);

            $user_ins = new user();
            $user_data = $user_ins->userByIdData($user->id);
            unset($user_data->password);
            $id = $user_data->id;


            if ($user_data->visibility != 1) {
                if ($user_data->mobile_verified_at != NULL) {
                    $response = [
                        'data' => $user_data,
                        'status' => true,
                        'message' => 'Registered', 'verified' => true
                    ];
                    return response()->json($response, $this->successStatus);
                } else {
                    $otp = $this->OtpGeneration($user_data->mobile);
                    $user_data = $user_ins->userByIdData($user->id);
                    // $user_data->verification_code=$otp;
                    $response = [
                        'data' => $user_data,
                        'status' => true,
                        'message' => 'Please Verify Your Mobile Number', 'verified' => false
                    ];
                    return response()->json($response, $this->successStatus);
                }
            } else {
                $response = ['status' => false, 'message' => 'Please Contact Admin (Temporary Blocked)'];
                return response()->json($response, $this->failureStatus);
            }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {

            $user_id = $request->input('user_id');
            $password = $request->input('password');
            $country_code = $request->input('country_code') ?? '+91';
            $device_token = $request->input('device_token');
            $mobile_set = "";
            $email_set = "";


            if (is_numeric($user_id)) {
                $loginData = ["mobile" => $user_id, "country_code" => $country_code, "password" => $password];
                $mobile_set = $user_id;
            } else {
                $loginData = ["email" => $user_id, "password" => $password];
                $email_set = $user_id;
            }


            if (!auth()->attempt($loginData)) {
                return response()->json(['message' => 'Invalid Credentials', 'status' => false], $this->failureStatus);
            }

            //CHECK VERIFICATION DONE OR NOT
            $accessToken = auth()->user()->createToken('teckzy')->accessToken;
            //Mobile Login
            if ($mobile_set != NULL) {
                $userid = $mobile_set;
                $user_data = auth()->user()->userData($userid);

                $users = new User;
                $user_data = $users->userIdData($user_data->id)
                ->with('userAddress')->first();

                $user_update_data = array();
                $user_update_data['id'] = $user_data->id;

                if ($request->has('device_token')) {
                    $user_update_data['device_token'] = $device_token;
                    $user_dev_token = auth()->user()->UpdateLogin($user_update_data);
                }
                unset($user_data->password);

                if ($user_data->visibility == 1) {
                    $status = false;
                    $message = "Account needs Approval";
                } elseif ($user_data->visibility == 2) {
                    $status = false;
                    $message = "Account Blocked Or Disabled";
                } elseif ($user_data->visibility == 3) {
                    $status = false;
                    $message = "Account Disabled";
                } else {
                    $status = true;
                    $message = "success";
                }
                if ($user_data->user_type == 3) {
                    if ($user_data->mobile_verified_at == NULL) {
                        $otp = $this->OtpGeneration($user_data->mobile);
                        $user_data->access_token = $accessToken;
                        return response()->json([
                            'verified' => false,
                            'data' => $user_data,
                            'message' => $message,
                            'status' => $status
                        ], $this->successStatus);
                    } else {
                        $user_data->access_token = $accessToken;
                        return response()->json([
                            'verified' => true,
                            'data' => $user_data,
                            'message' => $message,
                            'status' => $status
                        ], $this->successStatus);
                    }
                } else {
                    return response()->json(['message' => 'Invalid Account Type', 'status' => false], $this->failureStatus);
                }
            } //Email Login
            elseif ($email_set != NULL) {
                $userid = $email_set;
                $user_data = auth()->user()->userData($userid);

                $users = new User;
                $user_data = $users->userIdData($user_data->id)
                ->with('userAddress')->first();

                $user_update_data = array();
                $user_update_data['id'] = $user_data->id;

                if ($request->has('device_token')) {
                    $user_update_data['device_token'] = $device_token;
                    $user_dev_token = auth()->user()->UpdateLogin($user_update_data);
                }
                unset($user_data->password);

                if ($user_data->visibility == 1) {
                    $status = false;
                    $message = "Account needs Approval";
                } elseif ($user_data->visibility == 2) {
                    $status = false;
                    $message = "Account Blocked Or Disabled";
                } elseif ($user_data->visibility == 3) {
                    $status = false;
                    $message = "Account Disabled";
                } else {
                    $status = true;
                    $message = "success";
                }
                if ($user_data->user_type == 3) {
                    if ($user_data->email_verified_at == NULL) {
                        $otp = $this->OtpGeneration($user_data->email);
                        $user_data->access_token = $accessToken;
                        return response()->json([
                            'verified' => false,
                            'data' => $user_data,
                            'message' => $message,
                            'status' => $status
                        ], $this->successStatus);
                    } else {
                        $user_data->access_token = $accessToken;
                        return response()->json([
                            'verified' => true,
                            'data' => $user_data,
                            'message' => $message,
                            'status' => $status
                        ], $this->successStatus);
                    }
                } else {
                    return response()->json(['message' => 'Invalid Account Type', 'status' => false], $this->failureStatus);
                }
            } else {
                return response()->json(['message' => 'Invalid User-Id', 'status' => false], $this->failureStatus);
            }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

    public function userDetails()
    {
        $user = Auth::user();
        $users = new User;
        $user_data = $users->userIdData($user->id)
            ->with('userAddress')->first();

        return response()->json([
            'data' => $user_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }

    public function forgetPassword(UserForgetPasswordRequest $request)
    {
        try {
            $remember_password_token = $request->input('remember_password_token');
            //  dd($remember_password_token);
            $password = $request->input('password');
            $user_token = oauth_access_token::where('id', $remember_password_token)
            ->first();
            if(!isset($user_token)){
                return response()->json(['message' => 'Invalid Remember Password Token', 'status' => false], $this->failureStatus);
            }
            $user = new User();
            $user_data = $user->userByIdData($user_token->user_id);

            if ($user_data != NULL) {
                $data = ['userid' => $user_data->mobile ,'country_code' => $country_code ?? '+91', 'password' => $password];

                $user = new User();
                $user->changePassword($data);
                $user_data = User::find($user_data->id);
                $user_data->verification_code = NULL;
                $user_data->save();
                $user_token = oauth_access_token::where('id', $remember_password_token)->update(['revoked'=> 1]);

                return response()->json(['message' => 'Password Changed', 'status' => true], $this->successStatus);
            } else {
                return response()->json(['message' => 'Invalid User-Id', 'status' => false], $this->failureStatus);
            }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

}
