<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$urlmain = $_SERVER['HTTP_HOST'];
$domain = env("apisite_domain");
$shopname = "";

if ($urlmain != $domain) {

    $shopurl = explode("." . $domain, $urlmain);
    $shopname = $shopurl[0];

}
//dd($shopname);
//Route::group(['domain' => "$shopname.localvesylapi.com"], function () {


    Route::get('/', function () {
        return view('welcome');
    });


    Route::resource('/product-details', 'Campaign\ProductController@productdetails');
    Route::resource('/flashsale-details', 'Campaign\FlashsaleController@flashsaleDetails');
    Route::resource('/campaign-details', 'Campaign\FlashsaleController@campaignDetails');
    Route::resource('/flashsale-ajax-handler', 'Campaign\FlashsaleController@flashsaleAjaxHandler');
    Route::resource('/dailyspecial-details', 'Campaign\DailyspecialController@dailyspecialdetails');
    Route::resource('/shop-details', 'Campaign\ShopController@shopdetails');
    Route::resource('/campaign/product-ajax-handler', 'Campaign\ProductController@productAjaxHandler');
    Route::resource('/product-filter', 'Campaign\ProductController@productfilter');
    Route::resource('/signup', 'User\AuthenticationController@signup');
    Route::resource('/login', 'User\AuthenticationController@login');
    Route::resource('/forgot-password', 'User\AuthenticationController@forgotPassword');
    Route::resource('/profile-settings', 'User\ProfileController@profileSettings');
    Route::resource('/profile-ajax-handler', 'User\ProfileController@profileAjaxHandler');
    Route::resource('/flashsale-products', 'Campaign\FlashsaleController@flashsaleProducts');
    Route::resource('/product-popup', 'Campaign\FlashsaleController@productPopup');
    Route::resource('/product-list', 'Product\ProductController@productList');
    Route::resource('/product-ajax-handler', 'Product\ProductController@productAjaxHandler');
    Route::resource('/order-ajax-handler', 'Order\OrderController@orderAjaxHandler');
    Route::resource('/order-actions-handler', 'Order\OrderController@orderActionsHandler');
    Route::resource('/order-details', 'Order\OrderController@orderDetails');
    Route::resource('/order/insert-transaction-details', 'Order\OrderController@insertTransactionDetails');
    Route::resource('/payment-handler', 'Order\PaymentController@paymentHandler');
    Route::resource('/language-translate', 'User\ProfileController@languageTranslate');
    Route::resource('/order/giftcertificate-list', 'Order\GiftCertificateController@giftCertificateList');
    Route::resource('/order/giftcertificate-handler', 'Order\GiftCertificateController@giftcertificateHandler');
    Route::resource('/order/redeem-gift-certificate', 'Order\GiftCertificateController@redeemGiftCertificate');
    Route::resource('/user/notification-handler', 'User\NotificationController@notificationHandler');
    Route::resource('/shop-list', 'Product\ShopController@shopList');
    Route::resource('/shop-detail', 'Product\ShopController@shopDetail');

    Route::get('image/{filename}', function ($filename) {
        $filePath = explode("_", $filename);
        $folderPath = '';
        switch ($filePath[0]) {
            case 'profileavatar':
                $folderPath = $filePath[0];
                break;

            default:
                unset($filePath[count($filePath) - 1]);
                $folderPath = implode('/', array_map(function ($value) {
                    return $value;
                }, $filePath));
                break;
        }
        $path = storage_path() . '/uploads/' . $folderPath . '/' . $filename;
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    });

    Route::get('get-setting/{settingObject}', function ($settingObject) {

        return getSetting($settingObject);

    });


    Route::resource('/process-checkout', 'Order\PaymentController@processCheckout');

//});
