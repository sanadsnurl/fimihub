<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Cms;
use Illuminate\Http\Request;
use App\Model\Reason;

class CmsController extends Controller
{
    public function getCms(int $type = 0)
    {
        $cmsObj = new Cms;
        $data = $cmsObj->getCms($type)->get();

        return response()->json(['data' => $data,
        'status'=>true], $this->successStatus);
    }

    public function getReasons($userType) {
        $objReason = new Reason;
        $Reasons = $objReason->getReasons($userType)->get();
        if(count($Reasons)) {
        $response =  ['status'=> true, 'message' => 'Success', 'data' => $Reasons];
        } else {
            $response =  ['status'=> false, 'message' => 'No data found!'];
        }

        return response()->json($response, $this->successStatus);
    }
}
