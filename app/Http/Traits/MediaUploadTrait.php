<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use File;

trait MediaUploadTrait
{

    function mediaUpload($media)
    {

        $media_file = $media['media_file'];
        $name = $media['name'];
        $media_path = $media['media_path'];

        if (hasfile($media_file)) {
            $profile_pic = file($media_file);
            $input['imagename'] = $name . time() . '.' . $profile_pic->getClientOriginalExtension();

            $path = public_path('uploads/'.$media_path);
            File::makeDirectory($path, $mode = 0777, true, true);

            $destinationPath = 'uploads/'.$media_path.'/';
            if ($profile_pic->move($destinationPath, $input['imagename'])) {
                return $destinationPath . $input['imagename'];
            } else {
                return 0;

            }
        } else {
            return 0;
        }
    }


    function checkFile(){
        if
    }

    function checkEnv($value){
        if(env('APP_ENV')=='staged'){
            $path = $this->domain.$value;
        }else{
            $path = url($value)
        }
    }
}
