@extends('Buyer/Layouts/buyerlayout')

@section('pageheadcontent')
    {{--OPTIONAL--}}

    {{--<link href="/assets/global/css/etalage.css" media="screen" rel="stylesheet" type="text/css">--}}
    <link href="/assets/buyer/css/style.css" rel="stylesheet" type="text/css"/>

    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <style>
        .etalage_zoom_preview {
            opacity: 1 !important;
        }

        li.etalage_zoom_area div:last-child {
            width: 440px !important;
            /*height: 440px !important;*/
        }
    </style>
    <style>
        div.stars {

            display: inline-block;
        }

        input.star {
            display: none;
        }

        label.star {
            float: right;
            padding: 5px;
            font-size: 25px;
            color: #444;
            transition: all .2s;
        }

        input.star:checked ~ label.star:before {
            content: '\f005';
            color: #FD4;
            transition: all .25s;
        }

        label.star:hover {
            transform: rotate(-15deg) scale(1.3);
        }

        label.star:before {
            content: '\f006';
            font-family: FontAwesome;
        }

        .rating-box {
            display: inline-block;
            margin-right: 8px;
        }

        .tab-content {
            border: 1px solid #e9e9e9;
            color: #666;
            font-size: 14px;
            line-height: 18px;
            margin-top: -1px;
            padding: 16px 19px 18px;
        }

        div.size:active, div.size.active {
            background: #a1a1a1 none repeat scroll 0 0;
        }

        div.size:hover {
            cursor: pointer;
            background: #d1d1d1 none repeat scroll 0 0;
        }
    </style>
@endsection

@section('content')
    {{--<div class="container"><!---main seaction start--->--}}
    <div class="panel info-box panel-white">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-5 clearfix">
                        <div class="bzoom_wrap">
                            {{--<div class="col-md-6 clearfix"></div>--}}
                            <ul id="etalage"> <!--  style="width: 100%"-->

                                @if (isset($productdetails))
                                    @foreach (explode(",", $productdetails[0]['image_urls']) as $value)
                                        <li>
                                            <img class="bzoom_thumb_image" src="{{$value}}"/>
                                            <img class="bzoom_big_image" src="{{$value}}"/>
                                        </li>
                                    @endforeach
                                @endif


                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7 qdsec_right">
                        <div class="row xy_tshirt">
                            <div class="col-md-6 t_shirt">
                                <h4>{{ $productdetails[0]['product_name']}}</h4>
                                {{--<div class=" stars st_reviews">--}}
                                {{--<i class="fa fa-star fa-1"></i>--}}
                                {{--<i class="fa fa-star fa-1"></i>--}}
                                {{--<i class="fa fa-star fa-1"></i>--}}
                                {{--<i class="fa fa-star-o fa-1"></i>--}}
                                {{--<i class="fa fa-star-o fa-1"></i>--}}
                                {{--</div>--}}
                                {{--<div class="reviews">--}}
                                {{--<span class="review count">1 Review(s) |</span>--}}
                                {{--<a href="#">Add Your Review</a>--}}
                                {{--</div>--}}
                            </div>
                            <div class="col-md-6">
                                <div class="pul-right">
                                    FREE DELIVERY
                                </div>
                            </div>
                        </div>
                        <div class="row offer_price">
                            <div class="col-md-6 price_dis">
                                <h3 id="price-total" data-price="{{$productdetails[0]['price_total']}}">
                                    <span class="discounted-price">${{$productdetails[0]['price_total']}}</span>
                                    {{--<span class="real-price">€100,99</span><br>--}}
                                    {{--<span class="offer-discount">15% OFF</span>--}}
                                </h3>
                            </div>
                            <div class="col-md-6">
                                <div class="row quick-delivery">
                                    <p class="mar-top-10 text-color-lg">
                                        Product code:
                                        <span class="product-code text-color-dg"> 275</span>
                                    </p>
                                    <p class="text-color-lg">
                                        Availablity:
                                        <span class="availablity text-color-dg">{{($productdetails[0]['in_stock'] != 0) ? 'InStock' : 'Outofstock'}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row quickoverview">
                            <div class="col-md-12">
                                <h4>Description</h4>
                                <div class="comment more">
                                    <span class="moreelipses">{{$productdetails[0]['short_description']}}</span>
                                    <span class="morecontent">
                                        <a class="morelink" href="#">Read more</a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php $combination = [];
                        if ($productdetails[0]['variant_ids_combination'] != '' && $productdetails[0]['variant_ids_combination'] != null) {
                            $array1 = array_unique(explode(",", str_replace('_', ',', $productdetails[0]['variant_ids_combination'])));
                            $combinedVariantIds = array_unique(explode(",", str_replace('_', ',', $productdetails[0]['variant_ids_combination'])));
                        }?>
                        @foreach($productdetails[0]['options'] as $optionKey => $optionVal)
                            <div class="row quick-size-color">
                                <div class="col-md-6 option">
                                    <h5>{{$optionVal['option_name']}}</h5>
                                    @foreach($optionVal['variantData']['variant_id']  as $variantKey => $variantVal)
                                        <div prod-id="{{$productdetails[0]['product_id']}}" data-id="{{$optionVal['variantData']['variant_id'][$variantKey]}}" pricemodifier="{{$optionVal['variantData']['price_modifier'][$variantKey]}}" value="{{$optionVal['variantData']['variant_id'][$variantKey]}}" option-id="{{$optionVal['option_id']}}" variant-id="{{$optionVal['variantData']['variant_id'][$variantKey]}}" class="size option-variant">
                                            {{$optionVal['variantData']['variant_name'][$variantKey]}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        @if(isset($productdetails[0]['quantity_discount']) && $productdetails[0]['quantity_discount'] != '')
                            @foreach(json_decode($productdetails[0]['quantity_discount']) as $qKey => $qVal)
                            @endforeach
                            @if ($qVal->quantity != null && $qVal->value != null &&
                            $qVal->type != null)
                                <div class="portlet-body">
                                    <div class="table-scrollable">
                                        Our quantity discounts:
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(json_decode($productdetails[0]['quantity_discount']) as $qKey => $qVal)
                                            @if ($qVal->quantity != null && $qVal->value != null &&
                                            $qVal->type != null)
                                                <tr>
                                                    <td>{{$qVal->quantity}}</td>
                                                    <td>
                                                        <span>{{$qVal->value}}</span>{{($qVal->type) ? '%' : '$'}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        <div class="row add-cart">
                            <div class="col-md-2 col-xs-2 col-sm-3">
                                <p class="sub-head">Quantity</p>
                                <div class="form-group width-100">
                                    {{--<label class="control-label decrease-quan" for="">--}}
                                    {{--<i class="fa fa-minus"></i>--}}
                                    {{--</label>--}}
                                    <input min="0" id="input-item-count" class="form-control quantity" type="text"
                                            style="padding:0; text-align:center; width:50px;margin-left: 6px;"
                                            name="input-item-count">
                                    {{--<label class="control-label increase-quan" for="">--}}
                                    {{--<i class="fa fa-plus"></i>--}}
                                    {{--</label>--}}
                                </div>

                            </div>
                            <div class="col-md-10">
                                <div class="row buy_button">
                                    <button class="btn btn-primary btn-lg brdr_non pading_lr top15_margin cartOptions" role="button">
                                        ADD TO CART
                                    </button>
                                    {{--<a class="btn btn-primary btn-lg brdr_non pading_lr top15_margin" href="#"--}}
                                    {{--role="button">ADD TO WISHLIST</a>--}}
                                    {{--<form class="payment" method="POST" id="pay-payment" action="/paypal-payment">--}}
                                    {{--<input type="hidden" id="hidden-product-id" name="productId" value="{{$productdetails[0]['product_id']}}">--}}
                                    {{--<input type="hidden" id="hidden-option-id" name="variantId" value="">--}}
                                    {{--<input type="hidden" id="hidden-qunatity-id" name="quantityId" value="">--}}
                                    {{--<a href="javascript:;" data-paypal-button="true">--}}
                                    {{--<img src="//www.paypalobjects.com/en_US/i/btn/btn_paynow_LG.gif" alt="Pay Now" id="buy-now"/>--}}
                                    {{--</a>--}}
                                    {{--</form>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                            {{--<div class="row Estimated_date">--}}
                            {{--<b>ESTIMATED DELIVERY</b><br>--}}
                            {{--<SPAN CLASS="EDELIVERY">--}}
                            {{--<span class="SUN">SUN 03/20/2016-</span>--}}
                            {{--<span class="real-price">04/06/2016</span>--}}
                            {{--</SPAN>--}}
                            {{--</div>--}}
                            <div class="clearfix"></div>
                            <div class="portlet-title tabbable-line">
                                <ul class="nav nav-tabs">
                                    @if(isset($productdetails[0]['product_tabs']))
                                        <?php $mainDesc = json_decode($productdetails[0]['product_tabs']); ?>
                                        @if($mainDesc->description == 1)
                                            @if(isset($productdetails[0]['product_full_description']) && !empty($productdetails[0]['product_full_description']))
                                                <li class="active"><a href="#tab_1_1" data-toggle="tab">Description</a>
                                                </li>
                                            @endif
                                        @endif
                                        @if($mainDesc->features == 1)
                                            @if(isset($productdetails[0]['feature_names']) && !empty($productdetails[0]['feature_names']))
                                                <li><a href="#tab_1_2" data-toggle="tab">Features</a></li>
                                            @endif
                                        @endif
                                        @if($mainDesc->tags == 1)
                                            <li><a href="#tab_1_3" data-toggle="tab">Tags</a></li>
                                        @endif
                                        {{--@if($mainDesc->reviews == 1)--}}
                                        {{--<li><a href="#tab_1_4" data-toggle="tab">Reviews</a></li>--}}
                                        {{--@endif--}}
                                    @endif
                                </ul>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data" autocomplete="on" id="addProductForm" onsubmit="return false;">

                                <div class="tab-content">
                                    {{--GENERAL DETAILS TAB--}}
                                    <div class="tab-pane active" id="tab_1_1">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingInfo">
                                                    <p>{{$productdetails[0]['product_full_description']}} </p>
                                                    {{--<p>{{$reviewdetails[0]['product_full_description']}} </p>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane" id="tab_1_2">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingInfo">
                                                    @foreach($productdetails as $featurekey => $featureval)
                                                        {{--                                                <h3>{{$featureval['feature_names']}} </h3>--}}
                                                        <div class="ty-product-feature">
                                                            {{--<span class="ty-product-feature__label">{{$featureval['feature_names']}}--}}
                                                            {{--:</span>--}}
                                                            <h3>{{$featureval['feature_names']}} :</h3>
                                                            <div class="ty-product-feature__value">
                                                                {{--@if(isset($featureval) && !empty($featureval))--}}
                                                                @foreach (explode(",", $featureval['feature_variant_name']) as $key => $val)
                                                                    {{" " . $val}}
                                                                @endforeach
                                                                {{--@else--}}
                                                                {{--{{"No features"}}--}}
                                                                {{--@endif--}}
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab_1_3">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingInfo">
                                                    <p>Tags</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="tab-pane" id="tab_1_4">--}}
                                    {{--<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">--}}
                                    {{--<div class="panel panel-default">--}}
                                    {{--<div class="panel-heading" role="tab" id="headingInfo">--}}

                                    {{--<div id="review_details">--}}

                                    {{--<div id='appenderror'></div>--}}
                                    {{--<br><br>--}}
                                    {{--<ul class="comments" id='appendreview' style="list-style-type: none;">--}}

                                    {{--</ul>--}}
                                    {{--<div id="writenewreview">--}}
                                    {{--<h3>WRITE A REVIEW</h3>--}}
                                    {{--<p>Now please write a (short) review....(min. 200, max. 2000 characters)</p>--}}
                                    {{--<textarea id="review_description" name="review_textarea"></textarea>--}}
                                    {{--<span class="success_error1"></span><br>--}}
                                    {{--<div class="stars">--}}
                                    {{--<label class="pull-left rating-box-label">Your Rate:</label>--}}
                                    {{--<input type="radio" name="star" id="star-5" class="star star-5" value="5">--}}
                                    {{--<label for="star-5" class="star star-5"></label>--}}
                                    {{--<input type="radio" name="star" id="star-4" class="star star-4" value="4">--}}
                                    {{--<label for="star-4" class="star star-4"></label>--}}
                                    {{--<input type="radio" name="star" id="star-3" class="star star-3" value="3">--}}
                                    {{--<label for="star-3" class="star star-3"></label>--}}
                                    {{--<input type="radio" name="star" id="star-2" class="star star-2" value="2">--}}
                                    {{--<label for="star-2" class="star star-2"></label>--}}
                                    {{--<input type="radio" name="star" id="star-1" class="star star-1" value="1">--}}
                                    {{--<label for="star-1" class="star star-1"></label>--}}
                                    {{--</div>--}}
                                    {{--<br>--}}
                                    {{--<span class="success_error"></span>--}}

                                    {{--<input type="submit" class="btn btn-primary" id="submitbutton" style="color: white !important;" data-id="@if (isset($productdetails[0])) {{$productdetails[0]['product_id']}} @endif" value="Submit a review">--}}
                                    {{--</div>--}}
                                    {{--<button id="seemore" class="btn btn-primary col-md-8 col-sm-offset-2 " style="margin-top:15px;" data-id="@if (isset($productdetails[0])) {{$productdetails[0]['product_id']}} @endif">--}}
                                    {{--See more--}}
                                    {{--</button>--}}
                                    {{--<br><br><br><br>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid u_heading">
                <p>YOU MAY ALSO LIKE</p>
            </div>

            <div class="container h_text">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        @if(isset($productdetails[0]))
                            @foreach($productdetails[0]['relatedProducts'] as $relatedKey => $relatedVal)
                                <div class="col-md-3">
                                    <div class="quickdown">
                                        <a href="/product-details/{{$relatedVal['product_id']}}/{{str_replace(" ","-",$relatedVal['product_name'])}}">
                                            <img src="{{$relatedVal['image_url']}}" class="img-responsive">
                                        </a>
                                    </div>
                                    <div class="you_text1">
                                        <p>{{$relatedVal['product_name']}}<br>{{$relatedVal['full_description']}}
                                            <br> {{$relatedVal['price_total']}}(incl. Duties and VAT)<br>
                                            {{--<del> £ 132.47-£ 169.78</del>--}}
                                        </p>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{--</div>--}}

@endsection
@section('pagejavascripts')
    {{--<script type="text/javascript" src="/assets/scripts/jquery.mThumbnailScroller.js"></script>--}}
    {{--<script src="/assets/scripts/jquery-ui.js" type="text/javascript"></script>--}}
    {{--<script type="text/javascript" src="/assets/scripts/fancybox.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/scripts/script.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/global/plugins/jquery.etalage.min.js"></script>--}}
    <script type="text/javascript" src="/assets/buyer/js/jqzoom.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {

//            $('.fixed-menu , .slide-left').on('click', function () {
//                $("#filter-md-sm, .fixed-menu ").toggle("slide");
//            });

            var actualResponse = [];
            var combinedVariantIds = [];
            $(window).on("load", function () {

                actualResponse['price_total'] = "{{$productdetails[0]['price_total']}}"
                actualResponse['product_name'] = "{{$productdetails[0]['product_name']}}"
                actualResponse['variant_ids_combination'] = "{{$productdetails[0]['variant_ids_combination']}}"
                actualResponse['image_url'] = "{{$productdetails[0]['image_url']}}"
                actualResponse['image_urls'] = "{{$productdetails[0]['image_urls']}}"
                actualResponse['variant_ids_combination'] = "{{$productdetails[0]['variant_ids_combination']}}"
//            if (actualResponse['variant_ids_combintaion'] != null && actualResponse['variant_ids_combination'] != '') {
                combinedVariantIds = ("{{implode(',', array_unique(explode(',', str_replace('_', ',', $productdetails[0]['variant_ids_combination']))))}}").split(',')
//            }

            });

            $("#etalage").zoom({
                zoom_area_width: 350,
                autoplay_interval: 3000,
                small_thumbs: 4,
                autoplay: false
            });


            var actualResponse = [];
            var combinedVariantIds = [];

            actualResponse['price_total'] = "{{$productdetails[0]['price_total']}}"
            actualResponse['product_name'] = "{{$productdetails[0]['product_name']}}"
            actualResponse['variant_ids_combination'] = "{{$productdetails[0]['variant_ids_combination']}}"
            actualResponse['image_url'] = "{{$productdetails[0]['image_url']}}"
            actualResponse['image_urls'] = "{{$productdetails[0]['image_urls']}}"
            actualResponse['variant_ids_combination'] = "{{$productdetails[0]['variant_ids_combination']}}"
//            if (actualResponse['variant_ids_combintaion'] != null && actualResponse['variant_ids_combination'] != '') {
            combinedVariantIds = ("{{implode(',', array_unique(explode(',', str_replace('_', ',', $productdetails[0]['variant_ids_combination']))))}}").split(',')
//            }


            var selected = [];
            var submitFlag = false;

            function checkSelectedOption() {
                if (Number($('.quantity').val()) < 1) {
                    submitFlag = false;
                    toastr["error"]('Please select quantity');
                } else {
                    submitFlag = true;
                }
                $.each($(".option"), function (i, v) {
                    if ($(this).find('.active').not('.disabled').length == 0) {
                        submitFlag = false;
                        toastr["error"]('Please select ' + $(this).find('h5').text());
                    } else {
                        submitFlag = true;
                    }
                    if ($(this).find('.active').not('.disabled').length != 0) {
                        selected.push($(this).find('.active').attr('variant-id'));
                    }

                });
            }

            var newData = [],
                    arrIndex = {};

            $(document.body).on('click', '.cartOptions', function () {//todo write this in common_scripts
                alert("pd");
                var obj = $(this);
                var selectedCookie = [];
                selected = [];
                checkSelectedOption();
                console.log(selected);
                $('#add-to-cart').empty();
                console.log($('.quantity').val());
                if (((selected != null && selected.length == $(".option").length) || selected == null) && Number($('.quantity').val()) > 0) {
                    var oldCookieData = '';
                    var prodId = $('.option-variant').attr('prod-id');
                    var priceModifier = $('.option-variant').attr('pricemodifier');
                    var quantity = Number($('.quantity').val());
                    var variantId = $('.option-variant').attr('variant-id');
                    var arr = [{"prodId": prodId, "quantity": quantity, "combination_id": selected.toString()}];
                    var cookie_value = JSON.stringify(arr);
                    var usersession = "{{Session::get('fs_buyer')['id']}}";
                    if (typeof usersession != "undefined" && usersession) {
                        $.ajax({
                            url: '/order-ajax-handler',
                            type: 'POST',
                            datatype: 'json',
                            data: {
                                method: 'add-to-cart',
                                prodId: prodId,
                                quantity: quantity,
                                selected: selected
                            },
                            success: function (response) {
                                console.log(response);
                                response = $.parseJSON(response);
                                toastr[response['status']](response['msg']);
                            }
                        });
//                    } else {
//                        newData = (getCookie('cart_cookie_name') != '') ? JSON.parse(getCookie('cart_cookie_name')) : [];
//                        addNewElement(newData, {
//                            prodId: prodId,
//                            quantity: quantity,
//                            combination_id: selected.toString(),
//                            cmbImage: cmbImage,
//                            cmbPrice: cmbPrice
//                        });
                    }
//                    if (newData.length != 0) {
//                        $('#add-to-cart').append('<a class="index" href="#cart" data-toggle="modal" data-target=".cart-bs-example-modal-lg"><img src="/assets/images/cart.png" class="img-responsive;" style="float: left; margin-top: 5px; margin-left: 10px;"><span>' + newData.length + '</span><span id="cart">Cart</span> </a>');
//                    } else {
//                        $('#add-to-cart').append('<a class="index"href="#cart" data-toggle="modal" data-target=".cart-bs-example-modal-lg"><img src="/assets/images/cart.png" class="img-responsive;" style="float: left; margin-top: 5px; margin-left: 10px;"><span>0</span><span id="cart">Cart</span> </a>');
//                    }
                }
            });

            //        $('.quick-view.p-a').on('click', function () {
            //            $('#myModal').modal("show");
            //        }
            //  FOR QUICK VIEW OF PRODUCT IN MODAL      //
            //        var actualResponse = [];
            //        var discountPrice = [];
            //        var discounts = [];
            //        var combinedVariantIds = [];
            //        $(document.body).on('click', '#quicks', function () {
            //            var prodId = $(this).attr('data-id');
            //            var productName = $(this).attr('product-name');
            //            $.ajax({
            //                url: '/flashsale-ajax-handler',
            //                type: 'POST',
            //                datatype: 'json',
            //                data: {
            //                    method: 'getProductDetailsForPopUp',
            //                    prodId: prodId
            //                },
            //                success: function (response) {
            //                    var response = $.parseJSON(response);
            //                    var optionResponse = [];
            //                    $.each(response, function (actindex, actval) {
            //                        actualResponse[actindex] = actval;
            //                    });
            //                    $('#prods').html('<span class="prodcut_name">' + actualResponse['product_name'] + '</span>');
            //                    $('#price-total').html('<span class="real-price">' + actualResponse['price_total'] + '</span>');
            //                    if (actualResponse['in_stock'] != '') {
            //                        availableResponse = 'InStock';
            //                    }
            //                    else {
            //                        availableResponse = 'Outofstock';
            //                    }
            //                    $('.available').html('<span class="availablity text-color-dg">Available: ' + availableResponse + '</span>');
            //                    $("#discount-value").empty();
            //                    var toAppendProductVariant = '';
            //                    if ($.parseJSON(response['quantity_discount'] != '')) {
            //                        toAppendProductVariant += '<div class="portlet-body">';
            //                        toAppendProductVariant += '<div class="table-scrollable">Our quantity discounts:</div>';
            //                        toAppendProductVariant += '<table class="table table-bordered table-hover">';
            //                        toAppendProductVariant += '<thead>';
            //                        toAppendProductVariant += '<tr>';
            //                        toAppendProductVariant += '<th>Quantity</th>';
            //                        toAppendProductVariant += '<th>Price</th>';
            //                        $.each($.parseJSON(response['quantity_discount']), function (discountIndex, discountValue) {
            //                            if (discountValue['quantity'] != null && discountValue['value'] != null && discountValue['type'] != null) {
            ////                            toAppendProductVariant += '<th>' + discountValue['quantity'] + '</th>';
            //                                toAppendProductVariant += '</tr>';
            //                                toAppendProductVariant += '</thead>';
            //                                toAppendProductVariant += '<tbody>';
            //                                toAppendProductVariant += '<tr>';
            //                                toAppendProductVariant += '<td>' + discountValue['quantity'] + '</td>';
            //                                toAppendProductVariant += '<td><span>' + discountValue['value'] + '</span>' + ((discountValue['type']) ? '%' : '$') + '</td>';
            //                                toAppendProductVariant += '</tr>';
            //
            //                            }
            //                            else {
            //                                toAppendProductVariant += '<span></span>';
            //                            }
            //                        });
            //                        toAppendProductVariant += '</tbody>';
            //                        toAppendProductVariant += '</table>';
            //                        toAppendProductVariant += '</div>';
            //                        toAppendProductVariant += '</div>';
            //                        toAppendProductVariant += '</div>';
            //                    }
            //                    $("#discount-value").append(toAppendProductVariant);
            //                    var image = [];
            //                    var otherimg = [];
            //                    $("#etalage").empty();
            //                    $('.quick-view-img').empty();
            //                    $.each(response['image_urls'].split(","), function (index, value) {
            //                        image[index] = value;
            //                        $("#etalage").append('<li><img class="etalage_thumb_image" src="..' + value + '" alt="" />');
            //                        $('#etalage').append('<img class="etalage_source_image" src="..' + value + '" alt="" /></li>');
            //
            //                    });
            //                    var combination = [];
            //                    if (response['variant_ids_combination'] != '' && response['variant_ids_combination'] != null) {
            //                        var array1 = $.unique((response['variant_ids_combination'].replace(/_+/g, ',')).split(','));
            //                        combinedVariantIds = $.unique((response['variant_ids_combination'].replace(/_+/g, ',')).split(','));
            //                    }
            //                    $("#option-details").empty();
            //                    var toAppendOptionDetails = '';
            //                    $.each(response['options'], function (optionIndex, optionValue) {
            //                        toAppendOptionDetails += '<div class="col-md-12 bdr-btm-col paddr-10 option-variant-container" style="margin-bottom: 3%">';
            //                        toAppendOptionDetails += '<div class="row">';
            //                        toAppendOptionDetails += '<div class="col-md-12">';
            //                        toAppendOptionDetails += '<div class="clearfix" id="option_id_"' + optionValue['option_id'] + '>';
            //                        toAppendOptionDetails += '<div class="form-group">';
            //                        toAppendOptionDetails += '<label class="col-sm-3 control-label">Select ' + optionValue['option_name'] + '</label>';
            //                        toAppendOptionDetails += '<div class="col-sm-4">';
            ////                         TODO check for type
            //
            //
            //                        toAppendOptionDetails += '<select class="pull-right basic form-control option">';
            //                        toAppendOptionDetails += '<option  value="">Select...</option>';
            //                        $.each(optionValue['variantData']['variant_id'], function (i, v) {
            //                            toAppendOptionDetails += '<option prod-id="' + response['product_id'] + '" data-id="' + optionValue['variantData']['variant_id'][i] + '" pricemodifier="' + optionValue['variantData']['price_modifier'][i] + '" value="' + optionValue['variantData']['variant_id'][i] + '" class="option-variant">' + optionValue['variantData']['variant_name'][i] + '</option>';
            //                        });
            //                        toAppendOptionDetails += '</select>';
            ////                       TODO end- check for type
            //                        toAppendOptionDetails += '</div>';
            //                        toAppendOptionDetails += '</div>';
            //                        toAppendOptionDetails += '</div>';
            //                        toAppendOptionDetails += '</div>';
            //                        toAppendOptionDetails += '</div>';
            //                        toAppendOptionDetails += '</div>';
            //                    });
            //
            //                    $("#option-details").append(toAppendOptionDetails);
            //                    $('#etalage').etalage({
            //                        thumb_image_width: 400,
            //                        thumb_image_height: 400,
            //                        source_image_width: 900,
            //                        source_image_height: 1200,
            //                        show_hint: true,
            //                        click_callback: function (image_anchor, instance_id) {
            //                            alert('Callback example:\nYou clicked on an image with the anchor: "' + image_anchor + '"\n(in Etalage instance: "' + instance_id + '")');
            //                        }
            //                    });
            //                    $('#myModal').modal("show");
            //                }
            //            });
            //
            //        });

            var autoplay = false;
            $(document.body).on('click', '.option-variant', function () {
                var obj = $(this);
                var variantId = obj.attr('variant-id');
                if (!obj.hasClass('active')) {
                    var optionObj = obj.parents('.option');
                    var allVariants = optionObj.children('.option-variant');
                    allVariants.removeClass('active');
                    obj.addClass('active');
                }
                if (combinedVariantIds && ((combinedVariantIds.indexOf(obj.attr('data-id')) >= 0))) {
                    autoplay = false;
                    var optionOBJ;
                    $.each($(".option-variant"), function (index, value) {
                        optionOBJ = $(this);
                        if (combinedVariantIds.indexOf(optionOBJ.attr('data-id')) >= 0) {
                            optionOBJ.removeClass('disabled');
                        } else {
                            optionOBJ.addClass('disabled');
                        }
                    });
                    $('.availablity').html('Instock');
                    $('.cartOptions').prop('disabled', false);
                } else {
                    autoplay = false;
                    $('.option-variant').not(obj).addClass('disabled');
                    $('.availablity').html('Out of stock');
                    $('.cartOptions').prop('disabled', true);
                }

                var prodId = $('.option-variant').attr('prod-id');

                var selectedCombination = [];
                $.each($('.option-variant.active'), function (i, v) {
                    selectedCombination.push($(this).attr('variant-id'));
                });

                var priceModifier = $(this).attr('pricemodifier');
                var image = [];
                $.ajax({
                    url: '/flashsale-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getOptionVariantDetails',
                        priceModifier: priceModifier,
                        variantId: variantId,
                        prodId: prodId,
                        selectedCombination: selectedCombination
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response['variant_ids_combination'] != '' && response['variant_ids_combination'] != null) {
                            var variantPrice = Number(parseFloat(response['price_modifier']).toFixed(3)) + Number(parseFloat(actualResponse['price_total']).toFixed(3));
                            if (variantPrice) {
                                $('#price-total').html('<span class="real">$' + variantPrice + '</span>');
                            } else {
                                $('#price-total').html('<span class="real">' + actualResponse['price_total'] + '</span>');
                            }
                            $("#etalage").empty();
                            if (response['image_urls'] != '' && response['image_urls'] != null) {
                                $("#etalage").append('<li><img class="bzoom_thumb_image" src="' + response['image_urls'] + '" alt="" /><img class="bzoom_big_image" src="' + response['image_urls'] + '" alt="" /></li>');
                            } else {
                                $("#etalage").append('<li><img class="bzoom_thumb_image" src="' + actualResponse['image_url'] + '" alt="" /><img class="bzoom_big_image" src="' + actualResponse['image_url'] + '" alt="" /></li>');
                            }
                            $("#etalage").zoom({
                                zoom_area_width: 350,
                                autoplay_interval: 3000,
                                small_thumbs: 4,
                                autoplay: false
                            });
                        } else {
                            var variantPrice = Number(parseFloat(response['price_modifier']).toFixed(3)) + Number(parseFloat(actualResponse['price_total']).toFixed(3));
                            if (variantPrice) {
                                $('#price-total').html('<span class="real">' + variantPrice + '</span>');
                            } else {
                                $('#price-total').html('<span class="real">' + actualResponse['price_total'] + '</span>');
                            }
                            $("#etalage").empty();
                            $('.quick-view-img').empty();
                            $.each(actualResponse['image_urls'].split(","), function (index, value) {
                                image[index] = value;
                                $("#etalage").append('<li><img class="bzoom_thumb_image" src="' + value + '" alt="" /><img class="bzoom_big_image" src="' + value + '" alt="" /></li>');
                            });
                            $("#etalage").zoom({
                                zoom_area_width: 350,
                                autoplay_interval: 3000,
                                small_thumbs: 4,
                                autoplay: false
                            });
                        }
                    }
                });

            });

            //        $(document.body).on('click', '.quicks', function () {
            //            $('#myModal').modal("hide");
            //        });

            //            $(obj.parents('div.items-selection-parent').find("input")).each(function () {
            //                if ($(this).attr("checked")) {
            //                    selected.push($(this).val());
            //                }
            //            });

            //            if (selected.length > 1) {
            //                var finalSelect = selected;
            //            } else {
            //                var finalSelect = selected;
            //                toastr["error"]('plz select ' + $(this).find('h5').text());
            //            }

//        $('#login-content').show();

            /*TODO low:easy decide wether to show reviews here or not and which reviews to show
             $("#seemore").click(function () {
             console.log("its came");
             var obj = $(this);
             var product_id = obj.attr('data-id');
             var counter = 0;

             $.ajax({
             url: "/users/product-ajax-handler",
             type: 'POST',
             datatype: 'json',
             data: {
             method: 'productreviewDetail',
             start: counter,
             productId: product_id

             },
             success: function (response) {

             //                    console.log(response[]);
             //                    $.each(response,function(i,o){
             //                       console.log(o.username);
             //                        response=0.username;
             //                    });

             var response = response[0];

             //                    console.log(response);
             //                    console.log(response.username);
             //                        <p><span class="date">'+response.updated_at+'</span></p>

             $('#appendreview').append('<p><strong> Username: </strong>' + response.username + '</p><br><p><strong>Review rating: </strong>' + response.review_rating + '</p><br><p><strong>Review Details: </strong>' + response.review_details + '</p><br><br>');
             //                    $('#appendreview').append('<li><div class="clearfix"><p class="pull-left"><strong><a href="javascript:void(0);" >' + value.username + ' </a></strong></p><span class="date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +response.updated_at+ '</span></div></li>');
             if (userdata) {
             $("#seemore").hide();
             if (counter == 0) {
             $('#appenderror').append('<h3 style="float:left;position:relative;left:35%;color:red">No Review for this Product</h3><br><br>');
             $("#writenewreview").removeClass('hidden');
             }
             }
             counter = counter + 20;
             },
             error: function () {
             }
             });

             });

             $("#submitbutton").click(function () {
             var review_rating = $('input[name="star"]:checked').attr('value');
             var product_id = $(this).attr('data-id');
             var review_details = $("#review_description").val();
             var review_type = $(this).attr('data_review');
             if (review_rating == undefined) {
             $('.success_error').show();
             $('.success_error').html("Please provide star rating.");
             $('.success_error').css('color', 'red');
             $('.success_error').delay(5000).hide('slow');

             }
             if (!review_details) {
             $('.success_error1').show();
             $('.success_error1').html("Please provide Review.");
             $('.success_error1').css('color', 'red');
             $('.success_error1').delay(5000).hide('slow');
             }
             if (review_details != '' && review_rating != undefined) {
             $.ajax({
             url: "/user/product-ajax-handler",
             type: 'POST',
             datatype: 'json',
             data: {
             method: 'add_review',
             starrating: review_rating,
             review_details: review_details,
             review_by: product_id
             },
             success: function (response) {
             console.log(response);
             if (response['code'] == 200) {

             $('#appenderror').html('<h3 style="float:left;position:relative;left:35%;color:red">Thank you for your valuable feedback.</h3><br><br>');
             $("#writenewreview").addClass('hidden');
             $("#review_description").val("");
             } else {
             if (response == 0) {
             window.scrollTo(0, 0);
             $('#login-content').show();
             }

             }
             }
             });
             }
             })
             */

        });
    </script>

@endsection