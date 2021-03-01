<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\user\EditAddressRequest;
use App\Http\Requests\user\InsertAddressRequest;
use App\Model\user_address;
//custom import
use App\User;
use App\Model\oauth_access_token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\category;
use App\Model\store;
use App\Model\subcategory;
use Response;

class AddressController extends Controller
{
    public function getUserAddress(Request $request){
        $user = Auth::user();
        // dd($user);
        $user_address = new user_address();
        $user_add =  $user_address->getUserAddress($user->id ?? '');

        return response()->json(['user_address' => $user_add, 'status' => true], $this->successStatus);

    }

    public function insertAddress(InsertAddressRequest $request){

            $user = Auth::user();
            $data = $request->toarray();
            $add_data =array();
            if($data['latitude'] == 0 || $data['longitude'] == 0){
                return response()->json(['custom_error' => 'Invalid Address', 'status' => false], $this->failureStatus);

            }
            else{
                $add_data['user_id']=$user->id;
                $add_data['address']=$data['address'];
                $add_data['name']=$data['name'];
                $add_data['flat_no']=$data['flat_no'];
                $add_data['landmark']=$data['landmark'];
                $add_data['latitude']=$data['latitude'];
                $add_data['longitude']=$data['longitude'];
                $user_address = new user_address;
                $subscribe = $user_address->makeAddress($add_data);

                return response()->json(['message' => 'Address Added !', 'status' => true], $this->successStatus);

            }
    }

    public function makeDeliveryAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|numeric|exists:user_address,id',
        ]);
        if (!$validator->fails()) {
        $user = Auth::user();
        $add_id = (request('address_id'));

        $user_address = new user_address;
        $default_add = array();
        $default_add['user_id'] = $user->id;
        $default_add['id'] = $add_id;

        $change_default_setting = $user_address->changeDefault($default_add);

        return response()->json(['user_address' => "Success", 'status' => true], $this->successStatus);
        } else {
            $first_keys = $validator->messages();
            $first_key = reset($first_keys);
            $first_value = reset($first_key)[0];
            return response()->json(['message' => $first_value, 'status' => false], $this->failureStatus);
        }
    }

    public function deleteAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|numeric|exists:user_address,id',
        ]);
        if (!$validator->fails()) {
        $user = Auth::user();
        $add_id = (request('address_id'));

        $user_address = new user_address;
        $delete_add = array();
        $delete_add['user_id'] = $user->id;
        $delete_add['id'] = $add_id;

        $delete_address = $user_address->deleteAddress($delete_add);

        return response()->json(['user_address' => "Address Deleted Successfully !", 'status' => true], $this->successStatus);
        } else {
            $first_keys = $validator->messages();
            $first_key = reset($first_keys);
            $first_value = reset($first_key)[0];
            return response()->json(['message' => $first_value, 'status' => false], $this->failureStatus);
        }
    }

    public function editAddress(EditAddressRequest $request){

        $user = Auth::user();
        $data = $request->toarray();
        $add_data =array();
        if($data['latitude'] == 0 || $data['longitude'] == 0){
            return response()->json(['custom_error' => 'Invalid Address', 'status' => false], $this->failureStatus);

        }
        else{
            $add_data['user_id']=$user->id;
            $add_data['id']=$data['address_id'];
            $add_data['address']=$data['address'];
            $add_data['name']=$data['name'];
            $add_data['flat_no']=$data['flat_no'];
            $add_data['landmark']=$data['landmark'];
            $add_data['latitude']=$data['latitude'];
            $add_data['longitude']=$data['longitude'];
            $user_address = new user_address;
            $subscribe = $user_address->editAddress($add_data);

            return response()->json(['message' => 'Address Updated !', 'status' => true], $this->successStatus);

        }
}
}
