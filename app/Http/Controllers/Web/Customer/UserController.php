<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\GetBasicPageDataTraits;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\user_address;
use App\Model\contactUs;
use App\Model\order;
use App\Model\Cms;
use App\Model\cart_submenu;
use App\Model\cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use Response;
use Session;
use File;

class UserController extends Controller
{
    use GetBasicPageDataTraits;
    public function index(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);
        return view('customer.myAccount')->with(['user_data'=>$user_data]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:150',
                'email' => 'email|nullable',
                'picture' => 'mimes:png,jpg,jpeg|max:3072|nullable',

            ]);
            if(!$validator->fails()){
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();
            $data['id']= $id;
            $user_instance = new User;
            $user_data = $user_instance->userByIdData($data['id']);
            $user_update_data=array();
            $user_update_data['id']=$id;
            if($request->has('password'))
            {
                unset($data['password']);
            }
            if($request->has('email')){
                if($data['email'] == $user_data->email){
                    $ab=1;
                }else{
                    $user_update_data['email']=$data['email'];
                    $user_update_data['email_verified_at']=NULL;
                }
            }

            if($request->has('mobile')){
                if($data['mobile'] == $user_data->email){
                    $ab=1;
                }else{
                    $user_update_data['mobile']=$data['mobile'];
                    $user_update_data['mobile_verified_at']=NULL;
                }
            }
            if($request->has('name')){
                $user_update_data['name']=$data['name'];
            }
            if($request->hasfile('picture'))
            {
                $profile_pic = $request->file('picture');
                $input['imagename'] = 'ProfilePicture'.time().'.'.$profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/'.$id.'/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/'.$id.'/images'.'/';
                if($profile_pic->move($destinationPath, $input['imagename']))
                {
                    $file_url=url($destinationPath.$input['imagename']);
                    $user_update_data['picture']=$file_url;

                }else{
                    $error_file_not_required[]="Profile Picture Have Some Issue";
                    $user_update_data['picture']="";
                }

            }
            $user = auth()->user()->UpdateLogin($user_update_data);
            $user_data = auth()->user()->userByIdData($id);
            unset($user_data->password);
            // dd($user_data);
            Session::flash('modal_message', 'Profile Updated !');

            Session::flash('modal_check_subscribe', 'open');
            return redirect()->back();

        }
        else{
            return redirect()->back()->withInput()->withErrors($validator);
        }
        } catch (\Throwable $th) {
            report($th);

            return response()->json(['message'=> $th->getMessage(),'status'=>false], $this->invalidStatus);

        }


    }


    public function getChangePasswordPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        return view('customer.changePassword')->with(['user_data'=>$user_data]);
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6',
            'current_password' => 'required|string|min:6',
            'password_confirmation' => 'required|string',


        ]);
        if(!$validator->fails()){
            $user=Auth::user();
            $data = $request->toarray();
            $data['userid']=$user->mobile;
            if(Hash::check($data['current_password'], $user->password)){
                $user = new User();
                $user->changePassword($data);
                Session::flash('modal_message', 'Password Changed ');
                Session::flash('modal_check_subscribe', 'open');

                return redirect()->back();
            }else{
                Session::flash('message', 'Invalid Current Password');
                return redirect()->back();
            }
        }
        else{
            return redirect()->back()->withInput()->withErrors($validator);
        }

    }

    public function getContactUsPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        return view('customer.contactUs')->with(['user_data'=>$user_data]);
    }

    public function contactUs(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'email|nullable',
            'mobile' => 'required|numeric|digits:10',
            'message' => 'required|string',


        ]);
        if(!$validator->fails()){
            $user=Auth::user();
            $data = $request->toarray();
            $contactUs = new contactUs();
            $contactUs->makeContactUs($data);
            Session::flash('modal_message', 'Message Sent ');
            Session::flash('modal_check_subscribe', 'open');

            return redirect()->back();
        }
        else{
            return redirect()->back()->withInput()->withErrors($validator);
        }

    }

    public function getSaveAddressPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $user_address = new user_address();
        $user_add = $user_address->getUserAddress($user->id);

        return view('customer.savedAddress')->with(['user_data'=>$user_data,'user_address'=>$user_add]);
    }

    public function getMyOrderPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $orders = new order;
        $order_data = $orders->allUserOrderPastData($user->id);
        foreach($order_data as $order){
            $order->ordered_menu = json_decode($order->ordered_menu);
        }
        $current_order_data = $orders->allUserCurrentPastData($user->id);
        foreach($current_order_data as $c_order){

            $c_order->ordered_menu = json_decode($c_order->ordered_menu);
        }
        $user_data['currency']=$this->currency;
        // dd($current_order_data);
        return view('customer.myOrder')->with(['user_data'=>$user_data,
                                            'order_data'=>$order_data,
                                            'current_order_data'=>$current_order_data]);
    }

    public function getTermsConditionPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $cmsObj = new Cms;
        $tnc_data = $cmsObj->getCms(2)->get();

        return view('customer.termsCondition')->with(['user_data'=>$user_data,'tnc_data'=>$tnc_data]);
    }

    public function getFaqPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $cmsObj = new Cms;
        $faq_data = $cmsObj->getCms(3)->get();

        return view('customer.faq')->with(['user_data'=>$user_data,'faq_data'=>$faq_data]);
    }

    public function getLegalInformationPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $cmsObj = new Cms;
        $legal_data = $cmsObj->getCms(4)->get();
        return view('customer.legalInformation')->with(['user_data'=>$user_data,'legal_data'=>$legal_data]);
    }

    public function getAboutUsPage(Request $request){
        $user=Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $cmsObj = new Cms;
        $about_data = $cmsObj->getCms(1)->get();

        return view('customer.aboutUs')->with(['user_data'=>$user_data,'about_data'=>$about_data]);
    }
}
