<?php
$productListSetting = getSetting('available_product_list_sortings');
$productListSetting = $productListSetting ? $productListSetting : '';
$prod = str_replace('#M#', '', $productListSetting);
$setting = array_map(function ($val) {
    return rtrim($val, "=Y");
}, explode("&", $prod));

?>
@extends('Buyer/Layouts/buyerlayout')

@section('title')
    @if($wholesaleDetails['code'] == 200 && !empty($wholesaleDetails['data'])) Products in {{$wholesaleDetails['data']['campaign_name']}} @endif
@endsection

@section('pageheadcontent')
    <link href="/assets/buyer/css/style.css" rel="stylesheet" type="text/css"/>
    <style>
        ._hidden_wrapper {
            background-color: rgba(225, 225, 225, 0.7);
        }

        .ctitle {
            border: 2px solid #a1a1a1;
        }

        .mrgn_btm_ovrhid {
            border: 2px solid #a1a1a1;
        }
    </style>
@endsection

@section('content')
    {{--<div class="container-fluid hero_bg" style="padding: 0">--}}
    {{--<img style="width: 100%; height:600px" src="{{$flashsaledetails['campaign_banner']}}">--}}
    {{--</div>--}}
    {{--<div class="container salecatelog ">--}}
    {{--<div class="row">--}}
    {{--@if(isset($flashsaledetails) && (!empty($flashsaledetails)))--}}
    {{--<div class="col-md-12">--}}
    {{--<div class="sorting_options clearfix">--}}
    {{--<div class="count_tovar_items">--}}
    {{--<p>Total</p>--}}
    {{--<span id="total_filtered_products"></span><span>&nbsp;Products</span>--}}
    {{--</div>--}}
    {{--<div class="product_sort" style="text-align: right">--}}
    {{--<p>SORT BY</p>--}}
    {{--<select class="changeables" id="selectsortby">--}}
    {{--<option selected value="">Sort By</option>--}}
    {{--@foreach($setting as $key => $val)--}}
    {{--<option value="{{$val}}">{{$val}}</option>--}}
    {{--@endforeach--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row shop_block" id="productsdiv">--}}
    {{--</div>--}}
    {{--<div class="col-md-3 ctitle">--}}
    {{--<div class="categorie images">--}}
    {{--<img src="assets/images/categories.png" class="img-responsive middle">--}}
    {{--</div>--}}
    {{--@if(isset($flashsaledetails['filter_info']) && !empty($flashsaledetails['filter_info']))--}}
    {{--@foreach($flashsaledetails['filter_info'] as $filterKey => $filterVal)--}}
    <?php
    //    $variantName = explode(",", $filterVal['variant_name']);
    //    $variantId = explode(",", $filterVal['variant_ids']);
    ?>
    {{--<div class="row brand">--}}
    {{--@if($filterVal['product_filter_parent_product_id'] == 0)--}}
    {{--<h4>{{$filterVal['product_filter_option_name']}}</h4>--}}
    {{--@endif--}}
    {{--@if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)--}}
    {{--<select class="form-control  _select_age_range changeable selectoption">--}}
    {{--<option selected="selected" class="select-filter">--}}
    {{--choose {{$filterVal['product_filter_option_name']}}</option>--}}
    {{--@foreach($variantName as $varKey => $varVal)--}}
    {{--<option class="select-filter" value="{{$variantId[$varKey]}}"--}}
    {{--data-id="{{$variantId[$varKey]}}">{{$varVal}}</option>--}}
    {{--@endforeach--}}
    {{--</select>--}}
    {{--@elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)--}}
    {{--@foreach($variantName as $varKey => $varVal)--}}
    {{--<input class="changeable select-filter" name="brand"--}}
    {{--data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}"--}}
    {{--type="radio">{{$varVal}}<br>--}}
    {{--@endforeach--}}
    {{--@elseif($filterVal['product_filter_variant_type'] == '3' && $filterVal['product_filter_parent_product_id'] == 0)--}}
    {{--@foreach($variantName as $varKey => $varVal)--}}
    {{--<a href="#">--}}
    {{--<div class="color changeable select-filter"--}}
    {{--data-id="{{$variantId[$varKey]}}"--}}
    {{--value="{{$variantId[$varKey]}}">{{$varVal}}</div>--}}
    {{--</a>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--@if($filterVal['product_filter_parent_product_id'] == 1)--}}
    <?php
    //    $variantNames = explode(",", $filterVal['variant_name']);
    //    $variantIds = explode(",", $filterVal['variant_ids']);
    ?>
    {{--<div class="sidepanel widget_pricefilter">--}}
    {{--<h3>{{$filterVal['product_filter_option_name']}}</h3>--}}
    {{--@foreach($variantNames as $priceKey => $priceVal)--}}
    {{--<input class="pricecheckbox" id="{{$priceVal}}" data-id="{{$priceVal}}"--}}
    {{--type="checkbox">--}}
    {{--<label for="{{$priceVal}}">Rs. {{$priceVal}}</label>--}}
    {{--<div class="clearfix"></div>--}}
    {{--@endforeach--}}

    {{--</div>--}}
    {{--@endif--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--<div class="col-md-9 salemain_section">--}}
    {{--<div class="row prod_main">--}}
    {{--@if(isset($flashsaledetails) && !empty($flashsaledetails))--}}
    {{--@foreach($flashsaledetails['product_info'] as $productKey => $productVal)--}}
    {{--<div class="col-md-4  ib_item_mainsalecate padding0">--}}
    {{--<a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}"--}}
    {{--class="ib_item_mainsalecate">--}}
    {{--<div class="images">--}}
    {{--<img src="{{$productVal['image_url']}}" class="img-responsive"--}}
    {{--id="image-res{{$productVal['product_id']}}"--}}
    {{--main-image="{{$productVal['image_url']}}">--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--<div class="">--}}
    {{--<span class="ib_itemsalecate">--}}
    {{--<span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>--}}
    {{--<span class="ib_textsalecate">{{$productVal['product_name']}}--}}
    {{--<br> ${{$productVal['price_total']}} <i class="offer_percent">15% off</i></span>--}}
    {{--</span>--}}
    {{--<span class="quick-view" class="quick-view"--}}
    {{--data-id="{{$productVal['product_id']}}"--}}
    {{--product-name="{{$productVal['product_name']}}">QUICK VIEW</span>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--@endforeach--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@else--}}
    {{--echo "No Data Found";--}}
    {{--@endif--}}
    {{--<div class="clearfix"></div>--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-offset-3 col-md-6">--}}
    {{--<button class="btn col-md-12" id="loadmore">Load more</button>--}}
    {{--</div>--}}
    {{--<div class="clearfix"></div>--}}
    {{--<hr>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div class="row">

        {{--<div class="salecatelog">--}}
        {{--<div class="row">--}}
        <div class="col-md-12 ">
            <div class="panel info-box panel-white">
                <div class="panel-body">

                    <div class="col-md-3 ctitle">
                        <div class=" cate">
                            <div class="container">
                                @if(isset($allCategories) && $allCategories['code'] == 200)

                                    <h4 data-toggle="collapse" data-target="#demo">
                                        <i class=" glyphicon glyphicon-chevron-down down_arrow"></i>CATEGORIES</h4>
                                    <div id="demo" class="collapse"><!--todo collapse-->
                                        @foreach($allCategories['data'] as $keyAllCat => $valAllCat)
                                            {{--@if($keyAllCat == 0)--}}
                                            {{--<a data-toggle="collapse" data-target="#demo{{$keyAllCat}}">{{$valAllCat['display_name'].$valAllCat['category_name']}}</a>--}}
                                            {{--<div id="demo{{$keyAllCat}}" class="collapse">--}}
                                            {{--@elseif($keyAllCat != 0 && $allCategories['data'][$keyAllCat-1]['cat_level'] == $valAllCat['cat_level'])--}}
                                            {{--<a>{{$valAllCat['display_name'].$valAllCat['category_name']}}</a>--}}
                                            {{--@elseif()--}}
                                            {{--<a>{{$valAllCat['display_name'].$valAllCat['category_name']}}</a>--}}
                                            {{--@endif--}}
                                            {{--data-toggle="collapse" data-target="#demo{{$valAllCat['cat_level']}}"--}}
                                            {{--href="{{$valAllCat['category_id']}}"--}}
                                            <a>
                                                {{$valAllCat['display_name'].$valAllCat['category_name']}}
                                            </a>
                                            <br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row gender">
                            <h4>Choose Brands</h4>

                            <form>
                                <label><input name="gender" value="unisex" checked="checked" type="radio">
                                    <span class="label-text">Brand One</span></label>
                                <label><input name="gender" value="female" type="radio">
                                    <span class="label-text">Brand Two</span> </label>
                                <label><input name="gender" value="male" type="radio"><span class="label-text">Brand
                                        Three</span></label>
                            </form>
                        </div>

                        <!--<div class="row brand">
                            <h4>BRAND</h4>

                            <form>
                                <input name="brand" value="brandone" checked="checked" type="radio"> Brandone<br>
                                <input name="brand" value="brandtwp" type="radio"> Brandtwp<br>
                                <input name="brand" value="sample" type="radio"> Sample<br>
                                <input name="brand" value="sampletwo" type="radio"> Sampletwo
                            </form>
                        </div>-->
                        <div class="row gender">
                            <h4>GENDER</h4>

                            <form>
                                <label><input name="gender" value="unisex" checked="checked" type="radio">
                                    <span class="label-text">Unisex</span></label>
                                <label><input name="gender" value="female" type="radio">
                                    <span class="label-text">Female</span> </label>
                                <label><input name="gender" value="male" type="radio"><span class="label-text">Male</span></label>
                            </form>
                        </div>
                        <div class="row range">
                            <h4>AGE RANGE</h4>
                            <select class="form-control  _select_age_range">
                                <option selected="selected">choose any range</option>
                                <option value="1">1-18</option>
                                <option value="2">18-30</option>
                                <option value="3">30-50</option>
                                <option value="4">50-70</option>
                                <option value="5">70-100</option>
                            </select>
                        </div>
                        <div class="row range">
                            <h4>SIZE</h4>

                            <form class="size">
                                <label class="active">
                                    <input type="checkbox" name="checkbox"/><span class="label-text active">XS</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text">S</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text">M</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text">L</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text">XL </span>
                                </label>
                            </form>
                        </div>
                        <div class="row range">
                            <h4>COLOR</h4>
                            <form class="color">
                                <label class="active">
                                    <input type="checkbox" name="checkbox"/><span class="label-text orange"> </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text green"> </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text pink"> </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text black"> </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text white"> </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="checkbox"/><span class="label-text blue"> </span>
                                </label>
                            </form>

                        </div>

                    </div>

                    <div class="col-md-9 salemain_section">
                        <div class="col-md-12">
                            <div class="col-md-4 mrgn_btm_ovrhid"> <!--12aug2016-->
                                <a href="#" class="ib_item_mainsalecate" data-toggle="modal" data-target=".bs-example-modal-lg" class="ib_item_mainsalecate">
                                    <div class="images">
                                        <img src="/assets/images/sc2.png" class="img-responsive">
                                    </div>

                                    <div class="col-md-12">
                                        <span class="ib_itemsalecate">
                                            <span class="inner_imagessalecate"><img src="/assets/images/ib.png" alt=""></span>
                                            <span class="ib_textsalecate">YX White T-SHIRT<br> ? 89.90
                                                <del> ? 100.90</del>
                                                <br>
                                                <!--12aug2016-->
                                                <p class="offer_percent" style="color:#B72652;"><b>15% OFF</b>
                                                </p>
                                            </span>
                                        </span>
                                        <span class="quick-view">QUICK VIEW</span>
                                    </div>

                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                        <div class="modal-dialog _vp">
                            <div class="modal-content _pv">
                                <div class="container-fluid  _vip">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="modal-body _vip2 row">
                                                <div class="col-md-5">
                                                    <div class="bzoom_wrap ">
                                                        <ul id="bzoom">
                                                            <li>
                                                                <img class="bzoom_thumb_image" src="/assets/images/q1.png" title="first img"/>
                                                                <img class="bzoom_big_image" src="/assets/images/q1.png"/>
                                                            </li>
                                                            <li>
                                                                <img class="bzoom_thumb_image" src="/assets/images/q2.png"/>
                                                                <img class="bzoom_big_image" src="/assets/images/q2.png"/>
                                                            </li>
                                                            <li>
                                                                <img class="bzoom_thumb_image" src="/assets/images/q3.png"/>
                                                                <img class="bzoom_big_image" src="/assets/images/q3.png"/>
                                                            </li>
                                                            <li>
                                                                <img class="bzoom_thumb_image" src="/assets/images/q4.png"/>
                                                                <img class="bzoom_big_image" src="/assets/images/q4.png"/>
                                                            </li>
                                                            <li>
                                                                <img class="bzoom_thumb_image" src="/assets/images/q5.png"/>
                                                                <img class="bzoom_big_image" src="/assets/images/q5.png"/>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i style="background-color: whitesmoke; border-radius: 5px; width: 125%;" class="fa fa-close"></i>
                                                        {{--<img src="/assets/images/close.png">--}}
                                                    </button>
                                                    <div class="row xy_tshirt">
                                                        <div class="col-md-6 t_shirt">
                                                            <h4>YX WHITE T-SHIRTS</h4>
                                                            <div class=" stars st_reviews">
                                                                <i class="fa fa-star fa-1"></i>
                                                                <i class="fa fa-star fa-1"></i>
                                                                <i class="fa fa-star fa-1"></i>
                                                                <i class="fa fa-star-o fa-1"></i>
                                                                <i class="fa fa-star-o fa-1"></i>
                                                            </div>
                                                            <div class="reviews">
                                                                <span class="review count">1 Review(s) |</span>

                                                                <a href="#">Add Your Review</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 P_FD">
                                                            <div class="pul-right">
                                                                FREE DELIVERY
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row offer_price">
                                                        <div class="col-md-6 price_dis">
                                                            <h3>
                                                                <span class="discounted-price">&pound
                                                                    89,99</span>
                                                                <span class="real-price">&pound
                                                                    100,99</span><br>
                                                                <span class="offer-discount">15% OFF</span>
                                                            </h3>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row quick-delivery">
                                                                <p class="mar-top-10 text-color-lg">
                                                                    Product code:
                                                                    <span class="product-code text-color-dg">
                                                                        275</span>
                                                                </p>
                                                                <p class="text-color-lg">
                                                                    Availablity:
                                                                    <span class="availablity text-color-dg">In
                                                                        Stock</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row quickoverview">
                                                        <div class="col-md-12">
                                                            <h4>QUICK REVIEW</h4>
                                                            <div class="comment more">
                                                                Lorem ipsum dolor sit amet, consectetur
                                                                adipiscing elit. Vestibulum laoreet, nunc eget
                                                                laoreet sa
                                                                <span class="moreelipses">Lorem ipsum dolor sit
                                                                    amet, consectetur adipiscing elit.
                                                                    Vestibulum laoreet, nunc eget laoreet
                                                                    sa</span>
                                                                <span class="morecontent">
                                                                    <a class="morelink" href="#">Read more</a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row quick-size-color">
                                                        <div class="col-md-12">
                                                            <h5>SIZE</h5>

                                                            <form class="size">
                                                                <label class="active">
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text active">XS</span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text">S</span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text">M</span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text">L</span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text">XL </span>
                                                                </label>
                                                            </form>

                                                        </div>
                                                        <div class="col-md-12">
                                                            <h5>COLOR</h5>
                                                            <form class="color">
                                                                <label class="active">
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text orange"> </span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text green"> </span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text pink"> </span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text black"> </span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text white"> </span>
                                                                </label>
                                                                <label>
                                                                    <input type="checkbox" name="checkbox"/><span class="label-text blue"> </span>
                                                                </label>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row add-cart">
                                                        <div class="col-md-2">
                                                            <p class="sub-head">Quantity</p>
                                                            <div class="form-group width-100">
                                                                <label class="control-label decrease-quan" for="">
                                                                    <i class="fa fa-minus"></i>
                                                                </label>
                                                                <input id="input-item-count _qqq" class="form-control" type="text" name="input-item-count" style="width:60%; text-align:center; margin-left:5px;">
                                                                <label class="control-label increase-quan" for="">
                                                                    <i class="fa fa-plus"></i>
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-7">
                                                            <div class="row buy_button">
                                                                <a class="btn btn-primary btn-lg brdr_non pading_lr top15_margin p_pop" href="#" role="button">ADD
                                                                    TO CART</a>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <a href="#">+Add to Wish list</a><br>
                                                            <a href="#">+Add to Compare</a><br>
                                                            <a href="#">+Email to a Friend</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    {{--<div class="modal fade" id="quick-view-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                    </button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>--}}

                </div>
            </div>
        </div>
        {{--</div>--}}
        {{--</div>--}}

    </div>


@endsection

@section('pagejavascripts')
    {{--<script type="text/javascript" src="/assets/scripts/jquery.mThumbnailScroller.js"></script>--}}
    {{--<script src="/assets/scripts/jquery-ui.js" type="text/javascript"></script>--}}

    <script type="text/javascript" src="/assets/buyer/js/jqzoom.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {

            /*
             var pagenumber = 1;
             $(document.body).on('change', "#selectsortby", function () {
             pagenumber = 1;
             window.setTimeout(function () {
             filter();
             }, 200);

             });

             var option = [];
             $(document.body).on('click', '.select-filter', function (e) {
             pagenumber = 1;
             e.preventDefault();
             var obj = $(this);
             option = obj.attr('data-id');
             window.setTimeout(function () {
             filter(option);
             }, 200);
             });

             $(document.body).on('click', '.pricecheckbox', function (e) {
             pagenumber = 1;
             window.setTimeout(function () {
             filter();
             }, 200);
             });

             $(document.body).on('click', '#loadmore', function (e) {
             pagenumber++;
             window.setTimeout(function () {
             filter(option, {loadMore: true});
             }, 200);
             });

             function filter(option, extraOption) {
             $('#loadmore').addClass('disabled');
             var pageurl = window.location.href;
             var urlparams = (pageurl.split("?"));
             var categoryname = '';
             var subcategoryname = '';
             var campaign_type = '';
             var flashId = '';
             if (urlparams.length > 1) {
             urlparams = urlparams[1].split('&');
             if (urlparams.length > 0) {
             $.each(urlparams, function (i, a) {
             a = a.split("=");
             var key = a[0];
             if (key == "catName") {
             categoryname = a[1];
             }
             if (key == "subcatName") {
             subcategoryname = a[1];
             }
             if (key == "flashId") {
             flashId = a[1];
             }

             });
             }
             }
             var sortby = $('#selectsortby > option:selected').val();
             var pricerangefrom = [];//array();
             var pricerangeto = [];//array();
             var pricecheckboxes = $('.pricecheckbox:checked');
             if (pricecheckboxes.length > 0) {
             $.each(pricecheckboxes, function (i, a) {
             var pricerange = $(a).attr('data-id');
             var pricerangearr = pricerange.split("-");
             pricerangefrom.push(pricerangearr[0]);
             pricerangeto.push(pricerangearr[1]);
             });
             }
             $.ajax({
             url: '/flashsale-ajax-handler',
             type: 'POST',
             datatype: 'json',
             data: {
             method: 'getFilterCampaignDetails',
             option: option,
             priceRangeFrom: pricerangefrom[0],
             priceRangeUpto: pricerangeto[pricerangeto.length - 1],
             sort_by: sortby,
             catName: categoryname,
             pagenumber: pagenumber,
             subcatName: subcategoryname,
             flashId: flashId

             },
             success: function (response) {
             var rawResponseLength = response.length;
             response = $.parseJSON(response);
             if (rawResponseLength < 50) {
             toAppend = '<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>' + response + '</span></div>';
             $('#loadmore').addClass('hide');
             } else {
             if (pagenumber == 1) {
             $('.prod_main').empty();
             $(".prod_main .padding0").empty();
             }
             $("#total_filtered_products").html('0');
             $("#total_filtered_products").html(response['total']);
             if (typeof(response) != "undefined" && response !== null) {
             //                                        $('.prod_main').empty();
             var appendProduct = '';
             $.each(response['product_info'], function (index, val) {
             appendProduct += '<div class="col-md-4  ib_item_mainsalecate padding0">';
             appendProduct += '<a class="ib_item_mainsalecate" href="/product-details/' + val['product_id'] + '/' + val['product_name'].replace(" ", "-") + '">';
             appendProduct += '<div class="images">';
             appendProduct += '<img src="' + val['image_url'] + '" class="img-responsive" id="image-res' + val['product_id'] + '" main-image="' + val['image_url'] + '">';
             appendProduct += '</div>';
             appendProduct += '</a>';
             appendProduct += '<div class="">';
             appendProduct += '<span class="ib_itemsalecate">';
             appendProduct += '<span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>';
             appendProduct += '<span class="ib_textsalecate">' + val['product_name'] + '<br> $' + val['price_total'] + '<del> £ 100.90</del> <br> <iclass="offer_percent">15% off</i></span>';
             appendProduct += '</span>';
             appendProduct += '<span class="quick-view" class="quick-view" data-id="' + val['product_id'] + '" product-name="' + val['product_name'] + '" >QUICK VIEW</span>';
             appendProduct += '</div>';
             appendProduct += '</div>';
             });
             $(".prod_main").append(appendProduct);
             if ($(".prod_main .padding0").length == response['total']) {
             $('#loadmore').addClass('hide');
             $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center; margin-top: 44px;"><span>No more products are available at the moment.</span></div>');
             } else {
             $('#loadmore').removeClass('hide');
             }
             }
             $('#loadmore').removeClass('disabled');

             }
             window.resize;
             }


             });
             }
             */

            $('.bs-example-modal-lg').on('shown.bs.modal', function () {
                console.log($('#bzoom').parent());
                $("#bzoom").zoom({
                    zoom_area_width: 350,
//                    thumb_image_width: 400,
//                    thumb_image_height: 400,
//                    source_image_width: $('#bzoom').parent().width(),
//                    source_image_height: 1200,
                    autoplay_interval: 3000,
                    small_thumbs: 4,
                    autoplay: false
                });
            });


        });
    </script>

@endsection