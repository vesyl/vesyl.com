<?php \App::setLocale(\Session::get('user_locale'));
$categoryInfo = \FlashSale\Http\Modules\Home\Controllers\HomeController::getCategoriesForMenu();
$cartInfo = \FlashSale\Http\Modules\Home\Controllers\HomeController::getCartCount();

?>
        <!DOCTYPE html>


<!-- RAJESH NEW DESIGN START -->
<html lang="en">

<head>
    @include('Home/Layouts/home_header_scripts')
    @yield('pageheadcontent')
    <style>
        #squarespaceModal1 .modal-body .form-group button {
            background: #000 none repeat scroll 0 0;
            border: 1px solid #ddd;
            border-radius: 0;
            color: #ddd;
            cursor: pointer;
            font-size: 17px;
            height: 37px;
            width: 100%;
        }
        label.error, span.error{
            color: red !important;
        }
        .features-info{
            padding: 10px;
        }


    </style>
</head>
<body>

<!-- HEADER START -->
<header>
    <!-- HEADER TOP BAR START -->
    <div class="header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">

                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="header-top-menu">
                        <nav>
                            <ul>
                                @if (Session::has('fs_user'))
                                    {{--$userProfilePic = Session::get('fs_user')['profilepic'];--}}

                                    <li>
                                        <a href="javascript:;"><i class="fa fa-envelope message-icon" aria-hidden="true"></i></a>
                                        <div id="notification-content">
                                            You have not Notification
                                        </div>
                                        <ul class="message-list">
                                            <li><a href="#">List 1</a></li>
                                            <li><a href="#">List 2</a></li>
                                            <li><a href="#">List 3</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">ACCOUNT</a>
                                        <ul class="account-list">
                                            <li style="border-bottom: none;">
                                                <a href="#"><img src="/assets/home/img/country/en.gif" alt=""/>UNITED
                                                    KINGDOM |
                                                    GBP</a>
                                            </li>
                                            <hr>
                                            <li>
                                                <a href="/my-orders"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                                    Orders</a>
                                            </li>
                                            <li><a href="/giftcertificate"><i class="fa fa-gift" aria-hidden="true"></i>
                                                    Gift
                                                    Certificate</a></li>
                                            <li>
                                                <a href="/redeem-giftcertificate"><i class="fa fa-gift" aria-hidden="true"></i>
                                                    Redeem Gift
                                                    Certificate</a></li>
                                            <li><a href="/profile-setting"><i class="fa fa-cog" aria-hidden="true"></i>
                                                    Account &
                                                    Settings</a>
                                            </li>
                                            <li style="border-bottom: none;">
                                                <a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    Help</a></li>
                                            <li>
                                                <a href="/logout" class="text-center"><i class="fa fa-sign-out" aria-hidden="true"></i></i>
                                                    {{ trans('message.logout') }}</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#"><img src="/assets/home/img/country/en.gif" alt=""/> </a>
                                        <ul class="language">
                                            <li><a href="#"><img src="/assets/home/img/country/it.gif" alt=""/>italiano</a>
                                            </li>
                                            <li><a href="#"><img src="/assets/home/img/country/nl_nl.gif" alt=""/>Nederlands</a>
                                            </li>
                                            <li><a href="#"><img src="/assets/home/img/country/de_de.gif" alt=""/>Deutsch</a>
                                            </li>
                                            <li><a href="#"><img src="/assets/home/img/country/he_il.gif" alt=""/> עברית</a>
                                            </li>
                                            <li>
                                                <a href="#"><img src="/assets/home/img/country/en.gif" alt=""/>English</a>
                                            </li>
                                        </ul>
                                    </li>

                                @else

                                    <li>
                                        <a href="javascript:;"><i class="fa fa-sign-in" aria-hidden="true"></i> {{ trans('message.login') }}
                                        </a>
                                        <ul class="message-list sign-in-page">
                                            <li>
                                                <form class="signin_form" method="post" id="userloginform">
                                                    <div class="form-group">
                                                        <label for="email">{{ trans('message.username') }}
                                                            or {{ trans('message.email') }}*</label>
                                                        <input type="text" class="form-control" id="login_email" name="login_email" placeholder="Username or email">
                                                        <span id="login_email_err" class="error"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="login_password" class="login_password">{{ trans('message.password') }}
                                                            *</label>
                                                        <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Password">
                                                        <span id="login_password_err" class="error"></span>
                                                    </div>
                                                    <div class="">
                                                        <!--<label><input type="checkbox"> Remember me</label>-->
                                                        <span class="pull-right"><a href="javascript:;" onClick="forgotpd()">Forget
                                                                password</a></span>
                                                    </div>
                                                    <button type="submit" value="{{ trans('message.login')}}" class="btn btn-default">{{ trans('message.login')}}</button>
                                                </form>
                                                <div class="forgotpd col-lg-12 hidden" style="width: 100%">
                                                    <div class="col-lg-12">
                                                        <h4 class="text-uppercase" style="color: white;">Forgot
                                                            Password</h4>

                                                        <form class="forgotpd_form" method="post" id="forgotpasswordform">
                                                            <div class="form-group">
                                                                <label for="fp_email" class="">{{ trans('message.email') }}</label>
                                                                <input type="email" class="form-control" id="fp_email" name="fp_email" placeholder="Email">
                                                                <span id="fp_email_err" class="error"></span>
                                                            </div>
                                                            <div class="resetcode hidden" id="resetcodediv">
                                                                <p>
                                                                    Check Mail and Enter your reset code below to Reset
                                                                    password
                                                                </p>

                                                                <div class="form-group">
                                                                    <label for="fp_email" class="white_color">Reset
                                                                        code</label>
                                                                    <input type="text" class="form-control white_color" id="resetcode" name="resetcode" placeholder="Reset Code">
                                                                    <span id="resetcode_err" class="error"></span>
                                                                </div>

                                                            </div>
                                                            <a class="btn signin pull-right" onClick="login()" href="javascript:;">Back</a>
                                                            <button type="submit" value="Send" class="btn btn-default">
                                                                Send
                                                            </button>
                                                            <div id="fp-suc-err" class="error"></div>
                                                        </form>
                                                    </div>

                                                    <div class="col-lg-6">

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#squarespaceModal1"><i class="fa fa-user-plus" aria-hidden="true"></i>
                                            {{ trans('message.register') }}</a>
                                    </li>

                                @endif
                                <li class="cart"><a href="#"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; 99 CART</a>

                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER TOP BAR END -->

    <!-- MAINMENU START -->
    <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="logo">
                        <a href="/"><img src="/assets/home/img/rajesh_name_logo.png" alt=""/></a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <div class="mainmenu">
                        <nav>
                            <ul id="nav">

                                @if(isset($categoryInfo) && !(empty($categoryInfo)))
                                    <?php
                                    $mainCategory = array_filter(array_map(function ($category) {
                                        if ($category['parent_category_id'] == 0)
                                            return $category;
                                    }, $categoryInfo));
                                    ?>
                                    @if (!empty($mainCategory))
                                        <?php
                                        $mainCategory = $mainCategory[0];
                                        $mainCatId = explode(",", $mainCategory['category_ids']);
                                        $mainCatNames = explode(",", $mainCategory['category_names']);
                                        ?>
                                        @foreach($mainCatNames as $catKey => $catValue)
                                            <?php
                                            $categoryId = $mainCatId[$catKey];
                                            ?>
                                            <li>
                                                <a href="/product-list?catName={{urlencode(trim($catValue))}}">{{$catValue}}</a>
                                                <div class="mega-menu mega-menu-shop before-icon1" style="left: 10px;">
                                                    <span>
                                                        <a class="mega-menu-title " href="#">Today's Sales</a>
                                                        @if(isset($mainCategory['campaignDetails']) && !empty($mainCategory['campaignDetails']))
                                                            <?php foreach($mainCategory['campaignDetails'] as $campDetailKey => $campDetailVal){
                                                            $camInfo = in_array($categoryId, array_keys(json_decode($campDetailVal['for_category_ids'], true)));
                                                            if ($camInfo) {
                                                            $camps = $campDetailVal['campaign_name']; ?>
                                                            <a href="/flashsale-details?flashId={{$campDetailVal['campaign_id']}}&catName={{urlencode(trim($catValue))}}" class="badge-populer">{{$camps}}</a>
                                                            <?php }else {
                                                                continue;
                                                            }
                                                            }
                                                            ?>

                                                        @else
                                                            <a>NO Sales</a>
                                                        @endif

                                                        <a>--------View All Sales--------</a>
                                                    </span>
                                                    <span>
                                                        <a class="mega-menu-title badge-hot" href="#">Categories</a>

                                                        @foreach($categoryInfo as $allCatKey => $allCatValue)
                                                            @if($mainCatId[$catKey] == $allCatValue['parent_category_id'])
                                                                @foreach (explode(',', $allCatValue['category_names']) as $index => $item)
                                                                    <a class="new-pro" href="/product-list?catName={{urlencode(trim($catValue))}}&subcatName={{urlencode(trim($item))}}"><?php echo $item; ?></a>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </span>
                                                    <span>
                                                        <a href="/"><img src="/assets/home/img/promo/5.jpeg" alt=""/></a>
                                                        <a href="/"><img src="/assets/home/img/product/nike-logo-3.jpg" alt="" class="offer-link-logo"/></a>
                                                        <a class="shopping-btn" href="/"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;
                                                            Shop Now</a>
                                                    </span>
                                                </div>
                                            </li>

                                        @endforeach
                                    @endif
                                @endif

                                <?php $campaignInfo = \FlashSale\Http\Modules\Home\Controllers\HomeController::getCampaignsForMenu();?>
                                @if(isset($campaignInfo) && !(empty($campaignInfo)))
                                    <li>
                                        <a href="">Featured</a>
                                        <div class="mega-menu mega-menu-shop before-icon1" style="left: 10px;">
                                            <span>
                                                <a class="mega-menu-title " href="#">Today's Sales</a>
                                                @foreach($campaignInfo['todaySale']['FS'] as $todayFlashKey => $todayFlashVal)
                                                    <a href="/flashsale-details?flashId={{key($todayFlashVal)}}" class="badge-populer">{{current($todayFlashVal)}}</a>
                                                @endforeach
                                                <a>--------View All Sales--------</a>
                                            </span>
                                            <span>
                                                <a class="mega-menu-title " href="#">Categories</a>
                                                <?php
                                                $mainCategory = array_filter(array_map(function ($mainCat) {
                                                    return $mainCat;
                                                }, $campaignInfo))['categoryInfo'];
                                                ?>

                                                @foreach (explode(',', $campaignInfo['campaignCatId']['FS']) as $index => $item)
                                                    @foreach ($mainCategory as $maniCatKey => $mainCatValue)
                                                        @if ($item == $mainCatValue['category_id'])

                                                            <a href=/product-list?catName={{urlencode(trim($mainCatValue['category_name']))}}&campaign_type=FS" class="badge-populer">{{$mainCatValue['category_name']}}</a>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </span>

                                            <span>
                                                <a href="/"><img src="/assets/home/img/promo/5.jpeg" alt=""/></a>
                                                <a href="/"><img src="/assets/home/img/product/nike-logo-3.jpg" alt="" class="offer-link-logo"/></a>
                                                <a class="shopping-btn" href="/"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;
                                                    Shop Now</a>
                                            </span>
                                        </div>
                                    </li>



                                    <li>
                                        <a href="">Dailyspecial</a>
                                        <div class="mega-menu mega-menu-shop before-icon1" style="left: 10px;">
                                            <span>
                                                <a class="mega-menu-title " href="#">Today's Sales</a>

                                                @foreach($campaignInfo['todaySale']['DS'] as $todayFlashKey => $todayFlashVal)
                                                    <a href="/flashsale-details?flashId={{key($todayFlashVal)}}">{{current($todayFlashVal)}}</a>
                                                @endforeach
                                                <a>--------View All Sales--------</a>
                                            </span>
                                            <span>
                                                <a class="mega-menu-title " href="#">Categories</a>
                                                <?php
                                                $mainCategory = array_filter(array_map(function ($mainCat) {
                                                    return $mainCat;
                                                }, $campaignInfo))['categoryInfo'];
                                                ?>

                                                @foreach (explode(',', $campaignInfo['campaignCatId']['DS']) as $index => $item)
                                                    @foreach ($mainCategory as $maniCatKey => $mainCatValue)
                                                        @if ($item == $mainCatValue['category_id'])

                                                            <a href=/product-list?catName={{urlencode(trim($mainCatValue['category_name']))}}&campaign_type=DS" class="badge-populer">{{$mainCatValue['category_name']}}</a>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </span>

                                            <span>
                                                <a href="/"><img src="/assets/home/img/promo/5.jpeg" alt=""/></a>
                                                <a href="/"><img src="/assets/home/img/product/nike-logo-3.jpg" alt="" class="offer-link-logo"/></a>
                                                <a class="shopping-btn" href="/"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;
                                                    Shop Now</a>
                                            </span>
                                        </div>
                                    </li>

                            @endif

                            <!--<li><a href="blog.html">Blog</a>
                                    <ul class="sub-menu">
                                        <li><a href="single-blog.html">Blog details</a></li>
                                    </ul>
                                </li>-->

                                <li><a href="/shop-list">Shops</a></li>


                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
                    <div class="navbar-search">
                        <form action="#">
                            <input type="text" placeholder="Search">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAINMENU END -->
    <!-- mobile-menu-area start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul>
                                <li><a href="/">Home page</a>
                                    <ul>
                                        <li><a href="index-2.html">Homepage Version 2</a></li>
                                        <li><a href="index-3.html">Homepage Version 3</a></li>
                                        <li><a href="index-4.html">Homepage Version 4</a></li>
                                        <li><a href="index-5.html">Homepage Version 5</a></li>
                                        <li><a href="index-6.html">Homepage Version 6</a></li>
                                    </ul>
                                </li>
                                <li><a href="about-us.html">About us</a></li>
                                <li><a href="service.html">Services</a></li>
                                <li><a href="blog.html">Blog</a>
                                    <ul>
                                        <li><a href="single-blog.html">Blog details</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Pages</a>
                                    <ul>
                                        <li><a href="about-us.html">About Us</a></li>
                                        <li><a href="our-office.html">Our Office</a></li>
                                        <li><a href="sidebar-left.html">Sidebar Left</a></li>
                                        <li><a href="sidebar-right.html">Sidebar Right</a></li>
                                        <li><a href="contact-us.html">Contact Us Page</a></li>
                                        <li><a href="question-answers.html">Question & Answers</a></li>
                                        <li><a href="team.html">Meet The Team</a></li>
                                        <li><a href="team-2.html">Meet The Team 2</a></li>
                                        <li><a href="team-3.html">Meet The Team 3</a></li>
                                        <li><a href="team-member.html">Team Member</a></li>
                                        <li><a href="pricing.html">Plan and Pricing</a></li>
                                        <li><a href="service.html">Our Service Page</a></li>
                                        <li><a href="help-center.html">Help Center</a></li>
                                        <li><a href="testimonial.html">Client & Testimonial </a></li>
                                        <li><a href="product.html">Product Detail Page</a></li>
                                        <li><a href="blog.html">Blog</a></li>
                                        <li><a href="single-blog.html">Blog details</a></li>
                                    </ul>
                                </li>
                                <li><a href="shop.html">Shop</a></li>
                                <li><a href="contact-us.html">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- mobile-menu-area end -->
</header>
<!-- HEADER END -->

{{------------------------------------------PAGE CONTENT STARTS HERE-----------------------------------------}}
@yield('content')
{{------------------------------------------PAGE CONTENT ENDS HERE-----------------------------------------}}


<!-- FOOTER START -->
<footer>
    <!-- FOOTER-TOP-AREA START -->
    <div class="footer-top-area">
        <div class="container">
            <div class="row">
                <!-- FOOTER-MENU START -->
                <div class="col-lg-3 col-md-3 col-sm-4">
                    <div class="footer-menu">
                        <h3 class="footer-heading"><strong>Company</strong></h3>
                        <ul>
                            <li><a href="/pages/about">About</a></li>
                            <li><a href="/pages/press">Press </a></li>
                            <li><a href="/pages/careers">Carrier</a></li>
                        </ul>

                        <a href="/"><img src="/assets/home/img/rajesh_name_logo.png" alt="" style="width: 60%;"></a>
                    </div>
                </div>
                <!-- FOOTER-MENU END -->

                <!-- FOOTER-MENU START -->
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="footer-menu">
                        <h3 class="footer-heading"><strong>Customer </strong> Services</h3>
                        <ul>
                            <li><a href="/pages/faq">Faq</a></li>
                            <li><a href="/pages/contacts">Contacts </a></li>
                            <li><a href="/pages/return policy">Return policy</a></li>
                            <li><a href="/pages/taxes">Taxes</a></li>
                        </ul>
                    </div>
                </div>
                <!-- FOOTER-MENU END -->

                <!-- FOOTER-MENU START -->
                <div class="col-lg-3 col-md-3">
                    <div class="footer-menu shop-footer">
                        <h3 class="footer-heading"><strong>Policies </strong></h3>
                        <ul>
                            <li><a href="/pages/terms&conditions">Terms & condition</a></li>
                            <li><a href="/pages/privacy">Privacy</a></li>
                            <li><a href="/pages/security">Security</a></li>
                            <li><a href="/pages/termsofuse">Terms and uses</a></li>
                        </ul>
                    </div>
                </div>
                <!-- FOOTER-MENU END -->

                <!-- NEWSLETTER START -->
                <div class="col-lg-3 col-md-3 col-sm-5">
                    <div class="newsletter">
                        <h3 class="footer-heading"><strong>Contacts </strong> Us</h3>
                        <div class="social-icon pull-left"><!--todo give links here-->
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-google-plus"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-envelope"></i></a>
                        </div>
                        <span>@ 2017 ALL RIGHTS RESERVED</span>
                    </div>
                </div>
                <!-- NEWSLETTER END -->
            </div>
        </div>
    </div>
    <!-- line modal -->
    <div class="modal fade flash-product-modal" id="squarespaceModal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!--<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">trans('message.signup')</h3>
                </div>-->
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="modal-title" id="lineModalLabel">{{ trans('message.signup') }}</h4><br>
                            <form class="regsiter_form" method="post" id="usersignupform">
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label for="firstname">{{ trans('message.firstname') }} *</label>
                                        <input type="text" name="firstname" id="firstname" placeholder="First Name" required class="form-control"/>
                                        <span id="first_name_err" class="error"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="lastname">{{ trans('message.lastname') }} *</label>
                                        <input type="text" name="lastname" id="lastname" placeholder="Last Name" required class="form-control"/>
                                        <span id="last_name_err" class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username">{{ trans('message.username') }} *</label>
                                    <input type="text" id="username" name="username" placeholder="Username" required class="form-control"/>
                                    <span id="username_err" class="error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="email">{{ trans('message.email') }} *</label>
                                    <input type="text" id="email" name="email" placeholder="Enter your Email address" required class="form-control"/>
                                    <span id="email_err" class="error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="optradio">Gender: </label>
                                    <label class="radio-inline"><input type="radio" id="optradio" name="optradio">Female</label>
                                    <label class="radio-inline"><input type="radio" id="optradio" name="optradio">Male</label>
                                    <span id="contact_no_err" class="error"></span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="contact_no">Contact No.: *</label>
                                        <input type="telephone number" name="contact_no" id="contact_no" placeholder="Enter your contact number" maxlength="10" class="form-control"/>
                                        <span id="contact_no_err"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="date_of_birth">Birthday: </label>
                                        <select class="date_dd" id="date_dd" name="date_of_birth">
                                            <option>DD</option>
                                            <?php for($d = 1;$d <= 31;$d++){?>
                                            <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                                            <?php } ?>
                                            <span id="date_dd_err" class="error"></span> </select>
                                        <select class="month_mm" id="month_mm" name="date_of_birth">
                                            <option>MM</option>
                                            <?php for($m = 1;$m <= 12;$m++){?>
                                            <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                            <?php } ?>
                                            <span id="month_mm_err" class="error"></span> </select>
                                        <select class="year_yy" id="year_yy" name="date_of_birth">
                                            <option>YYYY</option>
                                            <?php
                                            $year = 1960;
                                            for($i = 1;$i <= 100;$i++){?>
                                            <option value="<?php echo $year + $i; ?>"><?php echo $year + $i; ?></option>
                                            <?php } ?>
                                            <span id="year_yy_err" class="error"></span> </select>
                                    </div>
                                </div>
                                <div class="form-group"><br>
                                    <button type="submit" value="{{ trans('message.signup') }}" class="btn btn-primary">{{ trans('message.signup') }}</button>
                                </div>
                                <div id="pw-suc-err" class="error"></div>
                            </form>
                        </div>
                        <div class="col-sm-4"><!--todo change content for this block-->
                            <h4 class="modal-title" id="lineModalLabel">About us</h4><br>
                            <p class="text-reg"> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br>
                            <hr>
                            <p class="text-reg text-center"> By registering you are agree to <br>our terms and condition
                            </p>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</footer>

@include('Home/Layouts/home_footer_scripts')
@include('Home/Layouts/home_common_code_script')
@yield('pagejavascripts')

<script src="/assets/global/plugins/time/time_ago.js"></script>
<script type="text/javascript">

    function forgotpd() {
        $("#login-content").css("width", "20rem");
        $(".signin_form").addClass('hidden');
        $('.forgotpd').removeClass('hidden');
        $('.loginmodel').addClass('hidden');
        $('.enternewpw').addClass('hidden');//emailID resetcode password1 password2
        $('#fp_email').prop('disabled', false);
        $('#resetcodediv').addClass('hidden');
    }

    function login() {
        //        $("#login-content").css("width", "90rem");
        $('.signin_form').removeClass('hidden');
        $('.forgotpd').addClass('hidden');
        $('.enternewpw').addClass('hidden');
        $('#fp_email').val('');
        $('#resetcode').val('');
        $('#password1').val('');
        $('#password2').val('');
        $('#fp_email').prop('disabled', false);
    }

    function enternewpw() {
        $("#login-content").css("width", "20rem");
        $('.enternewpw').removeClass('hidden');
        $('.forgotpd').addClass('hidden');
        $('.signin_form').addClass('hidden');
        $('#password1').val('');
        $('#password2').val('');
        $('#fp_email').prop('disabled', false);
    }

</script>

<script type="text/javascript">

    $(document).ready(function () {

        /*
         $('#showdetails').click(function () {
         $('#userpanel').slideToggle('slow', function () {
         $("#triangle_down").toggle();
         $("#triangle_up").toggle();
         });
         });
         */

        $.validator.addMethod("nameregex", function (value, element) {
            return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
        }, "Name cannot contain special characters.");

        $.validator.addMethod("usernameregex", function (value, element) {
            return this.optional(element) || /^[A-Za-z0-9._\s]+$/.test(value);
        }, "Username cannot contain special characters.");

        $.validator.addMethod("emailregex", function (value, element) {
            return this.optional(element) || /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/.test(value);
        }, "Invalid email address.");

        $.validator.addMethod("passwordregex", function (value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{7,14}$/.test(value);
        }, "Min 7 and Max 14 characters at least 1 Uppercase Alphabet, 1 Lowercase Alphabet, 1 Number and 1 Special Character (@$!%*?&):");

        $('#forgotpasswordform').validate({
            rules: {
                fp_email: {
                    required: true,
                    emailregex: true
                },
                resetcode: {
                    required: true
                }
            },
            messages: {
                fp_email: {
                    required: "Email cannot be empty"
                },
                resetcode: {
                    required: "Reset Code cannot be empty"
                }
            }
        });

        $('#EnterNewPasswordform').validate({
            rules: {
                password1: {
                    required: true,
                    passwordregex: true
                },
                password2: {
                    required: true,
                    equalTo: "#password1"
                }
            },
            messages: {
                password1: {
                    required: "Please Enter Password"
                },
                password2: {
                    required: "Please Re-enter Password"
                }
            }
        });

        $('#usersignupform').validate({
            rules: {
                firstname: {
                    required: true,
                    nameregex: true
                },
                lastname: {
                    required: true,
                    nameregex: true
                },
                username: {
                    required: true,
                    usernameregex: true
                },
                email: {
                    required: true,
                    emailregex: true
                }
            },
            messages: {
                firstname: {
                    required: "First name required"
                },
                lastname: {
                    required: "Last name required"
                },
                username: {
                    required: "User name cannot be empty"
                },
                email: {
                    required: "Email id required"
                }
            }
        });

        $('#userloginform').validate({
            rules: {
                login_email: {
                    required: true
                },
                login_password: {
                    required: true
                }
            },
            messages: {
                login_email: {
                    required: "Username or Email cannot be empty"
                },
                login_password: {
                    required: "Password cannot be empty"
                }
            }
        });

        $("#usersignupform").submit(function (e) {
            e.preventDefault();
            var Firstname = $('#firstname').val();
            var Lastname = $('#lastname').val();
            var Username = $('#username').val();
            var Email = $('#email').val();
            var Gender = $('#optradio').val();
            var Contact = $('#contact_no').val();
            var Date = $('#date_dd').val();
            var Month = $('#month_mm').val();
            var Year = $('#year_yy').val();
            if ($('#usersignupform').valid()) {
                $.ajax({
                    url: '/home-ajax-handler',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        method: 'user_signup',
                        fname: Firstname,
                        lname: Lastname,
                        uname: Username,
                        email: Email,
                        optradio: Gender,
                        contact_no: Contact,
                        date_of_birth: Year + "-" + Month + "-" + Date
                    },
                    success: function (response) {
//                    console.log(response);
                        if (response) {
                            if (response == 200) {
                                $('#pw-suc-err').show();
                                $('#pw-suc-err').html("Signup successful. Please check your email for Password");
                                $('#pw-suc-err').css('color', 'green');
                                $('#pw-suc-err').delay(6000).hide('slow');
                            } else {
                                if (response['code'] == 100) {
                                    var message = response['message'];
                                    $.each(message, function (key, value) {
//                                    console.log(key);
                                        $('#' + key + '_err').show();
                                        $('#' + key + '_err').html(message[key]);
                                        $('#' + key + '_err').css('color', 'red');
                                        $('#' + key + '_err').delay(6000).hide('slow');
                                    });
                                } else {
                                    $('#pw-suc-err').show();
                                    $('#pw-suc-err').html(response['message']);
                                    $('#pw-suc-err').css('color', 'red');
                                    $('#pw-suc-err').delay(6000).hide('slow');
                                }
                            }
                        }
                    }
                });
            }
        });

        $("#userloginform").submit(function (e) {
            e.preventDefault();
            var Username = $('#login_email').val();
            var Password = $('#login_password').val();
            if ($('#userloginform').valid()) {
                $.ajax({
                    url: '/home-ajax-handler',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        method: 'user_login',
                        uname: Username,
                        password: Password
                    },
                    success: function (response) {
//                    console.log(response);
                        if (response) {
                            if (response == 200) {
                                $('#login-suc-err').show();
                                $('#login-suc-err').html("Login successful");
                                $('#login-suc-err').css('color', 'green');
                                $('#login-suc-err').delay(6000).hide('slow');
                                window.location = "/";
                            } else {
                                if (response['code'] == 100) {
                                    var message = response['message'];
                                    $.each(message, function (key, value) {
//                                    console.log(key);
                                        if (key == "username") {
                                            $('#login_email_err').show();
                                            $('#login_email_err').html(message[key]);
                                            $('#login_email_err').css('color', 'red');
                                            $('#login_email_err').delay(6000).hide('slow');
                                        }
                                        if (key == "password") {
                                            $('#login_password_err').show();
                                            $('#login_password_err').html(message[key]);
                                            $('#login_password_err').css('color', 'red');
                                            $('#login_password_err').delay(6000).hide('slow');
                                        }
                                    })
                                } else {
                                    $('#login-suc-err').show();
                                    $('#login-suc-err').html(response['message']);
                                    $('#login-suc-err').css('color', 'red');
                                    $('#login-suc-err').delay(6000).hide('slow');
                                }
                            }
                        }
                    }
                });
            }
        });

        $("#forgotpasswordform").submit(function (e) {
            e.preventDefault();
            var fpdemail = $('#fp_email').val();
            var resetcode = $('#resetcode').val();

            if ($("#forgotpasswordform").valid()) {
                if (resetcode == '' && fpdemail != '') {
                    $.ajax({
                        url: '/home-ajax-handler',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            method: 'forgotpw',
                            fpwemail: fpdemail
                        },
                        success: function (response) {

                            if (response['code'] == 200) {
                                $('#fp_email_err').show();
                                $('#fp_email_err').html(response['message']);
                                $('#fp_email_err').css('color', 'green');
                                $('#fp_email_err').delay(4000).hide('slow');
                                $('#resetcodediv').removeClass('hidden');
                                $('#fp_email').prop('disabled', true);
                            } else {
                                $('#fp_email_err').show();
                                $('#fp_email_err').html(response['message']);
                                $('#fp_email_err').css('color', 'red');
                                $('#fp_email_err').delay(4000).hide('slow');
                            }
                        }
                    });
                } else {
                    $.ajax({
                        url: '/home-ajax-handler',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            method: 'verifyResetCode',
                            fpwemail: fpdemail,
                            resetcode: resetcode
                        },
                        success: function (response) {
                            if (response['code'] == 200) {
                                $('#resetcode_err').show();
                                $('#resetcode_err').html(response['message']);
                                $('#resetcode_err').css('color', 'green');
                                $('#resetcode_err').delay(4000).hide('slow');
                                enternewpw();
                            } else {
                                $('#resetcode_err').show();
                                $('#resetcode_err').html(response['message']);
                                $('#resetcode_err').css('color', 'red');
                                $('#resetcode_err').delay(4000).hide('slow');
                            }
                        }
                    });

                }
            }
        });

        $("#EnterNewPasswordform").submit(function (e) {
            e.preventDefault();
            var fpdemail = $('#fp_email').val();
            var resetcode = $('#resetcode').val();
            var password1 = $('#password1').val();
            var password2 = $('#password2').val();
            if ($("#EnterNewPasswordform").valid()) {
                if (resetcode != '' && fpdemail != '' && password1 != '' && password2 != '') {
                    $.ajax({
                        url: '/home-ajax-handler',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            method: 'resetPassword',
                            fpwemail: fpdemail,
                            password: password1,
                            re_password: password2,
                            reset_code: resetcode
                        },
                        success: function (response) {
                            if (response['code'] == 200) {
                                $('#resetpw-suc-err').show();
                                $('#resetpw-suc-err').html(response['message']);
                                $('#resetpw-suc-err').css('color', 'green');
                                $('#resetpw-suc-err').delay(4000).hide('slow');
                                $('#password1').val('');
                                $('#password2').val('');
                            } else {
                                $('#resetpw-suc-err').show();
                                $('#resetpw-suc-err').html(response['message']);
                                $('#resetpw-suc-err').css('color', 'red');
                                $('#resetpw-suc-err').delay(4000).hide('slow');
                                $('#password1').val('');
                                $('#password2').val('');
                            }
                        }
                    });
                }
            }
        });

    });

</script>

</body>
</html>


<!-- RAJESH NEW DESIGN ENDs -->
