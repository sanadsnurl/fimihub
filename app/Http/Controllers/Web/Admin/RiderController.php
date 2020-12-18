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
        $user_list = $users->allUserPaginateListRiderData(2)
            ->with('riderBankDetails', 'vehicleDetails');
        if ($request->ajax()) {
            return Datatables::of($user_list)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="editResto?resto_user_id=' . base64_encode($row->resto_user_id) . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                    <a href="deleteResto?resto_user_id=' . base64_encode($row->resto_user_id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                    ';
                    return $btn;
                })
                ->addColumn('vehicle_image', function ($row) {
                    $url = $row->vehicleDetails->vehicle_image;
                    if ($url != NUll) {
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View Image</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('id_proof', function ($row) {


                    $url = $row->vehicleDetails->id_proof;
                    if ($url != NUll) {
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('driving_license', function ($row) {
                    $url = $row->vehicleDetails->driving_license;
                    if ($url != NUll) {
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View</a>';
                    } else {
                        $btns = 'N.A';
                    }
                })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action', 'vehicle_image', 'id_proof', 'driving_license'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        $user_list = $user_list->get();

        // dd($user_list);
        return view('admin.riderList')->with(['data' => $user]);
    }
}
