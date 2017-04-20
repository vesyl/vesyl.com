@extends('Admin/Layouts/adminlayout')

@section('title', 'Dashboard')

@section('headcontent')
    <link href="/assets/css/components.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    {{--<div class="row">--}}
    {{--<div class="col-lg-3 col-md-6">--}}
    {{--<div class="panel info-box panel-white">--}}
    {{--<div class="panel-body">--}}
    {{--<div class="info-box-stats">--}}
    {{--<p class="counter">107,200</p>--}}
    {{--<span class="info-box-title">User activity this month</span>--}}
    {{--</div>--}}
    {{--<div class="info-box-icon">--}}
    {{--<i class="icon-users"></i>--}}
    {{--</div>--}}
    {{--<div class="info-box-progress">--}}
    {{--<div class="progress progress-xs progress-squared bs-n">--}}
    {{--<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"--}}
    {{--aria-valuemin="0" aria-valuemax="100" style="width: 40%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-3 col-md-6">--}}
    {{--<div class="panel info-box panel-white">--}}
    {{--<div class="panel-body">--}}
    {{--<div class="info-box-stats">--}}
    {{--<p class="counter">340,230</p>--}}
    {{--<span class="info-box-title">Page views</span>--}}
    {{--</div>--}}
    {{--<div class="info-box-icon">--}}
    {{--<i class="icon-eye"></i>--}}
    {{--</div>--}}
    {{--<div class="info-box-progress">--}}
    {{--<div class="progress progress-xs progress-squared bs-n">--}}
    {{--<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80"--}}
    {{--aria-valuemin="0" aria-valuemax="100" style="width: 80%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-3 col-md-6">--}}
    {{--<div class="panel info-box panel-white">--}}
    {{--<div class="panel-body">--}}
    {{--<div class="info-box-stats">--}}
    {{--<p>$<span class="counter">653,000</span></p>--}}
    {{--<span class="info-box-title">Monthly revenue goal</span>--}}
    {{--</div>--}}
    {{--<div class="info-box-icon">--}}
    {{--<i class="icon-basket"></i>--}}
    {{--</div>--}}
    {{--<div class="info-box-progress">--}}
    {{--<div class="progress progress-xs progress-squared bs-n">--}}
    {{--<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"--}}
    {{--aria-valuemin="0" aria-valuemax="100" style="width: 60%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-3 col-md-6">--}}
    {{--<div class="panel info-box panel-white">--}}
    {{--<div class="panel-body">--}}
    {{--<div class="info-box-stats">--}}
    {{--<p class="counter">47,500</p>--}}
    {{--<span class="info-box-title">New emails recieved</span>--}}
    {{--</div>--}}
    {{--<div class="info-box-icon">--}}
    {{--<i class="icon-envelope"></i>--}}
    {{--</div>--}}
    {{--<div class="info-box-progress">--}}
    {{--<div class="progress progress-xs progress-squared bs-n">--}}
    {{--<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50"--}}
    {{--aria-valuemin="0" aria-valuemax="100" style="width: 50%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<!-- Row -->--}}
    {{--<div class="row">--}}
    {{--<div class="col-lg-9 col-md-12">--}}
    {{--<div class="panel panel-white">--}}
    {{--<div class="row">--}}
    {{--<div class="col-sm-8">--}}
    {{--<div class="visitors-chart">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">Visitors</h4>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<div id="flotchart1"></div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-4">--}}
    {{--<div class="stats-info">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">Browser Stats</h4>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<ul class="list-unstyled">--}}
    {{--<li>Google Chrome--}}
    {{--<div class="text-success pull-right">32%<i class="fa fa-level-up"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li>Firefox--}}
    {{--<div class="text-success pull-right">25%<i class="fa fa-level-up"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li>Internet Explorer--}}
    {{--<div class="text-success pull-right">16%<i class="fa fa-level-up"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li>Safari--}}
    {{--<div class="text-danger pull-right">13%<i class="fa fa-level-down"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li>Opera--}}
    {{--<div class="text-danger pull-right">7%<i class="fa fa-level-down"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li>Mobile &amp; tablet--}}
    {{--<div class="text-success pull-right">4%<i class="fa fa-level-up"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li>Others--}}
    {{--<div class="text-success pull-right">3%<i class="fa fa-level-up"></i>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-3 col-md-6">--}}
    {{--<div class="panel panel-white" style="height: 100%;">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">Server Load</h4>--}}

    {{--<div class="panel-control">--}}
    {{--<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top"--}}
    {{--title="Expand/Collapse" class="panel-collapse"><i class="icon-arrow-down"></i></a>--}}
    {{--<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Reload"--}}
    {{--class="panel-reload"><i class="icon-reload"></i></a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<div class="server-load">--}}
    {{--<div class="server-stat">--}}
    {{--<span>Total Usage</span>--}}

    {{--<p>67GB</p>--}}
    {{--</div>--}}
    {{--<div class="server-stat">--}}
    {{--<span>Total Space</span>--}}

    {{--<p>320GB</p>--}}
    {{--</div>--}}
    {{--<div class="server-stat">--}}
    {{--<span>CPU</span>--}}

    {{--<p>57%</p>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div id="flotchart2"></div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-5 col-md-6">--}}
    {{--<div class="panel panel-white">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">Weather</h4>--}}

    {{--<div class="panel-control">--}}
    {{--<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Reload"--}}
    {{--class="panel-reload"><i class="icon-reload"></i></a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<div class="weather-widget">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-12">--}}
    {{--<div class="weather-top">--}}
    {{--<div class="weather-current pull-left">--}}
    {{--<i class="wi wi-day-cloudy weather-icon"></i>--}}

    {{--<p><span>83<sup>&deg;F</sup></span></p>--}}
    {{--</div>--}}
    {{--<h2 class="weather-day pull-right">Miami, FL<br>--}}
    {{--<small><b>13th April, 2015</b></small>--}}
    {{--</h2>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-6">--}}
    {{--<ul class="list-unstyled weather-info">--}}
    {{--<li>Wind <span class="pull-right"><b>ESE 16 mph</b></span></li>--}}
    {{--<li>Humidity <span class="pull-right"><b>64%</b></span></li>--}}
    {{--<li>Pressure <span class="pull-right"><b>30.15 in</b></span></li>--}}
    {{--<li>UV Index <span class="pull-right"><b>6</b></span></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--<div class="col-md-6">--}}
    {{--<ul class="list-unstyled weather-info">--}}
    {{--<li>Cloud Cover <span class="pull-right"><b>60%</b></span></li>--}}
    {{--<li>Ceiling <span class="pull-right"><b>17800 ft</b></span></li>--}}
    {{--<li>Dew Point <span class="pull-right"><b>70� F</b></span></li>--}}
    {{--<li>Visibility <span class="pull-right"><b>10 mi</b></span></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--<div class="col-md-12">--}}
    {{--<ul class="list-unstyled weather-days row">--}}
    {{--<li class="col-xs-4 col-sm-2"><span>12:00</span><i--}}
    {{--class="wi wi-day-cloudy"></i><span>82<sup>&deg;F</sup></span>--}}
    {{--</li>--}}
    {{--<li class="col-xs-4 col-sm-2"><span>13:00</span><i--}}
    {{--class="wi wi-day-cloudy"></i><span>82<sup>&deg;F</sup></span>--}}
    {{--</li>--}}
    {{--<li class="col-xs-4 col-sm-2"><span>14:00</span><i--}}
    {{--class="wi wi-day-cloudy"></i><span>82<sup>&deg;F</sup></span>--}}
    {{--</li>--}}
    {{--<li class="col-xs-4 col-sm-2"><span>15:00</span><i--}}
    {{--class="wi wi-day-cloudy"></i><span>83<sup>&deg;F</sup></span>--}}
    {{--</li>--}}
    {{--<li class="col-xs-4 col-sm-2"><span>16:00</span><i--}}
    {{--class="wi wi-day-cloudy"></i><span>82<sup>&deg;F</sup></span>--}}
    {{--</li>--}}
    {{--<li class="col-xs-4 col-sm-2"><span>17:00</span><i--}}
    {{--class="wi wi-day-sunny-overcast"></i><span>82<sup>&deg;F</sup></span>--}}
    {{--</li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-4 col-md-6">--}}
    {{--<div class="panel panel-white">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">Inbox</h4>--}}

    {{--<div class="panel-control">--}}
    {{--<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Reload"--}}
    {{--class="panel-reload"><i class="icon-reload"></i></a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<div class="inbox-widget slimscroll">--}}
    {{--<a href="#">--}}
    {{--<div class="inbox-item">--}}
    {{--<div class="inbox-item-img"><img src="assets/images/avatar2.png"--}}
    {{--class="img-circle" alt=""></div>--}}
    {{--<p class="inbox-item-author">Sandra Smith</p>--}}

    {{--<p class="inbox-item-text">Hey! I'm working on your...</p>--}}

    {{--<p class="inbox-item-date">13:40 PM</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--<a href="#">--}}
    {{--<div class="inbox-item">--}}
    {{--<div class="inbox-item-img"><img src="assets/images/avatar3.png"--}}
    {{--class="img-circle" alt=""></div>--}}
    {{--<p class="inbox-item-author">Christopher</p>--}}

    {{--<p class="inbox-item-text">I've finished it! See you so...</p>--}}

    {{--<p class="inbox-item-date">13:34 PM</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--<a href="#">--}}
    {{--<div class="inbox-item">--}}
    {{--<div class="inbox-item-img"><img src="assets/images/avatar4.png"--}}
    {{--class="img-circle" alt=""></div>--}}
    {{--<p class="inbox-item-author">Amily Lee</p>--}}

    {{--<p class="inbox-item-text">This theme is awesome!</p>--}}

    {{--<p class="inbox-item-date">13:17 PM</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--<a href="#">--}}
    {{--<div class="inbox-item">--}}
    {{--<div class="inbox-item-img"><img src="assets/images/avatar5.png"--}}
    {{--class="img-circle" alt=""></div>--}}
    {{--<p class="inbox-item-author">Nick Doe</p>--}}

    {{--<p class="inbox-item-text">Nice to meet you</p>--}}

    {{--<p class="inbox-item-date">12:20 PM</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--<a href="#">--}}
    {{--<div class="inbox-item">--}}
    {{--<div class="inbox-item-img"><img src="assets/images/avatar2.png"--}}
    {{--class="img-circle" alt=""></div>--}}
    {{--<p class="inbox-item-author">Sandra Smith</p>--}}

    {{--<p class="inbox-item-text">Hey! I'm working on your...</p>--}}

    {{--<p class="inbox-item-date">10:15 AM</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--<a href="#">--}}
    {{--<div class="inbox-item">--}}
    {{--<div class="inbox-item-img"><img src="assets/images/avatar4.png"--}}
    {{--class="img-circle" alt=""></div>--}}
    {{--<p class="inbox-item-author">Amily Lee</p>--}}

    {{--<p class="inbox-item-text">This theme is awesome!</p>--}}

    {{--<p class="inbox-item-date">9:56 AM</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-3 col-md-6">--}}
    {{--<div class="panel twitter-box">--}}
    {{--<div class="panel-body">--}}
    {{--<div class="live-tile" data-mode="flip" data-speed="750" data-delay="3000">--}}
    {{--<span class="tile-title pull-right">New Tweets</span>--}}
    {{--<i class="fa fa-twitter"></i>--}}

    {{--<div><h2 class="no-m">It�s kind of fun to do the impossible...</h2><span--}}
    {{--class="tile-date">10 April, 2015</span></div>--}}
    {{--<div><h2 class="no-m">Sometimes by losing a battle you find a new way to win the--}}
    {{--war...</h2><span class="tile-date">6 April, 2015</span></div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel facebook-box">--}}
    {{--<div class="panel-body">--}}
    {{--<div class="live-tile" data-mode="carousel" data-direction="horizontal" data-speed="750"--}}
    {{--data-delay="4500">--}}
    {{--<span class="tile-title pull-right">Facebook Feed</span>--}}
    {{--<i class="fa fa-facebook"></i>--}}

    {{--<div><h2 class="no-m">If you're going through hell, keep going...</h2><span--}}
    {{--class="tile-date">23 March, 2015</span></div>--}}
    {{--<div><h2 class="no-m">To improve is to change; to be perfect is to change often...</h2>--}}
    {{--<span class="tile-date">15 March, 2015</span></div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-12 col-md-12">--}}
    {{--<div class="panel panel-white">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">Project Stats</h4>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<div class="table-responsive project-stats">--}}
    {{--<table class="table">--}}
    {{--<thead>--}}
    {{--<tr>--}}
    {{--<th>#</th>--}}
    {{--<th>Project</th>--}}
    {{--<th>Status</th>--}}
    {{--<th>Manager</th>--}}
    {{--<th>Progress</th>--}}
    {{--</tr>--}}
    {{--</thead>--}}
    {{--<tbody>--}}
    {{--<tr>--}}
    {{--<th scope="row">452</th>--}}
    {{--<td>Mailbox Template</td>--}}
    {{--<td><span class="label label-info">Pending</span></td>--}}
    {{--<td>David Green</td>--}}
    {{--<td>--}}
    {{--<div class="progress progress-sm">--}}
    {{--<div class="progress-bar progress-bar-info" role="progressbar"--}}
    {{--aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"--}}
    {{--style="width: 40%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<th scope="row">327</th>--}}
    {{--<td>Wordpress Theme</td>--}}
    {{--<td><span class="label label-primary">In Progress</span></td>--}}
    {{--<td>Sandra Smith</td>--}}
    {{--<td>--}}
    {{--<div class="progress progress-sm">--}}
    {{--<div class="progress-bar progress-bar-primary" role="progressbar"--}}
    {{--aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"--}}
    {{--style="width: 60%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<th scope="row">226</th>--}}
    {{--<td>Modern Admin Template</td>--}}
    {{--<td><span class="label label-success">Finished</span></td>--}}
    {{--<td>Chritopher Palmer</td>--}}
    {{--<td>--}}
    {{--<div class="progress progress-sm">--}}
    {{--<div class="progress-bar progress-bar-success" role="progressbar"--}}
    {{--aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"--}}
    {{--style="width: 100%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<th scope="row">178</th>--}}
    {{--<td>eCommerce template</td>--}}
    {{--<td><span class="label label-danger">Canceled</span></td>--}}
    {{--<td>Amily Lee</td>--}}
    {{--<td>--}}
    {{--<div class="progress progress-sm">--}}
    {{--<div class="progress-bar progress-bar-danger" role="progressbar"--}}
    {{--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"--}}
    {{--style="width: 20%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<th scope="row">157</th>--}}
    {{--<td>Website PSD</td>--}}
    {{--<td><span class="label label-info">Testing</span></td>--}}
    {{--<td>Nick Doe</td>--}}
    {{--<td>--}}
    {{--<div class="progress progress-sm">--}}
    {{--<div class="progress-bar progress-bar-info" role="progressbar"--}}
    {{--aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"--}}
    {{--style="width: 50%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
    {{--<th scope="row">157</th>--}}
    {{--<td>Fronted Theme</td>--}}
    {{--<td><span class="label label-warning">Waiting</span></td>--}}
    {{--<td>David Green</td>--}}
    {{--<td>--}}
    {{--<div class="progress progress-sm">--}}
    {{--<div class="progress-bar progress-bar-warning" role="progressbar"--}}
    {{--aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"--}}
    {{--style="width: 70%">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>--}}
    {{--</tbody>--}}
    {{--</table>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div id="main-wrapper">
        <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat red">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">{{$count}}</div>
                        <div class="desc"> Total Orders</div>
                    </div>
                    <a class="more" href="javascript:;">
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>


    <div class="col-md-6">
        <!-- Begin: life time stats -->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-blue"></i>
                    <span class="caption-subject font-blue bold uppercase">Overview</span>
                    <span class="caption-helper">report overview...</span>
                </div>
                {{--<div class="actions">--}}
                    {{--<div class="btn-group">--}}
                        {{--<a class="btn green btn-circle btn-sm" href="javascript:;" data-toggle="dropdown"--}}
                           {{--data-hover="dropdown" data-close-others="true"> Actions--}}
                            {{--<i class="fa fa-angle-down"></i>--}}
                        {{--</a>--}}
                        {{--<ul class="dropdown-menu pull-right">--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> All Project </a>--}}
                            {{--</li>--}}
                            {{--<li class="divider"></li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> AirAsia </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> Cruise </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> HSBC </a>--}}
                            {{--</li>--}}
                            {{--<li class="divider"></li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> Pending--}}
                                    {{--<span class="badge badge-danger"> 4 </span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> Completed--}}
                                    {{--<span class="badge badge-success"> 12 </span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;"> Overdue--}}
                                    {{--<span class="badge badge-warning"> 9 </span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#overview_1" data-toggle="tab"> Top Selling </a>
                        </li>
                        {{--<li>--}}
                            {{--<a href="#overview_2" data-toggle="tab"> Most Viewed </a>--}}
                        {{--</li>--}}

                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> Orders
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="#overview_4" data-toggle="tab">
                                        <i class="icon-bell"></i> Latest 10 Orders </a>
                                </li>
                                {{--<li>--}}
                                    {{--<a href="#overview_4" data-toggle="tab">--}}
                                        {{--<i class="icon-info"></i> Pending Orders </a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#overview_4" data-toggle="tab">--}}
                                        {{--<i class="icon-speech"></i> Completed Orders </a>--}}
                                {{--</li>--}}
                                {{--<li class="divider"></li>--}}
                                {{--<li>--}}
                                    {{--<a href="#overview_4" data-toggle="tab">--}}
                                        {{--<i class="icon-settings"></i> Rejected Orders </a>--}}
                                {{--</li>--}}
                            </ul>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="overview_1">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th> Product Name</th>
                                        <th> Price</th>
                                        <th> Sold</th>
                                        {{--<th></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($productdetails as $ProductDetail)
                                    <tr>
                                        <td><a href="javascript:;">{{$ProductDetail->product_name}}</a></td>
                                        <td>{{$ProductDetail->list_price}}</td>
                                        <td>{{$ProductDetail->quantity}}</td>
                                        {{--<td>--}}
                                            {{--<a href="javascript:;" class="btn btn-sm btn-default">--}}
                                                {{--<i class="fa fa-search"></i> View </a>--}}
                                        {{--</td>--}}
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_2">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th> Product Name</th>
                                        <th> Price</th>
                                        <th> Views</th>
                                        {{--<th></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($productdetails as $ProductDetail)
                                        <tr>
                                            <td>
                                                <a href="javascript:;">{{$ProductDetail->product_name}}</a>
                                            </td>
                                            <td>{{$ProductDetail->list_price}}</td>
                                            <td>{{$ProductDetail->product_id_new}}</td>
                                            {{--<td>--}}
                                                {{--<a href="javascript:;" class="btn btn-sm btn-default">--}}
                                                    {{--<i class="fa fa-search"></i> View </a>--}}
                                            {{--</td>--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_4">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th> Customer Name</th>
                                        <th> Date</th>
                                        <th> Amount</th>
                                        <th> Status</th>
                                        {{--<th></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($productdetails as $ProductDetail)
                                        <tr>
                                        <td>
                                            <a href="javascript:;"> {{$ProductDetail->product_name}}</a>
                                        </td>
                                        <td> {{$ProductDetail->updated_at}}
                                        </td>
                                        <td> {{$ProductDetail->list_price}}</td>
                                        <td>
                                            <span class="label label-sm label-warning" {{$ProductDetail->order_status}}> Pending </span>
                                        </td>
                                        {{--<td>--}}
                                            {{--<a href="javascript:;" class="btn btn-sm btn-default">--}}
                                                {{--<i class="fa fa-search"></i> View </a>--}}
                                        {{--</td>--}}
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>

    <div class="col-md-6">
        <!-- Begin: life time stats -->
        <!-- BEGIN PORTLET-->

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-globe font-red"></i>
                    <span class="caption-subject font-red bold uppercase">Records</span>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#portlet_ecommerce_tab_1" data-toggle="tab"> Products </a>
                    </li>
                    <li>
                        <a href="#portlet_ecommerce_tab_2" id="statistics_orders_tab" data-toggle="tab"> Orders </a>
                    </li>
                </ul>
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="portlet_ecommerce_tab_1">
                        <div id="statistics_1" class="chart" style="padding: 0px; position: relative;">
                            <canvas class="flot-base"
                                    style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 496px; height: 300px;"
                                    width="496" height="300"></canvas>
                            <div class="flot-text"
                                 style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
                                <div class="flot-x-axis flot-x1-axis xAxis x1Axis"
                                     style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 25px; text-align: center;">
                                        03/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 79px; text-align: center;">
                                        04/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 132px; text-align: center;">
                                        05/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 186px; text-align: center;">
                                        06/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 240px; text-align: center;">
                                        07/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 293px; text-align: center;">
                                        08/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 347px; text-align: center;">
                                        09/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 400px; text-align: center;">
                                        10/2013
                                    </div>
                                    <div style="position: absolute; max-width: 54px; top: 284px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 454px; text-align: center;">
                                        11/2013
                                    </div>
                                </div>
                                <div class="flot-y-axis flot-y1-axis yAxis y1Axis"
                                     style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                                    <div style="position: absolute; top: 257px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 20px; text-align: right;">
                                        0
                                    </div>
                                    <div style="position: absolute; top: 205px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 7px; text-align: right;">
                                        500
                                    </div>
                                    <div style="position: absolute; top: 154px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">
                                        1000
                                    </div>
                                    <div style="position: absolute; top: 103px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">
                                        1500
                                    </div>
                                    <div style="position: absolute; top: 52px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">
                                        2000
                                    </div>
                                    <div style="position: absolute; top: 1px; font: small-caps 400 11px/15px &quot;Open Sans&quot;,sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">
                                        2500
                                    </div>
                                </div>
                            </div>
                            <canvas class="flot-overlay"
                                    style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 496px; height: 300px;"
                                    width="496" height="300"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane" id="portlet_ecommerce_tab_2">
                        <div id="statistics_2" class="chart"></div>
                    </div>
                </div>

                <div class="well margin-top-20">

                    <div class="row">

                        <div class="col-md-3 col-sm-3 col-xs-6 text-stat">

                            <span class="label label-success"> Revenue: </span>

                            <h3>{{$revenuestatus}}</h3>

                        </div>

                        {{--<div class="col-md-3 col-sm-3 col-xs-6 text-stat">--}}
                            {{--<span class="label label-info"> Tax: </span>--}}
                            {{--<h3>$134,90.10</h3>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3 col-sm-3 col-xs-6 text-stat">--}}
                            {{--<span class="label label-danger"> Shipment: </span>--}}
                            {{--<h3>$1,134,90.10</h3>--}}
                        {{--</div>--}}
                        <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                            <span class="label label-warning"> Orders: </span>
                            <h3>{{$orderstatus}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End: life time stats -->
    </div>

@endsection

@section('pagejavascripts')
    <script src="/assets/plugins/3d-bold-navigation/js/main.js"></script>
    <script src="/assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="/assets/plugins/jquery-counterup/jquery.counterup.min.js"></script>
    <script src="/assets/plugins/toastr/toastr.min.js"></script>

    <script src="/assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script><!--new line-->
    <script src="/assets/plugins/curvedlines/curvedLines.js"></script>
    <script src="/assets/plugins/metrojs/MetroJs.min.js"></script>

    {{--<script src="/assets/js/ecommerce-dashboard.min.js"></script>--}}

    {{--<script src="/assets/js/pages/dashboard.js"></script>--}}
<script>
    var EcommerceDashboard = function () {
        function o(o, i, t, a) {
            $('<div id="tooltip" class="chart-tooltip">' + a.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + "USD</div>").css({
                position: "absolute",
                display: "none",
                top: i - 40,
                left: o - 60,
                border: "0px solid #ccc",
                padding: "2px 6px",
                "background-color": "#fff"
            }).appendTo("body").fadeIn(200)
        }

        var i = function () {
            var i = [["Jan",{{$products['0']->jan}}],["Feb",{{$products['0']->feb}}],["Mar",{{$products['0']->mar}}],["Apr",{{$products['0']->apr}}],["May",{{$products['0']->may}}],["Jun",{{$products['0']->jun}}],["Jul",{{$products['0']->jul}}],["Aug",{{$products['0']->aug}}],["Sept",{{$products['0']->sep}}], ["Oct",{{$products['0']->oct}}],["Nov",{{$products['0']->nov}}],["Dec",{{$products['0']->december}}]],
                t = ($.plot($("#statistics_1"), [{
                data: i,
                lines: {fill: .6, lineWidth: 0},
                color: ["#f89f9f"]
            }, {
                data: i,
                points: {show: !0, fill: !0, radius: 5, fillColor: "#f89f9f", lineWidth: 3},
                color: "#fff",
                shadowSize: 0
            }], {
                xaxis: {
                    tickLength: 0,
                    tickDecimals: 0,
                    mode: "categories",
                    min: 0,
                    font: {lineHeight: 15, style: "normal", variant: "small-caps", color: "#6F7B8A"}
                },
                yaxis: {
                    ticks: 3,
                    tickDecimals: 0,
                    tickColor: "#f0f0f0",
                    font: {lineHeight: 15, style: "normal", variant: "small-caps", color: "#6F7B8A"}
                },
                grid: {
                    backgroundColor: {colors: ["#fff", "#fff"]},
                    borderWidth: 1,
                    borderColor: "#f0f0f0",
                    margin: 0,
                    minBorderMargin: 0,
                    labelMargin: 20,
                    hoverable: !0,
                    clickable: !0,
                    mouseActiveRadius: 6
                },
                legend: {show: !1}
            }), null);
            $("#statistics_1").bind("plothover", function (i, a, e) {
                if ($("#x").text(a.x.toFixed(2)), $("#y").text(a.y.toFixed(2)), e) {
                    if (t != e.dataIndex) {
                        t = e.dataIndex, $("#tooltip").remove();
                        e.datapoint[0].toFixed(2), e.datapoint[1].toFixed(2);
                        o(e.pageX, e.pageY, e.datapoint[0], e.datapoint[1])
                    }
                } else $("#tooltip").remove(), t = null
            })
        }, t = function () {
            var i = [["Jan",{{$orderdetails['0']->jan}}],["Feb",{{$orderdetails['0']->feb}}],["Mar",{{$orderdetails['0']->mar}}],["Apr",{{$orderdetails['0']->apr}}],["May",{{$orderdetails['0']->may}}],["Jun",{{$orderdetails['0']->jun}}],["Jul",{{$orderdetails['0']->jul}}],["Aug",{{$orderdetails['0']->aug}}],["Sept",{{$orderdetails['0']->sep}}], ["Oct",{{$orderdetails['0']->oct}}],["Nov",{{$orderdetails['0']->nov}}],["Dec",{{$orderdetails['0']->december}}]],
                t = ($.plot($("#statistics_2"), [{
                data: i,
                lines: {fill: .6, lineWidth: 0},
                color: ["#BAD9F5"]
            }, {
                data: i,
                points: {show: !0, fill: !0, radius: 5, fillColor: "#BAD9F5", lineWidth: 3},
                color: "#fff",
                shadowSize: 0
            }], {
                xaxis: {
                    tickLength: 0,
                    tickDecimals: 0,
                    mode: "categories",
                    min: 0,
                    font: {lineHeight: 14, style: "normal", variant: "small-caps", color: "#6F7B8A"}
                },
                yaxis: {
                    ticks: 3,
                    tickDecimals: 0,
                    tickColor: "#f0f0f0",
                    font: {lineHeight: 14, style: "normal", variant: "small-caps", color: "#6F7B8A"}
                },
                grid: {
                    backgroundColor: {colors: ["#fff", "#fff"]},
                    borderWidth: 1,
                    borderColor: "#f0f0f0",
                    margin: 0,
                    minBorderMargin: 0,
                    labelMargin: 20,
                    hoverable: !0,
                    clickable: !0,
                    mouseActiveRadius: 6
                },
                legend: {show: !1}
            }), null);
            $("#statistics_2").bind("plothover", function (i, a, e) {
                if ($("#x").text(a.x.toFixed(2)), $("#y").text(a.y.toFixed(2)), e) {
                    if (t != e.dataIndex) {
                        t = e.dataIndex, $("#tooltip").remove();
                        e.datapoint[0].toFixed(2), e.datapoint[1].toFixed(2);
                        o(e.pageX, e.pageY, e.datapoint[0], e.datapoint[1])
                    }
                } else $("#tooltip").remove(), t = null
            })
        };
        return {
            init: function () {
                i(), $("#statistics_orders_tab").on("shown.bs.tab", function (o) {
                    t()
                })
            }
        }
    }();
    jQuery(document).ready(function () {
        EcommerceDashboard.init()
    });
</script>
@endsection
