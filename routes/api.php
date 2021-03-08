<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('vendor-registrationpanel', 'HomeController@vendorregistrationpanel');
Route::post('vendor-registrationpanel', 'HomeController@vendorRegistrationpanelSubmit');

Route::post('/register', 'HomeController@register');
Route::post('/verifyOtp', 'HomeController@verifyOtp');
Route::post('/userlogin', 'HomeController@userlogin');
Route::post('/forgetPassword', 'HomeController@forgetPassword');
Route::post('/resendOtp', 'HomeController@resendOtp');
Route::post('/store', 'HomeController@store');
Route::post('/registerClient', 'HomeController@registerClient');
Route::post('/updateprofileEdit', 'HomeController@updateprofileEdit');
Route::post('/updateprofile', 'HomeController@updateprofile');
Route::post('/updatededeliveryBoyEdit', 'HomeController@updatededeliveryBoyEdit');
Route::post('/updateDeliveryBoy', 'HomeController@updateDeliveryBoy');
Route::get('/vehicleInformation', 'HomeController@vehicleInformation');
Route::get('/showBanner', 'HomeController@showBanner');
Route::get('/EcommBussiness', 'HomeController@EcommBussiness');
Route::post('/showResCategory', 'HomeController@showResCategory');
Route::get('/HotelList', 'HomeController@HotelList');
Route::post('/showHotelCategorywise', 'HomeController@showHotelCategorywise');
Route::post('/showHotelItemCategory', 'HomeController@showHotelItemCategory');
Route::post('/showHotelitemList', 'HomeController@showHotelitemList');
Route::post('/OrderDeatils', 'HomeController@OrderDeatils');
Route::post('/deliveryCharges', 'HomeController@deliveryCharges');
Route::post('/drawerorder', 'HomeController@drawerorder');
Route::post('/drawerorder_deatials', 'HomeController@drawerorder_deatials');
Route::post('/deliveryBoylogin', 'HomeController@deliveryBoylogin');
Route::post('/forgetPassdeliveryboy', 'HomeController@forgetPassdeliveryboy');
Route::post('/AssignOrder', 'HomeController@AssignOrder');
Route::post('/AssignOrder_deatials', 'HomeController@AssignOrder_deatials');
Route::post('/BillDeatils', 'HomeController@BillDeatils');
Route::post('/acceptRejectorder', 'HomeController@acceptRejectorder');
Route::post('/deliveryAssignOrder', 'HomeController@deliveryAssignOrder');
Route::post('/deliveryAssignOrder_deatials', 'HomeController@deliveryAssignOrder_deatials');
Route::post('/deliveryBoyHistory', 'HomeController@deliveryBoyHistory');
Route::post('/deliveryBoyHistory_deatials', 'HomeController@deliveryBoyHistory_deatials');
Route::get('/showPaymentMode', 'HomeController@showPaymentMode');
Route::post('/FeedbackSubmit', 'HomeController@FeedbackSubmit');

Route::get('/roundToNextHour', 'HomeController@roundToNextHour');

Route::post('/cancelOrder', 'HomeController@cancelOrder');

Route::get('/JweelerycatList', 'HomeController@JweelerycatList');
Route::post('/showJwelleryitemList', 'HomeController@showJwelleryitemList');

Route::post('/showJwelleryitemListDeatils', 'HomeController@showJwelleryitemListDeatils');

Route::post('/showJwelleryRelateditemList', 'HomeController@showJwelleryRelateditemList');
