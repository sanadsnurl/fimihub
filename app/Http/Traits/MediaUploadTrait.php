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
use Imagick;

trait MediaUploadTrait
{
    // Staged File Domain
    public $domain = 'https://fimihub.com/';

    function mediaUpload($media)
    {

        $media_file = $media['media_file'];
        $name = $media['name'];
        $media_path = $media['media_path'];

        if ($media_file) {
            // $profile_pic = file($media_file);
            $input['imagename'] = $name . time() . '.' . $media_file->getClientOriginalExtension();

            if(env('APP_ENV')=='staged'){
                $path = $this->domain.'fimihub/uploads/'.$media_path;
                $destinationPath = $this->domain.'fimihub/uploads/'.$media_path.'/';

            }else{
                $path = public_path('uploads/'.$media_path);
                $destinationPath = 'uploads/'.$media_path.'/';

            }

            File::makeDirectory($path, $mode = 0777, true, true);

            if ($media_file->move($destinationPath, $input['imagename'])) {
                return $destinationPath . $input['imagename'];
            } else {
                return '';

            }
        } else {
            return '';
        }
    }


    function checkEnv($value){
        if(env('APP_ENV')=='staged'){
            $path = $this->domain.$value;
        }else{
            $path = url($value);
        }
    }
}
