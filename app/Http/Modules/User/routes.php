<?php


$urlmain = $_SERVER['HTTP_HOST'];
$domain = env("site_domain");
$shopname = "";

if ($urlmain != $domain) {

    $shopurl = explode("." . $domain, $urlmain);
    $shopname = $shopurl[0];
}
//Route::group(['domain' => "$shopname.localvesyl.com"], function () {

    Route::group(array('module' => 'User', 'namespace' => 'User\Controllers'), function () {

        Route::group(['middleware' => 'auth:user'], function () {

            Route::resource('/profile-setting', 'ProfileController@profileSetting');
            Route::resource('/profile-ajax-handler', 'ProfileController@profileAjaxHandler');
//    Route::resource('/home-ajax-handler', 'HomeController@homeAjaxHandler');
//    Route::resource('/logout', 'HomeController@logout');

        });


        Route::get('image/{filename}', function ($filename) {

            $filePath = explode("_", $filename);
            $folderPath = '';
            switch ($filePath[0]) {
                case 'product'://product_14_0_1456562271.jpg
                    $folderPath = $filePath[0] . '/' . $filePath[1];

                    break;
                default://folderName_id_timeStamp.jpg
                    $folderPath = $filePath[0];
                    break;
            }
            $path = storage_path() . '/uploads/' . $folderPath . '/' . $filename;

            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        });


    });

//});

