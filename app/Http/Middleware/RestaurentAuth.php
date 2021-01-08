<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class RestaurentAuth
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
        if(!$request->session()->exists('restaurent')  && Auth::check()){
            // user value cannot be found in session
            Session::flash('message', 'Please Login First!');
            return redirect('Restaurent/login');
        }
        else{
            if($request->session()->exists('user') || $request->session()->exists('admin_data'))
            {
                Auth::logout();
                Session::flush();
                Session::flash('message', 'Service Violation (Please Try Again)!');
                return redirect('Restaurent/login');
            }
            else
            {
                if($user->user_type !=4){

                    Session::flash('message', 'User Type Invalid !');
                    return redirect('Restaurent/login');
                }
                elseif($user->mobile_verified_at ==NULL){
                    Session::flash('message', 'Please verify your account!');
                    return redirect('Restaurent/login');
                }
                elseif($user->visibility ==1){

                    Session::flash('message', 'Account Not Activated , Admin Approval Needed !');
                    return redirect('Restaurent/login');
                }
                elseif($user->visibility ==2){
                    Session::flash('message', 'Account Deleted !');
                    return redirect('Restaurent/login');;

                }
                elseif($user->visibility ==1){
                    Session::flash('message', 'Account Pending !');
                    return redirect('Restaurent/login');;

                }
                elseif($user->visibility ==3){
                    Session::flash('message', 'Account Rejeted or Revoked !');
                    return redirect('Restaurent/login');;

                }
                elseif($user->visibility ==4){
                    Session::flash('message', 'Account Blocked !');
                    return redirect('Restaurent/login');;

                }
            }

        }



        return $next($request);
    }
}
