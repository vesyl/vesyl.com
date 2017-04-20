<?php


Route::group(array('module' => 'Product', 'namespace' => 'Product\Controllers'), function () {

    Route::resource('/product-list', 'ProductController@productList');
    Route::resource('/product-ajax-handler', 'ProductController@productAjaxHandler');
    Route::resource('/shop-list', 'ShopController@shopList');
    Route::get('/shop-detail/{sid}/{sname}','ShopController@shopDetail');
    Route::put('/shop-detail/{sid}/{sname}','ShopController@shopDetail');
    Route::resource('/shop-ajax-handler', 'ShopController@shopAjaxHandler');


});

