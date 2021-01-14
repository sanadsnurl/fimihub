<?php

namespace App\Http\Controllers\Web\Restaurent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
//custom import
use App\Model\restaurent_detail;
use App\Model\menu_categories;
use App\Model\menu_list;
use App\Model\user_address;
use App\Model\resto_menu_categorie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\custom_menu_categorie;
use App\Model\menu_custom_list;
use App\Model\menu_customization;
use App\Model\resto_custom_menu_categorie;
use Response;
use Session;
use File;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;


class RestaurentController extends Controller
{
    public function accountDetails()
    {
        $user = Auth::user();
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        if ($resto_data == NUll) {
            $resto_data = null;
        }
        $user_address = new user_address;
        $resto_add = $user_address->getUserAddress($user->id);

        if (count($resto_add) < 1) {

            return view('restaurent.myDetails')->with([
                'data' => $user,
                'resto_data' => $resto_data,
                'resto_add' => null
            ]);
        } else {

            return view('restaurent.myDetails')->with([
                'data' => $user,
                'resto_data' => $resto_data,
                'resto_add' => $resto_add[0]
            ]);
        }
    }

    public function addRestaurentDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|nullable',
            'about' => 'string|nullable',
            'other_details' => 'string|nullable',
            'picture' => 'mimes:png,jpg,jpeg|max:3072|nullable',
            'official_number' => 'numeric|nullable',
            'avg_cost' => 'numeric|nullable',
            'avg_time' => 'string|nullable',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'address_address' => 'required|string',
            'pincode' => 'string|nullable',
            'resto_type' => 'in:1,2,3|nullable',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();
            $data['user_id'] = $user->id;
            $restaurent_detail = new restaurent_detail;
            if ($request->hasfile('picture')) {
                $profile_pic = $request->file('picture');
                $input['imagename'] = 'RestaurentProfilePicture' . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $data['picture'] = $file_url;
                } else {
                    $error_file_not_required[] = "Profile Picture Have Some Issue";
                    unset($data['picture']);
                }
            } else {
                unset($data['picture']);
            }


            if ($request->has('address_address')) {
                $add_data = array();
                if ($data['address_latitude'] == 0 || $data['address_longitude'] == 0 || $data['address_longitude'] == NULL|| $data['address_latitude'] == NULL) {
                    Session::flash('message', 'Invalid Address !');
                    return redirect()->back();
                } else {
                    $add_data['user_id'] = $user->id;
                    $add_data['address'] = $data['address_address'];
                    $add_data['latitude'] = $data['address_latitude'];
                    $add_data['longitude'] = $data['address_longitude'];
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
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function categoryDetails(Request $request)
    {
        $user = Auth::user();


        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        $menu_categories = new menu_categories;
        $cat_data = $menu_categories->restaurentCategoryPaginationData()->where('service_catagory_id', 1)->get();
        if ($resto_data == NULL) {
            $resto_add = NULL;
            Session::flash('message', 'Please add Restaurant Details!');

            if ($resto_add == NULL) {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => null
                ]);
            } else {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => $resto_add[0]
                ]);
            }
        } else {
            $resto_menu_categories = new resto_menu_categorie;
            $resto_cate_data = $resto_menu_categories->restaurentCategoryData($resto_data->id);
            $resto_cate_datas = $resto_cate_data->get();
        }

        if ($request->ajax()) {
            return Datatables::of($resto_cate_data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $btn = '<a href="userDetails?id='.base64_encode($row->id).'" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Details</a>
                //         <a href="?id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Block</a>';
                //     return $btn;
                // })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $user['currency'] = $this->currency;

        // dd($resto_cate_data);
        return view('restaurent.menuCategory')->with(['data' => $user, 'cat_data' => $cat_data]);
    }

    public function addCategoryProcess(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|nullable|unique:menu_categories,name,'.auth()->user()->id.',restaurent_id',
            // 'menu_category_id' => 'required|numeric|unique:resto_menu_categories,menu_category_id,' . auth()->user()->id . ',user_id',
            'menu_category_id' => [
                                    'required',
                                    Rule::unique('resto_menu_categories')
                                            ->where('user_id',auth()->user()->id)
                                            ->where('visibility',0)
            ],
            'cat_name' => 'unique:menu_categories,name|string|nullable',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $data = $request->toarray();

            $menu_categories = new menu_categories;
            $resto_menu_categories = new resto_menu_categorie;

            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoData($user->id);


            if ($data['menu_category_id'] == -1) {
                $menu_data = array();
                $menu_data['name'] = $data['cat_name'];
                $menu_data['service_catagory_id'] = 1;

                $cat_id = $menu_categories->makeMenuCategory($menu_data);

                $resto_menu_cat = array();
                $resto_menu_cat['menu_category_id'] = $cat_id;
                $resto_menu_cat['user_id'] = $user->id;
                $resto_menu_cat['restaurent_id'] = $resto_data->id;

                $resto_cate_id = $resto_menu_categories->makeRestoMenuCategory($resto_menu_cat);
            } else {
                $resto_menu_cat = array();
                $resto_menu_cat['menu_category_id'] = $data['menu_category_id'];
                $resto_menu_cat['user_id'] = $user->id;
                $resto_menu_cat['restaurent_id'] = $resto_data->id;
                $resto_cate_id = $resto_menu_categories->makeRestoMenuCategory($resto_menu_cat);
            }
            Session::flash('message', 'Category Added Successfully!');

            return redirect()->back();
        } else {
            // dd($validator->messages());
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function getMenuList(Request $request)
    {
        $user = Auth::user();


        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        if ($resto_data == NULL) {
            $resto_add = NULL;
            Session::flash('message', 'Please add Restaurant Details!');

            if ($resto_add == NULL) {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => null
                ]);
            } else {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => $resto_add[0]
                ]);
            }
        }

        $menu_categories = new menu_categories;
        $cat_data = $menu_categories->restaurentCategoryPaginationData()->where('service_catagory_id', 1);
        $resto_menu_categories = new resto_menu_categorie;
        $resto_cate_data = $resto_menu_categories->restaurentCategoryData($resto_data->id)->get();
        $resto_custom_menu_categories = new resto_custom_menu_categorie();
        $resto_cate_variant = $resto_custom_menu_categories
                            ->restaurentCategoryCustomData($resto_data->id)
                            ->where('customization_variant',2)
                            ->get();
        $resto_cate_add_on = $resto_custom_menu_categories
                            ->restaurentCategoryCustomData($resto_data->id)
                            ->where('customization_variant',1)
                            ->get();
// dd($resto_cate_variant);
        $menu_list = new menu_list;
        $menu_data = $menu_list->menuPaginationData($resto_data->id);
        if ($request->ajax()) {
            return Datatables::of($menu_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="editDish?dish_id=' . base64_encode($row->id) . '" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                        <a href="deleteDish?dish_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Delete</a>
                        ';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->addColumn('dish_type', function ($row) {
                    if ($row->dish_type == 1) {
                        return "Non-Veg";
                    } else {
                        return "Veg";
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
            //dd($user_data);
        }
        $cat_data = $cat_data->get();
        // dd($resto_cate_data);
        return view('restaurent.menuList')->with(['data' => $user,
                                        'resto_cate_variant'=>$resto_cate_variant,
                                        'resto_cate_add_on'=>$resto_cate_add_on,
                                        'cat_data' => $resto_cate_data]);
    }

    public function deleteMenuList(Request $request)
    {
        $user = Auth::user();
        $dish_id = base64_decode(request('dish_id'));

        $menu_lists = new menu_list;
        $delete_menu = array();
        $delete_menu['id'] = $dish_id;

        $delete_menu = $menu_lists->deleteMenu($delete_menu);
        Session::flash('menu_message', 'Dish Deleted Successfully !');

        return redirect()->back();
    }

    public function editMenu(Request $request)
    {
        $user = Auth::user();
        $dish_id = base64_decode(request('dish_id'));

        $menu_categories = new menu_categories;
        $cat_data = $menu_categories->restaurentCategoryPaginationData()->get();

        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);

        $resto_menu_categories = new resto_menu_categorie;
        $resto_cate_data = $resto_menu_categories->restaurentCategoryData($resto_data->id);
        $resto_custom_menu_categories = new resto_custom_menu_categorie();
        $resto_cate_variant = $resto_custom_menu_categories
            ->restaurentCategoryCustomData($resto_data->id)
            ->where('customization_variant',2)
            ->get();
        $resto_cate_add_on = $resto_custom_menu_categories
            ->restaurentCategoryCustomData($resto_data->id)
            ->where('customization_variant',1)
            ->get();

        $menu_lists = new menu_list;
        $menu_data = $menu_lists->menuListById($dish_id);

        $menu_data->product_add_on_id = json_decode($menu_data->product_add_on_id);
        // dd($menu_data);
        return view('restaurent.editMenu')->with(['data' => $user,
                                            'menu_data' => $menu_data,
                                            'resto_cate_variant'=>$resto_cate_variant,
                                            'resto_cate_add_on'=>$resto_cate_add_on,
                                            'cat_data' => $resto_cate_data->get()]);
    }

    public function editMenuProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|nullable',
            'picture' => 'mimes:png,jpg,jpeg|nullable',
            'about' => 'string|nullable',
            'discount' => 'numeric|nullable',
            'price' => 'required|numeric|not_in:0',
            'dish_type' => 'required|in:1,2|nullable',
            'menu_category_id' => 'required|exists:resto_menu_categories,id|nullable',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();


            if ($request->hasfile('picture')) {
                $profile_pic = $request->file('picture');
                $input['imagename'] = $data['name'] . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $data['picture'] = $file_url;
                } else {
                    $error_file_not_required[] = "Food Picture Have Some Issue";
                    unset($data['picture']);
                }
            } else {
                unset($data['picture']);
            }
            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoData($user->id);
            $data['restaurent_id'] = $resto_data->id;
            $menu_list = new menu_list;

            $cate_id = $menu_list->editMenu($data);
            Session::flash('message', 'Dish Details Updated!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function menuListProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|nullable',
            'picture' => 'mimes:png,jpg,jpeg|nullable',
            'about' => 'string|nullable',
            'discount' => 'numeric|nullable',
            'price' => 'numeric|not_in:0',
            'dish_type' => 'required|in:1,2|nullable',
            'menu_category_id' => 'required|exists:resto_menu_categories,id|nullable',
            'product_variant_id' => 'integer',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();

            $data['product_add_on_id'] = json_encode($data['product_add_on_id']);

            if ($request->hasfile('picture')) {
                $profile_pic = $request->file('picture');
                $input['imagename'] = $data['name'] . time() . '.' . $profile_pic->getClientOriginalExtension();

                $path = public_path('uploads/' . $id . '/images');
                File::makeDirectory($path, $mode = 0777, true, true);

                $destinationPath = 'uploads/' . $id . '/images' . '/';
                if ($profile_pic->move($destinationPath, $input['imagename'])) {
                    $file_url = url($destinationPath . $input['imagename']);
                    $data['picture'] = $file_url;
                } else {
                    $error_file_not_required[] = "Food Picture Have Some Issue";
                    unset($data['picture']);
                }
            } else {
                unset($data['picture']);
            }
            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoData($user->id);
            $data['restaurent_id'] = $resto_data->id;
            $menu_list = new menu_list;


            $cate_id = $menu_list->makeMenu($data);
            Session::flash('message', 'Menu Added Successfully!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function getAddOn(Request $request)
    {
        $user = Auth::user();

        $dish_id = base64_decode(request('dish_id'));

        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);



        if ($resto_data == NULL) {
            $resto_add = NULL;
            Session::flash('message', 'Please add Restaurant Details!');

            if ($resto_add == NULL) {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => null
                ]);
            } else {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => $resto_add[0]
                ]);
            }
        }
        $menu_customizations = new menu_customization();
        $menu_customization_data = $menu_customizations->getAddOnData($dish_id);
        // dd($menu_customization_data->get());

        if ($request->ajax()) {
            return Datatables::of($menu_customization_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="editAddOn?custom_id=' . base64_encode($row->id) . '" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                        <a href="deleteAddOn?custom_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Delete</a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->addColumn('customization_type', function ($row) {
                    if ($row->customization_type == 1) {
                        return "Non-Veg";
                    } else {
                        return "Veg";
                    }
                })
                ->rawColumns(['action','customization_type'])
                ->make(true);
        }
        $menu_customization_data = $menu_customization_data->get();
        // dd($menu_customization_data);
        return view('restaurent.manageAddOn')->with(['data' => $user, 'menu_customization_data' => $menu_customization_data,'menu_list_id'=>$dish_id]);
    }

    public function addOnProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|nullable',
            'about' => 'string|nullable',
            'price' => 'required|numeric',
            'customization_type' => 'required|in:1,2|nullable',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();

            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoData($user->id);
            $data['restaurent_id'] = $resto_data->id;

            $menu_customizations = new menu_customization();
            if(isset($data['id'])){
                $add_on = $menu_customizations->editCustomization($data);
                Session::flash('message', 'Data Updated!');
            }else{
                $add_on = $menu_customizations->makeCustomization($data);
                Session::flash('message', 'Customization Added Successfully!');
            }

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function deleteCustomization(Request $request)
    {
        $user = Auth::user();
        $custom_id = base64_decode(request('custom_id'));

        $menu_customizations = new menu_customization();
        $delete_menu = array();
        $delete_menu['id'] = $custom_id;

        $delete_menu = $menu_customizations->deleteCustomization($delete_menu);
        Session::flash('menu_message', 'Customization Deleted Successfully !');

        return redirect()->back();
    }

    public function editCustomization(Request $request)
    {
        $user = Auth::user();
        $custom_id = base64_decode(request('custom_id'));

        $menu_customizations = new menu_customization();
        $customization_data = $menu_customizations->customizationById($custom_id)->first();
        return view('restaurent.editAddOn')->with(['data' => $user, 'custom_data' => $customization_data]);

    }

    public function categoryCustomDetails(Request $request)
    {
        $user = Auth::user();


        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        $custom_menu_categories = new custom_menu_categorie;
        $cat_data = $custom_menu_categories->restaurentCategoryCustomPaginationData()->where('service_catagory_id', 1)->get();
        if ($resto_data == NULL) {
            $resto_add = NULL;
            Session::flash('message', 'Please add Restaurant Details!');

            if ($resto_add == NULL) {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => null
                ]);
            } else {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => $resto_add[0]
                ]);
            }
        } else {
            $resto_custom_menu_categories = new resto_custom_menu_categorie();
            $resto_cate_data = $resto_custom_menu_categories->restaurentCategoryCustomData($resto_data->id);
            $resto_cate_datas = $resto_cate_data->get();
        }
// dd($resto_cate_datas);
        if ($request->ajax()) {
            return Datatables::of($resto_cate_data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $btn = '<a href="userDetails?id='.base64_encode($row->id).'" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Details</a>
                //         <a href="?id='.base64_encode($row->id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Block</a>';
                //     return $btn;
                // })
                ->addColumn('customization_variant', function ($row) {
                    if($row->customization_variant == 2){
                        return 'Menu Variant';
                        }else{
                        return 'ADD-ON';
                        }
                })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $user['currency'] = $this->currency;

        // dd($resto_cate_data);
        return view('restaurent.menuCustomCategory')->with(['data' => $user, 'cat_data' => $cat_data]);
    }

    public function addCustomCategoryProcess(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|nullable|unique:menu_categories,name,'.auth()->user()->id.',restaurent_id',
            // 'menu_category_id' => 'required|numeric|unique:resto_menu_categories,menu_category_id,' . auth()->user()->id . ',user_id',
            'custom_cat_id' => [
                                    'required',
                                    Rule::unique('resto_custom_menu_categories')
                                            ->where('user_id',auth()->user()->id)
                                            ->where('visibility',0)
            ],
            'cat_name' => 'unique:custom_menu_categories,name|string|nullable',
            'customization_variant' => 'required|in:1,2',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $data = $request->toarray();

            $custom_menu_categories = new custom_menu_categorie;
            $resto_custom_menu_categories = new resto_custom_menu_categorie;

            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoData($user->id);


            if ($data['custom_cat_id'] == -1) {
                $menu_data = array();
                $menu_data['name'] = $data['cat_name'];
                $menu_data['service_catagory_id'] = 1;

                $cat_id = $custom_menu_categories->makeMenuCustomCategory($menu_data);

                $resto_menu_cat = array();
                $resto_menu_cat['custom_cat_id'] = $cat_id;
                $resto_menu_cat['user_id'] = $user->id;
                $resto_menu_cat['restaurent_id'] = $resto_data->id;
                $resto_menu_cat['customization_variant'] = $data['customization_variant'];

                $resto_cate_id = $resto_custom_menu_categories->makeRestoMenuCustomCategory($resto_menu_cat);
            } else {
                $resto_menu_cat = array();
                $resto_menu_cat['custom_cat_id'] = $data['custom_cat_id'];
                $resto_menu_cat['user_id'] = $user->id;
                $resto_menu_cat['restaurent_id'] = $resto_data->id;
                $resto_menu_cat['customization_variant'] = $data['customization_variant'];
                $resto_cate_id = $resto_custom_menu_categories->makeRestoMenuCustomCategory($resto_menu_cat);
            }
            Session::flash('message', 'Custom Category Added Successfully!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function getMenuCustomList(Request $request)
    {
        $user = Auth::user();


        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        if ($resto_data == NULL) {
            $resto_add = NULL;
            Session::flash('message', 'Please add Restaurant Details!');

            if ($resto_add == NULL) {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => null
                ]);
            } else {
                return view('restaurent.myDetails')->with([
                    'data' => $user,
                    'resto_data' => $resto_data,
                    'resto_add' => $resto_add[0]
                ]);
            }
        }

        $custom_menu_categories = new custom_menu_categorie();
        $cat_data = $custom_menu_categories->restaurentCategoryCustomPaginationData()->where('service_catagory_id', 1);
        $resto_custom_menu_categories = new resto_custom_menu_categorie();
        $resto_cate_data = $resto_custom_menu_categories->restaurentCategoryCustomData($resto_data->id)->get();
        $menu_custom_list = new menu_custom_list();
        $menu_data = $menu_custom_list->menuCustomPaginationData($resto_data->id);
        if ($request->ajax()) {
            return Datatables::of($menu_data)
                ->addIndexColumn()
                // ->addColumn('action', function ($row) {
                //     $btn = '<a href="editDish?dish_id=' . base64_encode($row->id) . '" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                //         <a href="deleteDish?dish_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Delete</a>
                //         <a href="addOn?dish_id=' . base64_encode($row->id) . '" class="btn btn-outline-primary btn-sm btn-round waves-effect waves-light m-0">Add-on</a>';
                //     return $btn;
                // })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->addColumn('dish_type', function ($row) {
                    if ($row->dish_type == 1) {
                        return "Non-Veg";
                    } else {
                        return "Veg";
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
            //dd($user_data);
        }
        $cat_data = $cat_data->get();
        // dd($resto_cate_data);
        return view('restaurent.customMenuList')->with(['data' => $user, 'cat_data' => $resto_cate_data]);
    }

    public function menuCustomListProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|nullable',
            'price' => 'required|numeric',
            'resto_custom_cat_id' => 'required|exists:resto_custom_menu_categories,id|nullable',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $id = $user->id;
            $data = $request->toarray();

            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoData($user->id);
            $data['restaurent_id'] = $resto_data->id;
            $menu_custom_lists = new menu_custom_list();

            $cate_id = $menu_custom_lists->makeCustomMenu($data);
            Session::flash('message', 'Add-On Added Successfully!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

}
