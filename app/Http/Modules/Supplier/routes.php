<?php
Route::get('/supplier', function () {
    return redirect('supplier/login');
});

Route::group(['middleware' => ['guest']], function () {

    /* // Authentication routes...
    //    Route::get('/logout', 'Auth\AuthController@getLogout');
        Route::resource('/login', 'Auth\AuthController@login');

    // Registration routes...
        Route::get('/register', 'Auth\AuthController@getRegister');
        Route::post('/register', 'Auth\AuthController@postRegister');
    */

});

Route::group(array('module' => 'Supplier', 'namespace' => 'Supplier\Controllers'), function () {
//    \DB::listen(function ($query, $bindings, $time, $connection) {
//        $fullQuery = vsprintf(str_replace(array('%', '?'), array('%%', '%s'), $query), $bindings);
//        $result = $connection . ' (' . $time . '): ' . $fullQuery;
//        dump($result);
//    });

    Route::resource('/supplier/register', 'SupplierController@register');
    Route::post('/supplier/userAjaxHandler', 'SupplierController@userAjaxHandler');
    Route::post('/supplier/shopNameAjaxHandler', 'SupplierController@shopNameAjaxHandler');

    $urlmain = $_SERVER['HTTP_HOST'];
    $domain = env("site_domain");
    $shopname = "";

    if ($urlmain != $domain) {

        $shopurl = explode("." . $domain, $urlmain);
        $shopname = $shopurl[0];
    }
    Route::group(['subdomain' => "$shopname.localvesyl.com"], function () {
        Route::group(['middleware' => ['subdomain']], function () {


            Route::resource('/supplier/login', 'SupplierController@login');
            Route::resource('/supplier/control-panel', 'SettingController@controlPanel');
            Route::get('/supplier/manage-settings/{section_id}', 'SettingController@manageSettings');
            Route::post('/supplier/manage-settings/{section_id}', 'SettingController@manageSettings');
            Route::post('/supplier/settings-ajax-handler', 'SettingController@settingsAjaxHandler');


            Route::resource('/supplier/manage-currencies', 'CurrencyController@manageCurrencies');
            Route::resource('/supplier/add-currency', 'CurrencyController@addCurrency');
            Route::get('/supplier/edit-currency/{currencyId}', 'CurrencyController@editCurrency');
            Route::post('/supplier/edit-currency/{currencyId}', 'CurrencyController@editCurrency');
            Route::post('/supplier/currency-ajax-handler', 'CurrencyController@currencyAjaxHandler');

            Route::resource('/supplier/resetPassword', 'SupplierController@forgotpassword');
            Route::resource('/supplier/submit', 'SupplierController@submit');
            Route::resource('/supplier/changepassword', 'SupplierController@changepassword');

            Route::resource('/supplier/logout', 'SupplierController@logout');

            Route::resource('/supplier/supplierdetails', 'SupplierController@supplierDetails');



//IF  YOU NEED TO USE GET POST, USE THIS FORMAT AS IN BELOW BLOCK COMMENT
            /*Route::get('supplier/dashboard', function () {
                return view("Admin/Views/dashboard");
            }); */

            Route::group(['middleware' => 'auth:supplier'], function () {
//        Supplier Controller
                Route::resource('/supplier/dashboard', 'SupplierController@dashboard');
                Route::resource('/supplier/profile', 'SupplierController@profile');

                Route::resource('/supplier/ajaxHandler', 'SupplierController@ajaxHandler');
                Route::resource('/supplier/addNewShop', 'SupplierController@addNewShop');
                Route::resource('/supplier/shopList', 'SupplierController@shopList');
                Route::get('/supplier/editShop/{shop_id}', 'SupplierController@editShop');
                Route::post('/supplier/editShop/{shop_id}', 'SupplierController@editShop');

//        Product Controller
                Route::resource('/supplier/add-product', 'ProductController@addProduct');
                Route::get('/supplier/edit-product/{productId}', 'ProductController@editProduct');//Akash M. Pai
                Route::post('/supplier/edit-product/{productId}', 'ProductController@editProduct');//Akash M. Pai
                Route::resource('/supplier/add-product-csv', 'ProductController@addProductCSV');//Akash M. Pai

//        Category Controller
                Route::resource('/supplier/manage-categories', 'CategoryController@manageCategories');
                Route::resource('/supplier/add-category', 'CategoryController@addCategory');
                Route::get('/supplier/edit-category/{id}', 'CategoryController@editCategory');
                Route::post('/supplier/edit-category/{id}', 'CategoryController@editCategory');

                Route::resource('/supplier/manage-options', 'OptionController@manageOptions');
                Route::resource('/supplier/add-option', 'OptionController@addOption');
                Route::get('/supplier/edit-option/{id}', 'OptionController@editOption');
                Route::post('/supplier/edit-option/{id}', 'OptionController@editOption');
                Route::post('/supplier/option-ajax-handler', 'OptionController@optionAjaxHandler');


                Route::resource('/supplier/add-flashsale', 'FlashsaleController@addFlashsale');
                Route::resource('/supplier/manage-flashsale', 'FlashsaleController@manageFlashsale');
                Route::get('/supplier/edit-flashsale/{fid}', 'FlashsaleController@editFlashsale');
                Route::post('/supplier/edit-flashsale/{fid}', 'FlashsaleController@editFlashsale');
                Route::resource('/supplier/flashsale-ajax-handler', 'FlashsaleController@flashsaleAjaxHandler');

                Route::resource('/supplier/add-new-campaign', 'DailyspecialController@addDailyspecial');
                Route::resource('/supplier/manage-campaign', 'DailyspecialController@manageDailyspecial');
                Route::get('/supplier/edit-campaign/{did}', 'DailyspecialController@editDailyspecial');
                Route::post('/supplier/edit-campaign/{did}', 'DailyspecialController@editDailyspecial');
                Route::resource('/supplier/dailyspecial-ajax-handler', 'DailyspecialController@dailyspecialAjaxHandler');
                Route::post('/supplier/campaign-list-ajax-handler/{method}', 'DailyspecialController@campaignListAjaxHandler');


                Route::resource('/supplier/add-wholesale', 'WholesaleController@addWholesale');
                Route::resource('/supplier/manage-wholesale', 'WholesaleController@manageWholesale');
                Route::get('/supplier/edit-wholesale/{wid}', 'WholesaleController@editWholesale');
                Route::post('/supplier/edit-wholesale/{wid}', 'WholesaleController@editWholesale');
                Route::post('/supplier/wholesale-ajax-handler/{method}', 'WholesaleController@wholesaleAjaxHandler');


//        --------------------------------Product-feautre----------------------------------

                Route::resource('/supplier/manage-features', 'FeaturesController@manageFeatures');
                Route::resource('/supplier/add-feature-group', 'FeaturesController@addFeatureGroup');
                Route::get('/supplier/edit-feature-group/{featureId}', 'FeaturesController@editFeatureGroup');
                Route::post('/supplier/edit-feature-group/{featureId}', 'FeaturesController@editFeatureGroup');
//        Route::resource('/admin/edit-feature-group/{featureId}', 'FeaturesController@editFeatureGroup');
                Route::resource('/supplier/add-feature', 'FeaturesController@addFeature');
                Route::get('/supplier/edit-feature/{featureId}', 'FeaturesController@editFeature');
                Route::post('/supplier/edit-feature/{featureId}', 'FeaturesController@editFeature');

//        -------------------------------------Filter module--------------------------


                Route::resource('/supplier/add-new-filtergroup', 'FilterController@addNewFiltergroup');
                Route::resource('/supplier/manage-filtergroup', 'FilterController@manageFilterGroup');
                Route::resource('/supplier/add-filter-variant', 'FilterController@addFilterVariant');

                Route::resource('/supplier/filter-ajax-handler', 'FilterController@filterAjaxHandler');

                Route::get('/supplier/edit-filtergroup/{id}', 'FilterController@editFilterGroup');
                Route::post('/supplier/edit-filtergroup/{id}', 'FilterController@editFilterGroup');


//     =================================Newsletter================================================
                Route::resource('/supplier/add-newsletter', 'NewsletterController@addNewsletter');
                Route::resource('/supplier/send-newsletter', 'NewsletterController@sendNewsletter');
                Route::resource('/supplier/subscriber-details', 'NewsletterController@subscriberDetails');
                Route::resource('/supplier/newsletter-ajax-handler', 'NewsletterController@newsletterAjaxHandler');


//===========================================product======================================================


                Route::post('/supplier/product-ajax-handler', 'ProductController@productAjaxHandler');
                Route::post('/supplier/product-list-ajax-handler/{method}', 'ProductController@productListAjaxHandler');
                Route::resource('/supplier/manage-products', 'ProductController@manageProducts');//in these four functions in controlle rcheck which methods are called from models
                Route::resource('/supplier/deleted-products', 'ProductController@deletedProducts');
                Route::resource('/supplier/pending-products', 'ProductController@pendingProducts');
                Route::resource('/supplier/rejected-products', 'ProductController@rejectedProducts');

//        -------------------------------Reviews-----------------------------------------


                Route::resource('/supplier/approvedreviews', 'ReviewsController@approvedreviews');
                Route::resource('/supplier/pendingreviews', 'ReviewsController@pendingreviews');
                Route::resource('/supplier/rejectedreviews', 'ReviewsController@rejectedreviews');
                Route::resource('/supplier/reviews', 'ReviewsController@reviews');

                Route::get('image/{filename}', function ($filename) {
//            return $filename;

//            return displayImageFromStoragePath($filename);
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

                Route::get('supplier/lang/{locale}', [
                    'as' => 'lang',
                    'uses' => 'SupplierController@changeLang'
                ]);
            });
        });
    });
});