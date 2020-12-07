<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

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
        // dd(session('user'));
        if(!$request->session()->exists('user')){
            
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
                if(session('user')->user_type !=3){
                
                    Session::flash('message', 'User Type Invalid !'); 
                    return redirect('/login');
                }
                elseif(session('user')->mobile_verified_at ==NULL){
                    Session::flash('error_message', 'Please verify your account !'); 
                    Session::flash('modal_check2', 'open'); 
                    return redirect('/login');
        
                }
            }
        }
        

        return $next($request);
    }
}
