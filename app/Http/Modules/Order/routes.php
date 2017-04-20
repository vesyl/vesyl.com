<?php
Route::group(array('module' => 'Order', 'namespace' => 'Order\Controllers'), function () {

//    Route::group(['middleware' => 'auth:user'], function () {

    Route::resource('/order-ajax-handler', 'OrderController@orderAjaxHandler');
    Route::resource('/my-orders', 'OrderController@orderHistory');
    Route::get('/order-details/{orderId}', 'OrderController@orderDetails');
    Route::post('/order-details/{orderId}', 'OrderController@orderDetails');
    Route::resource('/orders/orders-datatables-handler', 'OrderController@ordersDatatablesHandler');

//    });

    Route::group(['middleware' => 'auth:buyer'], function () {

        Route::resource('/buyer/my-orders', 'OrderController@buyerOrderHistory');
        Route::get('/buyer/orders/{orderId}/details', 'OrderController@buyerOrderDetails');
        Route::post('/buyer/orders/{orderId}/details', 'OrderController@buyerOrderDetails');

    });


});