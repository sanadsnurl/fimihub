<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Model\EnvSetting;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Session;

class EnvSettingCtroller extends Controller
{
    public function envSettingAdd(Request $request) {
        $envSettings = EnvSetting::all();
        // dd($envSettings);
        $user = Auth::user();

        if ($request->ajax()) {
            // return 'asfsdf';
            return DataTables::of($envSettings)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // $btn = '<a href="editResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                    // <a href="deleteResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                    // ';
                    return 'N.A';
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $user['currency']=$this->currency;
        $envSettings = $envSettings;
        // return view('admin.restaurentList')->with(['data'=>$user]);

        return view('admin.envSetting')->with(['data' => $user]);
    }
    public function envSettingStore(Request $request) {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:150',
            'key' => 'required|string|max:150',
            'value' => 'required|string|max:150',
        ]);
        if($validator->fails()){

            return redirect()->back()->withInput()->withErrors($validator);
        }

        if(EnvSetting::create($data)) {
            Session::flash('message', 'Updated Successfully !');
        } else {
            Session::flash('message', 'Unable to Update! Please try again.');
        }
        // return view('admin.envSetting', compact('envSettings'));
        return redirect()->back();


    }
}
