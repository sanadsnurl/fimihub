<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\UpdateProfile;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\contactUs;
use App\Model\menu_list;
use App\Model\oauth_access_token;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\restaurent_detail;
use Response;
use File;

class UserController extends Controller
{
    public function getMyPastOrder(Request $request)
    {
        $user = Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);

        $orders = new order();
        $past_order_data = $orders->allUserOrderPastDataApp($user->id)
            ->with(['restaurentDetails'])->paginate(10);
        foreach ($past_order_data as $order) {
            unset($order->ordered_menu);
            // $order->ordered_menu = json_decode($order->ordered_menu);
        }
        $user_data['currency'] = $this->currency;
        // dd($current_order_data);
        return response()->json([
            'past_order_data' => $past_order_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }

    public function getMyCurrentOrder(Request $request)
    {
        $user = Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);

        $orders = new order();
        $current_order_data = $orders->allUserCurrentAppOrderData($user->id)
                ->with(['restaurentDetails'])->paginate(10);
        foreach ($current_order_data as $c_order) {
            unset($c_order->ordered_menu);
            // $c_order->ordered_menu = json_decode($c_order->ordered_menu);
        }
        $user_data['currency'] = $this->currency;
        // dd($current_order_data);
        return response()->json([
            'current_order_data' => $current_order_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);

    }

    public function updateProfile(UpdateProfile $request)
    {
        try {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();
            $data['id'] = $id;
            $user_instance = new User;
            $user_data = $user_instance->userByIdData($data['id']);
            $user_update_data = array();
            $user_update_data['id'] = $id;
            if ($request->has('password')) {
                unset($data['password']);
            }
            if ($request->has('email')) {
                if ($data['email'] == $user_data->email) {
                    $ab = 1;
                } else {
                    $user_update_data['email'] = $data['email'];
                    $user_update_data['email_verified_at'] = NULL;
                }
            }

            if ($request->has('mobile')) {
                if ($data['mobile'] == $user_data->email) {
                    $ab = 1;
                } else {
                    $user_update_data['mobile'] = $data['mobile'];
                    $user_update_data['mobile_verified_at'] = NULL;
                }
            }
            if ($request->has('name')) {
                $user_update_data['name'] = $data['name'];
            }
            if ($request->hasfile('picture')) {
                $profile_pic = $request->file('picture');
                $input['imagename'] = 'ProfilePicture' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = asset($destinationPath . $input['imagename']);
                    $user_update_data['picture'] = $file_url;
                } else {
                    $error_file_not_required[] = "Profile Picture Have Some Issue";
                    $user_update_data['picture'] = "";
                }
            }
            $user = auth()->user()->UpdateLogin($user_update_data);
            $user_data = auth()->user()->userByIdData($id);

            unset($user_data->password);
            return response()->json([
                'message' => 'Profile Updated',
                'status' => true
            ], $this->successStatus);

        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message' => $th->getMessage(), 'status' => false], $this->invalidStatus);
        }
    }

    protected function contactUs(Request $request)
    {
        $user = Auth::user();

        $data = $request->toarray();

        $contactUs = new contactUs();
        $contactUs->makeContactUs($data);
        return response()->json([
            'message' => 'Message Sent',
            'status' => true
        ], $this->successStatus);

    }

}
