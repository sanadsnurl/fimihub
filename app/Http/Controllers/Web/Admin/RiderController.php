<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\restaurent_detail;
use App\Model\menu_categories;
use App\Model\ServiceCategory;
use App\Model\user_address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;
use DataTables;
use File;

class RiderController extends Controller
{
    public function RiderListDetails(Request $request)
    {
        $user = Auth::user();

        $users = new user;
        $user_list = $users->allUserPaginateListRiderData(2)->orWhere('users.visibility', 3)
            ->with('riderBankDetails', 'vehicleDetails');
        if ($request->ajax()) {
            return Datatables::of($user_list)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if($row->visibility == 3){
                        $btn = '<a href="deleteRider?delete_status=' . base64_encode(1) . '&rider_user_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                        <a href="riderEarnings?rider_user_id='.base64_encode($row->id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Earnings</a>
                        <a href="enableRider?rider_user_id=' . base64_encode($row->id) . '" class="btn btn-outline-success btn-sm btn-round waves-effect waves-light mt-1">Enable</a>';
                    }else{
                        $btn = '<a href="deleteRider?delete_status=' . base64_encode(1) . '&rider_user_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                        <a href="riderEarnings?rider_user_id='.base64_encode($row->id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Earnings</a>
                        <a href="disableRider?rider_user_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Disable</a>';
                    }

                    return $btn;
                })
                ->addColumn('vehicle_image', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->vehicle_image;
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View Image</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('id_proof', function ($row) {


                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->id_proof;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('driving_license', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->driving_license;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;

                })
                ->addColumn('background_check', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->background_check;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;

                })
                ->addColumn('food_permit', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->food_permit;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;

                })
                ->addColumn('role', function ($row) {
                    $type = "";
                    if($row->role == 1){
                        $type = "Driver";
                    }
                    if($row->role == 2){
                        $type = "Runner";
                    }
                    return $type;
                })
                ->filterColumn('role', function ($query, $keyword) {
                    $orderStatus = collect(array(
                        1 => "Driver",
                        2 => "Runner",
                    ));
                    $keys  = array();
                    foreach($orderStatus as $key => $value) {
                        if(!empty(stristr($value, $keyword))) {
                            $keys[] = $key;
                        }
                    }

                    if (count($keys)) {
                        $query->whereIn("users.role", $keys);
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d F Y', strtotime($row->created_at));
                })
                ->filterColumn('created_at', function ($query, $keyword){
                    $query->whereRaw("DATE_FORMAT(orders.created_at,'%d %M %Y') like ?", ["%$keyword%"]);
                })
                ->addColumn('mobile', function ($row) {
                    if($row->country_code != NULL){
                        return '('.$row->country_code.')'.$row->mobile;
                    }else{
                        return $row->mobile;

                    }
                })
                ->rawColumns(['action', 'vehicle_image', 'id_proof', 'driving_license', 'background_check', 'food_permit'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        $user_list = $user_list->get();

        // dd($user_list);
        return view('admin.riderList')->with(['data' => $user]);
    }

    public function pendingRider(Request $request)
    {
        $user = Auth::user();

        $users = new user;
        $pending_user = $users->allUserPaginateListRiderPendingData(2)
                    ->with('riderBankDetails', 'vehicleDetails');
        if ($request->ajax()) {
            return Datatables::of($pending_user)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a href="approveRider?user_id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Approve</a>';
                return $btn;
                })
                ->addColumn('vehicle_image', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->vehicle_image;
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View Image</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('id_proof', function ($row) {


                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->id_proof;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('driving_license', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->driving_license;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;

                })
                ->addColumn('background_check', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->background_check;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;

                })
                ->addColumn('food_permit', function ($row) {
                    if ($row->vehicleDetails != NUll) {
                        $url = $row->vehicleDetails->food_permit;

                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;

                })
                ->addColumn('role', function ($row) {
                    $type = "";
                    if($row->role == 1){
                        $type = "Driver";
                    }
                    if($row->role == 2){
                        $type = "Runner";
                    }
                    return $type;
                })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->addColumn('mobile', function ($row) {
                    if($row->country_code != NULL){
                        return '('.$row->country_code.')'.$row->mobile;
                    }else{
                        return $row->mobile;

                    }
                })
                ->rawColumns(['action', 'vehicle_image', 'id_proof', 'driving_license', 'background_check', 'food_permit'])
                ->make(true);
        }
                $user['currency']=$this->currency;

        $pending_user = $pending_user->get();
        return view('admin.riderRequest')->with(['data'=>$user,'pending_user'=>$pending_user]);

    }

    public function approveRider(Request $request)
    {
        $user = Auth::user();

        $user_id = base64_decode(request('user_id'));

        $users = new user;
        $approved = $users->requestApprove($user_id);
        Session::flash('message', 'Approved!');

        return redirect()->back();

    }

    public function deleteRider(Request $request){
        $user = Auth::user();
        $delete_status = base64_decode(request('delete_status'));
        $delete_url = $request->fullUrl();
        $delete_url = str_replace('delete_status=MQ%3D%3D','delete_status=Mg%3D%3D',  $delete_url);

        if($delete_status == 1){
            Session::flash('popup_delete', $delete_url);

            return redirect()->back();
        }
        $rider_user_id = base64_decode(request('rider_user_id'));

        $users = new User;
        $delete_rider = array();
        $delete_rider['id'] = $rider_user_id;

        $delete_rider = $users->deleteUser($delete_rider);
        Session::flash('message', 'Rider Deleted Successfully !');

        return redirect()->back();
    }

    public function enableRider(Request $request){
        $user = Auth::user();
        $rider_user_id = base64_decode(request('rider_user_id'));

        $users = new User;
        $delete_rider = array();
        $delete_rider['id'] = $rider_user_id;
        $delete_rider['visibility'] = 0;

        $delete_rider = $users->changeLoginStatus($delete_rider);
        Session::flash('message', 'Rider Enabled  !');

        return redirect()->back();
    }

    public function disableRider(Request $request){
        $user = Auth::user();
        $rider_user_id = base64_decode(request('rider_user_id'));
        $users = new User;
        $delete_rider = array();
        $delete_rider['id'] = $rider_user_id;
        $delete_rider['visibility'] = 3;

        $delete_rider = $users->changeLoginStatus($delete_rider);
        Session::flash('message', 'Rider Disabled !');

        return redirect()->back();
    }

    public function nearByRider(){
        $user = Auth::user();
        $users = new User;
        $rider_list = $users->allUserPaginateListRiderData(2)->where('users.status',1)->with(['userAddress'])->get();
        $location = array();
        $locations = array();
        $i = 1;
        foreach($rider_list as $r_list){
            $map_text = $r_list->name."<br>".$r_list->country_code." ".$r_list->mobile."<br>".(string)$r_list['userAddress'][0]->address;

            $locations[] = [$map_text, (float)$r_list['userAddress'][0]->latitude, (float)$r_list['userAddress'][0]->longitude, $i];
        $i+=1;
        }
        $location =  json_encode($locations);
        // var_dump($location);
        // die();
        return view('admin.nearByRiders')->with(['data'=>$user,'rider_list'=>$rider_list,'location'=>$location]);
    }
}
