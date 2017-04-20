<link href="/assets/global/css/etalage.css" media="screen" rel="stylesheet" type="text/css"/>


<style>
    .etalage_zoom_preview {
        opacity: 1 !important;
    }

    li.etalage_zoom_area div:last-child {
        width: 440px !important;
        /*height: 440px !important;*/
    }
</style>

<div class="modal fade bs-example-modal-lg-product-popup" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" id="myModal">
    <div class="modal-dialog _vp">
        <div class="modal-content _pv">
            <div class="container-fluid  _vip">
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body _vip2">
                            <div class="col-md-6 clearfix">
                                {{--<div class="bzoom_wrap ">--}}
                                <ul id="etalage" class="image_zoom"
                                        style="margin-bottom: 0; padding-bottom: 0;">
                                    <li>
                                        <img class="etalage_thumb_image" src=""/>
                                        <img class="etalage_source_image" src=""/>
                                    </li>
                                </ul>
                                {{--</div>--}}
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="close fa fa-times fa-2x" data-dismiss="modal"
                                        aria-label="Close">
                                </button>
                                <div class="row xy_tshirt">
                                    <div class="col-md-6 t_shirt">
                                        <span id="prods"></span>
                                        <div class="stars st_reviews">
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
                                        <h3 id="price-total">
                                            {{--<span class="discounted-price">&pound 89,99</span>--}}
                                            {{--<span class="real-price">&pound 100,99</span><br>--}}
                                            {{--<span class="offer-discount">15% OFF</span>--}}
                                        </h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row quick-delivery">
                                            <p class="mar-top-10 text-color-lg">
                                                Product code:
                                                <span class="product-code text-color-dg"> 275</span>
                                            </p>
                                            <p class="text-color-lg available">
                                                Availablity:
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row quickoverview">
                                    <div class="col-md-12">
                                        <h4>QUICK REVIEW</h4>
                                        <p class="tp-pro" id="full_description">
                                        </p>
                                        {{--<div class="comment more">--}}
                                        {{--Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum laoreet, nunc eget laoreet sa--}}
                                        {{--<span class="moreelipses">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum laoreet, nunc eget laoreet sa</span>--}}
                                        {{--<span class="morecontent">--}}
                                        {{--<a class="morelink" href="#">Read more</a>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                                <div class="option-details col-md-12 bdr-btm-col"
                                        id="option-details"></div>
                                {{--<div class="row quick-size-color">--}}
                                {{--<div class="col-md-12 option">--}}
                                {{--<h5>SIZE</h5>--}}
                                {{--<div class="size">XS</div>--}}
                                {{--<div class="size">S</div>--}}
                                {{--<div class="size">M</div>--}}
                                {{--<div class="size active">L</div>--}}
                                {{--<div class="size">XL</div>--}}
                                {{--<div class="size">XXL</div>--}}

                                {{--</div>--}}
                                {{--<div class="col-md-12">--}}
                                {{--<h5>COLOR</h5>--}}
                                {{--<a href="#">--}}
                                {{--<div class="color first"></div>--}}
                                {{--<div class="color sec"></div>--}}
                                {{--<div class="color thi"></div>--}}
                                {{--<div class="color fou"></div>--}}
                                {{--<div class="color fiv"></div>--}}
                                {{--<div class="color six"></div>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="discount-value col-md-12 bdr-btm-col"
                                        id="discount-value"></div>
                                <div class="row add-cart">
                                    <div class="col-md-2 col-xs-2 col-sm-3">
                                        <p class="sub-head">Quantity</p>
                                        <div class="form-group width-100">
                                            <label class="control-label decrease-quan" for="">
                                                <i class="fa fa-minus"></i>
                                            </label>
                                            <input id="input-item-count" class="form-control quantity" type="text"
                                                    style="padding:0; text-align:center; width:50px;margin-left: 6px;"
                                                    name="input-item-count">
                                            <label class="control-label increase-quan" for="">
                                                <i class="fa fa-plus"></i>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-md-7">
                                        <div class="row buy_button">
                                            <a class="btn btn-primary btn-lg brdr_non pading_lr top15_margin p_pop cartOptions"
                                                    href="#" role="button">ADD TO CART</a>
                                        </div>
                                    </div>
                                    <!--<div class="">
                                        <a href="#">+Add to Whistlist</a><br>
                                        <a href="#">+Add to Comepare</a><br>
                                        <a href="#">+Email to a Friend</a>
                                    </div>-->
                                </div>
                            </div>


                        </div>
                        <br/>
                    <!--<div class="col-md-12">
                            <div class="portlet-title tabbable-line">
                                <ul class="nav nav-tabs feature-tab" id="features-tab">
                                    {{--<li class="active feature-desc"><a href="#tab_1_1" data-toggle="tab">Description</a></li>--}}
                    {{--<li class="feature-feature"><a href="#tab_1_2" data-toggle="tab">Features</a></li>--}}
                    {{--<li class="feature-tags"><a href="#tab_1_3" data-toggle="tab">Tags</a></li>--}}
                    {{--<li class="feature-review"><a href="#tab_1_4" data-toggle="tab">Reviews</a></li>--}}
                            </ul>
                        </div>
                    </div>-->
                    </div>
                </div>
            </div>
            <br/>
        </div>
    </div>
</div>
<div class="modal fade cart-bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog">

        {{--=========================================================--}}

        <div class="modal-content _check_pop">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img
                            src="/assets/images/close.png">
                </button>
                <h4 class="modal-title" id="gridSystemModalLabel">My Chart</h4>
            </div>
            <div class="modal_body">
                <div class="container _ccp">
                    <div class="row pop_2nd_row">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="col-md-3 vat_included">
                                <p class="sub_total fa fa-rupee"><span id="sub_total">Sub Total: </span></p>
                                <p>VAT included</p>

                            </div>
                            <div class="col-md-3 pop_button">
                                <a href="javascript:;" class="btn btn-info" role="button" id="checkout">CHECK OUT
                                    NOW</a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="container cartcontainer">
                    {{--<div class="row">--}}
                    {{--<div class="col-md-12 pop_image">--}}
                    {{--<div class="col-md-4">--}}
                    {{--<img src="" class="img-responsive pop-img" style="height: 100px; width:100px;">--}}

                    {{--<div class="pop_text">--}}
                    {{--<p class="prod_name">YX White T-SHIRT </p>--}}

                    {{--<p class="prod_price">£ 89.90--}}
                    {{--<del> £ 100.90</del>--}}
                    {{--</p>--}}
                    {{--<b class="offer_percent">15% off</b>--}}

                    {{--<p class="pop_estimate">ESTIMATED DELIVERY:--}}
                    {{--<span>SUN 03/20/2016- WED 04/06/2016</span>--}}
                    {{--</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<!--<div class="col-md-2 3rd_rowtext">-->--}}
                    {{--<!--<span class="pop_text">YX White T-SHIRT<br> £ 89.90 <del> £ 100.90</del> <br> <i class="offer_percent">15% off</i></span><br>-->--}}
                    {{--<!--<span class="pop_estimate">ESTIMATED DELIVERY<br>-->--}}
                    {{--<!--<span class="SUN">SUN 03/20/2016-</span>-->--}}
                    {{--<!--<span class="real-price">04/06/2016</span>-->--}}
                    {{--<!--</span>-->--}}
                    {{--<!--</div>-->--}}
                    {{--<div class="col-md-1 select_quantity">--}}
                    {{--<a class="btn btn-default btn-select btn-select-light _cop3">--}}
                    {{--<input type="hidden" class="btn-select-input" id="" name="" value=""/>--}}
                    {{--<span class="btn-select-value cop">1</span>--}}
                    {{--<span><img class="arrow1 _cop1" src="/assets/images/arrow.png"></span>--}}
                    {{--<ul>--}}
                    {{--<li>1</li>--}}
                    {{--<li>2</li>--}}
                    {{--<li>3</li>--}}
                    {{--<li>4</li>--}}
                    {{--<li>5</li>--}}
                    {{--</ul>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2 pop_rate">--}}
                    {{--<p>&pound89.99</p>--}}
                    {{--<a href="#">--}}
                    {{--<p>Remove</p>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
        {{--==================================================================--}}

    </div>
</div>

<script type="text/javascript" src="/assets/scripts/jquery.mThumbnailScroller.js"></script>
{{--<script src="/assets/scripts/jquery-ui.js" type="text/javascript"></script>--}}
{{--<script type="text/javascript" src="/assets/scripts/fancybox.js"></script>--}}
{{--<script type="text/javascript" src="/assets/scripts/script.js"></script>--}}
<script type="text/javascript" src="/assets/global/plugins/jquery.etalage.min.js"></script>

<script type="text/javascript">

    (function ($) {
        $(window).load(function () {
//                $(".product-thumbs-list").mThumbnailScroller({
//                    axis: "y",
//                    type: "click-thumb",
//                    theme: "buttons-out" //change to "y" for vertical scroller
//                });
        });
    })(jQuery);

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

    $('#add-to-cart').empty();

    function get_notification() {

        var feedback = $.ajax({
            type: "POST",
            url: "/notification-ajax-handler",
            data: {
                method: 'getUserNotification',
            },
            async: false
        }).success(function (data) {
            var notifdata = jQuery.parseJSON(data);
            var notifics = notifdata[0];
            var noti = notifdata[1];

            $("#notification-content").empty();
            if (noti != null && noti != 0) {
                $.each(noti, function (index, value) {
                    var notiId = value['notification_id'];
                    var message = value['message'];
                    var timestamp = value['send_date'];
                    var time = time_ago(timestamp);
                    $("#notification-content").append('<ul class="dropdown-menu-list scroller" style="height: 40px;"><li><a data-desc="' + message + '" data-id=' + notiId + ' data-count=' + notifics + '  id="descmod"  class="modaldescription" data-toggle="modal" data-target="#mymodel">' + message + ' <span class="time">' + time + '</span></a></li></ul>');
                });
            } else {
                $("#notification-content").append('<ul class="dropdown-menu-list scroller" style="height: 40px;"><li><a id="descmod"  class="modaldescription" data-toggle="modal" data-target="#mymodel">No Notification<span class="time"></span></a></li></ul>');
            }
            $("#count").html(notifdata[0]);
            setTimeout(function () {
                get_notification();
            }, 30000);
        })
    }

    $(document).ready(function () {
        var cartCount = ((getCookie('cart_cookie_name') != '') ? JSON.parse(getCookie('cart_cookie_name')) : []).length;
        if (cartCount != '') {
            $('#add-to-cart').append('<a class="index" href="#cart" data-toggle="modal" data-target=".cart-bs-example-modal-lg"><img src="/assets/images/cart.png" class="img-responsive;" style="float: left; margin-top: 5px; margin-left: 10px;"><span>' + cartCount + '</span><span id="cart">Cart</span> </a>');

        } else {
            $('#add-to-cart').append('<a class="index" href="#cart" data-toggle="modal" data-target=".cart-bs-example-modal-lg"><img src="/assets/images/cart.png" class="img-responsive;" style="float: left; margin-top: 5px; margin-left: 10px;"><span>0</span><span id="cart">Cart</span> </a>');
        }

        var user_id = "<?php echo \Illuminate\Support\Facades\Session::get('fs_user')['id'];?>";
        if (user_id) {
            new get_notification();
        }

        $("#input-item-count").bind("change mouseleave", function () {

            var str_test = $("#input-item-count").val();

            if (/\D/.test(str_test)) {
                $("#input-item-count").val('1');
            }
        });

        $(document.body).on('click', ".increase-quan", function () {
            var tar_ele = $(this).siblings("input:text");
            if (isNaN(parseInt(tar_ele.val()))) {
                tar_ele.val('1');
            }
            value = parseInt(tar_ele.val()) + 1;
            tar_ele.val(value);
        });

        $(document.body).on('click', ".decrease-quan", function () {
            var tar_ele = $(this).siblings("input:text");
            if (isNaN(parseInt(tar_ele.val()))) {
                tar_ele.val('1');
            }
            value = parseInt(tar_ele.val()) - 1;
            tar_ele.val(value);
        });

        //  FOR QUICK VIEW OF PRODUCT IN MODAL      //
        var actualResponse = [];
        var discountPrice = [];
        var discounts = [];
        var combinedVariantIds = [];

        var autoplay = false;
        $(document.body).on('click', '.quick-view', function () {
            var prodId = $(this).attr('data-id');
            var productName = $(this).attr('product-name');
            var mainImage = $('#image-res' + prodId).attr('main-image');
            $.ajax({
                url: '/flashsale-ajax-handler',
                type: 'POST',
                datatype: 'json',
                data: {
                    method: 'getProductDetailsForPopUp',
                    prodId: prodId
                },
                success: function (response) {
                    var mainResponse = $.parseJSON(response);
                    var response = mainResponse[0];
                    var optionResponse = [];
                    $.each(response, function (actindex, actval) {
                        actualResponse[actindex] = actval;
                    });
                    $('#prods').html('<h4 class="prodcut_name">' + actualResponse['product_name'] + '</h4>');
                    $('#price-total').html('<span class="real-price">$' + actualResponse['price_total'] + '</span>');
                    $('#full_description').html(actualResponse['short_description']);
                    if (actualResponse['in_stock'] != 0) {
                        availableResponse = 'InStock';
                    }
                    else {
                        availableResponse = 'Outofstock';
                    }
                    $('.available').html('<span class="availablity text-color-dg">Available: ' + availableResponse + '</span>');
                    $("#discount-value").empty();
                    $("#features-tab").empty();
                    var toAppendProductVariant = '';
                    var toAppendFeatures = '';
                    if ($.parseJSON(response['quantity_discount'] != '')) {
                        toAppendProductVariant += '<div class="portlet-body">';
                        toAppendProductVariant += '<div class="table-scrollable">Our quantity discounts:</div>';
                        toAppendProductVariant += '<table style="width:97%" class="table table-bordered ">';
                        toAppendProductVariant += '<thead>';
                        toAppendProductVariant += '<tr>';
                        toAppendProductVariant += '<th>Quantity</th>';
                        toAppendProductVariant += '<th>Price</th>';
                        $.each($.parseJSON(response['quantity_discount']), function (discountIndex, discountValue) {
                            if (discountValue['quantity'] != null && discountValue['value'] != null && discountValue['type'] != null) {
//                            toAppendProductVariant += '<th>' + discountValue['quantity'] + '</th>';
                                toAppendProductVariant += '</tr>';
                                toAppendProductVariant += '</thead>';
                                toAppendProductVariant += '<tbody>';
                                toAppendProductVariant += '<tr>';
                                toAppendProductVariant += '<td>' + discountValue['quantity'] + '</td>';
                                toAppendProductVariant += '<td><span>' + discountValue['value'] + '</span>' + ((discountValue['type'] == 1) ? '%' : '$') + '</td>';
                                toAppendProductVariant += '</tr>';

                            }
                            else {
                                toAppendProductVariant += '<span></span>';
                            }
                        });
                        toAppendProductVariant += '</tbody>';
                        toAppendProductVariant += '</table>';
                        toAppendProductVariant += '</div>';
                        toAppendProductVariant += '</div>';
                        toAppendProductVariant += '</div>';
                    }
                    $("#discount-value").append(toAppendProductVariant);
                    if (typeof(response['product_tabs']) != "undefined") {
                        var mainDesc = $.parseJSON(response['product_tabs']);
                        if (mainDesc['description'] == 1) {
                            if (typeof(response['product_full_description']) != "undefined") {
                                toAppendFeatures += '<li class="active"><a href="#tab_1_1" data-toggle="tab">Description</a></li>';
                            }
                        }
                        if (mainDesc['features'] == 1) {
                            if (typeof(response['feature_names']) != "undefined") {
                                toAppendFeatures += '<li><a href="#tab_1_2" data-toggle="tab">Features</a></li>';
                            }
                        }
                        if (mainDesc['tags'] == 1) {
                            toAppendFeatures += '<li><a href="#tab_1_3" data-toggle="tab">Tags</a></li>';
                        }
                        if (mainDesc['reviews'] == 1) {
                            toAppendFeatures += '<li><a href="#tab_1_4" data-toggle="tab">Reviews</a></li>';
                        }
                    }
                    toAppendFeatures += '<div class="tab-content">';
                    toAppendFeatures += '<div class="tab-pane active" id="tab_1_1">';
                    toAppendFeatures += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
                    toAppendFeatures += '<div class="panel panel-default">';
                    toAppendFeatures += '<div class="panel-heading" role="tab" id="headingInfo">';
                    toAppendFeatures += '<p>' + response['product_full_description'] + ' </p>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '<div class="tab-pane" id="tab_1_2">';
                    toAppendFeatures += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
                    toAppendFeatures += '<div class="panel panel-default">';
                    toAppendFeatures += ' <div class="panel-heading" role="tab" id="headingInfo">';

                    $.each(mainResponse, function (featureKey, featureVal) {
                        toAppendFeatures += '<div class="ty-product-feature">';
                        if (featureVal['feature_names'] != null) {
                            toAppendFeatures += '<h3>' + featureVal['feature_names'] + ':</h3>';
                        }
                        toAppendFeatures += '<div class="ty-product-feature__value">';
                        if (featureVal['feature_variant_name'] != null) {
                            $.each(featureVal['feature_variant_name'].split(","), function (key, Val) {
                                toAppendFeatures += '<h5>' + Val + '</h5>';

                            });
                        }
                        toAppendFeatures += '</div>';
                        toAppendFeatures += '</div>';
                    });
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '<div class="tab-pane" id="tab_1_3">';
                    toAppendFeatures += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
                    toAppendFeatures += '<div class="panel panel-default">';
                    toAppendFeatures += '<div class="panel-heading" role="tab" id="headingInfo">';
                    toAppendFeatures += '<p>Tags</p>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '<div class="tab-pane" id="tab_1_4">';
                    toAppendFeatures += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
                    toAppendFeatures += '<div class="panel panel-default">';
                    toAppendFeatures += '<div class="panel-heading" role="tab" id="headingInfo">';
                    toAppendFeatures += '<p>Reviews</p>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    toAppendFeatures += '</div>';
                    $("#features-tab").append(toAppendFeatures);

                    var image = [];
                    var otherimg = [];
                    $("#etalage").empty();
                    $('.quick-view-img').empty();
                    if (response['image_url']) {
                        $("#etalage").append('<li><img class="etalage_thumb_image" src="..' + mainImage + '" alt="" />');
                        $('#etalage').append('<img class="etalage_source_image" src="..' + mainImage + '" alt="" /></li>');
                    }
                    $.each(response['image_urls'].split(","), function (index, value) {
                        image[index] = value;
                        $("#etalage").append('<li><img class="etalage_thumb_image" src="..' + value + '" alt="" />');
                        $('#etalage').append('<img class="etalage_source_image" src="..' + value + '" alt="" /></li>');

                    });
                    var combination = [];
                    if (response['variant_ids_combination'] != '' && response['variant_ids_combination'] != null) {
                        var array1 = $.unique((response['variant_ids_combination'].replace(/_+/g, ',')).split(','));
                        combinedVariantIds = $.unique((response['variant_ids_combination'].replace(/_+/g, ',')).split(','));
                    }
                    $("#option-details").empty();
                    var toAppendOptionDetails = '';
                    $.each(response['options'], function (optionIndex, optionValue) {
                        toAppendOptionDetails += '<div class="row quick-size-color">';
                        toAppendOptionDetails += '<div class="col-md-6 option">';
                        toAppendOptionDetails += '<h5>' + optionValue['option_name'] + '</h5>';
                        $.each(optionValue['variantData']['variant_id'], function (i, v) {
                            toAppendOptionDetails += '<div prod-id="' + response['product_id'] + '" data-id="' + optionValue['variantData']['variant_id'][i] + '" pricemodifier="' + optionValue['variantData']['price_modifier'][i] + '" value="' + optionValue['variantData']['variant_id'][i] + '" option-id="' + optionValue['option_id'] + '" variant-id="' + optionValue['variantData']['variant_id'][i] + '" class="size option-variant">' + optionValue['variantData']['variant_name'][i] + '</div>';
                        });
////                       TODO end- check for type
                        toAppendOptionDetails += '</div>';
                        toAppendOptionDetails += '</div>';
                    });

                    $("#option-details").append(toAppendOptionDetails);
                    $('#etalage').etalage({
                        autoplay: autoplay,
                        thumb_image_width: 400,
                        thumb_image_height: 400,
                        source_image_width: 900,
                        source_image_height: 1200,
                        show_hint: true,
                        click_callback: function (image_anchor, instance_id) {
                            alert('Callback example:\nYou clicked on an image with the anchor: "' + image_anchor + '"\n(in Etalage instance: "' + instance_id + '")');
                        }
                    });
                    $('#myModal').modal("show");
                }
            });

        });

        $(document.body).on('click', '.increase-quan,.decrease-quan', function () {
            var obj = $(this);
            var inputObj = obj.siblings('#input-item-count');
            var qty = inputObj.val();
            if (qty < 0) {
                inputObj.val(0);
            }
            $("#hidden-qunatity-id").val(qty);
        });

        var cmbImage = '';
        var cmbPrice = '';

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
                // autoplay = false;
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
                $(".cartOptions").removeClass('hide');
            } else {
                $('.option-variant').not(obj).addClass('disabled');
                $('.availablity').html('Out of stock');
                $(".cartOptions").addClass('hide');
                toastr["error"]('Product out of stock');
            }

            var prodId = $('.option-variant').attr('prod-id');

            var selectedCombination = [];
            $.each($('.option-variant.active'), function (i, v) {
                selectedCombination.push($(this).attr('variant-id'));
            });
            var priceModifier = $(this).attr('pricemodifier');
            var image = [];
            if (selectedCombination.length > 0) {
                $("#hidden-option-id").val(selectedCombination.join());
            }

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
//                console.log(response);
                    if (response['variant_ids_combination'] != '' && response['variant_ids_combination'] != null) {
                        var variantPrice = response['finalPrice'];
                        if (variantPrice) {
                            $('#price-total').html('<span class="real">$' + variantPrice + '</span>');
                            cmbPrice = variantPrice;
                        } else {
                            $('#price-total').html('<span class="real">$' + actualResponse['price_total'] + '</span>');
                            cmbPrice = actualResponse['price_total'];
                        }
                        $("#etalage").empty();
                        if (response['image_urls'] != '' && response['image_urls'] != null) {
                            cmbImage = response['image_urls'];
                            $("#etalage").append('<li><img class="etalage_thumb_image" src="' + response['image_urls'] + '" alt=""  data-type="comb_image"/ >');
                            $('#etalage').append('<img class="etalage_source_image" src="' + response['image_urls'] + '" alt="" data-type="comb_image"/></li>');
                        } else {
                            cmbImage = actualResponse['image_url'];
                            $("#etalage").append('<li><img class="etalage_thumb_image" src="' + actualResponse['image_url'] + '" alt=""  data-type="parent_image"/ >');
                            $('#etalage').append('<img class="etalage_source_image" src="' + actualResponse['image_url'] + '" alt=""  data-type="parent_image"/></li>');
                        }
                        ///////////////////////working here
                        $('#etalage').etalage({
                            autoplay: autoplay,
                            thumb_image_width: 400,
                            thumb_image_height: 400,
                            source_image_width: 900,
                            source_image_height: 1200,
                            show_hint: true,
                            click_callback: function (image_anchor, instance_id) {
                                alert('Callback example:\nYou clicked on an image with the anchor: "' + image_anchor + '"\n(in Etalage instance: "' + instance_id + '")');
                            }
                        });
                    } else {
                        var variantPrice = response['finalPrice'];
                        if (variantPrice) {
                            $('#price-total').html('<span class="real">' + variantPrice + '</span>');
                            cmbPrice = variantPrice;
                        } else {
                            cmbPrice = actualResponse['price_total'];
                            $('#price-total').html('<span class="real">' + actualResponse['price_total'] + '</span>');
                        }
                        $("#etalage").empty();
                        $('.quick-view-img').empty();
                        $.each(actualResponse['image_urls'].split(","), function (index, value) {
                            if (index == 0) {
                                cmbImage = value;
                            }
                            image[index] = value;
                            $("#etalage").append('<li><img class="etalage_thumb_image" src="' + value + '" alt="" />');
                            $('#etalage').append('<img class="etalage_source_image" src="' + value + '" alt="" /></li>');
                        });
                        $('#etalage').etalage({
                            autoplay: autoplay,
                            thumb_image_width: 400,
                            thumb_image_height: 400,
                            source_image_width: 900,
                            source_image_height: 1200,
                            show_hint: true,
                            click_callback: function (image_anchor, instance_id) {
                                alert('Callback example:\nYou clicked on an image with the anchor: "' + image_anchor + '"\n(in Etalage instance: "' + instance_id + '")');
                            }
                        });
                    }
                }
            });
        });

        $(document.body).on('click', '.quick-view', function () {//todo check why this used
            $('#myModal').modal("hide");
        });

        $(document.body).on('click', '#buy-now', function (e) {
            e.preventDefault();
            var usersession = "<?php echo Session::get('fs_user')['id'];?>"
            if (usersession) {
                var obj = $(this);
                checkSelectedOption();
                if (submitFlag) {
                    $('#pay-payment').submit();
                }
            } else {
                toastr["error"]('You Need to login before purchase');
                setTimeout(function () {
                    window.location = "http://localhost.flashsale.com";
                }, 3000);

            }
        });

        var newData = [],
                arrIndex = {};

        $(document.body).on('click', '.cartOptions', function () {
            var obj = $(this);
            var selectedCookie = [];
            checkSelectedOption();
            $('#add-to-cart').empty();
            if (((selected != null && selected.length == $(".option").length) || selected == null) && Number($('.quantity').val()) > 0) {
                var oldCookieData = '';
                var prodId = $('.option-variant').attr('prod-id');
                var priceModifier = $('.option-variant').attr('pricemodifier');
                var quantity = Number($('.quantity').val());
                var variantId = $('.option-variant').attr('variant-id');
                var arr = [{"prodId": prodId, "quantity": quantity, "combination_id": selected.toString()}];
                var cookie_value = JSON.stringify(arr);
                var usersession = "<?php echo Session::get('fs_user')['id'];?>"
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
                } else {//todo error here Fck Vini Fckin ahole
                    newData = (getCookie('cart_cookie_name') != '') ? JSON.parse(getCookie('cart_cookie_name')) : [];
                    addNewElement(newData, {
                        prodId: prodId,
                        quantity: quantity,
                        combination_id: selected.toString(),
                        cmbImage: cmbImage,
                        cmbPrice: cmbPrice
                    });
                    toastr['success']("Added to cart.");
                }
                if (newData.length != 0) {
                    $('#add-to-cart').append('<a class="index" href="#cart" data-toggle="modal" data-target=".cart-bs-example-modal-lg"><img src="/assets/images/cart.png" class="img-responsive;" style="float: left; margin-top: 5px; margin-left: 10px;"><span>' + newData.length + '</span><span id="cart">Cart</span> </a>');
                } else {
                    $('#add-to-cart').append('<a class="index"href="#cart" data-toggle="modal" data-target=".cart-bs-example-modal-lg"><img src="/assets/images/cart.png" class="img-responsive;" style="float: left; margin-top: 5px; margin-left: 10px;"><span>0</span><span id="cart">Cart</span> </a>');
                }

            }
        });

        $(document.body).on('click', '#cart', function () {
            var cartDetails = '';

            var usersession = "<?php echo Session::get('fs_user')['id'];?>"
            if (typeof usersession != "undefined" && usersession) {
                $.ajax({
                    url: '/order-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'cartDetails',

                    },
                    success: function (response) {
                        mainresponse = $.parseJSON(response);
                        console.log(mainresponse);
                        $('.cartcontainer').empty();
                        var toAppend = '';
                        var res = [];
                        $.each(mainresponse, function (index, val) {
                            if (index == 0 && typeof val['subtotal'] != "undefined") {
                                $('#sub_total').html(val['subtotal']);
                            }
                            toAppend += '<div class="row">';
                            toAppend += '<div class="col-md-12 pop_image">';
                            toAppend += '<div class="col-md-4">';
                            toAppend += '<img src="' + val['image_url'] + '" class="img-responsive pop-img" style="height: 100px; width:100px;">';
                            toAppend += '<div class="pop_text">';
                            toAppend += '<p class="prod_name">' + val['product_name'] + '</p>';
                            toAppend += '<p class="prod_price">' + val['price_total'] + ' <del> £ 100.90</del></p>';
                            toAppend += '<b class="offer_percent">15% off</b>';
                            toAppend += '<p class="pop_estimate">ESTIMATED DELIVERY:<span>SUN 03/20/2016- WED 04/06/2016</span></p>';
                            toAppend += '</div>';
                            toAppend += '</div>';
                            toAppend += '<div class="form-group width-100">';
                            toAppend += '<label class="control-label decrease-quan" for="">';
                            toAppend += ' <i class="fa fa-minus"></i>';
                            toAppend += '</label>';
                            toAppend += '<input id="input-item-count" class="form-control quantity" type="text" style="padding:0; text-align:center; width:50px;margin-left: 6px;"  name="input-item-count" value="' + val['quantity'] + '">';
                            toAppend += '<input type="hidden" class="btn-select-input" id="" name="" value=""/>';
                            toAppend += '<label class="control-label increase-quan" for=""><i class="fa fa-plus"></i></label>';
                            toAppend += '</div>';
                            toAppend += '<div class="col-md-2 pop_rate">';
                            toAppend += '<p class="fa fa-inr">' + val['finalPrice'] + '</p>';
                            toAppend += '<a href="javascript:;" class="remove-from-cart" data-id="' + val['order_id'] + '"><p>Remove</p></a>';
                            toAppend += '</div>';
                            toAppend += '</div>';
                            toAppend += '</div>';
                        });
                        $('.cartcontainer').append(toAppend);
                    }
                });
            } else {
                cartDetails = $.parseJSON(getCookie('cart_cookie_name'));
                if (cartDetails.length != 0) {
                    $('.cartcontainer').empty();
                    var toAppend = '';
                    var res = [];
                    var sub_total = 0;
                    $.each(cartDetails, function (index, val) {
                        sub_total = Number(sub_total) + Number(val['cmbPrice']);
                        if (index == (cartDetails.length - 1) && sub_total != 0) {
                            $('#sub_total').html(sub_total);
                        }
                        toAppend += '<div class="row">';
                        toAppend += '<div class="col-md-12 pop_image">';
                        toAppend += '<div class="col-md-4">';
                        toAppend += '<img src="' + val['cmbImage'] + '" class="img-responsive pop-img" style="height: 100px; width:100px;">';
                        toAppend += '<div class="pop_text">';
                        toAppend += '<p class="prod_name">' + val['cmbPrice'] + '</p>';
                        toAppend += '<p class="prod_price">' + val['cmbPrice'] + ' <del> £ 100.90</del></p>';
                        toAppend += '<b class="offer_percent">15% off</b>';
                        toAppend += '<p class="pop_estimate">ESTIMATED DELIVERY:<span>SUN 03/20/2016- WED 04/06/2016</span></p>';
                        toAppend += '</div>';
                        toAppend += '</div>';
                        toAppend += '<div class="col-md-1 select_quantity">';
                        toAppend += '<a class="btn btn-default btn-select btn-select-light _cop3">';
                        toAppend += '<input type="hidden" class="btn-select-input" id="" name="" value=""/>';
                        toAppend += '<span class="btn-select-value cop">' + val['quantity'] + '</span>';
                        toAppend += '<span><img class="arrow1 _cop1" src="/assets/images/arrow.png"></span>';
                        toAppend += '<ul><li>' + val['quantity'] + '</li></ul>';
                        toAppend += '</a>';
                        toAppend += '</div>';
                        toAppend += '<div class="col-md-2 pop_rate">';
                        toAppend += '<p class="fa fa-inr">' + val['cmbPrice'] + '</p>';
                        toAppend += '<a href="javascript:;" class="remove-from-cart" data-id="' + val['cmbPrice'] + '"><p>Remove</p></a>';
                        toAppend += '</div>';
                        toAppend += '</div>';
                        toAppend += '</div>';
                    });
                    $('.cartcontainer').append(toAppend);
                }


            }

        });

        $(document.body).on('click', '.remove-from-cart', function () {
            var obj = $(this);
            var orderId = obj.attr('data-id');
            if (confirm("Do you want to Remove This Product From Your Cart!") == true) {
                $.ajax({
                    url: '/order-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'removerCartOrder',
                        orderId: orderId,
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['status']](response['msg']);
                        var effect = 'slide';
                        // Set the options for the effect type chosen
                        var options = {direction: 'right'};
                        // Set the duration (default: 400 milliseconds)
                        var duration = 500;
                        obj.parents('.row').toggle(effect, options, duration);
                    }
                });
            }


        });

        $(document.body).on('click', '#checkout', function () {
            var usersession = "<?php echo Session::get('fs_user')['id'];?>"
            var siteUrl = "<?php echo env("WEB_URL")?>";
            if (typeof usersession != "undefined" && usersession) {
                window.location = siteUrl + '/checkout';
            } else {
                toastr["error"]("You need to login for continue");
            }
        });

        $(document.body).on("click", ".modaldescription", function () {
            var obj = $(this);
            var message = $(this).attr('data-desc');
            var count = $('#count').html();
            $('#message1').html(message);
            var notificationId = $(this).attr('data-id');
            $.ajax({
                url: '/notification-ajax-handler',
                type: 'POST',
                datatype: 'json',
                data: {
                    method: 'changenotificationstatus',
                    NotificationId: notificationId

                },
                success: function (response) {
                    if (response) {
                        obj.parent().hide();
                        $('#count').html(count - 1);

                    }
                }
            });
        });

        var addNewElement = function (arr, newElement) {
            console.log(newElement);
            var found = false;
            for (var i = 0; element = arr[i]; i++) {

                if (element.prodId == newElement.prodId && element.combination_id == newElement.combination_id) {
                    found = true;
                    if (newElement.quantity === 0) {
                        arr[i] = false;
                    } else {
                        arr[i] = {
                            prodId: newElement.prodId,
                            quantity: (Number(element.quantity) + Number(newElement.quantity)),
                            combination_id: newElement.combination_id,
                            cmbImage: newElement.cmbImage,
                            cmbPrice: newElement.cmbPrice
                        };
                    }
                }
            }


            if (found === false) {
                arr.push(newElement);
            }
            // removing elements
            var newArr = [];
            for (var i = 0; element = arr[i]; i++) {
                if (element !== false) newArr.push(element);
            }
            createCookie('cart_cookie_name', JSON.stringify(newArr), 1);
            return newArr;
        }

    });

</script>
