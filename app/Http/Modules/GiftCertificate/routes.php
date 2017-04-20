<?php
Route::group(array('module' => 'GiftCertificate', 'namespace' => 'GiftCertificate\Controllers'), function () {


    Route::resource('/giftcertificate', 'GiftCertificateController@getAdminGiftCertificates');
    Route::resource('/giftcertificate-ajax-handler', 'GiftCertificateController@giftcertificateAjaxHandler');
    Route::resource('/redeem-giftcertificate', 'GiftCertificateController@redeemGiftCertificate');


});