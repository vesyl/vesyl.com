<?php
Route::get('/buyer', function () {
    return redirect('/buyer/login');
});
//Route::group(['middleware' => ['guest']], function () {
Route::group(array('module' => 'buyer', 'namespace' => 'Buyer\Controllers'), function () {
    Route::resource('/buyer/login', 'BuyerController@login');
    Route::resource('/buyer/register', 'BuyerController@register');
    Route::resource('/buyer/logout', 'BuyerController@logout');
    Route::resource('/buyer/resetPassword', 'BuyerController@forgotpassword');
    Route::resource('/buyer/submit', 'BuyerController@submit');

//    Route::group(['middleware' => 'auth:buyer'], function () {

        Route::resource('/buyer/dashboard', 'BuyerController@dashboard');
        Route::resource('/buyer/changepassword', 'BuyerController@changepassword');

        Route::resource('/buyer/profile', 'BuyerController@profile');
        Route::post('/buyer/userAjaxHandler', 'BuyerController@userAjaxHandler');
        Route::resource('/buyer/buyerdetails', 'BuyerController@buyerDetails');
        Route::resource('/buyer/ajaxHandler', 'BuyerController@ajaxHandler');
//    });
});
//});