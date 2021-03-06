<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\GetBasicPageDataTraits;
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
    use MediaUploadTrait,GetBasicPageDataTraits;
    public function indexHandShake(Request $request)
    {

        $slider_cms = new slider_cms;
        $slider_array = ['slider_type'=> 1, 'user_id'=>NULL];
        $slider_data = $slider_cms->getSlider($slider_array);
        $sl_data = array();
        foreach ($slider_data as $s_data) {
            // if (file_exists($s_data->media)) {
                $s_data->media = asset($s_data->media);
                $sl_data[] =  $s_data;
            // }
        }
// dd($slider_data);
        return view('customer.index')->with(['slider_data' => $sl_data,
                                            ]);
    }

    public function indexAboutUsPage(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.aboutUs')->with([
            'user_data' => $user,
        ]);
    }

    public function indexCardPolicy(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.cardPolicy')->with([
            'user_data' => $user,
        ]);
    }
    public function indexTandC(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.termAndCond')->with([
            'user_data' => $user,
        ]);
    }

    public function indexMerchantQnA(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.merchantQnA')->with([
            'user_data' => $user,
        ]);
    }

    public function indexPrivacyPolicy(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.privacyPolicy')->with([
            'user_data' => $user,
        ]);
    }

    public function indexReturnPolicy(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.returnPolicy')->with([
            'user_data' => $user,
        ]);
    }

    public function indexPartnerAgreementPolicy(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.partnerAgreement')->with([
            'user_data' => $user,
        ]);
    }

    public function indexMerchantTandC(Request $request)
    {
        $user = Auth::user();

        return view('customer.pages.merchantT&C')->with([
            'user_data' => $user,
        ]);
    }
}
