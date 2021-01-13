<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if(!$request->session()->exists('admin_data')  || !Auth::check()){
            // user value cannot be found in session
            Session::flash('message', 'Please Login!');
            return redirect('/adminfimihub/login');
        }
        else{
            if($request->session()->exists('restaurent') || $request->session()->exists('user'))
            {
                Auth::logout();
                Session::flush();
                Session::flash('message', 'Service Violation (Please Try Again)!');
                return redirect('adminfimihub/login');
            }
            else
            {
                if($request->session()->exists('admin_data'))
                {
                    if($user->user_type !=1){
                        Session::flash('message', 'User Type Invalid !');
                        return redirect('/adminfimihub/login');
                    }
                    elseif($user->visibility ==2){
                        Session::flash('message', 'Account Deleted !');
                        return redirect('/adminfimihub/login');;

                    }
                    elseif($user->visibility ==1){
                        Session::flash('message', 'Account Pending !');
                        return redirect('/adminfimihub/login');;

                    }
                    elseif($user->visibility ==3){
                        Session::flash('message', 'Account Rejeted or Revoked !');
                        return redirect('/adminfimihub/login');;

                    }
                }else{
                    Session::flash('message', 'Please Login Again!');
                    return redirect('/adminfimihub/login');
                }
            }
        }


        return $next($request);
    }
}
