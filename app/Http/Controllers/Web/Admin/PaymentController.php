<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Model\payment_method;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;
use DataTables;

class PaymentController extends Controller
{

    public function getPaymentMethod(Request $request)
    {
        $user = Auth::user();

        $payment_methods = new payment_method();
        $payment_method_data = $payment_methods->getPaymentMethodList();

        if ($request->ajax()) {
            // return 'asfsdf';
            return DataTables::of($payment_method_data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="changePaymentStatus?visibility=2&payment_id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Delete</a>';
                    return $btn;
                })
                ->addColumn('web_active', function($row){
                    if($row->web_active == 0){
                        $btn = '<a href="changePaymentStatus?web_active=1&payment_id='.base64_encode($row->id).'" class="btn btn-danger btn-sm btn-round waves-effect waves-light m-0">Disable</a>';

                    }else{
                        $btn = '<a href="changePaymentStatus?web_active=0&payment_id='.base64_encode($row->id).'" class="btn btn-success btn-sm btn-round waves-effect waves-light m-0">Enable</a>';

                    }
                    return $btn;
                })
                ->addColumn('app_active', function($row){
                    if($row->app_active == 0){
                        $btn = '<a href="changePaymentStatus?app_active=1&payment_id='.base64_encode($row->id).'" class="btn btn-danger btn-sm btn-round waves-effect waves-light m-0">Disable</a>';

                    }else{
                        $btn = '<a href="changePaymentStatus?app_active=0&payment_id='.base64_encode($row->id).'" class="btn btn-success btn-sm btn-round waves-effect waves-light m-0">Enable</a>';

                    }
                    return $btn;
                })
                ->addColumn('status', function($row){
                    if($row->status == 0){
                        $btn = '<a href="changePaymentStatus?status=1&payment_id='.base64_encode($row->id).'" class="btn btn-danger btn-sm btn-round waves-effect waves-light m-0">Disable</a>';

                    }else{
                        $btn = '<a href="changePaymentStatus?status=0&payment_id='.base64_encode($row->id).'" class="btn btn-success btn-sm btn-round waves-effect waves-light m-0">Enable</a>';

                    }
                    return $btn;
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action','web_active','app_active','status'])
                ->make(true);

        }
        $user['currency']=$this->currency;
        $payment_method_data = $payment_method_data->get();

        return view('admin.paymentMethod')->with(['data' => $user]);
    }

    public function changePaymentStatus(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'web_active' => 'string|in:0,1|nullable',
            'app_active' => 'string|in:0,1|nullable',
            'visibility' => 'string|in:2|nullable',
            'status' => 'string|in:0,1|nullable',
            'payment_id' => 'required|string|max:150',
        ]);
        if($validator->fails()){

            return redirect()->back()->withInput()->withErrors($validator);
        }
        $data['id'] = base64_decode($data['payment_id']);
        unset($data['payment_id']);
        // dd($data);
        $payment_methods = new payment_method;
        $update = $payment_methods->updatePaymentMethod($data);
        Session::flash('message', 'Setting Updated');

        return redirect()->back();
    }
}
