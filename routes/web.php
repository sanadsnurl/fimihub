<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Model\order;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/updateorder', function() {
    $response =  DB::statement('update orders set order_status = 3');
    return response()->json( $response);
});

Route::group(['middleware' => ['cors', 'json.response']], function () {

//========================================== Customer Routes ===================================================

    // Customer index Page
    Route::get('/', 'Web\Customer\CmsController@indexHandShake');

    // Customer Login
    Route::get('/login', function () {
        return view('customer.auth.login');
    });
    // Customer Register
    Route::get('/register', function () {
        return view('customer.auth.register');
    });
    // About Us Page
    Route::get('/aboutUsPage', 'Web\Customer\CmsController@indexAboutUsPage');
    // Card Policy Page
    Route::get('/cardPolicy', 'Web\Customer\CmsController@indexCardPolicy');
    // T & C Page
    Route::get('/T&C', 'Web\Customer\CmsController@indexTandC');
    // Merchant Q n A
    Route::get('/mechantQnA', 'Web\Customer\CmsController@indexMerchantQnA');
    // Partner with us page
    Route::get('/partnerWithUs', function () {
        return view('customer.auth.partnerRegister');
    });
    // Test Payment page
    Route::get('/testPayment', function () {
        return view('customer.testPaymentPage');
    });
    // Partner with us Process
    Route::post('/partnerRegisterProcess', 'Web\Customer\DashboardController@partnerRegister');
    // Customer Login Process
    Route::post('/loginProcess', 'Web\Customer\LoginRegisterController@login');
    // Customer Register Process
    Route::post('/registerProcess', 'Web\Customer\LoginRegisterController@register');
    // Resend OTP
    Route::get('/resendOTP', 'Web\Customer\LoginRegisterController@resendOtp');
    // Signup Otp Verification
    Route::post('/verifyOtp', 'Web\Customer\LoginRegisterController@verifyOtp');
    // Send OTP
    Route::post('/getOTP', 'Web\Customer\LoginRegisterController@sendOtp');
    // Signup Otp Verification
    Route::post('/verifyAccount', 'Web\Customer\LoginRegisterController@verifyForgetPasswordOtp');
    // Customer Forget Password Process
    Route::post('/forgetPasswordProcess', 'Web\Customer\LoginRegisterController@forgetPassword');
    // Signin Otp Verification
    Route::post('/verifyOtpLogin', 'Web\Customer\LoginRegisterController@verifyOtpLogin');
    // Customer Logout
    Route::get('logout', 'Web\Customer\LoginRegisterController@logout');
    // Customer Subscribed
    Route::post('subscribeProcess', 'Web\Customer\DashboardController@subscribe');

    Route::get('/autocomplete', 'DashboardController@index');
    Route::post('/autocomplete/fetch', 'DashboardController@fetch')->name('autocomplete.fetch');
    //========================================== Session Customer Auth Routes ===================================================

    Route::group(['middleware' => 'customerauth'], function () {

        //Customer dashboard
        Route::get('/home', 'Web\Customer\DashboardController@index');
        //Cart Page
        Route::get('/cart', 'Web\Customer\CartController@index');
        // Save Addresss
        Route::post('saveAddress', 'Web\Customer\AddressController@insertAddress');
        // Save Addresss
        Route::get('myAccount', 'Web\Customer\UserController@index');
        //Update Profile
        Route::post('updateProfile', 'Web\Customer\UserController@updateProfile');
        //Change password Page
        Route::get('changePassword', 'Web\Customer\UserController@getChangePasswordPage');
        //Change password Process
        Route::post('changePassword', 'Web\Customer\UserController@changePassword');
        //Contact Us Page
        Route::get('contactUs', 'Web\Customer\UserController@getContactUsPage');
        //Contact Us Process
        Route::post('contactUs', 'Web\Customer\UserController@contactUs');
        //Saved Address Page
        Route::get('saveaddress', 'Web\Customer\UserController@getSaveAddressPage');
        //My Order Page
        Route::get('myOrder', 'Web\Customer\UserController@getMyOrderPage');
        //Terms and condition Page
        Route::get('termsCondition', 'Web\Customer\UserController@getTermsConditionPage');
        //FAQ Page
        Route::get('FAQ', 'Web\Customer\UserController@getFaqPage');
        //Legal Information Page
        Route::get('legalInformation', 'Web\Customer\UserController@getLegalInformationPage');
        //AboutUs Page
        Route::get('aboutUs', 'Web\Customer\UserController@getAboutUsPage');
        //AboutUs Page
        Route::get('restaurentDetails', 'Web\Customer\RestaurentController@getRestaurentDetails');
        //Add Menu Item To Cart
        Route::get('addMenuItem', 'Web\Customer\CartController@addToCart');
        //Subtract Menu Item To Cart
        Route::get('subtractMenuItem', 'Web\Customer\CartController@removeFromCart');
        //Add Custom Menu Item To Cart
        Route::get('addCustomMenuItem', 'Web\Customer\CartController@addToCartCustom');
        //Subtract Custom Menu Item To Cart
        Route::get('subtractCustomMenuItem', 'Web\Customer\CartController@removeFromCartCustom');
        //Add default address
        Route::get('addDefaultAddress', 'Web\Customer\AddressController@addToDefault');
        //Delete address
        Route::get('deleteAddress', 'Web\Customer\AddressController@deleteAddress');
        //Checkout Page -- Payment Page
        Route::get('checkoutPage', 'Web\Customer\OrderController@getPaymentPage');
        //Add Payment Method
        Route::post('addPaymentMethod', 'Web\Customer\OrderController@addPaymentType');
        //Track Order
        Route::get('trackOrder', 'Web\Customer\OrderController@trackOrder');
        //feedback
        Route::post('feedback', 'Web\Customer\OrderController@postFeedback');

    });


//========================================== Restaurent Routes ===================================================

    //Restaurent Login
    Route::get('/Restaurent/login', function () {
        return view('restaurent.auth.login');
    });
    // Restaurent Login Process
    Route::post('Restaurent/login', 'Web\Restaurent\LoginRegisterController@login');
    // Restaurent Logout
    Route::get('Restaurent/logout', 'Web\Restaurent\LoginRegisterController@logout');
    // Signin Otp Verification
    Route::post('Restaurent/verifyOtp', 'Web\Restaurent\LoginRegisterController@verifyOtp');
    // Resend Otp
    Route::get('/resendOtp', 'Web\Restaurent\LoginRegisterController@resendOtp');

    //========================================== Session RestaurentAuth Routes ===================================================

    Route::group(['middleware' => 'restaurentauth', 'prefix'=>'Restaurent'],function () {

        // Restaurent Dasboard
        Route::get('dashboard', 'Web\Restaurent\DashboardController@dashboardDetails');
        // Restaurent Details
        Route::get('myDetails', 'Web\Restaurent\RestaurentController@accountDetails');
        // Restaurent Details update or insert
        Route::post('addRestaurentDetails', 'Web\Restaurent\RestaurentController@addRestaurentDetails');
        // Menu Category
        Route::get('menuCategory', 'Web\Restaurent\RestaurentController@categoryDetails');
        // Menu Category update or insert
        Route::post('addCategory', 'Web\Restaurent\RestaurentController@addCategoryProcess');
        // Menu List
        Route::get('menuList', 'Web\Restaurent\RestaurentController@getMenuList');
        // Menu Category update or insert
        Route::post('addMenu', 'Web\Restaurent\RestaurentController@menuListProcess');
        // Customer Order List
        Route::get('customerOrder', 'Web\Restaurent\OrderController@getCustomerOrderList');
        //Accept Customer Order
        Route::get('acceptOrder', 'Web\Restaurent\OrderController@acceptOrder');
        //Reject Customer Order
        Route::get('rejectOrder', 'Web\Restaurent\OrderController@rejectOrder');
        //Packed Customer Order
        Route::get('packedOrder', 'Web\Restaurent\OrderController@packedOrder');
        //View Customer Order
        Route::get('viewOrder', 'Web\Restaurent\OrderController@viewOrder');
        //Delete Dish
        Route::get('deleteDish', 'Web\Restaurent\RestaurentController@deleteMenuList');
        //Edit Dish
        Route::get('editDish', 'Web\Restaurent\RestaurentController@editMenu');
        //Edit Dish Prcoess
        Route::post('editDishProcess', 'Web\Restaurent\RestaurentController@editMenuProcess');
        // Add ON List
        Route::get('addOn', 'Web\Restaurent\RestaurentController@getAddOn');
        // Add ON insert
        Route::post('createAddOn', 'Web\Restaurent\RestaurentController@addOnProcess');
        //Delete Add ON
        Route::get('deleteAddOn', 'Web\Restaurent\RestaurentController@deleteCustomization');
        //Edit Add ON
        Route::get('editAddOn', 'Web\Restaurent\RestaurentController@editCustomization');

    });












   //========================================== Admin Routes ===================================================

    //Admin Login
    Route::get('/adminfimihub/login', function () {
        return view('admin.auth.login');
    });
    // Admin Login Process
    Route::post('adminfimihub/login', 'Web\Admin\LoginRegisterController@login');
    // Admin Logout
    Route::get('adminfimihub/logout', 'Web\Admin\LoginRegisterController@logout');

    //========================================== Session AdminAuth Routes ===================================================

    Route::group(['middleware' => 'adminauth', 'prefix'=>'adminfimihub'], function () {

         // Admin Dasboard
        Route::get('dashboard', 'Web\Admin\DashboardController@dashboardDetails');

        // Admin Restaurant List
        Route::get('retaurantList', 'Web\Admin\RestaurentController@RestaurentListDetails');
        // Add Restaurent page
        Route::get('addRestaurent', 'Web\Admin\RestaurentController@addRestaurent');
        // Add Restaurent page Process
        Route::post('addRestaurent', 'Web\Admin\RestaurentController@addRestaurentProcess');
        // Menu Category
        Route::get('menuCategory', 'Web\Admin\RestaurentController@categoryDetails');
        // Menu Category update or insert
        Route::post('addCategory', 'Web\Admin\RestaurentController@addCategoryProcess');
        // Service List
        Route::get('serviceList', 'Web\Admin\ServiceController@serviceListDetails');
        // Edit Service
        Route::get('editService', 'Web\Admin\ServiceController@editService');
        // Edit Service Process
        Route::post('editServiceProcess', 'Web\Admin\ServiceController@editServiceProcess');
        // Pending Restaurent Partner Requests
        Route::get('pendingRetaurant', 'Web\Admin\RestaurentController@pendingRetaurant');
        // Approve Pending Restaurent Partner Requests
        Route::get('approveResto', 'Web\Admin\RestaurentController@approveRetaurant');
        // Edit Restaurant Details
        Route::get('editResto', 'Web\Admin\RestaurentController@editRestaurant');
        // Edit Restaurant Details Process
        Route::post('editRestoProcess', 'Web\Admin\RestaurentController@editRestaurantProcess');
        //Delete Restaurant
        Route::get('deleteResto', 'Web\Admin\RestaurentController@deleteRestaurent');
        // Admin Rider List
        Route::get('riderList', 'Web\Admin\RiderController@RiderListDetails');
        // Get FAQ's
        Route::get('getFaq', 'Web\Admin\DashboardController@getFaqPage');
        // Add FAQ's
        Route::post('addfaqs', 'Web\Admin\DashboardController@addFaqPage');
        // Delete Cms
        Route::get('deleteCms', 'Web\Admin\DashboardController@deleteCms');
        // User List
        Route::get('userList', 'Web\Admin\UserManageController@UserListDetails');
        // Delete User
        Route::get('deleteUser', 'Web\Admin\UserManageController@deleteUser');
        // Get T&C
        Route::get('tnc', 'Web\Admin\DashboardController@getTncPage');
        // Add T&C
        Route::post('addTnc', 'Web\Admin\DashboardController@addTncPage');
        // Get About Us
        Route::get('aboutUs', 'Web\Admin\DashboardController@getAboutUsPage');
        // Add About Us
        Route::post('addAboutUs', 'Web\Admin\DashboardController@addAboutUsPage');
        // Get Legal Information
        Route::get('legalInfo', 'Web\Admin\DashboardController@getLegalInfoPage');
        // Add Legal Information
        Route::post('addlegalInfo', 'Web\Admin\DashboardController@addLegalInfoPage');
        // Get Slider
        Route::get('slider', 'Web\Admin\CmsController@getSliderPage');
        // Add Slider
        Route::post('addSlider', 'Web\Admin\CmsController@addSliderPage');
        //Delete Slider Cms
        Route::get('deleteSliderCms', 'Web\Admin\CmsController@deleteSliderCms');
        //All Customer Order List
        Route::get('customerOrder', 'Web\Admin\OrderController@getCustomerOrderList');
        //View Customer Order
        Route::get('viewOrder', 'Web\Admin\OrderController@viewOrder');
        //View Order Set Txn Page
        Route::get('orderPaid', 'Web\Admin\OrderController@viewOrderPaid');
        //Set Txn Process
        Route::post('changePaidStatus', 'Web\Admin\OrderController@orderPaidProcess');
        // Pending Restaurent Partner Requests
        Route::get('pendingRider', 'Web\Admin\RiderController@pendingRider');
        // Approve Pending Restaurent Partner Requests
        Route::get('approveRider', 'Web\Admin\RiderController@approveRider');
        //Delete Rider
        Route::get('deleteRider', 'Web\Admin\RiderController@deleteRider');
    });

});

