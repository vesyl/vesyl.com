<!DOCTYPE html>
<html>
<head>
    @include('Buyer/Layouts/buyerheadscripts')
    @yield('pageheadcontent')
    <style>
        .btn-info {
            margin-top: 0px;
        }
    </style>
</head>
<body class="page-header-fixed compact-menu page-sidebar-fixed">
<div class="overlay"></div>
<!--<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s1">
    <h3><span class="pull-left">Chat</span><a href="javascript:void(0);" class="pull-right" id="closeRight"><i
                    class="fa fa-times"></i></a></h3>

    <div class="slimscroll">
        <a href="javascript:void(0);" class="showRight2"><img src="/assets/images/avatar2.png" alt=""><span>Sandra smith
                <small>
                    Hi! How're you?
                </small>
            </span></a>
    </div>
</nav>-->
<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
    <h3><span class="pull-left">Sandra Smith</span> <a href="javascript:void(0);" class="pull-right" id="closeRight2"><i
                    class="fa fa-angle-right"></i></a></h3>

    <div class="slimscroll chat">
        <div class="chat-item chat-item-left">
            <div class="chat-image">
                {{--<img src="/assets/images/avatar2.png" alt="">--}}
            </div>
            <div class="chat-message">
                Hi There!
            </div>
        </div>
        <div class="chat-item chat-item-right">
            <div class="chat-message">
                Hi! How are you?
            </div>
        </div>
    </div>
    <div class="chat-write">
        <form class="form-horizontal" action="javascript:void(0);">
            <input type="text" class="form-control" placeholder="Say something">
        </form>
    </div>
</nav>
<!--<div class="menu-wrap">
    <nav class="profile-menu">
        <div class="profile"><img src="/assets/images/avatar1.png" width="52" alt="David Green"/><span>David
                Green</span>
        </div>
        <div class="profile-menu-list">
            <a href="#"><i class="fa fa-star"></i><span>Favorites</span></a>
            <a href="#"><i class="fa fa-bell"></i><span>Alerts</span></a>
            <a href="#"><i class="fa fa-envelope"></i><span>Messages</span></a>
            <a href="#"><i class="fa fa-comment"></i><span>Comments</span></a>
        </div>
    </nav>
    <button class="close-button" id="close-button">Close Menu</button>
</div>-->
<form class="search-form" action="#" method="GET">
    <div class="input-group">
        <input type="text" name="search" class="form-control search-input" placeholder="Search...">
        <span class="input-group-btn">
            <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button">
                <i class="fa fa-times"></i>
            </button>
        </span>
    </div>
    <!-- Input Group -->
</form>
<!-- Search Form -->
<main class="page-content content-wrap">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="sidebar-pusher">
                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="logo-box">
                <a href="/" class="logo-text"><span>FlashSale</span></a>
            </div>
            <!-- Logo Box -->
            <div class="search-button">
                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i
                            class="fa fa-search"></i></a>
            </div>
            <div class="topmenu-outer">
                <div class="top-menu">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <?php $cartInfo = \FlashSale\Http\Modules\Home\Controllers\HomeController::getCartCount();?>
                            {{--<div class="col-md-12 col-xs-12 cart" id="add-to-carts">--}}
                            <a class="index" href="javascript:;" data-toggle="modal" data-target=".cart-bs-example-modal-lg">
                                <img src="/assets/images/cart.png" class="img-responsive;"
                                        style="float: left; margin-top: 5px; margin-left: 10px;">
                                <span>{{count($cartInfo)}}</span>
                                <span id="cart">Cart</span> </a>
                            {{--</div>--}}
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                                <span class="user-name">{{Session::get('fs_buyer')['name']}}
                                    <i class="fa fa-angle-down"></i>
                                </span>
                                <img class="img-circle avatar" src="{{Session::get('fs_buyer')['profilepic']}}" width="40" height="40" alt="">
                            </a>
                            <ul class="dropdown-menu dropdown-list" role="menu">
                                <li role="presentation">
                                    <a href="/buyer/profile"><i class="fa fa-user"></i>Profile</a>
                                </li>
                                {{--<li role="presentation"><a href="/calender"><i class="fa fa-calendar"></i>Calendar</a></li>--}}
                                {{--<li role="presentation"><a href="/inbox"><i class="fa fa-envelope"></i>Inbox<span class="badge badge-success pull-right">4</span></a></li>--}}
                                <li role="presentation" class="divider"></li>
                                {{--<li role="presentation"><a href="/lock-screen"><i class="fa fa-lock"></i>Lock screen</a></li>--}}
                                <li role="presentation">
                                    <a href="/buyer/logout"><i class="fa fa-sign-out m-r-xs"></i>Log out</a>
                                </li>
                            </ul>
                                <li class="langs-block dropdown dropdown-user">

                                    <a href="javascript:;" class="current dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        Notification<i class="fa fa-angle-down"></i>
                                        <span class="badge badge-default" id="count" style="background:red;color:black;"></span>
                                    </a>
                                    <div class="langs-block-others-wrapper dropdown-menu">
                                        <div class="langs-block-others" style="width: 215px ">
                                            <div class="container" style="width: 210px;">
                                                <div class="table-full-width " id="abc">
                                                    <div> </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>



                    </ul>
                    <!-- Nav -->
                </div>
                <!-- Top Menu -->
            </div>
        </div>
    </div>
    <!-- Navbar -->
    <div class="page-sidebar sidebar">
        <div class="page-sidebar-inner slimscroll">
            <ul class="menu accordion-menu">
                <li class="active">
                    <a href="/buyer/dashboard" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-home"></span>

                        <p>{{ trans('message.dashboard') }}</p>
                    </a>
                </li>

                <li class="">
                    <a href="/buyer/wholesale-list/1" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-th-large"></span>
                        <p>Wholesales</p>
                    </a>
                </li>

                <li class="">
                    <a href="/buyer/my-orders/1" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-shopping-cart"></span>
                        <p>My orders</p>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Page Sidebar Inner -->
    </div>
    <!-- Page Sidebar -->

    <div class="page-inner">
        <div class="page-title">

            <h3><b>@yield('title')</b></h3>
        </div>
        <div id="main-wrapper">
            {{------------------------------------------CONTENT GOES HERE-----------------------------------------}}
            @yield('content')
        </div>
        <!-- Main Wrapper -->
        <div class="page-footer">
            <p class="no-s">2015 &copy; FlashSale.</p>
        </div>
    </div>
    <!-- Page Inner -->

</main>
<div class="cd-overlay"></div>

@include('Buyer/Layouts/buyercommonfooterscripts')

@yield('pagejavascripts')
<script src="/assets/js/modern.js"></script>
<script src="/assets/global/plugins/time/time_ago.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        get_notification();

    });
    function get_notification() {

        var feedback = $.ajax({
            type: "POST",
            url: "/buyer/userAjaxHandler",
            data: {
                method: 'getbuyerNotification',
            },
            async: false
        }).success(function (data) {

            console.log(data)
            var notifdata = jQuery.parseJSON(data);
            var notifics = notifdata[0];
            var noti = notifdata[1];
            //   alert(notifics);
            $("#abc").empty();
            if (noti != null && noti != 0) {
                $.each(noti, function (index, value) {
                    var notiId = value['notification_id'];
                    var message = value['message'];
                    var timestamp = value['send_date'];
                    var time = time_ago(timestamp);
                    $("#abc").append('<ul class="dropdown-menu-list scroller" style="height: 40px;"><li><a data-desc="' + message + '" data-id=' + notiId + ' data-count=' + notifics + '  id="descmod"  class="modaldescription" data-toggle="modal" data-target="#mymodel"><span class="label label-sm label-icon label-success" height="><i class="fa fa-plus"></i></span>' + message + ' <span class="time">' + time + '</span></a></li></ul>');

                });
            } else {
                $("#abc").append('<ul class="dropdown-menu-list scroller" style="height: 40px;"><li><a id="descmod"  class="modaldescription" data-toggle="modal" data-target="#mymodel"><span class="label label-sm label-icon label-success" height="><i class="fa fa-plus"></i></span>No Notification<span class="time"></span></a></li></ul>');
            }
            $("#count").html(notifdata[0]);
            setTimeout(function () {
                get_notification();
            }, 30000);
        })
    }
    $(document.body).on("click", ".modaldescription", function () {
        var obj = $(this);
        var message = $(this).attr('data-desc');
        //var counts = $(this).attr('data-count');
        var count = $('#count').html();
        //  alert(count);
        $('#message1').html(message);
        // alert(counts - 1);
        var notificationId = $(this).attr('data-id');
        //alert(notificationId);
        $.ajax({
            url: '/admin/notification-ajax-handler',
            type: 'POST',
            datatype: 'json',
            data: {
                method: 'changenotificationstatus',
                NotificationId: notificationId

            },
            success: function (response) {
                if (response) {
                    //alert(response);
                    obj.parent().hide();
                    $('#count').html(count - 1);

                }
            }
        });
    });
</script>
</body>
</html>