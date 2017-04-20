<?php
Route::group(array('module' => 'Payment', 'namespace' => 'Payment\Controllers'), function () {

    Route::resource('/paypal-payment', 'PaymentController@payPayment');//not required
    Route::resource('/expressCallback', 'PaymentController@expressCallBack');
    Route::post('/paymentError', 'PaymentController@paymentError');

//    Route::group(['middleware' => 'auth:user'], function () {

    Route::resource('/checkout', 'PaymentController@checkOutDetails');
    Route::resource("/paypal-success", "PaymentController@paypalResponse");
    Route::resource("/paypal-cancel", "PaymentController@paypalCancel");
    Route::resource("/paypal-ipn", "PaymentController@paypalIPNListener");

//    });

    Route::resource('/paypal-payment-sandbox', 'PaymentController@paypalPayment');

//    Route::group(['middleware' => 'auth:buyer'], function () {
    Route::resource('/buyer/checkout', 'PaymentController@checkOutDetails');
//    });

});