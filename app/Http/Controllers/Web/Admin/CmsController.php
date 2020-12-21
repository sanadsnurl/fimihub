<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\MediaUploadTrait;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\restaurent_detail;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\menu_list;
use App\Model\Cms;
use App\Model\slider_cms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;
use DataTables;

class CmsController extends Controller
{
    use MediaUploadTrait;
    public function getSliderPage(Request $request)
    {
        $user = Auth::user();

        $slider_cms = new slider_cms();
        $slider_array = ['slider_type' => 1];
        $slider_data = $slider_cms->getallSlider($slider_array);

        if ($request->ajax()) {
            return Datatables::of($slider_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a href="deleteSliderCms?slider_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                    ';
                    return $btn;
                })
                ->addColumn('media', function ($row) {
                    if ($row->media != NUll) {
                        $url = '../' . $row->media;
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View Image</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('link', function ($row) {
                    if ($row->link != NUll) {
                        $url = $row->link;
                        $btns = '<a href="' . $url . '" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">View Link</a>';
                    } else {
                        $btns = 'N.A';
                    }
                    return $btns;
                })
                ->addColumn('created_at', function ($row) {

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action', 'media', 'link'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        $slider_data = $slider_data->get();
        return view('admin.manageSlider')->with(['data' => $user, 'slider_data' => $slider_data]);
    }

    public function addSliderPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text1' => 'required|string',
            'text2' => 'required|string',
            'link' => 'required|url',
            'media' => 'required|mimes:png,jpg,jpeg|max:8192',
        ]);
        if (!$validator->fails()) {
            $data = $request->toArray();
            $data['type'] = 1;

            if ($request->hasfile('media')) {
                $slider_trait = ['media_file' => $data['media'], 'name' => 'Slider', 'media_path' => 'web_asset/images'];
                $media_file = $this->mediaUpload($slider_trait);

                if ($media_file != NULL) {
                    $slider_cms = new slider_cms();
                    $slider_array = [
                        'slider_type' => 1,
                        'text1' => $data['text1'],
                        'text2' => $data['text2'],
                        'link' => $data['link'],
                        'media' => $media_file,
                    ];
                    $slider_data = $slider_cms->makeSlider($slider_array);
                    Session::flash('message', 'Slider Added !');
                }else{
                    Session::flash('message', 'Can\'t Upload Image!');
                }
            }else{
                Session::flash('message', 'Can\'t Upload Image!');
            }

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
}
