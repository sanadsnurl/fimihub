<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Model\EnvSetting;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Session;

class EnvSettingController extends Controller
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
                    $btn = '<a href="editEnv?config_id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Edit</a>';
                    return $btn;
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
    public function getEditEnvPage(Request $request){
        $user = Auth::user();
        $config_id =  base64_decode(request('config_id'));
        $env_inst = new EnvSetting();
        $env_data_by_id = $env_inst->getEnvById($config_id);

        return view('admin.editEnv')->with(['data' => $user, 'env_data' =>$env_data_by_id]);
    }

    public function getEditEnvProcess(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:190',
        ]);
        if($validator->fails()){

            return redirect()->back()->withInput()->withErrors($validator);
        }
        $env_inst = new EnvSetting();
        $env_data_by_id = $env_inst->editEnv($data);
        Session::flash('message', 'Updated Successfully !');
        return redirect('adminfimihub/envSetting');
    }
}
