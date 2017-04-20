@extends('Home/Layouts/home_layout')
@section('pageheadcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
@endsection

@section('content')
    <!--
    <div class="container-fluid hero_bg" style="padding: 0">
        <img src="/assets/images/hero_bg.png" style="width: 100%">
    </div>

    <section id="main_section">

        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-3 lady_women">
                        <a class="wshop" href="#">SHOP NOW</a>
                        <div class="_women_men_image">
                            <a href="#">
                                <img src="/assets/images/lady.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6  _women_men">
                        <div class="col-md-12  sub_item">
                            <div class="_section_middle item-2">
                                <a href="#">
                                    <img src="/assets/images/smen.png" alt="">
                                </a>
                                <div class="hidden_content">
                                    <div class="col-md-12 _hidden_wrapper">
                                        <div class="col-md-6">
                                            <span class="_delivery_wrapper">
                                                <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                                <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="_delivery_wrapper">
                                                <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                                <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-md-12 sub_item">
                            <div class="_section_middle item-2">
                                <a href="#">
                                    <img src="/assets/images/watch.png" alt="">
                                </a>
                                <div class="hidden_content">
                                    <div class="col-md-12 _hidden_wrapper">
                                        <div class="col-md-6">
                                            <span class="_delivery_wrapper">
                                                <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                                <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="_delivery_wrapper">
                                                <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                                <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div><!--------Hidden ends here->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 boy_men">
                        <a class="mshop" href="#">SHOP NOW</a>
                        <div class="_women_men_image">
                            <a href="#">
                                <img src="/assets/images/men.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 sub_item">
                    <div class="_section_middle item-2">
                        <a href="#">
                            <img src="/assets/images/sliper.png" alt="">
                        </a>
                        <div class="hidden_content">
                            <div class="col-md-12 _hidden_wrapper">
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                        <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                        <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                    </span>
                                </div>
                            </div>
                        </div><!--------Hidden ends here->
                    </div>
                </div>
                <div class="col-md-6 sub_item">
                    <div class="_section_middle item-2">
                        <a href="#">
                            <img src="/assets/images/wm.png" alt="">
                        </a>
                        <div class="hidden_content">
                            <div class="col-md-12 _hidden_wrapper">
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                        <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                        <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                    </span>
                                </div>
                            </div>
                        </div><!--------Hidden ends here->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 sub_item">
                    <div class="_section_middle item-2">
                        <a href="#">
                            <img src="/assets/images/girl.png" alt="">
                        </a>
                        <div class="hidden_content">
                            <div class="col-md-12 _hidden_wrapper">
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                        <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                        <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                    </span>
                                </div>
                            </div>
                        </div><!--------Hidden ends here->
                    </div>
                </div>
                <div class="col-md-6 sub_item">
                    <div class="_section_middle item-2">
                        <a href="#">
                            <img src="/assets/images/m1.png" alt="">
                        </a>
                        <div class="hidden_content">
                            <div class="col-md-12 _hidden_wrapper">
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                        <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                        <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                    </span>
                                </div>
                            </div>
                        </div><!--------Hidden ends here->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 sub_item">
                    <div class="_section_middle item-2">
                        <a href="#">
                            <img src="/assets/images/m2.png" alt="">
                        </a>
                        <div class="hidden_content">
                            <div class="col-md-12 _hidden_wrapper">
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                        <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                        <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                    </span>
                                </div>
                            </div>
                        </div><!--------Hidden ends here->
                    </div>
                </div>
                <div class="col-md-6 sub_item">
                    <div class="_section_middle item-2">
                        <a href="#">
                            <img src="/assets/images/watch2.png" alt="">
                        </a>
                        <div class="hidden_content">
                            <div class="col-md-12 _hidden_wrapper">
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_image"><img src="/assets/images/t1.png" alt=""></span>
                                        <span class="_delivery_text"><i>FREE DELIVERY</i></span>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="_delivery_wrapper">
                                        <span class="_delivery_time"><img src="/assets/images/vd.png" alt=""></span>
                                        <span class="_delivery_text">Valid until: <i class="_until_date">DD:MM:YYYY</i></span>
                                    </span>
                                </div>
                            </div>
                        </div><!--------Hidden ends here->
                    </div>
                </div>
            </div>
        </div>          <!--------CONTANER ENDS HERE->
    </section>  <!--------MAIN SECTION ENDS HERE------>
    <!--Blog-->

    <!-- NEW RAJESH DESIGN START -->

    @if($bannerData['code'] == 200)
        <!-- HOME SLIDER -->
        @if($bannerData['data']['settings']['type'] == 1)
            <div class="">
                <!-- slider start -->
                <div class="slider hidden-xs">
                    <div id="mainSlider" class="">
                        <img src="/assets/home/img/brand/bg2.jpg" alt="main slider" title="#htmlcaption1"/>
                    </div>
                </div>
                <!-- slider end -->
            </div>
        @elseif($bannerData['data']['settings']['type'] == 2)
            <div class="our-office-area">
                <div class="container">
                    <div class="row">
                        <!-- OFFICE-BANNER START -->
                        <div class="col-md-8 col-sm-8">
                            <div class="office-banner">
                                <img src="/assets/home/img/slider/slider2/bg5.jpg" alt=""/>
                                <img src="/assets/home/img/slider/slider2/bg6.jpg" alt=""/>
                                <img src="/assets/home/img/slider/slider2/bg7.jpg" alt=""/>
                                <img src="/assets/home/img/slider/slider2/bg8.jpg" alt=""/>
                            </div>
                            <!-- OFFICE-BANNER END -->
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="single-collection " data-wow-duration="1.5s" data-wow-delay=".5s" style="padding-bottom: 18px;">
                                <div class="grid">
                                    <figure class="effect-bubba">
                                        <img src="/assets/home/img/home-2/collection/2.jpg" alt="img02"/>
                                        <figcaption>
                                            <h2><span>NEW</span>&nbsp;&nbsp; ARRIVALs</h2>
                                            <p>SPRING / SUMMER 2016</p>
                                            <a class="readon" href="index.html"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;
                                                Shop Now</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                            <div class="single-collection" data-wow-duration="1.5s" data-wow-delay="1.1s">
                                <div class="grid">
                                    <figure class="effect-bubba">
                                        <img src="/assets/home/img/home-2/collection/4.jpg" alt="img02"/>
                                        <figcaption>
                                            <h2><span>SALE </span>&nbsp;UPTO&nbsp; 60%</h2>
                                            <p>Clothing ,Shoes and All</p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- OFFICE-BANNER END -->
                    </div>
                </div>
            </div>
            <br>
            <br>
            <br>
            <br>
        @elseif($bannerData['data']['settings']['type'] == 3)

        @else
            <!--todo show some default banner here or remove else and set default compulsary in db-->
            <div class="">
                <!-- slider start -->
                <div class="slider hidden-xs">
                    <div id="mainSlider" class="">
                        <img src="/assets/home/img/brand/bg2.jpg" alt="main slider" title="#htmlcaption1"/>
                    </div>
                </div>
                <!-- slider end -->
            </div>
        @endif
    @else
        <!--todo show some default banner here or remove else and set default compulsary in db-->
        <div class="">
            <!-- slider start -->
            <div class="slider hidden-xs">
                <div id="mainSlider" class="">
                    <img src="/assets/home/img/brand/bg2.jpg" alt="main slider" title="#htmlcaption1"/>
                </div>
            </div>
            <!-- slider end -->
        </div>
    @endif
    <!-- HOME SLIDER END -->

    <div class="clearfix"></div>

    <!-- PROMOTION-AREA START -->
    <div class="promotion-area">
        <div class="container">
            <!-- PROMO-LIST START -->
            <div class="row">
                <!-- SINGLE-PROMO START -->
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay=".40s"><a href="#">
                            <img src="/assets/home/img/promo/1.jpg" alt="">
                        </a>
                        <div class="promo-details shop-now"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/nike-logo-3.jpg" alt="" class="logo-for-card"></a>
                            <a class="readon" href="/"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;
                                Shop Now</a>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="row">
                        <div class="row">
                            <!-- SINGLE-PROMO START -->
                            <div class="col-md-12 col-sm-12 margin-bottom">
                                <a href="#">
                                </a>
                                <div class="single-promo " data-wow-duration="1.5s" data-wow-delay=".70s"><a href="#">
                                        <img src="/assets/home/img/promo/2.jpg" alt="">
                                    </a>
                                    <div class="promo-details"><a href="#">
                                        </a><a href="/"><img src="/assets/home/img/logo-for-card.jpg" alt="" class="logo-for-card"></a>
                                        <h2 class="promo-title">
                                            <strong>30<span class="persent-off">%</span></strong><span class="offer">OFF</span>
                                        </h2>
                                        <p class="transparent-offer-card"></p>
                                        <div class="link-for-time">
                                            <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                                DELIVERY</a>
                                            <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid
                                                until:
                                                DD:MM:YYYY</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- SINGLE-PROMO END -->

                            <!-- SINGLE-PROMO START -->
                            <div class="col-md-12 col-sm-12">
                                <a href="#">
                                </a>
                                <div class="single-promo " data-wow-duration="1.5s" data-wow-delay="1.30s"><a href="#">
                                        <img src="/assets/home/img/promo/3.jpg" alt="">
                                    </a>
                                    <div class="promo-details"><a href="#">
                                        </a><a href="/"><img src="/assets/home/img/product/images-2.jpeg" alt="" class="logo-for-card"></a>
                                        <h2 class="promo-title">
                                            <strong>20<span class="persent-off">%</span></strong><span class="offer">OFF</span>
                                        </h2>
                                        <p class="transparent-offer-card"></p>
                                        <div class="link-for-time">
                                            <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                                DELIVERY</a>
                                            <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid
                                                until:
                                                DD:MM:YYYY</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- SINGLE-PROMO END -->
                        </div>
                    </div>
                </div>

                <!-- SINGLE-PROMO START -->
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay="1s"><a href="#">
                            <img src="/assets/home/img/promo/4.jpg" alt="">
                        </a>
                        <div class="promo-details shop-now"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/Successful-Logos-swoosh.png" alt="" class="logo-for-card"></a>
                            <a class="readon" href="/"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;
                                Shop Now</a>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->
            </div>
            <br>
            <!--<div class="row">-->
            <!-- SINGLE-PROMO END -->

            <!--<div class="col-lg-6 col-md-6 col-sm-6">-->
            <div class="row">
                <div class="recent-client">
                    <h3>CAMPAIGNS FOR <strong>MEN</strong></h3>
                    <p></p>
                    <span></span>
                </div>
                <!-- SINGLE-PROMO START -->
                <div class="col-md-6 col-sm-6">

                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay=".70s"><a href="#">
                            <img src="/assets/home/img/promo/2.jpg" alt="">
                        </a>
                        <div class="promo-details"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/nike-logo-3.jpg" alt="" class="logo-for-card"></a>
                            <h2 class="promo-title">
                                <strong>35<span class="persent-off">%</span></strong><span class="offer">OFF</span>
                            </h2>
                            <p class="transparent-offer-card"></p>
                            <div class="link-for-time">
                                <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                    DELIVERY</a>
                                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid until:
                                    DD:MM:YYYY</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->

                <!-- SINGLE-PROMO START -->
                <div class="col-md-6 col-sm-6">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay="1.30s"><a href="#">
                            <img src="/assets/home/img/promo/3.jpg" alt="">
                        </a>
                        <div class="promo-details"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/images-2.jpeg" alt="" class="logo-for-card"></a>
                            <h2 class="promo-title">
                                <strong>40<span class="persent-off">%</span></strong><span class="offer">OFF</span>
                            </h2>
                            <p class="transparent-offer-card"></p>
                            <div class="link-for-time">
                                <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                    DELIVERY</a>
                                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid until:
                                    DD:MM:YYYY</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->
            </div>
            <!--</div>-->
            <!--<div class="col-lg-6 col-md-6 col-sm-6">-->
            <br>
            <div class="row">
                <div class="recent-client">
                    <h3>CAMPAIGNS FOR <strong>WOMEN</strong></h3>
                    <p></p>
                    <span></span>
                </div>
                <!-- SINGLE-PROMO START -->
                <div class="col-md-6 col-sm-6">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay=".70s"><a href="#">
                            <img src="/assets/home/img/promo/3.jpg" alt="">
                        </a>
                        <div class="promo-details"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/82c90710020339.560de26d363e2.png" alt="" class="logo-for-card"></a>
                            <h2 class="promo-title">
                                <strong>30<span class="persent-off">%</span></strong><span class="offer">OFF</span>
                            </h2>
                            <p class="transparent-offer-card"></p>
                            <div class="link-for-time">
                                <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                    DELIVERY</a>
                                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid until:
                                    DD:MM:YYYY</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->

                <!-- SINGLE-PROMO START -->
                <div class="col-md-6 col-sm-6">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay="1.30s"><a href="#">
                            <img src="/assets/home/img/promo/2.jpg" alt="">
                        </a>
                        <div class="promo-details"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/a27b20f85be0132562a615550f640bff.jpg" alt="" class="logo-for-card"></a>
                            <h2 class="promo-title">
                                <strong>45<span class="persent-off">%</span></strong><span class="offer">OFF</span>
                            </h2>
                            <p class="transparent-offer-card"></p>
                            <div class="link-for-time">
                                <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                    DELIVERY</a>
                                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid until:
                                    DD:MM:YYYY</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->
            </div>
            <!--</div>-->
            <br>

            <div class="row">
                <div class="recent-client">
                    <h3><strong>UPCOMING</strong> SALES</h3>
                    <p></p>
                    <span></span>
                </div>
                <!-- SINGLE-PROMO START -->
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay=".70s"><a href="#">
                            <img src="/assets/home/img/promo/2.jpg" alt="">
                        </a>
                        <div class="promo-details"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/dribbble-training-shed-brandmark.jpg" alt="" class="logo-for-card"></a>
                            <h2 class="promo-title">
                                <strong>50<span class="persent-off">%</span></strong><span class="offer">OFF</span></h2>
                            <p class="transparent-offer-card"></p>
                            <div class="link-for-time">
                                <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                    DELIVERY</a>
                                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid until:
                                    DD:MM:YYYY</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->

                <!-- SINGLE-PROMO START -->
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <a href="#">
                    </a>
                    <div class="single-promo " data-wow-duration="1.5s" data-wow-delay="1.30s"><a href="#">
                            <img src="/assets/home/img/promo/3.jpg" alt="">
                        </a>
                        <div class="promo-details"><a href="#">
                            </a><a href="/"><img src="/assets/home/img/product/YellowShed_logo.png" alt="" class="logo-for-card"></a>
                            <h2 class="promo-title">
                                <strong>15<span class="persent-off">%</span></strong><span class="offer">OFF</span></h2>
                            <p class="transparent-offer-card"></p>
                            <div class="link-for-time">
                                <a href="#" class="pull-left hidden-xs"><i class="fa fa-bus"></i>&nbsp; FREE
                                    DELIVERY</a>
                                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i>&nbsp; Valid until:
                                    DD:MM:YYYY</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- SINGLE-PROMO END -->
            </div>

            <!--</div>-->
            <!-- PROMO-LIST END -->
        </div>
    </div>
    <br>
    <!-- PROMOTION-AREA END -->

    <!-- NEW RAJESH DESIGN END -->


@endsection

@section('pagejavascripts')
    <script>
        /*
         //======Slider Revolution==============//
         $('.tp-banner').show().revolution({
         navigationType: "none",
         navigationArrows: "nexttobullets",
         navigationStyle: "preview4",

         keyboardNavigation: "off",

         navigationHAlign: "center",
         navigationVAlign: "center",
         navigationHOffset: 0,
         navigationVOffset: 20,

         soloArrowLeftHalign: "left",
         soloArrowLeftValign: "center",
         soloArrowLeftHOffset: 20,
         soloArrowLeftVOffset: 0,

         soloArrowRightHalign: "right",
         soloArrowRightValign: "center",
         soloArrowRightHOffset: 20,
         soloArrowRightVOffset: 0,
         dottedOverlay: "none",
         fullWidth: "on",
         forceFullWidth: "off",

         delay: 7000,
         startwidth: 1170,
         startheight: 700,
         hideThumbs: 10,
         });
         //  console.log("<?php echo \App::getLocale();?>");
         */
    </script>
@endsection
