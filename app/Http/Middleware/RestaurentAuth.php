<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

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
        if(!$request->session()->exists('restaurent')){         
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
                if(session('restaurent')->user_type !=4){
            
                    Session::flash('message', 'User Type Invalid !'); 
                    return redirect('Restaurent/login');
                }
                elseif(session('restaurent')->visibility ==1){
                    
                    Session::flash('message', 'Account Not Activated , Admin Approval Needed !'); 
                    return redirect('Restaurent/login');
                }
                elseif(session('restaurent')->mobile_verified_at ==NULL){
                    Session::flash('message', 'Please verify your account!'); 
                    return redirect('Restaurent/login');
                }
            }
            
        }
        
        

        return $next($request);
    }
}
