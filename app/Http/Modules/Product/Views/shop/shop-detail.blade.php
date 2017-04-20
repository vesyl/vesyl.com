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

@section('contentold')
    @if(isset($shopError))
        <div class="container-fluid hero_bg" style="padding: 0">
            <img style="width: 100%; height:600px" src="">
        </div>
        <div class="container salecatelog ">
            No products available for this shop
        </div>
    @else
        <div class="container-fluid hero_bg" style="padding: 0">
            <img style="width: 100%; height:600px" src="{{$shopDetail['shop_banner']}}">
        </div>
        <div class="container salecatelog ">
            <div class="row">
                <?php if(isset($shopDetail) && (!empty($shopDetail)))  { ?>
                <div class="col-md-12">
                    <div class="sorting_options clearfix">
                        <div class="count_tovar_items">
                            <p>Total</p>
                            <span id="total_filtered_products">{{$shopDetail['total']}}</span><span>&nbsp;Product</span>
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
                    <div class="col-md-3 ctitle">
                        @if(isset($shopDetail) && !empty($shopDetail))
                            @foreach($shopDetail['filter_info'] as $filterKey => $filterVal)
                                <?php $variantName = explode(",", $filterVal['variant_name']);
                                $variantId = explode(",", $filterVal['variant_ids']);  ?>
                                <div class="row brand">
                                    @if($filterVal['product_filter_parent_product_id'] == 0)
                                        <h4>{{$filterVal['product_filter_option_name']}}</h4>
                                    @endif
                                    @if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)
                                        <select class="form-control  _select_age_range changeable selectoption" name="product_filter" id="selectOptions">
                                            <option value="" class="select-filter" selected="selected">
                                                choose {{$filterVal['product_filter_option_name']}}</option>
                                            @foreach($variantName as $varKey => $varVal)
                                                <option class="select-filter" data-id="{{$variantId[$varKey]}}"
                                                        value="{{$variantId[$varKey]}}">{{$varVal}}</option>
                                            @endforeach
                                        </select>
                                    @elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)
                                        @foreach($variantName as $varKey => $varVal)
                                            <input class="changeable select-filter" data-id="{{$variantId[$varKey]}}" name="brand" id="radioCheck" value="{{$variantId[$varKey]}}" type="radio">{{$varVal}}
                                            <br>
                                        @endforeach
                                    @elseif($filterVal['product_filter_parent_product_id'] == 0)
                                        @foreach($variantName as $varKey => $varVal)
                                            <a href="#">
                                                <div class="color changeable select-filter" id="checks" data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}">{{$varVal}}</div>
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
                                            <input class="pricecheckbox" id="{{$priceVal}}" data-id="{{$priceVal}}" type="checkbox">
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
                    <div class="col-md-9 salemain_section">
                        <div class="row prod_main">
                            @if(isset($shopDetail['product_info']) && !empty($shopDetail['product_info']))
                                @foreach($shopDetail['product_info'] as $shopKey => $shopVal)
                                    <div class="col-md-4  ib_item_mainsalecate padding0">
                                        <a href="/product-details/{{$shopVal['product_id']}}/{{str_replace(" ","-",$shopVal['product_name'])}}" class="ib_item_mainsalecate">
                                            <div class="images">
                                                <img src="{{$shopVal['image_url']}}" class="img-responsive" id="image-res{{$shopVal['product_id']}}" main-image="{{$shopVal['image_url']}}">
                                            </div>
                                        </a>
                                        <div class="">
                                            <span class="ib_itemsalecate">
                                                <span class="inner_imagessalecate">
                                                    <img src="assets/images/ib.png" alt="">
                                                </span>
                                                <span class="ib_textsalecate">{{$shopVal['product_name']}}
                                                    <br> ${{$shopVal['price_total']}}
                                                    <i class="offer_percent">15% off</i>
                                                </span>
                                            </span>
                                            <span class="quick-view" class="quick-view" data-id="{{$shopVal['product_id']}}" product-name="{{$shopVal['product_name']}}">QUICK
                                                VIEW</span>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                        </div>
                    </div>
                    <?php } else {
                        echo "No Data Found";
                    }?>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <button class="btn col-md-12" id="loadmore">Load more</button>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('pagejavascripts')
    <script type="text/javascript"
            src="/assets/scripts/jquery.mThumbnailScroller.js"></script>
    <script src="/assets/scripts/jquery-ui.js" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function () {

            var pagenumber = 0;
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
                var urlparams = (pageurl.split("/"));
                var shopId = '';
                shopId = urlparams[4];

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
                    url: '/shop-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getProductShopDetails',
                        option: option,
                        priceRangeFrom: pricerangefrom[0],
                        priceRangeUpto: pricerangeto[pricerangeto.length - 1],
                        sort_by: sortby,
                        pagenumber: pagenumber,
                        shopId: shopId

                    },
                    success: function (response) {

                        var rawResponseLength = response.length;
                        response = $.parseJSON(response);
                        var toAppend = '';
                        if (rawResponseLength < 50) {
                            toAppend = '<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>' + response + '</span></div>';
                            $(".prod_main").append(toAppend);
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
                                    appendProduct += '<span class="ib_textsalecate">' + val['product_name'] + '<br> $' + val['price_total'] + ' <i class="offer_percent">15% off</i></span>';
                                    appendProduct += '</span>';
                                    appendProduct += '<span class="quick-view" class="quick-view" data-id="' + val['product_id'] + '" product-name="' + val['product_name'] + '" >QUICK VIEW</span>';
                                    appendProduct += '</div>';
                                    appendProduct += '</div>';
                                });
                                $(".prod_main").append(appendProduct);
                                if ($(".prod_main .padding0").length == response['total']) {
                                    $('#loadmore').addClass('hide');
                                    $('.prod_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center; margin-top: 44px;" ><span>No more products are available at the moment.</span></div>');
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


        });
    </script>

@endsection


@section('content')
    <!--<div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="breadcrumb">
                        <ul>
                            <li></li>
                            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                            <li><span>Shop</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    @if(isset($shopError) && !empty($shopError))
        <div class="container" style="min-height: 75%;">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <br>
                    <h3>No such shop available.</h3>
                    <p>Click <a href="/shop-list">here</a> to see the list of shops available.</p>
                    <br>
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid">
            <div class="row">
                <!-- OFFICE-BANNER START -->
                <div class="col-md-12 col-sm-12">
                    <div class="bg-for-catlouge" style="opacity: 0.8; max-height: 500px; overflow-y: hidden; width: 100%; height: auto;">
                        <img src="{{$shopDetail['shop_banner']}}" alt="" class="img-reponsive"/>
                    </div>
                </div>
                <!-- OFFICE-BANNER END -->
            </div>
        </div>

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
                            {{--<li><a href="#">Random Categories--}}
                            {{--<i class="fa fa-chevron-right pull-right" aria-hidden="true"></i></a>--}}
                            {{--</li>--}}
                            {{--<li><a href="#">Random Categories--}}
                            {{--<i class="fa fa-chevron-right pull-right" aria-hidden="true"></i></a>--}}
                            {{--</li>--}}
                            {{--<li><a href="#">Random Categories--}}
                            {{--<i class="fa fa-chevron-right pull-right" aria-hidden="true"></i></a>--}}
                            {{--</li>--}}
                            {{--<li><a href="#">Random Categories--}}
                            {{--<i class="fa fa-chevron-right pull-right" aria-hidden="true"></i></a>--}}
                            {{--</li>--}}
                            {{--<li><a href="#">Random Categories--}}
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

                            @if(isset($shopDetail) && !empty($shopDetail))
                                @foreach($shopDetail['filter_info'] as $filterKey => $filterVal)
                                    <?php $variantName = explode(",", $filterVal['variant_name']);
                                    $variantId = explode(",", $filterVal['variant_ids']);  ?>

                                    <div class="k2-content sidebar-p">
                                        @if($filterVal['product_filter_parent_product_id'] == 0)
                                            <h3 class="blog-right-title">
                                                <strong>{{$filterVal['product_filter_option_name']}}</strong></h3><br>
                                        @endif
                                        <div class="panel recipients">
                                            <div class="panel-heading">
                                                @if($filterVal['product_filter_variant_type'] == '1' && $filterVal['product_filter_parent_product_id'] != 1)
                                                    <select class="form-control  _select_age_range changeable selectoption" name="product_filter" id="selectOptions">

                                                        <option value="" class="select-filter" selected="selected">
                                                            choose {{$filterVal['product_filter_option_name']}}</option>
                                                        @foreach($variantName as $varKey => $varVal)
                                                            <option class="select-filter" data-id="{{$variantId[$varKey]}}"
                                                                    value="{{$variantId[$varKey]}}">{{$varVal}}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif($filterVal['product_filter_variant_type'] == '2' && $filterVal['product_filter_parent_product_id'] != 1)
                                                    @foreach($variantName as $varKey => $varVal)
                                                        <input class="changeable select-filter" name="brand" data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}" type="radio">{{$varVal}}
                                                        <br>
                                                    @endforeach
                                                @elseif($filterVal['product_filter_parent_product_id'] == 0)
                                                    @foreach($variantName as $varKey => $varVal)
                                                        <span class="button-checkbox select-filter" data-id="{{$variantId[$varKey]}}" value="{{$variantId[$varKey]}}">
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


                        </div>
                    </div>


                </div>
                <br>

                <!-- K2-CONTENT END -->
            </div>
        </div>
        <!-- LEFT SIDEBAR START -->

        <div class="col-lg-9 col-md-9 col-sm-9">
            <div class="sidebar-content">
                <div class="row left-sidebar-content">

                @if(isset($shopDetail['product_info']) && !empty($shopDetail['product_info']))
                    @foreach($shopDetail['product_info'] as $shopKey => $shopVal)

                        <!-- SINGLE-SHOP START -->
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="single-features single-shop">
                                    <div class="features-img shop-img">
                                        <a href="/product-details/{{$shopVal['product_id']}}/{{str_replace(" ","-",$shopVal['product_name'])}}"><img src="{{$shopVal['image_url']}}" alt="" id="image-res{{$shopVal['product_id']}}" main-image="{{$shopVal['image_url']}}"/></a>
                                        {{--<span>NEW</span>--}}
                                        <div class="button-shop">
                                            <form action="#">
                                                <span class="quick-view" class="quick-view" data-id="{{$shopVal['product_id']}}" product-name="{{$shopVal['product_name']}}">
                                                    QUICK VIEW
                                                </span>{{--<input type="button" value="Choose options">--}}
                                                {{--<a href="#"><i class="fa fa-heart"></i></a>--}}
                                            </form>
                                        </div>
                                    </div>
                                    <div class="features-info">
                                        <h2><a href="#">{{$shopVal['product_name']}}</a></h2>
                                        {{--<p><a href="#">New Product</a></p>--}}
                                        <span><big><strong>{{$shopVal['price_total']}}</strong></big></span></h3>
                                    </div>
                                </div>
                            </div>
                            <!-- SINGLE-SHOP END -->

                        @endforeach
                    @endif
                </div>
                {{--<div class="row creative-area ">--}}
                {{--<div class="col-lg-12 col-md-12 col-sm-12">--}}
                {{--<div class="creative-border">--}}
                {{--<div class="creative-content">--}}
                {{--<h2 class="wow fadeInRight animated" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInRight;">--}}
                {{--Awesome &amp; New And Different Color Products</h2>--}}
                {{--<h4 class="wow fadeInRight animated" data-wow-duration="1.5s" data-wow-delay=".5s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.5s; animation-name: fadeInRight;">--}}
                {{--New Offer is going on upto 50%</h4>--}}
                {{--</div>--}}
                {{--<div class="creative-photo">--}}
                {{--<div class="even-item-img">--}}
                {{--<div class="catitemimage">--}}
                {{--<a href="#">--}}
                {{--<span class="catItemLink"></span>--}}
                {{--<img src="img/home-2/f1.jpg" alt=""/>--}}
                {{--</a>--}}
                {{--<div class="blog-info-block">--}}
                {{--<span class="catItemDateCreated">--}}
                {{--<span class="blog-date">18</span>--}}
                {{--<span class="blog-month">Jan 2015</span>--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-offset-3 col-md-6">
                            <button class="btn col-md-12" id="loadmore">Load more</button>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>
        </div>
    @endif
@endsection