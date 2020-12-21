<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\MediaUploadTrait;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\subscribe;
use App\Model\restaurent_detail;
use App\Model\slider_cms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;

class CmsController extends Controller
{
    use MediaUploadTrait;
    public function indexHandShake(Request $request)
    {

        $slider_cms = new slider_cms;
        $slider_array = ['slider_type'=> 1, 'user_id'=>NULL];
        $slider_data = $slider_cms->getSlider($slider_array);
// dd($slider_data);
        return view('customer.index')->with(['slider_data' => $slider_data,
                                            ]);
    }
}
