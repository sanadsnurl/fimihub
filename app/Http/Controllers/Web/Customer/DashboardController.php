<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\GetBasicPageDataTraits;
use App\Http\Traits\LatLongRadiusScopeTrait;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\subscribe;
use App\Model\restaurent_detail;
use App\Model\slider_cms;
use App\Model\cart;
use App\Model\cart_submenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use Illuminate\Support\Facades\Cookie;
use Response;
use Session;
use Location;
use phpDocumentor\Reflection\Types\Null_;
use stdClass;

class DashboardController extends Controller
{
    use LatLongRadiusScopeTrait, GetBasicPageDataTraits;
    public function index(Request $request)
    {

// dd($_COOKIE["lat"]."--".$_COOKIE["long"]);
        $user = Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);


        // dd($user_data);
        if ($request->has('address_latitude')) {
            if(request('search_field') != NULL){
                $search_field = request('search_field');

            }else{
                unset($request['search_field']);
            }
        }

        $lat_lng_array = array();
        if ($request->has('address_latitude')) {
            $lat_lng_array['address_latitude'] = request('address_latitude');
            if ($lat_lng_array['address_latitude'] == 0) {
                $lat_lng_array['address_latitude'] = NULL;
            }
        }

        if ($request->has('address_longitude')) {
            $lat_lng_array['address_longitude'] = request('address_longitude');
            if ($lat_lng_array['address_longitude'] == 0) {
                $lat_lng_array['address_longitude'] = NULL;
            }
        }

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'; //Dynamic IP address get

        if ($ip == '127.0.0.1') {

            $lat = $_COOKIE["lat"] ?? '18.4490849';
            $lng = $_COOKIE["long"] ?? '-77.2419522';
        } else {
            $loc_data = Location::get($ip);

            $lat = $loc_data->latitude ??  '18.4490849';
            $lng = $loc_data->longitude ?? '-77.2419522';
        }
        $lat = $_COOKIE["lat"] ?? '18.4490849';
        $lng = $_COOKIE["long"] ?? '-77.2419522';
        if ($lat == 0 || $lng == 0) {
            $resto = [];

            $resto_data = [];

            $nonveg_resto_data = [];
            $veg_resto_data = [];
        } else {
            $lats = $lat_lng_array['address_latitude'] ??  $lat;
            $lngs = $lat_lng_array['address_longitude'] ?? $lng;
            // dd($lats."--".$lngs);
            // $kmRadius = $this->max_distance_km_resto;
            $resto = $this->closestRestaurant($user, $lats, $lngs);

            $restaurent_detail = new restaurent_detail;
            //all restaurants
            $resto_data_query = $this->closestRestaurant($user, $lats, $lngs);
            if ($request->has('search_field')) {
                $resto_data_query = $resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $resto_data = $resto_data_query->get();
            foreach ($resto_data as $value) {
                $value->frick= new stdClass ;
                $value->dakota = new stdClass ;
                $value->dakota->lat =  $lat;
                $value->dakota->lng =  $lng;
                $value->frick->lat =  $value->latitude;
                $value->frick->lng =  $value->longitude;
                $value->dis = $this->getDistanceBetweenPointsNew($lat,$lng, $value->latitude,$value->longitude);
            }
            // dd($resto_data->toArray());
            //all nonveg restaurants
            $nonveg_resto_data_query = $this->closestRestaurant($user, $lats, $lngs)->whereIn('resto_type', [1,3]);
            if ($request->has('search_field')) {
                $nonveg_resto_data_query = $nonveg_resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $nonveg_resto_data = $nonveg_resto_data_query->get();
            foreach ($nonveg_resto_data as $value) {
                $value->frick= new stdClass ;
                $value->dakota = new stdClass ;
                $value->dakota->lat =  $lat;
                $value->dakota->lng =  $lng;
                $value->frick->lat =  $value->latitude;
                $value->frick->lng =  $value->longitude;
                $value->dis = $this->getDistanceBetweenPointsNew($lat,$lng, $value->latitude,$value->longitude);
            }
            //all veg restaurants
            $veg_resto_data_query = $this->closestRestaurant($user, $lats, $lngs)->whereIn('resto_type', [2,3]);
            if ($request->has('search_field')) {
                $veg_resto_data_query = $veg_resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $veg_resto_data = $veg_resto_data_query->get();
            foreach ($veg_resto_data as $value) {
                $value->frick= new stdClass ;
                $value->dakota = new stdClass ;
                $value->dakota->lat =  $lat;
                $value->dakota->lng =  $lng;
                $value->frick->lat =  $value->latitude;
                $value->frick->lng =  $value->longitude;
                $value->dis = $this->getDistanceBetweenPointsNew($lat,$lng, $value->latitude,$value->longitude);
            }
        }

        $slider_cms = new slider_cms;
        $slider_array = ['slider_type' => 1, 'user_id' => NULL];
        $slider_data = $slider_cms->getSlider($slider_array);
        $sl_data = array();
        foreach ($slider_data as $s_data) {
            // if (file_exists($s_data->media)) {
                $s_data->media = asset($s_data->media);
                $sl_data[] =  $s_data;
            // }
        }
        // dd($resto_data);
        return view('customer.home')->with([
            'user_data' => $user_data,
            'resto_data' => $resto_data,
            'nonveg' => $nonveg_resto_data,
            'slider_data' => $sl_data,
            'veg' => $veg_resto_data
        ]);
    }

    public function subscribe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',


        ]);
        if (!$validator->fails()) {

            $data = $request->toarray();
            $subscribe = new subscribe;
            $subscribe = $subscribe->makeSubscription($data);
            Session::flash('modal_check_subscribe', 'open');
            Session::flash('modal_message', 'Successfully Subscribed !');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function partnerRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'password' => 'required|confirmed|string|min:6',
            'mobile' => 'required|numeric|unique:users|digits:10',
            'email' => 'email|unique:users|nullable',
        ]);
        if (!$validator->fails()) {
            $data = $request->toArray();
            $data['user_type'] = 4;
            $data['visibility'] = 1;
            $user = User::create($data);
            if ($user != NULL) {

                Session::flash('message', 'Request Sent Succesfully !');
                return redirect()->back();
            } else {
                Session::flash('message', 'Request Not Sent , Please try again!');
                return redirect()->back();
            }
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
}
