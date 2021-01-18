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
use Response;
use Session;
use DataTables;

class UserManageController extends Controller
{
    public function UserListDetails(Request $request)
    {
        $user = Auth::user();
        $user_instance = new User;

        $user_data = $user_instance->allUserPaginateList(3);
        if ($request->ajax()) {
            // dd($user_data);
            // date('d F Y')
            return Datatables::of($user_data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '
                        <a href="deleteUser?user_id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Delete</a>';
                    return $btn;
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);
                //dd($user_data);
        }
        $user['currency']=$this->currency;
        $user_data = $user_data->get();
        return view('admin.userList')->with(['data'=>$user,'user_data'=>$user_data]);

    }



    public function UserUpdateDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|nullable',
            'dob' => 'date|nullable',
            'email' => 'email|nullable',

        ]);
        if(!$validator->fails()){
            $user = Auth::user();
            $data = $request->toarray();
            $id = $data['id'];

            $user_instance = new User;
            $user_data = $user_instance->userByIdData($data['id']);
            $user_update_data=array();
            $user_update_data['id']=$id;
            if($request->has('password'))
            {
                unset($data['password']);
            }
            if($request->has('email')){
                if($data['email'] == $user_data->email){
                    $ab=1;
                }else{
                    $user_update_data['email']=$data['email'];
                    $user_update_data['email_verified_at']=NULL;
                }

            }

            if($request->has('mobile')){
                if($data['mobile'] == $user_data->email){
                    $ab=1;
                }else{
                    $user_update_data['mobile']=$data['mobile'];
                    $user_update_data['mobile_verified_at']=NULL;
                }

            }
            if($request->has('name')){
                $user_update_data['name']=$data['name'];
            }
            $user = auth()->user()->UpdateLogin($user_update_data);
            $user_data = auth()->user()->userByIdData($id);
            unset($user_data->password);

            $profile_data=array();
            $profile_data['user_id']=$data['id'];
            if($request->has('dob')){
                $profile_data['dob']=$data['dob'];
            }
            if($request->hasfile('profile_picture'))
            {
                $profile_pic = $request->file('profile_picture');
                $input['imagename'] = 'ProfilePicture'.time().'.'.$profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/'.$id.'/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/'.$id.'/images'.'/';
                if($profile_pic->move($destinationPath, $input['imagename']))
                {
                    $file_url=asset($destinationPath.$input['imagename']);
                    $profile_data['profile_picture']=$file_url;

                }else{
                    $error_file_not_required[]="Profile Picture Have Some Issue";
                    $profile_data['profile_picture']="";
                }

            }
            if($request->has('gender')){
                $profile_data['gender']=$data['gender'];
            }
            $user_profile = new user_profile();
            $profile_data_update = $user_profile->insertUpdateProfileData($profile_data);
            $profile_data = $user_profile->profileData($profile_data);

            Session::flash('message', 'Profile Updated !');
            return redirect()->back();

        }else{
            return redirect()->back()->withInput()->withErrors($validator);
        }

    }

    public function deleteUser(Request $request){
        $user = Auth::user();
        $user_id = base64_decode(request('user_id'));

        $delete_user = array();
        $delete_user['id'] = $user_id;

        $user_instant = new User;
        $faq_data = $user_instant->deleteUser($delete_user);
        Session::flash('message', 'User Deleted !');

        return redirect()->back();
    }


}
