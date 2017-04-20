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

//Route::get('/', function () {
//    return view('welcome');
//});
$urlmain = $_SERVER['HTTP_HOST'];
$domain = env("site_domain");
$shopname = "";

if ($urlmain != $domain) {

    $shopurl = explode("." . $domain, $urlmain);
    $shopname = $shopurl[0];
}
Route::group(['asddomain' => "$shopname.localvesyl.com"], function () {
    Route::group(['middleware' => ['subdomain']], function () {
        Route::group(['middleware' => ['guest']], function () {

            Route::get('user/lang/{locale}', function ($locale) {

                Session::put('user_locale', $locale);
//        return $locale;
                return Redirect::to(URL::previous());
            });
//    Route::get('user/lang/{locale}', [
//        'as'=>'lang',
//        'uses'=>'HomeController@changeLang'
//    ]);

            /* // Authentication routes...
            //    Route::get('/logout', 'Auth\AuthController@getLogout');
                Route::resource('/login', 'Auth\AuthController@login');

            // Registration routes...
                Route::get('/register', 'Auth\AuthController@getRegister');
                Route::post('/register', 'Auth\AuthController@postRegister');
            */

        });


//Route::resource('/admin/dashboard','Admin\AdminController@dashboard');
//Route::get('/admin/dashboard', function () {
//    return view('admin.dashboard');
//});

//Route::resource('/admin/dashboard', 'Admin\AdminController@adminlogin');
//
////Route group for access to authenticated users only.
//Route::group(['middleware' => ['auth']], function () {
//    //Route group for access to only admin
//    Route::group(['middleware' => 'admin'], function () {
//
//    });
//
//    //Route group for access to only admin
//    Route::group(['middleware' => 'user'], function () {
//
//    });
//
//    //Route group for access to only admin
//    Route::group(['middleware' => 'merchant'], function () {
//
//    });
//
//});
//
//
////Route group for access to only admin
//Route::group(['middleware' => 'manager'], function () {
//
//});


        Route::group(array('module' => 'Home', 'namespace' => 'Home\Controllers'), function () {


            Route::get('/', 'HomeController@home');
            Route::get('/pages/{pagename?}', 'HomeController@pages');
            Route::post('/pages/{pagename?}', 'HomeController@pages');

            Route::resource('/home-ajax-handler', 'HomeController@homeAjaxHandler');
            Route::resource('/logout', 'HomeController@logout');
            Route::resource('/notification-ajax-handler', 'NotificationController@notificationAjaxHandler');


        });
    });
});

