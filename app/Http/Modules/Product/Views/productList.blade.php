<?php $productListSetting = getSetting('available_product_list_sortings'); $productListSetting = $productListSetting ? $productListSetting : '';
$prod = str_replace('#M#', '', $productListSetting);
$setting = array_map(function ($val) {
    return rtrim($val, "=Y");
}, explode("&", $prod));

?>
@extends('Home/Layouts/home_layout')
@section('pageheadcontent')

    <style>
        .features-img {
            min-height: 250px;
            max-height: 250px;
            vertical-align: middle;
            display: inline-block;
        }

        .single-features {
            border: 1px solid rgba(0, 0, 0, 0.3);
        }

        .single-features:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            transform: scale(1.01, 1.01);
            -moz-transform: scale(1.01, 1.01);
        }

        .features-info {
            background: #e1e1e1 none repeat scroll 0 0;
            margin: 3px;
        }

        .features-info h2 a {
            color: #444;
            cursor: default;
        }

        .features-info:hover {
            color: #000;
        }
    </style>

@endsection

@section('content1')

    {{--<form id="paypalform" name="_xclick" action="https://sandbox.paypal.com/cgi-bin/webscr" method="post">--}}
    {{--<input type="hidden" name="cmd" value="_xclick">--}}
    {{--<input type="hidden" name="upload" value="1">--}}
    {{--<input type="hidden" name="business" value="raushank3-facilitator@gmail.com">--}}
    {{--<input type="hidden" name="currency_code" value="USD">--}}
    {{--<input type="hidden" name="item_name" id="items" value="cloth">--}}
    {{--<input type="hidden" name="amount" id="prices" value="100">--}}
    {{--<input type="hidden" name="rm" id="rm" value="2">--}}
    {{--<input type="hidden" name="return" value="http://localhost.flashsale.com/product-list">--}}
    {{--<input type="image" class="" src="/images/buynowpaypal.png" border="0"--}}
    {{--alt="Make payments with PayPal - it's fast, free and secure!">--}}
    {{--</form>--}}
    <div class="container catelog item" style="min-height: 300px">
        <div class="row">
            <div class="col-md-12">
                <div class="sorting_options clearfix">
                    <div class="count_tovar_items">
                        <p>Total</p>
                        <span id="total_filtered_products"></span><span>&nbsp;Products</span>
                    </div>
                    <div class="product_sort" style="text-align: right">
                        <p>SORT BY</p>
                        <select class="changeables" id="selectsortby">
                            <option selected value="">Sort By</option>
                            @foreach($setting as $key => $val)
                                <option value="{{$val}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row shop_block" id="productsdiv">
                </div>
                <?php if (isset($productDetailList) && !empty($productDetailList)) { ?>
                <div class="col-md-3 ctitle">
                    @if(isset($productDetailList['campaignCategoryProducts']) && !empty($productDetailList['campaignCategoryProducts']))
                        @foreach($productDetailList['campaignCategoryProducts']['filter'] as $filterKey => $filterVal)
                            <?php $variantName = explode(",", $filterVal['variant_name']);
                            $variantId = explode(",", $filterVal['variant_ids']);  ?>
                            <div class="row brand">
                                @if($filterVal['product_filter_parent_product_id'] == 0)
                                    <h4>{{$filterVal['product_filter_option_name']}}</h4>
                                @endif
                                @if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)
                                    {{--<a href="#">{{$varVal}}</a><br>--}}
                                    <select class="form-control  _select_age_range changeable selectoption">
                                        <option selected="selected" class="select-filter">
                                            choose {{$filterVal['product_filter_option_name']}}</option>
                                        @foreach($variantName as $varKey => $varVal)
                                            <option class="select-filter" value="{{$variantId[$varKey]}}"
                                                    data-id="{{$variantId[$varKey]}}">{{$varVal}}</option>
                                        @endforeach
                                    </select>
                                @elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)
                                    @foreach($variantName as $varKey => $varVal)
                                        <input class="changeable select-filter" name="brand"
                                                data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}"
                                                type="radio">{{$varVal}}<br>
                                    @endforeach
                                @elseif($filterVal['product_filter_variant_type'] == '3' && $filterVal['product_filter_parent_product_id'] == 0)
                                    @foreach($variantName as $varKey => $varVal)
                                        <a href="#">
                                            <div class="color changeable select-filter"
                                                    data-id="{{$variantId[$varKey]}}"
                                                    value="{{$variantId[$varKey]}}">{{$varVal}}</div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            @if($filterVal['product_filter_parent_product_id'] == 1)
                                <?php $variantNames = explode(",", $filterVal['variant_name']);
                                $variantIds = explode(",", $filterVal['variant_ids']);
                                ?>
                                <div class="sidepanel widget_pricefilter">
                                    <h3>{{$filterVal['product_filter_option_name']}}</h3>
                                    @foreach($variantNames as $priceKey => $priceVal)
                                        <input class="pricecheckbox" id="{{$priceVal}}" data-id="{{$priceVal}}"
                                                type="checkbox">
                                        <label for="{{$priceVal}}">Rs. {{$priceVal}}</label>
                                        <div class="clearfix"></div>
                                    @endforeach

                                </div>
                            @endif

                        @endforeach
                    @elseif(isset($productDetailList['productList']) && !empty($productDetailList['productList']))
                        @foreach($productDetailList['productList']['filter'] as $filterKey => $filterVal)
                            <?php $variantName = explode(",", $filterVal['variant_name']);
                            $variantId = explode(",", $filterVal['variant_ids']);  ?>
                            <div class="row brand">
                                @if($filterVal['product_filter_parent_product_id'] == 0)
                                    <h4>{{$filterVal['product_filter_option_name']}}</h4>
                                @endif
                                @if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)
                                    <select class="form-control  _select_age_range changeable selectoption"
                                            name="product_filter" id="selectOptions">
                                        <option value="" class="select-filter" selected="selected">
                                            choose {{$filterVal['product_filter_option_name']}}</option>
                                        @foreach($variantName as $varKey => $varVal)
                                            <option class="select-filter" data-id="{{$variantId[$varKey]}}"
                                                    value="{{$variantId[$varKey]}}">{{$varVal}}</option>
                                        @endforeach
                                    </select>
                                @elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)
                                    @foreach($variantName as $varKey => $varVal)
                                        <input class="changeable select-filter" data-id="{{$variantId[$varKey]}}"
                                                name="brand" id="radioCheck"
                                                value="{{$variantId[$varKey]}}" type="radio">{{$varVal}}<br>
                                    @endforeach
                                @elseif($filterVal['product_filter_parent_product_id'] == 0)
                                    @foreach($variantName as $varKey => $varVal)
                                        <a href="#">
                                            <div class="color changeable select-filter" id="checks"
                                                    data-id="{{$variantId[$varKey]}}"
                                                    value="{{$variantId[$varKey]}}">{{$varVal}}</div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            @if($filterVal['product_filter_parent_product_id'] == 1)
                                <?php $variantNames = explode(",", $filterVal['variant_name']);
                                $variantIds = explode(",", $filterVal['variant_ids']);
                                ?>
                                <div class="sidepanel widget_pricefilter">
                                    <h3>{{$filterVal['product_filter_option_name']}}</h3>
                                    @foreach($variantNames as $priceKey => $priceVal)
                                        <input class="pricecheckbox" id="{{$priceVal}}" data-id="{{$priceVal}}"
                                                type="checkbox">
                                        <label for="{{$priceVal}}">Rs. {{$priceVal}}</label>
                                        <div class="clearfix"></div>
                                    @endforeach

                                </div>
                            @endif
                        @endforeach
                    @else
                        <?php echo "No Filters"; ?>
                    @endif
                </div>
                {{--===========================--}}

                <div class="col-md-9 main_section">
                    <div class="row prod_main">
                        @if(isset($productDetailList['campaignCategoryProducts']['product']) && !empty($productDetailList['campaignCategoryProducts']['product']))
                            <div class="count_tovar_items">
                                <p>Total</p>
                                <span id="total_filtered_products">{{$productDetailList['campaignCategoryProducts']['total']}}</span><span>
                                    &nbsp;Products</span>
                            </div>
                            @foreach($productDetailList['campaignCategoryProducts']['product'] as $productKey => $productVal)
                                <div class="col-md-4  ib_item_mainsalecate padding0">
                                    <a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}"
                                            class="ib_item_mainsalecate">
                                        <div class="images">
                                            <img src="{{$productVal['image_url']}}" class="img-responsive"
                                                    id="image-res{{$productVal['product_id']}}"
                                                    main-image="{{$productVal['image_url']}}">
                                        </div>

                                    </a>

                                    <div class="">
                                        <span class="ib_itemsalecate">
                                            <span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>
                                            <span class="ib_textsalecate">{{$productVal['product_name']}}
                                                <br> ${{$productVal['price_total']}}
                                                {{--<i class="offer_percent">{{$productDetailList['productList']['product'][0]['quantity_discount']}}</i>--}}
                                            </span>
                                        </span>
                                        <span class="quick-view" class="quick-view"
                                                data-id="{{$productVal['product_id']}}"
                                                product-name="{{$productVal['product_name']}}">QUICK VIEW</span>
                                    </div>
                                </div>


                                {{--<div class="col-md-4 ib_item_full">--}}
                                {{--<a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}"--}}
                                {{--class="ib_item_main">--}}
                                {{--<a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}"--}}
                                {{--class="ib_item_main" >--}}
                                {{--<div class="images">--}}
                                {{--<img src="{{$productVal['image_url']}}" class="img-responsive" id="image-res{{$productVal['product_id']}}" main-image="{{$productVal['image_url']}}">--}}
                                {{--</div>--}}

                                {{--</a>--}}

                                {{--<a class="ib_item quick-view" href="javascript:;" data-id="{{$productVal['product_id']}}" product-name="{{$productVal['product_name']}}">--}}
                                {{--<span class="inner_images"><img src="assets/images/ib.png" alt=""></span>--}}
                                {{--<span class="ib_text">{{$productVal['product_name']}}--}}
                                {{--<br> {{$productVal['price_total']}}--}}
                                {{--<del> £ 100.90</del> <br> <i--}}
                                {{--class="offer_percent">15% off</i></span>--}}
                                {{--</a>--}}

                                {{--</div>--}}
                            @endforeach
                        @elseif(isset($productDetailList['productList']['product']) && !empty($productDetailList['productList']['product']))
                            <div class="count_tovar_items">
                                <p>Total</p>
                                <span id="total_filtered_products">{{$productDetailList['productList']['total']}}</span><span>
                                    &nbsp;Products</span>
                            </div>
                            @foreach($productDetailList['productList']['product'] as $productKey => $productVal)
                                <div class="col-md-4  ib_item_mainsalecate padding0">
                                    <a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}"
                                            class="ib_item_mainsalecate">
                                        <div class="images">
                                            <img src="{{$productVal['image_url']}}" class="img-responsive" id="image-res{{$productVal['product_id']}}" main-image="{{$productVal['image_url']}}">
                                        </div>

                                    </a>
                                    <div class="">
                                        <span class="ib_itemsalecate">
                                            <span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>
                                            <span class="ib_textsalecate">{{$productVal['product_name']}}
                                                <br> ${{$productVal['price_total']}}
                                                {{--<i class="offer_percent">15% off</i>--}}
                                            </span>
                                        </span>
                                        <span class="quick-view" class="quick-view"
                                                data-id="{{$productVal['product_id']}}"
                                                product-name="{{$productVal['product_name']}}">QUICK VIEW</span>
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <?php echo "No Products"; ?>
                        @endif
                    </div>
                </div>
                {{--======================--}}

                <?php } else { ?>
                     <?php echo "No products Found";
                }?>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <button class="btn col-md-12" id="loadmore">Load more</button>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                </div>


                <div class="error"></div>
            </div>
        </div>
    </div>

@endsection
@section('pagejavascripts')
    <script type="text/javascript"
            src="/assets/scripts/jquery.mThumbnailScroller.js"></script>
    <script src="/assets/scripts/jquery-ui.js" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function () {

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
                            if (key == "campaign_type") {
                                campaign_type = a[1];
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
                    url: '/product-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getFilterProductsList',
                        option: option,
                        priceRangeFrom: pricerangefrom[0],
                        priceRangeUpto: pricerangeto[pricerangeto.length - 1],
                        sort_by: sortby,
                        catName: categoryname,
                        pagenumber: pagenumber,
                        subcatName: subcategoryname,
                        campaign_type: campaign_type

                    },
                    success: function (response) {
                        var toAppend = '';
                        var rawResponseLength = response.length;
                        response = $.parseJSON(response);
                        console.log(response);
                        if (rawResponseLength < 50) {
                            toAppend = '<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>' + response + '</span></div>';
                            $('#loadmore').addClass('hide');
                        } else {
                            if (typeof(response) != "undefined" && response !== null) {
                                if (pagenumber == 1) {
                                    $('.prod_main').empty();
                                }

                                if (typeof(campaign_type) != "undefined" && campaign_type != '') {
                                    if (typeof(response['campaignCategoryProducts']) != "undefined" && response['campaignCategoryProducts'] !== null) {
                                        $("#total_filtered_products").html('0');
                                        $("#total_filtered_products").html(response['campaignCategoryProducts']['total']);
//                                        $('.prod_main').empty();
                                        var appendProduct = '';
                                        $.each(response['campaignCategoryProducts']['product'], function (index, val) {
                                            /*
                                             appendProduct += '<div class="col-md-4  ib_item_mainsalecate padding0">';
                                             appendProduct += '<a class="ib_item_mainsalecate" href="/product-details/' + val['product_id'] + '/' + val['product_name'].replace(" ", "-") + '">';
                                             appendProduct += '<div class="images">';
                                             appendProduct += '<img src="' + val['image_url'] + '" class="img-responsive" id="image-res' + val['product_id'] + '" main-image="' + val['image_url'] + '">';
                                             appendProduct += '</div>';
                                             appendProduct += '</a>';
                                             appendProduct += '<div class="">';
                                             appendProduct += '<span class="ib_itemsalecate">';
                                             appendProduct += '<span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>';
                                             appendProduct += '<span class="ib_textsalecate">' + val['product_name'] + '<br> $' + val['price_total'] + '<br> <i class="offer_percent">15% off</i></span>';
                                             appendProduct += '</span>';
                                             appendProduct += '<span class="quick-view" class="quick-view" data-id="' + val['product_id'] + '" product-name="' + val['product_name'] + '" >QUICK VIEW</span>';
                                             appendProduct += '</div>';
                                             appendProduct += '</div>';
                                             */
                                            appendProduct += '<div class="col-lg-4 col-md-4 col-sm-4">';
                                            appendProduct += '<div class="single-features single-shop">';
                                            appendProduct += '<div class="features-img shop-img" style=""><!--min-height: 333px;-->';
                                            appendProduct += '<a href="/product-details/' + val['product_id'] + '/' + val['product_name'].replace(" ", "-") + '"><img src="' + val['image_url'] + '" alt="" id="image-res' + val['product_id'] + '" main-image="' + val['image_url'] + '"></a>';
                                            appendProduct += '<span>NEW</span>';
                                            appendProduct += '<div class="button-shop">';
                                            appendProduct += '<form action="#">';
                                            appendProduct += '<span class="quick-view" class="quick-view" data-id="' + val['product_id'] + '" product-name="' + val['product_name'] + '">QUICK VIEW</span>';
                                            {{--<input type="button" value="Choose options">--}}
                                                    {{--<a href="#"><i class="fa fa-heart"></i></a>--}}
                                                    appendProduct += '</form>';
                                            appendProduct += '</div>';
                                            appendProduct += '</div>';
                                            appendProduct += '<div class="features-info">';
                                            appendProduct += '<h2><a href="#">' + val['product_name'] + '</a></h2>';
                                            appendProduct += '<p>';
                                            {{--<del>${{$productVal['price_total']}}</del>--}}
                                                    appendProduct += '<strong><big>$ ' + val['price_total'] + '</strong></big>';
                                            appendProduct += '</p>';
                                            appendProduct += '</div>';
                                            appendProduct += '</div>';
                                            appendProduct += '</div>';
                                        });

                                        $(".prod_main").append(appendProduct);
                                        if ($(".prod_main .padding0").length == response['campaignCategoryProducts']['total']) {
                                            $('#loadmore').addClass('hide');
                                            $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more products are available at the moment.</span></div>');
                                        } else {
                                            $('#loadmore').removeClass('hide');
                                        }
                                    }
                                } else {
                                    if (typeof(response['productList']) != "undefined" && response['productList'] !== null) {
                                        $("#total_filtered_products").html('0');
                                        $("#total_filtered_products").html(response['productList']['total']);
//                                        $('.prod_main').empty();
                                        var appendProduct = '';
                                        $.each(response['productList']['product'], function (index, val) {
                                            /*
                                             appendProduct += '<div class="col-md-4  ib_item_mainsalecate padding0">';
                                             appendProduct += '<a class="ib_item_mainsalecate" href="/product-details/' + val['product_id'] + '/' + val['product_name'].replace(" ", "-") + '">';
                                             appendProduct += '<div class="images">';
                                             appendProduct += '<img src="' + val['image_url'] + '" class="img-responsive" id="image-res' + val['product_id'] + '" main-image="' + val['image_url'] + '">';
                                             appendProduct += '</div>';
                                             appendProduct += '</a>';
                                             appendProduct += '<div class="">';
                                             appendProduct += '<span class="ib_itemsalecate">';
                                             appendProduct += '<span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>';
                                             appendProduct += '<span class="ib_textsalecate">' + val['product_name'] + '<br> $' + val['price_total'] + '<i class="offer_percent">15% off</i></span>';
                                             appendProduct += '</span>';
                                             appendProduct += '<span class="quick-view" class="quick-view" data-id="' + val['product_id'] + '" product-name="' + val['product_name'] + '" >QUICK VIEW</span>';
                                             appendProduct += '</div>';
                                             appendProduct += '</div>';
                                             */
                                            appendProduct += '<div class="col-lg-4 col-md-4 col-sm-4">';
                                            appendProduct += '<div class="single-features single-shop">';
                                            appendProduct += '<div class="features-img shop-img" style=""><!--min-height: 333px;-->';
                                            appendProduct += '<a href="/product-details/' + val['product_id'] + '/' + val['product_name'].replace(" ", "-") + '"><img src="' + val['image_url'] + '" alt="" id="image-res' + val['product_id'] + '" main-image="' + val['image_url'] + '"></a>';
                                            appendProduct += '<span>NEW</span>';
                                            appendProduct += '<div class="button-shop">';
                                            appendProduct += '<form action="#">';
                                            appendProduct += '<span class="quick-view" class="quick-view" data-id="' + val['product_id'] + '" product-name="' + val['product_name'] + '">QUICK VIEW</span>';
                                            {{--<input type="button" value="Choose options">--}}
                                                    {{--<a href="#"><i class="fa fa-heart"></i></a>--}}
                                                    appendProduct += '</form>';
                                            appendProduct += '</div>';
                                            appendProduct += '</div>';
                                            appendProduct += '<div class="features-info">';
                                            appendProduct += '<h2><a href="#">' + val['product_name'] + '</a></h2>';
                                            appendProduct += '<p>';
                                            {{--<del>${{$productVal['price_total']}}</del>--}}
                                                    appendProduct += '<strong><big>$ ' + val['price_total'] + '</strong></big>';
                                            appendProduct += '</p>';
                                            appendProduct += '</div>';
                                            appendProduct += '</div>';
                                            appendProduct += '</div>';
                                        });
                                        $(".prod_main").append(appendProduct);
                                        if ($(".prod_main .padding0").length == response['productList']['total']) {
                                            $('#loadmore').addClass('hide');
                                            $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center; "><span>No more products are available at the moment.</span></div>');
                                        } else {
                                            $('#loadmore').removeClass('hide');
                                        }
                                    }
                                }
                            }
                            $('#loadmore').removeClass('disabled');
                        }
//                        if (pagenumber == 1) {
//                            console.log("Load");
//                            $('.prod_main').html(appendProduct);
//                            if ($(".prod_main .prod").length == response['campaignCategoryProducts']['total']) {
//                                $('.loadmore').addClass('hide');
//                                $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more products are available at the moment.</span></div>');
//                            }
//                            else if ($(".prod_main .prod").length == response['productList']['total']) {
//                                $('#loadmore').addClass('hide');
//                                $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more products are available at the moment.</span></div>');
//                            } else {
//                                $('#loadmore').removeClass('hide');
//                            }
//                        } else {
//                            console.log("unLoad");
//                            $('.prod_main').append(appendProduct);
//                            if ($(".prod_main .prod").length == response['campaignCategoryProducts']['total']) {
//                                $('#loadmore').addClass('hide');
//                                $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more products are available at the moment.</span></div>');
//                            } else if ($(".prod_main .prod").length == response['productList']['total']) {
//                                $('#loadmore').addClass('hide');
//                                $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more products are available at the moment.</span></div>');
//                            } else {
//                                $('#loadmore').removeClass('hide');
//                            }
//                        }
//                        window.resize;
                    }

                });
            }

//            setTimeout(function () {
//                filter();
//            }, 1000);

        });


        //        $(function(){
        //            $('.ib_item_main').hover( function () {
        //                var status = $(this).hasClass('is-open');
        //                if (status) {
        //                    $('.ib_item_main, .ib_item').removeClass('is-open');
        //                }
        //                else {
        //                    $('.ib_item_main, .ib_item').addClass('is-open');
        //                }
        //            });
        //        });

        //        $('.ib_item_main').hover( function () {
        //            $('.ib_item').css('opacity', '1');
        //        });
    </script>

@endsection


@section('content')

    <div class="container-fluid">
        {{--<div class="row">--}}
        {{--<!-- OFFICE-BANNER START -->--}}
        {{--<div class="col-md-12 col-sm-12">--}}
        {{--<div class="bg-for-catlouge" style="opacity: 0.7;">--}}
        {{--<img src="/assets/home/img/office/11.jpg" alt="" class="img-reponsive"/>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<!-- OFFICE-BANNER END -->--}}
        {{--</div>--}}
    </div>
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="breadcrumb">
                        <ul>
                            <li>You are here:</li>
                            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                            <li><span>Products</span></li>
                            <li class="pull-right menu-bar-br">
                                <a class="menu-bar" data-toggle="collapse" href="#menu"><i class="fa fa-filter"></i>
                                    FILTER</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Shop link-->
    <!-- SIDEBAR-AREA START -->
    <div class="sidebar-area">
        <div class="container">
            <div class="row">
                <!-- LEFT SIDEBAR START -->
                <div class="col-lg-3 col-md-3 col-sm-3 sidebar-border">
                    <div class="left-sidebar-h">
                        <!-- K2-CONTENT START -->

                        <div class="k2-content sidebar-p">
                            <h3 class="blog-right-title"><strong>SORT BY</strong></h3>
                            <select class="changeables" id="selectsortby">
                                <option selected value="">Sort By</option>
                                @foreach($setting as $key => $val)
                                    <option value="{{$val}}">{{$val}}</option>
                                    {{--<a href="#"><input type="radio" name="optradio" class="pull-left radio-btn">Price high</a><br>--}}
                                @endforeach
                            </select>
                        </div>
                        <!-- K2-CONTENT END -->

                        {{--<div class="contact-accordion">--}}
                        {{--<!-- ACCORDION START -->--}}
                        {{--<h3 data-collapse-summary="" aria-expanded="false"><a href="#">CATEGORIES</a></h3>--}}
                        {{--<div class="contact-content" aria-hidden="true" style="display: none;">--}}
                        {{--<div class="contact-info">--}}
                        {{--<ul>--}}
                        {{--<li><a href="#">SUB CAT--}}
                        {{--<i class="fa fa-chevron-right pull-right" aria-hidden="true"></i></a>--}}
                        {{--</li>--}}
                        {{--<li><a href="#">Random Categories--}}
                        {{--<i class="fa fa-chevron-right pull-right" aria-hidden="true"></i></a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- ACCORDION END -->--}}
                        {{--</div>--}}

                        <br>

                        @if(isset($productDetailList['campaignCategoryProducts']) && !empty($productDetailList['campaignCategoryProducts']))
                            @foreach($productDetailList['campaignCategoryProducts']['filter'] as $filterKey => $filterVal)
                                <?php $variantName = explode(",", $filterVal['variant_name']);
                                $variantId = explode(",", $filterVal['variant_ids']);  ?>
                                <div class="k2-content sidebar-p" style="padding-top:80px;">
                                    @if($filterVal['product_filter_parent_product_id'] == 0)
                                        <h3 class="blog-right-title">
                                            <strong>{{$filterVal['product_filter_option_name']}}</strong></h3><br>
                                    @endif
                                    <div class="panel recipients">
                                        <div class="panel-heading">
                                            @if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)
                                                <select class="form-control  _select_age_range changeable selectoption">
                                                    <option selected="selected" class="select-filter">
                                                        choose {{$filterVal['product_filter_option_name']}}</option>
                                                    @foreach($variantName as $varKey => $varVal)
                                                        <option class="select-filter" value="{{$variantId[$varKey]}}"
                                                                data-id="{{$variantId[$varKey]}}">{{$varVal}}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)
                                                @foreach($variantName as $varKey => $varVal)
                                                    <input class="changeable select-filter" name="brand"
                                                            data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}"
                                                            type="radio">{{$varVal}}<br>
                                                @endforeach
                                            @elseif($filterVal['product_filter_variant_type'] == '3' && $filterVal['product_filter_parent_product_id'] == 0)
                                                @foreach($variantName as $varKey => $varVal)
                                                    <span class="button-checkbox select-filter"
                                                            data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}">
                                                        <button type="button" class="btn btn-sm" data-color="default">{{$varVal}}</button>
                                                        {{--<input type="checkbox" id="showall" class="hidden"/>--}}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @if($filterVal['product_filter_parent_product_id'] == 1)
                                        <?php $variantNames = explode(",", $filterVal['variant_name']);
                                        $variantIds = explode(",", $filterVal['variant_ids']);
                                        ?>
                                        <div class="sidepanel widget_pricefilter">
                                            <h3>{{$filterVal['product_filter_option_name']}}</h3>
                                            @foreach($variantNames as $priceKey => $priceVal)
                                                <input class="pricecheckbox" id="{{$priceVal}}" data-id="{{$priceVal}}"
                                                        type="checkbox">
                                                <label for="{{$priceVal}}">Rs. {{$priceVal}}</label>
                                                <div class="clearfix"></div>
                                            @endforeach

                                        </div>
                                    @endif
                                </div>

                            @endforeach

                        @elseif(isset($productDetailList['productList']) && !empty($productDetailList['productList']))
                            @foreach($productDetailList['productList']['filter'] as $filterKey => $filterVal)
                                <?php $variantName = explode(",", $filterVal['variant_name']);
                                $variantId = explode(",", $filterVal['variant_ids']);  ?>
                                <div class="k2-content sidebar-p" style="padding-top:80px;">
                                    @if($filterVal['product_filter_parent_product_id'] == 0)
                                        <h3 class="blog-right-title">
                                            <strong>{{$filterVal['product_filter_option_name']}}</strong></h3><br>
                                    @endif
                                    <div class="panel recipients">
                                        <div class="panel-heading">
                                            @if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)
                                                <select class="form-control  _select_age_range changeable selectoption"
                                                        name="product_filter" id="selectOptions">
                                                    <option value="" class="select-filter" selected="selected">
                                                        choose {{$filterVal['product_filter_option_name']}}</option>
                                                    @foreach($variantName as $varKey => $varVal)
                                                        <option class="select-filter" data-id="{{$variantId[$varKey]}}"
                                                                value="{{$variantId[$varKey]}}">{{$varVal}}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)
                                                @foreach($variantName as $varKey => $varVal)
                                                    <input class="changeable select-filter" data-id="{{$variantId[$varKey]}}"
                                                            name="brand" id="radioCheck"
                                                            value="{{$variantId[$varKey]}}" type="radio">{{$varVal}}<br>
                                                @endforeach
                                            @elseif($filterVal['product_filter_parent_product_id'] == 0)
                                                @foreach($variantName as $varKey => $varVal)
                                                    <span class="button-checkbox select-filter"
                                                            data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}">
                                                        <button type="button" class="btn btn-sm" data-color="default">{{$varVal}}</button>
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @if($filterVal['product_filter_parent_product_id'] == 1)
                                        <?php $variantNames = explode(",", $filterVal['variant_name']);
                                        $variantIds = explode(",", $filterVal['variant_ids']);
                                        ?>
                                        <div class="sidepanel widget_pricefilter">
                                            <h3>{{$filterVal['product_filter_option_name']}}</h3>
                                            @foreach($variantNames as $priceKey => $priceVal)
                                                <input class="pricecheckbox" id="{{$priceVal}}" data-id="{{$priceVal}}"
                                                        type="checkbox">
                                                <label for="{{$priceVal}}">Rs. {{$priceVal}}</label>
                                                <div class="clearfix"></div>
                                            @endforeach

                                        </div>
                                    @endif
                                </div>
                        @endforeach

                    @else
                        <?php echo "No Filters"; ?>
                    @endif

                    {{--<div class="k2-content sidebar-p">--}}
                    {{--<div class="form-group">--}}
                    {{--<h3 class="blog-right-title"><strong>Select Colour</strong></h3><br>--}}
                    {{--<div class="dropdown open">--}}
                    {{--<ul class="_select_color_drop" aria-labelledby="dropdownMenu1">--}}
                    {{--<li class="color-list"><span _text_display="TEAL" class="color TEAL"></span>--}}
                    {{--</li>--}}
                    {{--<li class="color-list"><span _text_display="NAVY" class="color NAVY"></span>--}}
                    {{--</li>--}}
                    {{--<li class="color-list"><span _text_display="PURPLE" class="color PURPLE"></span>--}}
                    {{--</li>--}}
                    {{--<li class="color-list"><span _text_display="OLIVE" class="color OLIVE"></span>--}}
                    {{--</li>--}}
                    {{--<input type="hidden" name="_color" value="Green">--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<br>--}}

                    {{--<div class="k2-content sidebar-p">--}}
                    {{--<h3 class="blog-right-title"><strong>GENDER</strong></h3>--}}
                    {{--<a href="#"><input type="radio" name="optradio" class="pull-left radio-btn">Unisex</a><br>--}}
                    {{--<a href="#"><input type="radio" name="optradio" class="pull-left radio-btn">Male</a><br>--}}
                    {{--<a href="#"><input type="radio" name="optradio" class="pull-left radio-btn">female</a><br>--}}
                    {{--</div>--}}
                    <!-- K2-CONTENT END -->


                    </div>
                </div>
                <!-- LEFT SIDEBAR START -->

                <div class="col-lg-9 col-md-9 col-sm-9">
                    <div class="sidebar-content">
                        <div class="row left-sidebar-content prod_main">

                            @if(isset($productDetailList['campaignCategoryProducts']['product']) && !empty($productDetailList['campaignCategoryProducts']['product']))

                                @foreach($productDetailList['campaignCategoryProducts']['product'] as $productKey => $productVal)

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="single-features single-shop">
                                            <div class="features-img shop-img" style=""><!--min-height: 333px;-->
                                                <a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}"><img src="{{$productVal['image_url']}}" alt="" id="image-res{{$productVal['product_id']}}"
                                                            main-image="{{$productVal['image_url']}}"></a>
                                                <span>NEW</span>
                                                <div class="button-shop">
                                                    <form action="#">
                                                        <span class="quick-view" class="quick-view"
                                                                data-id="{{$productVal['product_id']}}"
                                                                product-name="{{$productVal['product_name']}}">QUICK
                                                            VIEW</span>
                                                        {{--<input type="button" value="Choose options">--}}
                                                        {{--<a href="#"><i class="fa fa-heart"></i></a>--}}
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="features-info">
                                                <h2><a href="#">{{$productVal['product_name']}}</a></h2>
                                                <span>
                                                    {{--<del>${{$productVal['price_total']}}</del>--}}
                                                    <big><strong>$ {{$productVal['price_total']}}</strong></big>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            @elseif(isset($productDetailList['productList']['product']) && !empty($productDetailList['productList']['product']))

                                @foreach($productDetailList['productList']['product'] as $productKey => $productVal)

                                    <div class="col-lg-4 col-md-4 col-sm-4 box-border-shadow">
                                        <div class="single-features single-shop">
                                            <div class="features-img shop-img" style=""><!--min-height: 333px;-->
                                                <a href="/product-details/{{$productVal['product_id']}}/{{str_replace(" ","-",$productVal['product_name'])}}">
                                                    <img src="{{$productVal['image_url']}}" alt="" id="image-res{{$productVal['product_id']}}"
                                                            main-image="{{$productVal['image_url']}}" style="">
                                                </a>
                                                {{--<span class="offer-img" style="width: 55px;"><strong>40% Off</strong></span>--}}
                                                <div class="button-shop">
                                                    <form action="#">
                                                        <span class="quick-view" class="quick-view"
                                                                data-id="{{$productVal['product_id']}}"
                                                                product-name="{{$productVal['product_name']}}">QUICK
                                                            VIEW</span>
                                                        {{--<input type="button" value="Choose options">--}}
                                                        {{--<a href="#"><i class="fa fa-exchange"></i></a>--}}
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="features-info">
                                                <h2><a href="#">{{$productVal['product_name']}}</a></h2>
                                                <span>
                                                    {{--<a href="#">--}}
                                                    {{--<del>${{$productVal['price_total']}}</del>--}}
                                                    {{--</a>--}}
                                                    <big><strong>$ {{$productVal['price_total']}}</strong></big>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{"No Products"}}
                            @endif
                        </div>

                        <div class="row creative-area ">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="creative-border">
                                    <div class="creative-content">
                                        <h2 class="wow fadeInRight animated" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInRight;">
                                            Awesome &amp; New And Different Color Products</h2>
                                        <h4 class="wow fadeInRight animated" data-wow-duration="1.5s" data-wow-delay=".5s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.5s; animation-name: fadeInRight;">
                                            New Offer is going on upto 50%</h4>
                                    </div>
                                    <div class="creative-photo">
                                        <div class="even-item-img">
                                            <div class="catitemimage">
                                                <a href="#">
                                                    <span class="catItemLink"></span>
                                                    <img src="/assets/home/img/home-2/f1.jpg" alt=""/>
                                                </a>
                                                <div class="blog-info-block">
                                                    <span class="catItemDateCreated">
                                                        <span class="blog-date">18</span>
                                                        <span class="blog-month">Jan 2015</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--<div class="row">--}}
                        {{--<div class="col-md-12">--}}
                        {{--<div class="shop-pagination">--}}
                        {{--<div class="pagination-shop">--}}
                        {{--<ul>--}}
                        {{--<li><a href="#">Prev</a></li>--}}
                        {{--<li><a href="#">1</a></li>--}}
                        {{--<li><a href="#">2</a></li>--}}
                        {{--<li><a href="#">3</a></li>--}}
                        {{--<li><a href="#">4</a></li>--}}
                        {{--<li><a href="#">5</a></li>--}}
                        {{--<li><a href="#">Next</a></li>--}}
                        {{--</ul>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        <div class="row">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn col-md-12" id="loadmore">Load more</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                        </div>

                        <div class="error"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SIDEBAR-AREA END -->

@endsection

@section('pagejavascripts')


    <script type="text/javascript">

        $(document).ready(function () {
            $('ul .color-list').click(function () {
                // $('.color-list').removeClass("active");
                $(this).toggleClass("active");
            });
        });

        <!--size selector-->
        $(function () {
            $('.button-checkbox').each(function () {

                // Settings
                var $widget = $(this),
                        $button = $widget.find('button'),
                        $checkbox = $widget.find('input:checkbox'),
                        color = $button.data('color'),
                        settings = {
                            on: {
                                icon: 'glyphicon glyphicon-check'
                            },
                            off: {
                                icon: 'glyphicon glyphicon-unchecked'
                            }
                        };

                // Event Handlers
                $button.on('click', function () {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });
                $checkbox.on('change', function () {
                    updateDisplay();
                });

                // Actions
                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');

                    // Set the button's state
                    $button.data('state', (isChecked) ? "on" : "off");

                    // Set the button's icon
                    $button.find('.state-icon')
                            .removeClass()
                            .addClass('state-icon ' + settings[$button.data('state')].icon);

                    // Update the button's color
                    if (isChecked) {
                        $button
                                .removeClass('btn-default')
                                .addClass('btn-' + color + ' active');
                    }
                    else {
                        $button
                                .removeClass('btn-' + color + ' active')
                                .addClass('btn-default');
                    }
                }

                // Initialization
                function init() {

                    updateDisplay();

                    // Inject the icon if applicable
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                    }
                }

                init();
            });
        });
    </script>
@endsection
