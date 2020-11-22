<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Cms;
use Illuminate\Http\Request;
use App\Model\Resion;

class CmsController extends Controller
{
    public function getCms(int $type = 0)
    {
        $cmsObj = new Cms;
        $data = $cmsObj->getCms($type)->get();

        return response()->json(['data' => $data,
        'status'=>true], $this->successStatus);
    }

    public function getResions($userType) {
        $objResion = new Resion;
        $resions = $objResion->getResions($userType)->get();
        if(count($resions)) {
        $response =  ['status'=> true, 'message' => 'Success', 'data' => $resions];
        } else {
            $response =  ['status'=> false, 'message' => 'No data found!'];
        }

        return response()->json($response, $this->successStatus);
    }
}
