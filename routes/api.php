<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Fallback Route For All Invalid Routes


Route::group(['middleware' => ['cors', 'json.response']], function () {


    //Rider Registration
    Route::post('/register' , 'Api\LoginRegisterController@register');
    //Rider Login
    Route::post('/login' , 'Api\LoginRegisterController@login');
    //Generate And Send OTP
    Route::post('/SendOtp', 'Api\OtpManagerController@OtpGeneration');
    //Verify OTP
    Route::post('/VerifyOtp', 'Api\OtpManagerController@OtpVerification');
    //Forget password
    Route::post('/forgetPassword', 'Api\LoginRegisterController@forgetPassword');
    //CMS About us, Term And Condition, FAQ
    Route::get('/getcms/{type?}', 'Api\CmsController@getCms');


    //========================================== Bearer Api's===================================================

    Route::group(['middleware' => 'auth:api'], function(){
        //Rider Details
        Route::get('/details', 'Api\LoginRegisterController@details');
        //Rider Online Status Update
        Route::get('/dutyStatus', 'Api\LoginRegisterController@updateOnlineStatus');
        //Rider logout
        Route::get('/logout', 'Api\LoginRegisterController@logout');
        //Rider Device token updation
        Route::post('/updateDeviceToken', 'Api\LoginRegisterController@updateDeviceToken');
        //Rider change password
        Route::post('/changePassword', 'Api\LoginRegisterController@changePassword');
        //Rider Login details updation
        Route::post('/profileUpdate', 'Api\LoginRegisterController@updateLogin');
        //Rider Profile picture updation
        Route::post('/updateprofilepicture', 'Api\LoginRegisterController@updateProfilePicture');

        // Notifications
        //Get all Read and Unread notification
        Route::get('/getnotifications/{type?}', 'Api\NotificationController@getAllNotifications');
        //Get singale notification
        Route::get('/getnotificationbyid/{id}', 'Api\NotificationController@getNotificationById');
        //Mark as read notification
        Route::get('/markasread/{id?}', 'Api\NotificationController@markAsRead');
        // get reasons
        Route::get('/getreasons/{id}', 'Api\CmsController@getReasons');
        // get Service Category
        Route::get('/getservicecategory/{id?}', 'Api\ServiceCategoryController@getServiceCategory');
        //Insert User Address
        Route::post('/insertAddress', 'Api\user\AddressController@insertAddress');
        //Get User Address
        Route::get('/userAddressById', 'Api\user\AddressController@getUserAddress');
        //Make Delivery Address
        Route::get('/makeDeliveryAddress', 'Api\user\AddressController@makeDeliveryAddress');
        //Delete Address
        Route::get('/deleteAddress', 'Api\user\AddressController@deleteAddress');
        //Edit User Address
        Route::post('/editAddress', 'Api\user\AddressController@editAddress');

    });

    Route::group(['middleware' => 'auth:api', 'prefix'=>'rider'], function() {
        //Rider Details
        Route::get('/testingnotification', 'Api\Rider\OrderController@testingNotification');
        Route::get('/getorders/{id?}/{type?}', 'Api\Rider\OrderController@getOrders');
        Route::get('/getmypreviusorder/{id?}', 'Api\Rider\OrderController@getMyPreviusOrders');
        Route::get('/getactiveorder/{id?}', 'Api\Rider\OrderController@getActiveOrder');
        Route::post('/updatestatus', 'Api\Rider\OrderController@updateEventOrderStatus');
        Route::get('/getmyearning/{id?}', 'Api\Rider\MyEarningController@getMyEarning');
        Route::post('/getmyearningbyweekmonthyear', 'Api\Rider\MyEarningController@getMyEarningByWeekMonthYear');
        Route::get('/getOrderedData', 'Api\Rider\OrderController@getOrderedData');

    });
    // ...


    //========================================== User Api's===================================================

    //User Registration
    Route::post('user/register', 'Api\user\AuthController@userRegister');
    //User Login
    Route::post('user/login', 'Api\user\AuthController@login');
    //Forget password
    Route::post('user/forgetPassword', 'Api\user\AuthController@forgetPassword');

    //========================================== User Bearer Api's===================================================

    Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {
        //User Details
        Route::get('details', 'Api\user\UserAuthController@userDetails');
        //Customer dashboard
        Route::get('getRestaurantByCat', 'Api\user\RestaurentManageController@getRestaurentList');
        //Customer Menu List
        Route::get('getMenuByRestaurant', 'Api\user\RestaurentManageController@getRestaurentMenuDetails');
        //Get cart details
        Route::get('getCartDetails', 'Api\user\CartController@getCartDetails');
        //Get cart details
        Route::post('addToCart', 'Api\user\CartController@addToCart');
        //Get Payment method
        Route::get('getPaymentMethod', 'Api\user\OrderController@getPaymentMethod');
        //Make Payment
        Route::post('makeOrder', 'Api\user\OrderController@setPaymentMethod');
        //Track Order
        Route::get('trackOrder', 'Api\user\OrderController@trackOrder');
        //Get Past Order
        Route::get('getPastOrder', 'Api\user\UserController@getMyPastOrder');
        //Get Current Order
        Route::get('getCurrentOrder', 'Api\user\UserController@getMyCurrentOrder');
        //Order Feedback
        Route::post('orderFeedback', 'Api\user\OrderController@orderFeedback');
        //Update Profile
        Route::post('updateProfile', 'Api\user\UserController@updateProfile');
        //Contact Us
        Route::post('contactUs', 'Api\user\UserController@contactUs');
    });
});
