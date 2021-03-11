<?php

namespace App\Http\Middleware;

use App\Model\order;
use Closure;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAuth
{
    public $riderAllowedRoutes = ['dashboard', 'login', 'logout', 'notfound', 'resetPassword', 'disableRider', 'enableRider', 'riderEarnings', 'resetPasswordProcess', 'riderList', 'deleteUser', 'pendingRider', 'approveRider', 'deleteRider'];
    public $restoAllowedRoutes = ['dashboard', 'login', 'logout', 'notfound', 'resetPassword', 'resetPasswordProcess', 'retaurantList', 'deleteUser', 'addRestaurent', 'pendingRetaurant', 'approveResto', 'editResto', 'editRestoProcess', 'deleteResto', 'lookupResto', 'restoEarnings'];
    public $orderAllowedRoutes = ['dashboard', 'login', 'logout', 'notfound', 'resetPassword', 'resetPasswordProcess', 'changePaidStatus', 'orderPaid', 'viewOrder', 'customerOrder'];
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
        if (!$request->session()->exists('admin_data')  || !Auth::check()) {
            // user value cannot be found in session
            Session::flash('message', 'Please Login!');
            return redirect('/adminfimihub/login');
        } else {
            if ($request->session()->exists('restaurent') || $request->session()->exists('user')) {
                Auth::logout();
                Session::flush();
                Session::flash('message', 'Service Violation (Please Try Again)!');
                return redirect('adminfimihub/login');
            } else {
                if ($request->session()->exists('admin_data')) {
                    if ($user->user_type != 1) {
                        Session::flash('message', 'User Type Invalid !');
                        return redirect('/adminfimihub/login');
                    } elseif ($user->visibility == 2) {
                        Session::flash('message', 'Account Deleted !');
                        return redirect('/adminfimihub/login');;
                    } elseif ($user->visibility == 1) {
                        Session::flash('message', 'Account Pending !');
                        return redirect('/adminfimihub/login');;
                    } elseif ($user->visibility == 3) {
                        Session::flash('message', 'Account Rejeted or Revoked !');
                        return redirect('/adminfimihub/login');;
                    }
                    // 1-rider,2-Resto,3-order
                    $route_segment = request()->segment(2);
                    $flag = 0;
                    // dd($route_segment);
                    if($user->role != NULL){
                        if(in_array(1,$user->role) && in_array($route_segment,$this->riderAllowedRoutes)){
                            $flag = 1;
                        }
                        if(in_array(2,$user->role) && in_array($route_segment,$this->restoAllowedRoutes)){
                            $flag = 1;

                        }
                        if(in_array(3,$user->role) && in_array($route_segment,$this->orderAllowedRoutes)){
                            $flag = 1;
                        }
                        if($flag != 1){
                            return redirect('adminfimihub/notfound');
                        }
                    }
                    $orders = new order();
                    $order_data = $orders->allOrderPaginationData()
                        ->whereDate('created_at', date('Y-m-d'))
                        ->with('userAddress.userDetails', 'restaurentDetails.restroAddress', 'cart.cartItems.menuItems', 'orderEvent.reason');
                    $order_data = $order_data->limit(10)->get();

                    $_COOKIE['order_notification'] = $order_data;
                } else {
                    Session::flash('message', 'Please Login Again!');
                    return redirect('/adminfimihub/login');
                }
            }
        }


        return $next($request);
    }
}
