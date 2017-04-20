@extends('Home/Layouts/home_layout')

@section('title', 'My orders')

@section('pageheadcontent')
    <style>
        blockquote small::before, blockquote .small::before {
            content: normal;
        }

        blockquote {
            background-color: white;
            border-left-color: #CCCCCC;
        }

        /*for tracking steps start*/
        .mt-element-step .step-line .mt-step-col {
            padding: 10px 0;
            text-align: center;
        }

        .mt-element-step .step-line .mt-step-number {
            font-size: 10px;
            border-radius: 50% !important;
            display: inline-block;
            margin: auto;
            padding: 4px;
            margin-bottom: 2px;
            border: 1px solid;
            border-color: #e5e5e5;
            position: relative;
            z-index: 5;
            height: 20px;
            width: 20px;
            text-align: center;
        }

        .mt-element-step .step-line .mt-step-number > i {
            position: relative;
            top: 50%;
            transform: translateY(-60%);
        }

        .mt-element-step .step-line .mt-step-title {
            font-size: 7px;
            font-weight: 400;
            position: relative;
        }

        .mt-element-step .step-line .mt-step-title:after {
            content: '';
            height: 1px;
            width: 50%;
            position: absolute;
            background-color: #e5e5e5;
            top: -10px;
            left: 50%;
            z-index: 4;
            transform: translateY(-35%);
        }

        .mt-element-step .step-line .mt-step-title:before {
            content: '';
            height: 1px;
            width: 50%;
            position: absolute;
            background-color: #e5e5e5;
            top: -10px;
            right: 50%;
            z-index: 4;
            transform: translateY(-35%);
        }

        .mt-element-step .step-line .first .mt-step-title:before {
            content: none;
        }

        .mt-element-step .step-line .last .mt-step-title:after {
            content: none;
        }

        .mt-element-step .step-line .active .mt-step-number {
            color: #32c5d2 !important;
            border-color: #32c5d2 !important;
        }

        .mt-element-step .step-line .active .mt-step-title,
        .mt-element-step .step-line .active .mt-step-content {
            color: #32c5d2 !important;
        }

        .mt-element-step .step-line .active .mt-step-title:after, .mt-element-step .step-line .active .mt-step-title:before {
            background-color: #32c5d2;
        }

        .mt-element-step .step-line .done .mt-step-number {
            color: #26C281 !important;
            border-color: #26C281 !important;
        }

        .mt-element-step .step-line .done .mt-step-title,
        .mt-element-step .step-line .done .mt-step-content {
            color: #26C281 !important;
        }

        .mt-element-step .step-line .done .mt-step-title:after, .mt-element-step .step-line .done .mt-step-title:before {
            background-color: #26C281;
        }

        .mt-element-step .step-line .error .mt-step-number {
            color: #E7505A !important;
            border-color: #E7505A !important;
        }

        .mt-element-step .step-line .error .mt-step-title,
        .mt-element-step .step-line .error .mt-step-content {
            color: #E7505A !important;
        }

        .mt-element-step .step-line .error .mt-step-title:after, .mt-element-step .step-line .error .mt-step-title:before {
            background-color: #E7505A;
        }

        @media (max-width: 991px) {
            /* 991px */
            .mt-element-step .step-line .mt-step-title:after {
                content: none;
            }

            .mt-element-step .step-line .mt-step-title:before {
                content: none;
            }
        }

        /*for tracking steps end*/

    </style>

@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 last_order">
                <div class="clearfix"><h1>Order Details</h1></div>

                @if($orderDetails['code'] == 200)
                    <?php
                    $userDetails = json_decode($orderDetails['data']['user_details'], true);
                    $shippingDetails = json_decode($orderDetails['data']['shipping_addr'], true);
                    $billingDetails = json_decode($orderDetails['data']['billing_addr'], true);
                    $productDetails = json_decode($orderDetails['data']['product_details'], true);
                    ?>
                    <div class="col-md-5">
                        <div class="panel panel-default f_pan1" style="padding: 1px; background-color: rgb(223, 223, 223);">
                            <ul class="list-group">
                                <li class="list-group-item" style="padding: 5px 15px;">
                                    <label class="small">Order ID: </label>
                                    <mark class="lead text-primary">{{$orderDetails['data']['order_id']}}</mark>
                                    <!--<span>(1 Item)</span>-->
                                </li>
                                <li class="list-group-item" style="padding: 5px 15px;">
                                    <label class="small">Order
                                        Date:</label> {{$orderDetails['data']['added_date']}}
                                </li>
                                <li class="list-group-item" style="padding: 5px 15px;">
                                    <label class="small">Amount
                                        Paid:</label>
                                    <span class="text-success lead">
                                        {{$orderDetails['data']['final_price']}}</span>
                                    <span class="small"> through </span>
                                    <code><em> {{ $orderDetails['data']['pmethod_name']}}</em></code>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-12 panel panel-default f_pan1" style="padding: 1px; background-color: rgb(243, 243, 243);">
                            <div style="padding: 10px;">
                                <span class="lead" style="padding: 5px;"> {{$userDetails['firstname'] }} {{$userDetails['lastname']}}</span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                <blockquote class="text-muted">
                                    <p>Shipping address: </p>
                                    <small>
                                        {{$shippingDetails['addrline1']}} <br>
                                        {{$shippingDetails['addrline2']}} <br>
                                        {{$shippingDetails['city']}} @if($shippingDetails['state'] != '')
                                            ( {{$shippingDetails['state']}} ) @endif, <br>
                                        {{$shippingDetails['country']}} - {{$shippingDetails['zip']}} <br>
                                        <span class="small">Contact:</span> {{$shippingDetails['phone']}}
                                    </small>
                                </blockquote>
                            </div>
                            <div class="col-md-6">
                                <blockquote class="text-muted">
                                    <p>Billing address: </p>
                                    <small>
                                        {{$billingDetails['addrline1']}} <br>
                                        {{$billingDetails['addrline2']}} <br>
                                        {{$billingDetails['city']}} @if($billingDetails['state'] != '')
                                            ( {{$billingDetails['state']}} ) @endif,<br>
                                        {{$billingDetails['country']}} - {{$billingDetails['zip']}} <br>
                                        <span class="text-inline small">Contact:</span> {{$billingDetails['phone']}}
                                    </small>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">MANAGE ORDER</div>
                            <div class="panel-body order-actions">
                                <ul class="line">
                                    <li style="width:49%; display: block;" class="unit">
                                        <a alt="Print this page" id="print-order" style="color: #333; text-decoration: none;">
                                            <span class="fa fa-3x fa-print" style="display: block; height: 35px; margin: 0 auto 10px; width: 37px;"></span>
                                            PRINT ORDER
                                        </a>
                                    </li>
                                    <li style="width:49%; display: block;" class="unit last">
                                        <a id="print-order" style="color: #333; text-decoration: none;">
                                            <span class="fa fa-3x fa-print" style="display: block; height: 35px; margin: 0 auto 10px; width: 37px;"></span>
                                            CONTACT US
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>-->
                    <div class="col-md-12">

                        <div class="panel panel-default row">
                            <div class="panel-heading col-md-12">
                                <div class="col-md-5" style="">PRODUCT DETAILS</div>
                                <div class="col-md-7" style="">
                                    <span class="detail" style="width: 18%;">Approval</span>
                                    <span class="detail" style="width: 18%;">Processing</span>
                                    <span class="detail" style="width: 18%;">Shipping</span>
                                    <span class="detail" style="width: 18%;">Delivery</span>
                                    <span class="detail" style="width: 27%;">Sub-total</span>
                                </div>
                            </div>
                            <div class="panel-body col-md-12">
                                <div class="col-md-5">
                                    <div class="col-md-4">
                                        <img src="{{$productDetails['p_image']}}" class="img-rounded" style="width:100%; border: solid 2px #cccccc;">
                                    </div>
                                    <div class="col-md-8">{{ $productDetails['p_name'] }}</div>
                                    <!--todo product more details to be shown-->
                                </div>
                                <div class="col-md-7">
                                    <div class="col-md-9">
                                        <div class="mt-element-step">
                                            <div class="row step-line">
                                                <div class="col-md-3 mt-step-col first done">
                                                    <div class="mt-step-number bg-white">
                                                        <i class="fa fa-check"></i>
                                                        <!--fa-circle-o-notch fa-question-circle fa-times-circle fa-cc-paypal fa-cc-visa-->
                                                    </div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Approved
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-step-col active">
                                                    <div class="mt-step-number bg-white">
                                                        <i class="fa fa-circle-o-notch"></i>
                                                    </div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Processing
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-step-col">
                                                    <div class="mt-step-number bg-white">
                                                        <i class="fa fa-truck"></i>
                                                    </div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">Shipping
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-step-col last">
                                                    <div class="mt-step-number bg-white">
                                                        <i class="fa fa-gift"></i><!--fa-shopping-bag-->
                                                    </div>
                                                    <div class="mt-step-title uppercase font-grey-cascade">To be
                                                        delivered
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        {{$orderDetails['data']['unit_price']}} X {{$orderDetails['data']['quantity']}}
                                        @if($orderDetails['data']['discount_value'] != 0)
                                            - {{$orderDetails['data']['discount_value']}}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <hr>
                            <span style="float: right;">TOTAL : {{$orderDetails['data']['final_price']}}</span>
                        </div>
                    </div>
            </div>
            @else
                <h3>{{$orderDetails['message']}}</h3>
            @endif
        </div>
    </div>
    </div>
@endsection
@section('pagejavascripts')

    <script type="text/javascript">

        $(document).ready(function () {

            $(document.body).on('click', '#change_location', function () {
                var obj = $(this);
                var userId = "<?php echo Session::get('fs_user')['id'];?>"
//                $.ajax({
//                    url: '/profile-ajax-handler',
////                   type:
//                });

            });

        });

        //        P=pending [in cart],
        //        TS=tx success [if not cod then show cancel button],
        //        TP=tx in process[if COD show cancel button],
        //        TF=tx failed,
        //        S=shipping [show cancel button],
        //        UC=user cancel request [show dispute button],
        //        UCA=user cancel approved,
        //        MC=merchant cancel,
        //        D=delivered [show dispute button, show refund button],
        //        RR=refund request,
        //        RP=refund in process,
        //        RD=refund done [show dispute button]

    </script>

@endsection