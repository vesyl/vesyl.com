<?php $productListSetting = getSetting('available_product_list_sortings'); $productListSetting = $productListSetting ? $productListSetting : '';
$prod = str_replace('#M#', '', $productListSetting);
$setting = array_map(function ($val) {
    return rtrim($val, "=Y");
}, explode("&", $prod));

?>
@extends('Home/Layouts/home_layout')
@section('content')
    <div class="container-fluid hero_bg" style="padding: 0;  margin-left: auto; margin-right: auto;"><img style="width: 100%; height:auto; max-height: 500px; width:auto;"
                src="@if(isset($dailyspecialdetails) && (!empty($dailyspecialdetails))){{$dailyspecialdetails['campaign_banner']}} @else /assets/images/hero_bg.png @endif">
    </div>
    <section id="main_section">
        <div class="container salecatelog">
            <div class="row">
                @if(isset($dailyspecialdetails) && (!empty($dailyspecialdetails)))
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
                        <div class="col-md-3 ctitle">
                            <div class="categorie images">
                                <img src="assets/images/categories.png" class="img-responsive middle">
                            </div>
                            @if(isset($dailyspecialdetails['filter_info']) && !empty($dailyspecialdetails['filter_info']))
                                @foreach($dailyspecialdetails['filter_info'] as $filterKey => $filterVal)
                                    <?php $variantName = explode(",", $filterVal['variant_name']);
                                    $variantId = explode(",", $filterVal['variant_ids']);  ?>
                                    <div class="row brand">
                                        @if($filterVal['product_filter_parent_product_id'] == 0)
                                            <h4>{{$filterVal['product_filter_option_name']}}</h4>
                                        @endif
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
                            @endif
                        </div>
                        <div class="col-md-9 salemain_section">
                            <div class="row prod_main">
                                @if(isset($dailyspecialdetails) && !empty($dailyspecialdetails))
                                    @foreach($dailyspecialdetails['product_info'] as $productKey => $productVal)
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
                                                        <br> ${{$productVal['price_total']}} <i class="offer_percent">15%
                                                            off</i></span>
                                                </span>
                                                <span class="quick-view" class="quick-view"
                                                        data-id="{{$productVal['product_id']}}"
                                                        product-name="{{$productVal['product_name']}}">QUICK VIEW</span>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn col-md-12" id="loadmore">Load more</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                        </div>
                    </div>
                @else
                    <br> <br><br> <br><br> <br><br> <br>
                    No Data Found
                @endif
            </div>
        </div>
    </section>
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


        });
    </script>

@endsection
