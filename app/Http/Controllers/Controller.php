<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *    title="Your super  ApplicationAPI",
 *    version="1.0.0",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $successStatus = 200;
    public $successStatusCreated = 201;
    //stay and show message
    public $failureStatus = 400;
    //redirect
    public $invalidStatus = 401;
    //invalid request
    public $invalidRequest = 403;
    //currency
    public $currency = "$";
    //Wallet Key
    public $key = "MERCHANT";


}
