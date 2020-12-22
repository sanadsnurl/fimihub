<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UpdateLoginRequest;
use App\Http\Requests\UserForgetPasswordRequest;
use App\Http\Requests\UpdateDeviceTokenRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateLoginStatusRequest;
use App\Http\Traits\OtpGenerationTrait;
use Response;
use App\Model\rider_bank_detail;
use App\Model\vehicle_detail;
use File;
use App\Model\user_address;

class LoginRegisterController extends Controller
{
    use OtpGenerationTrait;
    public function register(UserStoreRequest $request)
    {

        try {
            $data = $request->toArray();

            $user_insert_data = array();
            $user_insert_data['mobile'] = $data['mobile'];
            $user_insert_data['name'] = $data['name'];
            $user_insert_data['password'] = $data['password'];
            $user_insert_data['country_code'] = $data['country_code'];
            $user_insert_data['email'] = $data['email'];
            $user_insert_data['user_type'] = 2;

            $user = User::create($user_insert_data);

            $user_ins = new user();
            $user_data = $user_ins->userByIdData($user->id);
            unset($user_data->password);
            $id = $user_data->id;

            $vehicle_data = array();
            $vehicle_data['user_id'] = $user_data->id;
            $vehicle_data['vehicle_number'] = $data['vehicle_number'];
            $vehicle_data['model_name'] = $data['model_name'];

            if ($request->hasfile('vehicle_image')) {
                $profile_pic = $request->file('vehicle_image');
                $input['imagename'] = 'VehiclePicture' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $vehicle_data['vehicle_image'] = $file_url;
                } else {
                    $error_file_not_required[] = "Vehicle Picture Have Some Issue";
                    $vehicle_data['vehicle_image'] = "";
                }
            }
            $vehicle_data['color'] = $data['color'];

            if ($request->hasfile('id_proof')) {
                $profile_pic = $request->file('id_proof');
                $input['imagename'] = 'IDProof' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $vehicle_data['id_proof'] = $file_url;
                } else {
                    $error_file_not_required[] = "ID Proof Have Some Issue";
                    $vehicle_data['id_proof'] = "";
                }
            }
            $vehicle_data['address'] = $data['address'];
            $vehicle_data['pincode'] = $data['pincode'];

            if ($request->hasfile('driving_license')) {
                $profile_pic = $request->file('driving_license');
                $input['imagename'] = 'DL' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $vehicle_data['driving_license'] = $file_url;
                } else {
                    $error_file_not_required[] = "ID Proof Have Some Issue";
                    $vehicle_data['driving_license'] = "";
                }
            }
            $vehicle_data['dl_start_date'] = $data['dl_start_date'];
            $vehicle_data['dl_end_date'] = $data['dl_end_date'];
            $vehicle_data['registraion_start_date'] = $data['registraion_start_date'];
            $vehicle_data['registraion_end_date'] = $data['registraion_end_date'];
            $vehicle_detail = new vehicle_detail;
            $vehicle_datas = $vehicle_detail->insertUpdateVehicleData($vehicle_data);
            $vehicle_datas = $vehicle_detail->getVehicleData($user->id);

            $bank_details = array();
            $bank_details['user_id'] = $user_data->id;
            $bank_details['account_number'] = $data['account_number'];
            $bank_details['holder_name'] = $data['holder_name'];
            $bank_details['branch_name'] = $data['branch_name'];
            $bank_details['ifsc_code'] = $data['ifsc_code'];
            $rider_bank_detail = new rider_bank_detail;
            $bank_data = $rider_bank_detail->insertUpdateBankData($bank_details);
            $bank_data = $rider_bank_detail->getBankData($user->id);

            $address_data =array();
            $address_data['user_id'] = $user_data->id;
            $address_data['address']=$data['address'];
            $address_data['latitude']=$data['lat'];
            $address_data['longitude']=$data['lng'];
            $user_address = new user_address;
            $subscribe = $user_address->insertUpdateAddress($address_data);
            $address_data = $user_address->getUserAddress($user->id);

            if ($user_data->visibility != 2) {
                if ($user_data->mobile_verified_at != NULL) {
                    $response = [
                        'data' => $user_data,
                        'bank_data' => $bank_data,
                        'vehicle_data' => $vehicle_datas,
                        'address_data' => $address_data,
                        'status' => true,
                        'message' => 'Registered', 'verified' => true
                    ];
                    return response()->json($response, $this->successStatusCreated);
                } else {
                    $otp = $this->OtpGeneration($user_data->mobile);
                    $user_data = $user_ins->userByIdData($user->id);
                    // $user_data->verification_code=$otp;
                    $response = [
                        'data' => $user_data,
                        'bank_data' => $bank_data,
                        'vehicle_data' => $vehicle_datas,
                        'address_data' => $address_data,
                        'status' => true,
                        'message' => 'Not Verified', 'verified' => false
                    ];
                    return response()->json($response, $this->successStatusCreated);
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

            $user_id = $request->input('userid');
            $password = $request->input('password');
            $mobile_set = "";
            $email_set = "";


            if (is_numeric($user_id)) {
                $loginData = ["mobile" => $user_id, "password" => $password];
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
            if ($mobile_set != NULL) {
                $userid = $mobile_set;
                $user_data = auth()->user()->userData($userid);
                unset($user_data->password);

                $rider_bank_detail = new rider_bank_detail;
                $bank_data = $rider_bank_detail->getBankData($user_data->id);

                $vehicle_detail = new vehicle_detail;
                $vehicle_datas = $vehicle_detail->getVehicleData($user_data->id);

                $user_address = new user_address;
                $address_data = $user_address->getUserAddress($user_data->id);

                if ($user_data->mobile_verified_at == NULL) {
                    $otp = $this->OtpGeneration($user_data->mobile);
                    $user_data->access_token = $accessToken;
                    return response()->json([
                        'verified' => false,
                        'data' => $user_data,
                        'bank_data' => $bank_data,
                        'vehicle_data' => $vehicle_datas,
                        'address_data' => $address_data,
                        'message' => 'success',
                        'status' => true
                    ], $this->successStatus);
                } else {
                    $user_data->access_token = $accessToken;
                    return response()->json([
                        'verified' => true,
                        'data' => $user_data,
                        'bank_data' => $bank_data,
                        'vehicle_data' => $vehicle_datas,
                        'address_data' => $address_data,
                        'message' => 'success',
                        'status' => true
                    ], $this->successStatus);
                }
            } else {
                $userid = $email_set;
                $user_data = auth()->user()->userData($userid);
                unset($user_data->password);

                $rider_bank_detail = new rider_bank_detail;
                $bank_data = $rider_bank_detail->getBankData($user_data->id);

                $vehicle_detail = new vehicle_detail;
                $vehicle_datas = $vehicle_detail->getVehicleData($user_data->id);

                $user_address = new user_address;
                $address_data = $user_address->getUserAddress($user_data->id);
                if ($user_data->email_verified_at == NULL) {
                    $user_data->access_token = $accessToken;
                    return response()->json([
                        'verified' => false,
                        'data' => $user_data,
                        'bank_data' => $bank_data,
                        'vehicle_data' => $vehicle_datas,
                        'address_data' => $address_data,
                        'message' => 'success',
                        'status' => true
                    ], $this->successStatus);
                } else {
                    $user_data->access_token = $accessToken;
                    return response()->json([
                        'verified' => true,
                        'data' => $user_data,
                        'bank_data' => $bank_data,
                        'vehicle_data' => $vehicle_datas,
                        'address_data' => $address_data,
                        'message' => 'success',
                        'status' => true
                    ], $this->successStatus);
                }
            }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }


    public function details()
    {
        $user = Auth::user();
        unset($user->password);

        $rider_bank_detail = new rider_bank_detail;
        $bank_data = $rider_bank_detail->getBankData($user->id);

        $vehicle_detail = new vehicle_detail;
        $vehicle_datas = $vehicle_detail->getVehicleData($user->id);

        $user_address = new user_address;
        $address_data = $user_address->getUserAddress($user->id);
        return response()->json([
            'data' => $user,
            'bank_data' => $bank_data,
            'vehicle_data' => $vehicle_datas,
            'address_data' => $address_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $Users = new User();
        $user_update_data = ['device_token' => NULL , 'id' => $user->id];
        $User_device_token = $Users->UpdateLogin($user_update_data);
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfull Logout', 'status' => true], 200);
    }

    public function forgetPassword(UserForgetPasswordRequest $request)
    {
        try {
            $userid = $request->input('userid');
            $verification_code = $request->input('verification_code');
            $password = $request->input('password');
            $user = new User();
            $user_data = $user->userData($userid);

            $data = ['userid' => $userid, 'password' => $password];
            if ($user_data != NULL) {
                if ($user_data->verification_code == $verification_code) {
                    $user = new User();
                    $user->changePassword($data);
                    $user_data = User::find($user_data->id);
                    $user_data->verification_code = NULL;
                    $user_data->save();
                    return response()->json(['message' => 'Password Changed', 'status' => true], $this->successStatusCreated);
                } else {
                    return response()->json(['message' => 'Invalid OTP', 'status' => false], $this->failureStatus);
                }
            } else {
                return response()->json(['message' => 'Invalid User-Id', 'status' => false], $this->failureStatus);
            }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }


    public function updateLogin(UpdateLoginRequest $request)
    {
        try {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();
            $data['id'] = $id;

            $user_update_data = array();
            $user_update_data['id'] = $id;
            if ($request->has('password')) {
                unset($data['password']);
            }
            if ($request->has('email')) {
                if ($data['email'] != $user->email) {
                    $user_update_data['email'] = $data['email'];
                    $user_update_data['email_verified_at'] = NULL;
                }
            }

            if ($request->has('mobile')) {
                if ($data['mobile'] != $user->email) {
                    if ($request->has('country_code')) {
                        $user_update_data['country_code'] = $data['country_code'];
                    }
                    $user_update_data['mobile'] = $data['mobile'];
                    $user_update_data['mobile_verified_at'] = NULL;
                }
            }
            if ($request->has('name')) {
                $user_update_data['name'] = $data['name'];
            }

            $user_inst = new user;
            $user_up = $user_inst->UpdateLogin($user_update_data);
            $user_data = auth()->user()->userByIdData($user->id);
            unset($user_data->password);

            $vehicle_update_data = array();
            $vehicle_update_data['user_id'] = $id;
            if ($request->has('vehicle_number')) {
                $vehicle_update_data['vehicle_number'] = $data['vehicle_number'];
            }
            if ($request->has('model_name')) {
                $vehicle_update_data['model_name'] = $data['model_name'];
            }

            if ($request->hasfile('vehicle_image')) {
                $profile_pic = $request->file('vehicle_image');
                $input['imagename'] = 'VehiclePicture' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $vehicle_update_data['vehicle_image'] = $file_url;
                } else {
                    $error_file_not_required[] = "Vehicle Picture Have Some Issue";
                    $vehicle_update_data['vehicle_image'] = "";
                }
            }
            if ($request->has('color')) {
                $vehicle_update_data['color'] = $data['color'];
            }

            if ($request->hasfile('id_proof')) {
                $profile_pic = $request->file('id_proof');
                $input['imagename'] = 'IDProof' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/documents');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/documents' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $vehicle_update_data['id_proof'] = $file_url;
                } else {
                    $error_file_not_required[] = "ID Proof Have Some Issue";
                    $vehicle_update_data['id_proof'] = "";
                }
            }
            if ($request->has('address')) {
                $vehicle_update_data['address'] = $data['address'];
            }
            if ($request->has('pincode')) {
                $vehicle_update_data['pincode'] = $data['pincode'];
            }

            if ($request->hasfile('driving_license')) {
                $profile_pic = $request->file('driving_license');
                $input['imagename'] = 'DL' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $vehicle_update_data['driving_license'] = $file_url;
                } else {
                    $error_file_not_required[] = "DL Have Some Issue";
                    $vehicle_update_data['driving_license'] = "";
                }
            }
            if ($request->has('dl_start_date')) {
                $vehicle_update_data['dl_start_date'] = $data['dl_start_date'];
            }
            if ($request->has('dl_end_date')) {
                $vehicle_update_data['dl_end_date'] = $data['dl_end_date'];
            }
            if ($request->has('registraion_start_date')) {
                $vehicle_update_data['registraion_start_date'] = $data['registraion_start_date'];
            }
            if ($request->has('registraion_end_date')) {
                $vehicle_update_data['registraion_end_date'] = $data['registraion_end_date'];
            }

            $vehicle_detail = new vehicle_detail;
            $vehicle_datas = $vehicle_detail->insertUpdateVehicleData($vehicle_update_data);

            $vehicle_datas = $vehicle_detail->getVehicleData($user->id);

            $bank_details = array();
            $bank_details['user_id'] = $user->id;
            if ($request->has('account_number')) {
                $bank_details['account_number'] = $data['account_number'];
            }
            if ($request->has('holder_name')) {
                $bank_details['holder_name'] = $data['holder_name'];
            }
            if ($request->has('branch_name')) {
                $bank_details['branch_name'] = $data['branch_name'];
            }
            if ($request->has('ifsc_code')) {
                $bank_details['ifsc_code'] = $data['ifsc_code'];
            }
            $rider_bank_detail = new rider_bank_detail;
            $bank_data = $rider_bank_detail->insertUpdateBankData($bank_details);
            $bank_data = $rider_bank_detail->getBankData($user->id);

            $address_data =array();
            $address_data['user_id'] = $user_data->id;
            $address_data['address']=$data['address'];
            $address_data['latitude']=$data['lat'];
            $address_data['longitude']=$data['lng'];
            $user_address = new user_address;
            $subscribe = $user_address->insertUpdateAddress($address_data);
            $address_data = $user_address->getUserAddress($user->id);
            return response()->json([
                'data' => $user_data,
                'bank_data' => $bank_data,
                'vehicle_data' => $vehicle_datas,
                'address_data' => $address_data,
                'message' => 'Profile Updated !',
                'status' => true
            ], $this->successStatusCreated);
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

    public function updateDeviceToken(UpdateDeviceTokenRequest $request)
    {
        try {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();
            $data['id'] = $id;
            $email = $mobile = "";
            $user_update_data = array();
            $user_update_data['id'] = $id;

            if ($request->has('device_token')) {
                $user_update_data['device_token'] = $data['device_token'];
            }
            $user = auth()->user()->UpdateLogin($user_update_data);
            $user_data = auth()->user()->userByIdData($id);
            unset($user_data->password);



            return response()->json([
                'data' => $user_data,
                'message' => 'Token Updated !',
                'status' => true
            ], $this->successStatusCreated);
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

    public function changePassword(UpdatePasswordRequest $request)
    {

        try {
            $user = Auth::user();

            $user_id = $user->mobile;
            $password = $request->input('password');
            $new_password = $request->input('new_password');
            $id = $user->id;
            if (!Hash::check($password, $user->password)) {
                return response()->json(['custom_error' => 'Invalid Old Password', 'status' => false], $this->failureStatus);
            } else {
                $data = ['userid' => $user->mobile, 'password' => $new_password];
                $user = auth()->user()->changePassword($data);
                return response()->json(['message' => 'Password Changed !', 'status' => true], $this->successStatusCreated);
            }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['custom_error' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'required|mimes:png,jpg,jpeg|max:3072',
        ]);

        $user = Auth::user();
        $id = $user->id;
        if (!$validator->fails()) {
            if ($request->hasfile('picture')) {
                $profile_pic = $request->file('picture');
                $input['imagename'] = 'DL' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $user->picture = $file_url;
                } else {
                    // $user->picture = url('asset/customer/assets/icons/user.png');
                    $user->picture = null;
                    $user->save();
                    // return response()->json(['message'=>'Update default picture','status'=>true], $this->successStatusCreated);
                }
                $user->save();
                // return response()->json(['message'=>'Profile picture updated.','status'=>true], $this->successStatusCreated);
            } else {
                // $user->picture = url('asset/customer/assets/icons/user.png');
                $user->picture = null;
                $user->save();
            }
            return response()->json(['message' => 'Updated successfully', 'status' => true], $this->successStatusCreated);
        } else {
            $message = collect($validator->messages())->values()->first();
            return response()->json(['message' =>  $message[0], 'status' => false], $this->successStatus);
        }
    }

    public function updateOnlineStatus(UpdateLoginStatusRequest $request){
        $user = Auth::user();
        $id = $user->id;
        $data = $request->toarray();
        $status_array = ['status' => $data['status'], 'id' => $id];

        $user_data = auth()->user()->updateStatus($status_array);

        return response()->json(['message' => 'Status Updated !', 'status' => true], $this->successStatus);

    }
}
