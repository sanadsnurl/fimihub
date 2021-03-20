<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Model\ServiceCategory;
use Response;
use Session;
use DataTables;


class ServiceController extends Controller
{
    public function serviceListDetails(Request $request)
    {
        $user = Auth::user();
        $ServiceCategories = new ServiceCategory;
        $service_list = $ServiceCategories->getAllServices();
        if ($request->ajax()) {
            return Datatables::of($service_list)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="editService?service_id='.base64_encode($row->id).'" class="btn btn-outline-success btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                        ';
                    return $btn;
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $user['currency']=$this->currency;
        $service_list = $service_list->get();
        return view('admin.serviceList')->with(['data'=>$user]);

    }

    public function editService(Request $request){
        $user = Auth::user();
        $service_id = base64_decode(request('service_id'));

        $ServiceCategories = new ServiceCategory;
        $service_data = $ServiceCategories->getServiceById($service_id);

        $user['currency']=$this->currency;
        return view('admin.editService')->with(['data'=>$user,'service_data'=>$service_data]);

    }

    public function editServiceProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'commission' => 'numeric|max:100.00',
            'tax' => 'numeric|max:100.00',
            'flat_delivery_charge' => 'numeric|min:0',
            'on_km' => 'numeric|min:1',
            'after_flat_delivery_charge' => 'numeric|min:0',
            'rider_commission' => 'numeric|min:0',

        ]);
        if(!$validator->fails()){

        }
        else{
        	return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = Auth::user();
        $data=$request->toArray();

        $ServiceCategories = new ServiceCategory;
        $service_data = $ServiceCategories->editService($data);

        Session::flash('message', 'Service Updated !');
        return redirect()->back();

    }
}
