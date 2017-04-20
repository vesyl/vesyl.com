<?php


Route::group(array('module' => 'Campaign', 'namespace' => 'Campaign\Controllers'), function () {
//    Route::group(array('prefix' => 'user'), function () {

    Route::get('/product-details/{pid}/{pname}', 'ProductController@productDetails');
    Route::put('/product-details/{pid}/{pname}', 'ProductController@productDetails');

//    Route::get('/flashsale-details/{campaign_type}/{flashid}','FlashsaleController@flashsaleDetails');
//    Route::post('/flashsale-details/{campaign_type}/{flashid}','FlashsaleController@flashsaleDetails');
    Route::get('/flashsale-details', 'FlashsaleController@flashsaleDetails');
    Route::post('/flashsale-details', 'FlashsaleController@flashsaleDetails');
    Route::resource('/flashsale-ajax-handler', 'FlashsaleController@flashsaleAjaxHandler');
    Route::resource('/dailyspecial-details', 'DailyspecialController@dailyspecialDetails');
    Route::resource('/product-ajax-handler', 'ProductController@productAjaxHandler');
    Route::resource('/user/product-ajax-handler', 'ProductController@productAjaxHandler');
    Route::resource('/users/product-ajax-handler', 'ProductController@productAjaxHandler');
    Route::resource('/product-filter', 'ProductController@productfilter');

//    Route::group(['middleware' => 'auth:admin'], function () {
//
//        Route::resource('/admin/flashsale-details','FlashSaleController@flashsaleDetailsAdmin');
//        Route::resource('/admin/daily-special','FlashSaleController@dailyspecialDetailsAdmin');
//
//    });
    Route::group(['middleware' => 'auth:buyer'], function () {

        Route::resource('/buyer/wholesale-list', 'WholesaleController@wholesaleList');
        Route::resource('/buyer/wholesale/{campaignId}/products', 'WholesaleController@wholesaleProductsList');
        Route::resource('/buyer/product/{productId}/details', 'WholesaleController@productDetails');

    });

});
