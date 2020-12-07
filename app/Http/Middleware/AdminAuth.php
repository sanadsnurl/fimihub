<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

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
        if(!$request->session()->exists('admin_data')){
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
                if(session('admin_data')->user_type !=1){
                    Session::flash('message', 'User Type Invalid !'); 
                    return redirect('/adminfimihub/login');
                }
            }
        }
        

        return $next($request);
    }
}
