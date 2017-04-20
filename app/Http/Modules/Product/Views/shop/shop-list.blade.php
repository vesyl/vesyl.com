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

    <div class="container catelog item" style="min-height: 300px">
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($shopList) && !empty($shopList)) { ?>
                {{--===========================--}}
                <div class="col-md-9 main_section">
                    <div class="row shop_main">
                        <div class="count_tovar_items">
                            <p>Total</p>
                            <span id="total_filtered_shops">{{$shopList['count']}}</span><span>&nbsp;Shops</span>
                        </div>
                        @foreach($shopList['shops'] as $shopKey => $shopVal)
                            <div class="col-md-4  ib_item_mainsalecate padding0">
                                <a href="/shop-detail/{{$shopVal['shop_id']}}/{{$shopVal['shop_name']}}"
                                        class="ib_item_mainsalecate">
                                    <div class="images">
                                        <img src="{{$shopVal['shop_banner']}}" class="img-responsive"
                                                id="image-res{{$shopVal['shop_id']}}"
                                                main-image="{{$shopVal['shop_banner']}}">
                                    </div>

                                </a>
                                <div class="">
                                    <span class="ib_itemsalecate">
                                        <span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>
                                        <span class="ib_textsalecate">{{$shopVal['shop_name']}}</span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{--======================--}}
                <?php } else { ?>
                     <?php echo "No Shops Found";
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
            var option = [];
            $(document.body).on('click', '#loadmore', function (e) {
                pagenumber++;
                window.setTimeout(function () {
                    filter(option, {loadMore: true});
                }, 200);
            });


            function filter(option) {
                $('#loadmore').addClass('disabled');
                $.ajax({
                    url: '/shop-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getShopsListByFilter',
                        pagenumber: pagenumber,

                    },
                    success: function (response) {
                        var toAppend = '';
                        response = $.parseJSON(response);
                        var rawResponseLength = response['shops'].length;
                        if (rawResponseLength < 50) {
                            toAppend = '<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more shops details found.</span></div>';
                            $(".shop_main").append(toAppend);
                            $('#loadmore').addClass('hide');
                        } else {
                            if (pagenumber == 1) {
                                $('.shop_main').empty();
                                $('.shop_main .padding0').empty();
                            }
                            if (typeof(response) != "undefined") {
                                $("#total_filtered_shops").html('0');
                                $("#total_filtered_shops").html(response['count']);
                                $('.shop_main').empty();
                                $('.shop_main .padding0').empty();
                                if (response['shops'].length > 0) {
                                    var appendProduct = '';
                                    $.each(response['shops'], function (index, val) {
                                        appendProduct += '<div class="col-md-4  ib_item_mainsalecate padding0">';
                                        appendProduct += '<a class="ib_item_mainsalecate" href="/shop-detail/' + val['shop_id'] + '/' + val['shop_name'] + '">';
                                        appendProduct += '<div class="images">';
                                        appendProduct += '<img src="' + val['shop_banner'] + '" class="img-responsive" id="image-res' + val['shop_id'] + '" main-image="' + val['shop_banner'] + '">';
                                        appendProduct += '</div>';
                                        appendProduct += '</a>';
                                        appendProduct += '<div class="">';
                                        appendProduct += '<span class="ib_itemsalecate">';
                                        appendProduct += '<span class="inner_imagessalecate"><img src="assets/images/ib.png" alt=""></span>';
                                        appendProduct += '<span class="ib_textsalecate">' + val['shop_name'] + '</span>';
                                        appendProduct += '</span>';
                                        appendProduct += '</div>';
                                        appendProduct += '</div>';
                                    });

                                    $(".shop_main").append(appendProduct);
                                }

                                if ($(".shop_main .padding0").length == response['count']) {
                                    $('#loadmore').addClass('hide');
                                    $('.shop_main').append('<div class="clearfix"></div><div class="col-md-12" style="text-align: center;"><span>No more Shops are available at the moment.</span></div>');
                                }
                                else {
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
    <div class="container catelog item" style="min-height: 300px">
        <div class="row">
            <div class="col-md-12">

                <div class="row prod_main">

                    <div class="breadcrumb-area">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="breadcrumb">
                                        <div class="pull-right">
                                            <span id="total_filtered_shops">{{$shopList['count']}}</span><span>&nbsp;Shops</span>
                                        </div>
                                        <ul>
                                            <li></li>
                                            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                                            <li><span>Shops</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="shop_main">
                        @if (isset($shopList) && !empty($shopList))
                            @foreach($shopList['shops'] as $shopKey => $shopVal)

                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="single-features single-shop">
                                        <div class="features-img shop-img" style=""><!--min-height: 333px;-->
                                            <a class="features-img" href="/shop-detail/{{$shopVal['shop_id']}}/{{$shopVal['shop_name']}}" style="background-color: #f5f5f5;">
                                                <img src="{{$shopVal['shop_banner']}}" alt="" id="image-res{{$shopVal['shop_id']}}" main-image="{{$shopVal['shop_banner']}}" style=" vertical-align: middle; display: inline-block; height: 100%;"/>
                                            </a>
                                            {{--<span>NEW</span>--}}
                                        </div>
                                        <div class="features-info">
                                            <h2><a>{{$shopVal['shop_name']}}</a></h2>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        @endif
                    </div>
                    <div class="container">
                        <div class="col-md-offset-4 col-md-4">
                            <button class="btn col-md-12" style="background: #000 none repeat scroll 0 0; color: #ddd;" id="loadmore">
                                Load more
                            </button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

@endsection
