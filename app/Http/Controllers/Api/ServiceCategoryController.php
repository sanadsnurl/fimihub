<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ServiceCategory;

class ServiceCategoryController extends Controller
{

    public function __construct(ServiceCategory $serviceCategory)
    {
        $this->serviceCategory = $serviceCategory;
    }
    public function getServiceCategory($id = false) {
        if ($id) {
            $response = $this->serviceCategory->getServiceCategory($id)->first();
        } else {
            $response = $this->serviceCategory->getServiceCategory($id)->get();
        }
        return response()->json(['data' => $response, 'message' => 'success', 'status' => true], $this->successStatus);
    }
}
