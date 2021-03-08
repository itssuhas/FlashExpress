<?php

Route::get('/', 'CommonController@showLogin');
Route::post('/login', 'CommonController@doLogin');
Route::get('/dashboard', 'CommonController@dashboard');
Route::get('/logout', 'CommonController@getSignOutadmin');
Route::get('/change-password', 'CommonController@changePassword');
Route::post('/change-password', 'CommonController@changePasswordSubmit');
Route::get('/register-user', 'CommonController@fastwatchregisteruser');
Route::get('/register-vendor', 'CommonController@registeruserClient');
Route::get('fastwatchdeleteuser/{id}','CommonController@fastwatchdeleteuser');
Route::get('approveedUser/{id}','CommonController@approveedUser');
Route::get('approveedVendor/{id}','CommonController@approveedVendor');
Route::get('/sales_orders', 'CommonController@SaleOrders');
Route::post('/sales_orders', 'CommonController@SaleOrdersSubmit');
Route::get('/packageupdate/{id}', 'CommonController@getPackage');
Route::post('/packageupdate', 'CommonController@lowRiskupdateSubmit');
Route::get('importExport', 'MaatwebsiteDemoController@importExport');
Route::get('downloadExcel/{type}', 'MaatwebsiteDemoController@downloadExcel');
Route::post('importExcel', 'MaatwebsiteDemoController@importExcel');
Route::get('/userupdate/{id}', 'CommonController@getuser');
Route::post('/userupdate', 'CommonController@userupdateSubmit');
Route::get('/vendorupdate/{id}', 'CommonController@getvendor');
Route::post('/vendorupdate', 'CommonController@vendorupdateSubmit');
Route::get('deletevendor/{id}','CommonController@deletevendor');
Route::get('/cityupdate/{id}', 'CommonController@getCity');
Route::post('/cityupdate', 'CommonController@CityUpdateSubmit');
Route::get('/city_deatils', 'CommonController@cityDeatils');
Route::post('/city_deatils', 'CommonController@citySubmit');
Route::get('citydelete/{id}','CommonController@citydelete');
Route::get('/area_deatils', 'CommonController@areaDeatils');
Route::post('/area_deatils', 'CommonController@areaSubmit');
Route::get('areadelete/{id}','CommonController@areadelete');
Route::get('/areaupdate/{id}', 'CommonController@getArea');
Route::post('/areaupdate', 'CommonController@AreaUpdateSubmit');
Route::get('/cat_deatils', 'CommonController@categoryDeatils');
Route::post('/cat_deatils', 'CommonController@categorySubmit');
Route::get('catdelete/{id}','CommonController@catdelete');
Route::get('/categoryupdate/{id}', 'CommonController@getCategory');
Route::post('/categoryupdate', 'CommonController@CategoryUpdateSubmit');
Route::get('/subcat_deatils', 'CommonController@SubcategoryDeatils');
Route::post('/subcat_deatils', 'CommonController@SubcategorySubmit');
Route::get('subcatdelete/{id}','CommonController@subcatdelete');
Route::get('/subcategoryupdate/{id}', 'CommonController@getSubcategory');
Route::post('/subcategoryupdate', 'CommonController@SubcategoryUpdateSubmit');
Route::get('/package_deatils', 'CommonController@PackageDeatils');
Route::post('/package_deatils', 'CommonController@PackageSubmit');
Route::get('packegedelete/{id}','CommonController@packegedelete');
Route::get('/deliveryboy_deatils', 'CommonController@deliveryboyDeatils');
Route::post('/deliveryboy_deatils', 'CommonController@deliveryBoySubmit');
Route::get('deliveryBoydelete/{id}','CommonController@deliveryBoydelete');
Route::get('/deliveryboyupdate/{id}', 'CommonController@getDeliveryBoy');
Route::post('/deliveryboyupdate', 'CommonController@deliveryBoyUpdateSubmit');
Route::get('/rateupdate/{id}', 'CommonController@getRate');
Route::post('/rateupdate', 'CommonController@RateUpdateSubmit');
Route::get('/hotel_deatils', 'CommonController@hoteldeatils');
Route::post('/hotel_deatils', 'CommonController@hoteldataSubmit');
Route::get('hoteldatadelete/{id}','CommonController@hoteldatadelete');
Route::get('/admin_hoteldeatils', 'CommonController@Retailerhoteldeatils');
Route::get('/comp_deatils', 'CommonController@Computerdeatils');
Route::post('/comp_deatils', 'CommonController@compdataSubmit');
Route::get('Compdatadelete/{id}','CommonController@Compdatadelete');
Route::get('/apparel_deatils', 'CommonController@Appareldeatils');
Route::post('/apparel_deatils', 'CommonController@ApparelSubmit');
Route::get('Appareldelete/{id}','CommonController@Appareldelete');
Route::get('/ecommcat_deatils', 'CommonController@EcommcategoryDeatils');
Route::post('/ecommcat_deatils', 'CommonController@EcommcategorySubmit');
Route::get('Ecommcatdelete/{id}','CommonController@Ecommcatdelete');
Route::get('/ecommcategoryupdate/{id}', 'CommonController@getEcommCategory');
Route::post('/ecommcategoryupdate', 'CommonController@EcommCategoryUpdateSubmit');
Route::get('/hotelupdate/{id}', 'CommonController@getHotel');
Route::post('/hotelupdate', 'CommonController@HotelUpdateSubmit');
Route::get('/restocat_deatils', 'CommonController@RestocategoryDeatils');
Route::post('/restocat_deatils', 'CommonController@RestocategorySubmit');
Route::get('Restocatdelete/{id}','CommonController@Restocatdelete');
Route::get('/restocatupdate/{id}', 'CommonController@getRestocat');
Route::post('/restocatupdate', 'CommonController@RestocatUpdateSubmit');
Route::get('/banner_deatils', 'CommonController@bannerDeatils');
Route::post('/banner_deatils', 'CommonController@bannerSubmit');
Route::get('bannerdelete/{id}','CommonController@bannerdelete');
Route::get('/item_deatils', 'CommonController@itemdeatils');
Route::post('/item_deatils', 'CommonController@itemdataSubmit');
Route::get('itemdelete/{id}','CommonController@itemdelete');

Route::get('itemdeleteadmin/{id}','CommonController@itemdeleteadmin');

Route::get('/itemupdate/{id}', 'CommonController@getitem');
Route::post('/itemupdate', 'CommonController@itemupdateSubmit');
Route::get('/charges_deatils', 'CommonController@DeliveryChargesDeatils');
Route::post('/charges_deatils', 'CommonController@DeliveryChargesSubmit');
Route::get('deliveryChargesdelete/{id}','CommonController@deliveryChargesdelete');
Route::get('/admin_itemdeatils', 'CommonController@Adminitemdeatils');
Route::get('/admin_itemdeatilscomplete', 'CommonController@CompleteAdminitemdeatils');

Route::get('/adminitemupdate/{id}', 'CommonController@getAdminitem');
Route::post('/adminitemupdate', 'CommonController@adminitemupdateSubmit');
Route::get('/admin_deatils', 'CommonController@adminDeatils');
Route::post('/admin_deatils', 'CommonController@adminSubmit');
Route::get('/admindelete/{id}', 'CommonController@admindelete');
Route::get('/vendororder_deatils', 'CommonController@vendororderdeatils');
Route::get('/vendorOrderdelete/{id}', 'CommonController@vendorOrderdelete');
Route::get('/getVendorOrder/{id}', 'CommonController@getVendorOrder');
Route::post('/getVendorOrder', 'CommonController@vendororderupdateSubmit');
Route::get('/assignvendororder_deatils', 'CommonController@assignorderdeatils');

Route::get('/paymentmode_deatils', 'CommonController@PaymentModeDeatils');
Route::post('/paymentmode_deatils', 'CommonController@PaymentModeSubmit');
Route::get('PaymentModedelete/{id}','CommonController@PaymentModedelete');


Route::get('/delivery_boy_payment', 'CommonController@deliveryBoyPayment');

Route::get('/paymentdboyupdate/{id}', 'CommonController@getdeliverBoyPayment');
Route::post('/paymentdboyupdate', 'CommonController@deliveryBoyPaymentupdateSubmit');

Route::get('/completed_order', 'CommonController@completedOrder');
Route::get('/completed_vendor_order', 'CommonController@completedOrdervendor');

Route::get('/FeedbackDeatils', 'CommonController@FeedbackDeatils');
Route::get('Feedbackdelete/{id}','CommonController@Feedbackdelete');

Route::get('/vendor_payment', 'CommonController@vendorPayment');

Route::get('/paymentvendorupdate/{id}', 'CommonController@getvendorPayment');
Route::post('/paymentvendorupdate', 'CommonController@vendorPaymentupdateSubmit');

Route::get('rejectVendorOrder/{id}','CommonController@rejectVendorOrder');

Route::get('blockItem/{id}','CommonController@blockItem');
Route::get('unblockItem/{id}','CommonController@unblockItem');

Route::get('/adminorder_deatils', 'CommonController@adminorderdeatils');

Route::get('/getAdminOrder/{id}', 'CommonController@getAdminOrder');
Route::post('/getAdminOrder', 'CommonController@adminorderupdateSubmit');

Route::get('rejectAdminOrder/{id}','CommonController@rejectAdminOrder');

Route::get('/getVendorOrderItemDeatils/{id}', 'CommonController@getVendorOrderItemDeatils');


Route::get('/jewlcat_deatils', 'CommonController@JewellerycategoryDeatils');
Route::post('/jewlcat_deatils', 'CommonController@JewellerycategorySubmit');
Route::get('Jewellerydelete/{id}','CommonController@Jewellerydelete');



Route::get('/jewlcat_deatils', 'CommonController@JewellerycategoryDeatils');
Route::post('/jewlcat_deatils', 'CommonController@JewellerycategorySubmit');
Route::get('Jewellerydelete/{id}','CommonController@Jewellerydelete');


Route::get('/jewlsubcat_deatils', 'CommonController@JewellerysubcategoryDeatils');
Route::post('/jewlsubcat_deatils', 'CommonController@JewellerysubcategorySubmit');
Route::get('Jewellerysubcatdelete/{id}','CommonController@Jewellerysubcatdelete');






Route::get('/superadmin_hoteldeatils', 'CommonController@SuperAdminhoteldeatils');
Route::get('/register-adminvendor', 'CommonController@registerAdminClient');

