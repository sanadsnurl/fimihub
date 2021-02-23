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

class RestaurentController extends Controller
{
    public function RestaurentListDetails(Request $request)
    {
        $user = Auth::user();
        // $restaurent_details = new restaurent_detail;
        // $resto_data = $restaurent_details->getallRestaurant();
        $users = new user;
        $user_list = $users->allUserPaginateListRestoData(4);
        if ($request->ajax()) {
            return Datatables::of($user_list)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->resto_visibility == 3){
                        $btn = '<a href="editResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                        <a href="deleteResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                        <a href="restoEarnings?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Earnings</a>
                        <a href="lookupResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Look-Up</a>
                        <a href="enableResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Enable</a>';
                    }else{
                        $btn = '<a href="editResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                    <a href="deleteResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                    <a href="restoEarnings?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Earnings</a>
                    <a href="lookupResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Look-Up</a>
                    <a href="disableResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Disable</a>';

                    }
                    return $btn;
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $user['currency']=$this->currency;
        $user_list = $user_list->get();
        // dd($user_list);
        return view('admin.restaurentList')->with(['data'=>$user]);

    }

    public function addRestaurent(Request $request)
    {
        $user = Auth::user();
        $user['currency']=$this->currency;

        return view('admin.addRestaurent')->with(['data'=>$user]);

    }

    public function addRestaurentProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'password' => 'required|confirmed|string|min:6',
            'mobile' => 'required|numeric|unique:users|digits:10',
            'email' => 'email|unique:users|nullable',
        ]);
        if(!$validator->fails()){
            $data=$request->toArray();
            $data['user_type']=4;
            $user = User::create($data);
                if($user != NULL){

                    Session::flash('message', 'Register Succesfully !');
                    return redirect()->back();

                }else{
                    Session::flash('message', 'Registration Failed , Please try again!');
                    return redirect()->back();
                }

        }
        else{
        	return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function categoryDetails(Request $request)
    {
        $user = Auth::user();

        $menu_categories = new menu_categories;
        $cat_data = $menu_categories->restaurentCategoryPaginationData();
        if ($request->ajax()) {
            return Datatables::of($cat_data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $btn = '
                //         <a href="deleteCat?cat_id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Delete</a>';
                //     return $btn;
                // })
                ->addColumn('service_catagory_id', function($row){
                    if($row->service_catagory_id == 1){
                        return "Food";
                    }
                    elseif($row->service_catagory_id == 2){
                        return "Grocery";
                    }
                    elseif($row->service_catagory_id == 3){
                        return "Electronics";
                    }
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $user['currency']=$this->currency;
        $ServiceCategories = new ServiceCategory;
        $service_list = $ServiceCategories->getAllServices()->get();
        $cat_data = $cat_data->get();
        return view('admin.menuCategory')->with(['data'=>$user,'cat_data'=>$cat_data,'service_list'=>$service_list]);

    }

    public function addCategoryProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'about' => 'string|nullable',
            'service_catagory_id' => 'required|in:1,2,3',
            'discount' => 'numeric|nullable',

        ]);
        if(!$validator->fails()){
            $user = Auth::user();

            $data = $request->toarray();
            $menu_categories = new menu_categories;
            $cate_id = $menu_categories->makeMenuCategory($data);
            Session::flash('message', 'Category Added Successfully!');

            return redirect()->back();

        }
        else{
        	return redirect()->back()->withInput()->withErrors($validator);
        }

    }


    public function pendingRetaurant(Request $request)
    {
        $user = Auth::user();

        $users = new user;
        $pending_user = $users->pendingUserPaginateList(4);
        if ($request->ajax()) {
            return Datatables::of($pending_user)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '
                        <a href="approveResto?user_id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Approve</a>';
                    return $btn;
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $user['currency']=$this->currency;

        $pending_user = $pending_user->get();
        return view('admin.restaurentRequest')->with(['data'=>$user,'pending_user'=>$pending_user]);

    }

    public function approveRetaurant(Request $request)
    {
        $user = Auth::user();

        $user_id = base64_decode(request('user_id'));

        $users = new user;
        $approved = $users->requestApprove($user_id);
        Session::flash('message', 'Approved!');

        return redirect()->back();

    }
    public function editRestaurant()
    {
        $user = Auth::user();
        $restaurent_detail = new restaurent_detail;
        $resto_add = NULL;
        $resto_user_id = base64_decode(request('resto_user_id'));
        $resto_data = $restaurent_detail->getRestoData($resto_user_id);
// dd($resto_user_id);
        $user_address = new user_address;
        $resto_add = $user_address->getUserAddress($resto_user_id);

        // dd($resto_data->user_id );
        if($resto_add == NULL || $resto_add->isEmpty()){
            return view('admin.editRestaurant')->with(['data'=>$user,
            'resto_data'=>$resto_data,
            'resto_add'=> null,
            'resto_user_id'=>$resto_user_id]);
        }
        else{
            return view('admin.editRestaurant')->with(['data'=>$user,
                                                'resto_data'=>$resto_data,
                                                'resto_user_id'=>$resto_user_id,
                                                'resto_add'=> $resto_add[0]]);
        }


    }

    public function editRestaurantProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|nullable',
            'user_id' => 'required|exists:users,id',
            'about' => 'string|nullable',
            'other_details' => 'string|nullable',
            'picture' => 'mimes:png,jpg,jpeg|max:3072|nullable',
            'official_number' => 'numeric|nullable',
            'avg_cost' => 'numeric|nullable',
            'avg_time' => 'string|nullable',
            'open_time' => 'string|nullable',
            'close_time' => 'string|nullable',
            'address_address' => 'required|string',
            'pincode' => 'string|nullable',
            'resto_type' => 'in:1,2,3|nullable',

        ]);
        if(!$validator->fails()){
            $resto_id = base64_decode(request('resto_id'));
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();
            $restaurent_detail = new restaurent_detail;
            if($request->hasfile('picture'))
            {
                $profile_pic = $request->file('picture');
                $input['imagename'] = 'RestaurentProfilePicture'.time().'.'.$profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/'.$data['user_id'].'/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/'.$data['user_id'].'/images'.'/';
                if($profile_pic->move($destinationPath, $input['imagename']))
                {
                    $file_url=asset($destinationPath.$input['imagename']);
                    $data['picture']=$file_url;

                }else{
                    $error_file_not_required[]="Profile Picture Have Some Issue";
                    unset($data['picture']);
                }

            }
            else{
                unset($data['picture']);
            }


            if($request->has('address_address')){
                $add_data =array();
                if($data['address_latitude'] == 0 || $data['address_longitude'] == 0){
                    Session::flash('message', 'Invalid Address !');
                    return redirect()->back();
                }
                else{
                    $add_data['user_id']=$data['user_id'];
                    $add_data['address']=$data['address_address'];
                    $add_data['latitude']=$data['address_latitude'];
                    $add_data['longitude']=$data['address_longitude'];
                    $user_address = new user_address;
                    $subscribe = $user_address->insertUpdateAddress($add_data);

                    $data['address'] = $data['address_address'];
                    unset($data['address_address']);
                    unset($data['address_latitude']);
                    unset($data['address_longitude']);
                    $resto_id = $restaurent_detail->insertUpdateRestoData($data);
                    Session::flash('message', 'Restaurent Data Updated !');

                    return redirect()->back();

                }
            }


        }
        else{
            // dd($validator->messages());
        	return redirect()->back()->withInput()->withErrors($validator);
        }

    }

    public function deleteRestaurent(Request $request){
        $user = Auth::user();
        $resto_user_id = base64_decode(request('resto_user_id'));

        $users = new User;
        $delete_resto = array();
        $delete_resto['id'] = $resto_user_id;

        $delete_resto = $users->deleteUser($delete_resto);
        Session::flash('message', 'Restaurant Deleted Successfully !');

        return redirect()->back();
    }

    public function enableResto(Request $request){
        $user = Auth::user();
        $resto_user_id = base64_decode(request('resto_user_id'));

        $users = new User;
        $delete_rider = array();
        $delete_rider['id'] = $resto_user_id;
        $delete_rider['visibility'] = 0;

        $delete_rider = $users->changeLoginStatus($delete_rider);
        Session::flash('message', 'Restaurent  Enabled  !');

        return redirect()->back();
    }

    public function disableResto(Request $request){
        $user = Auth::user();
        $resto_user_id = base64_decode(request('resto_user_id'));
        $users = new User;
        $delete_rider = array();
        $delete_rider['id'] = $resto_user_id;
        $delete_rider['visibility'] = 3;

        $delete_rider = $users->changeLoginStatus($delete_rider);
        Session::flash('message', 'Restaurent Disabled !');

        return redirect()->back();
    }
}
