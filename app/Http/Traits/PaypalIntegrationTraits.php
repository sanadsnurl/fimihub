<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use App\Model\cart;
use App\Model\cart_submenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use File;

use function GuzzleHttp\json_encode;

trait PaypalIntegrationTraits
{
    function makepayment($payment_data) {

		$payment_data = ($payment_data);
        // dd($payment_data);

		//payment url
        $url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        $header = [
            'X-CSRF-TOKEN' => csrf_token()
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_data);
        // Catch output (do NOT print!)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // Return follow location true
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_exec($ch);
        $redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        return $redirectedUrl;


    }
}
