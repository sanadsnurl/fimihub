<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\cart;
use App\Model\cart_submenu;
use App\Model\menu_custom_list;
use App\Model\menu_list;
use App\Model\oauth_access_token;
use App\Model\OrderEvent;
use App\Model\restaurent_detail;
use App\Model\user_address;
use Response;
use File;

class OrderController extends Controller
{

}
