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
use Response;
use Session;
use Location;
use phpDocumentor\Reflection\Types\Null_;

class DashboardController extends Controller
{
    use LatLongRadiusScopeTrait ,GetBasicPageDataTraits;
    public function index(Request $request)
    {
        $user = Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);

        $user_data = $this->getBasicCount($user);
        // dd($user_data);

        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '127.0.0.1'; //Dynamic IP address get

        if ($ip == '127.0.0.1') {

            $lat = '28.6581196';
            $lng = '77.14289509999999';
        } else {
            $loc_data = Location::get($ip);

            $lat = $loc_data->latitude ??  '0';
            $lng = $loc_data->longitude ?? '0';
        }

        if ($lat == 0 || $lng == 0) {
            $resto = [];

            $resto_data = [];

            $nonveg_resto_data = [];
            $veg_resto_data = [];
        } else {
            $kmRadius = $this->max_distance_km;
            $resto = $this->closestRestaurant($user, $lat, $lng, $kmRadius);

            $restaurent_detail = new restaurent_detail;
            $resto_data = $this->closestRestaurant($user, $lat, $lng, $kmRadius)->get();

            $nonveg_resto_data = $this->closestRestaurant($user, $lat, $lng, $kmRadius)->where('resto_type', 1)->get();
            $veg_resto_data = $this->closestRestaurant($user, $lat, $lng, $kmRadius)->where('resto_type', 2)->get();
        }

        $slider_cms = new slider_cms;
        $slider_array = ['slider_type' => 1, 'user_id' => NULL];
        $slider_data = $slider_cms->getSlider($slider_array);
        $sl_data = array();
        foreach ($slider_data as $s_data) {
            if (file_exists($s_data->media)) {
                $s_data->media = url($s_data->media);
                $sl_data[] =  $s_data;
            }
        }

        return view('customer.home')->with([
            'user_data' => $user_data,
            'resto_data' => $resto_data,
            'nonveg' => $nonveg_resto_data,
            'slider_data' => $sl_data,
            'veg' => $veg_resto_data
        ]);
    }
    function fetch(Request $request)
    {
     if($request->get('query'))
     {
      $query = $request->get('query');
      $data = DB::table('restaurent_details')
        ->where('name', 'LIKE', "%{$query}%")
        ->get();
      $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
      foreach($data as $row)
      {
       $output .= '
       <li><a href="#">'.$row->country_name.'</a></li>
       ';
      }
      $output .= '</ul>';
      echo $output;
     }
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
