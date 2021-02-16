<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\GetBasicPageDataTraits;
use App\Http\Traits\LatLongRadiusScopeTrait;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\oauth_access_token;
use App\Model\restaurent_detail;
use Response;
use File;

class RestaurentManageController extends Controller
{
    use LatLongRadiusScopeTrait, GetBasicPageDataTraits;
    public function getRestaurentList(Request $request)
    {
        $user = Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);


        // dd($user_data);
        if(request('search_field') != NULL){
            $search_field = request('search_field');

        }else{
            unset($request['search_field']);
        }

        $lat = request('latitude') ?? '18.4490849';
        $lng = request('longitude') ?? '-77.2419522';
        if ($lat == 0 || $lng == 0) {
            $resto = [];

            $resto_data = [];

            $nonveg_resto_data = [];
            $veg_resto_data = [];
        } else {
            $lats = $lat;
            $lngs = $lng;
            // print($lats);
            // $kmRadius = $this->max_distance_km_resto;
            $resto = $this->closestRestaurant($user, $lats, $lngs);

            $restaurent_detail = new restaurent_detail();
            //all restaurants
            $resto_data_query = $this->closestRestaurant($user, $lats, $lngs);
            if ($request->has('search_field')) {
                $resto_data_query = $resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $resto_data = $resto_data_query->get();
            // dd($resto_data->toArray());
            //all nonveg restaurants
            $nonveg_resto_data_query = $this->closestRestaurant($user, $lats, $lngs)->whereIn('resto_type', [1,3]);
            if ($request->has('search_field')) {
                $nonveg_resto_data_query = $nonveg_resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $nonveg_resto_data = $nonveg_resto_data_query->get();

            //all veg restaurants
            $veg_resto_data_query = $this->closestRestaurant($user, $lats, $lngs)->whereIn('resto_type', [2,3]);
            if ($request->has('search_field')) {
                $veg_resto_data_query = $veg_resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $veg_resto_data = $veg_resto_data_query->get();
        }

        return response()->json([
            'user_data' => $user_data,
            'resto_data' => $resto_data,
            'nonveg' => $nonveg_resto_data,
            'veg' => $veg_resto_data
        ], $this->successStatus);

    }
}
