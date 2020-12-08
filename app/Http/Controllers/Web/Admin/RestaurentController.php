<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\restaurent_detail;
use App\Model\menu_categories;
use App\Model\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;
use DataTables;

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
                // ->addColumn('action', function($row){
                //     $btn = '<a href="userDetails?id='.base64_encode($row->id).'" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Details</a> 
                //         <a href="?id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Block</a>';
                //     return $btn;
                // })
                ->addColumn('created_at', function($row){
                    
                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);
                
        }
        $user['currency']=$this->currency;
        $user_list = $user_list->get();        
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



}
