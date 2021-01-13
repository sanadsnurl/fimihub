<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class CustomerAuth
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
        if(!$request->session()->exists('user') || !Auth::check()){

            // user value cannot be found in session
            Session::flash('message', 'Please Login First!');
            return redirect('/login');
        }
        else
        {
            if($request->session()->exists('restaurent') || $request->session()->exists('admin_data'))
            {
                Auth::logout();
                Session::flush();
                Session::flash('message', 'Service Violation (Please Try Again)!');
                return redirect('login');
            }
            else
            {
                if($request->session()->exists('user'))
                {
                    if($user->user_type !=3){

                        Session::flash('message', 'User Type Invalid !');
                        return redirect('/login');
                    }
                    elseif($user->mobile_verified_at ==NULL){
                        Session::flash('error_message', 'Please verify your account !');
                        Session::flash('modal_check2', 'open');
                        return redirect('/login');

                    }
                    elseif($user->visibility ==2){
                        Session::flash('message', 'Account Deleted !');
                        return redirect('/login');;

                    }
                    elseif($user->visibility ==1){
                        Session::flash('message', 'Account Pending !');
                        return redirect('/login');;

                    }
                    elseif($user->visibility ==3){
                        Session::flash('message', 'Account Rejeted or Revoked !');
                        return redirect('/login');;

                    }
                    elseif($user->visibility ==4){
                        Session::flash('message', 'Account Blocked !');
                        return redirect('/login');;

                    }
                }else{
                    Session::flash('message', 'Please Login Again!');
                    return redirect('/login');
                }
            }
        }


        return $next($request);
    }
}
