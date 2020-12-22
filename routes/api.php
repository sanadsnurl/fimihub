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

    });
    // ...
});
