@extends('Home/Layouts/home_layout')
@section('pageheadcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link rel="stylesheet"
            href="/assets/css/owl.carousel.css">
    <style type="text/css">

        #owl-demo .item {
            margin: 3px;
        }

        #owl-demo .item img {
            display: block;
            width: 100%;
            height: 128px;
        }

        .error {
            color: red;
            font-weight: 100;
        }

        .spinner {
            height: 100%;
            width: 100%;
            position: absolute;
            z-index: 10
        }

        .spinner .spinWrap {
            width: 200px;
            height: 100px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -100px;
            margin-top: -50px
        }

        .spinner .loader,
        .spinner .spinnerImage {
            height: 100px;
            width: 100px;
            position: absolute;
            top: 0;
            left: 50%;
            opacity: 1;
            filter: alpha(opacity=100)
        }

        .spinner .spinnerImage {
            margin: 25px 0 0 -30px; /* 28px 0 0 -25px; */
            background: rgba(0, 0, 0, 0) url("https://www.paypalobjects.com/images/checkout/hermes/icon_ot_spin_lock_skinny.png") no-repeat scroll 0 0;
        }

        .spinner .loader {
            margin: 0 0 0 -55px;
            background-color: transparent;
            -webkit-animation: rotation .7s infinite linear;
            -moz-animation: rotation .7s infinite linear;
            -o-animation: rotation .7s infinite linear;
            animation: rotation .7s infinite linear;
            border-left: 5px solid #cbcbca;
            border-right: 5px solid #cbcbca;
            border-bottom: 5px solid #cbcbca;
            border-top: 5px solid #2380be;
            border-radius: 100%
        }

        .spinner .loadingMessage {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            box-sizing: border-box;
            width: 100%;
            margin-top: 125px;
            text-align: center;
            z-index: 100;
            outline: 0
        }

        .spinner .loadingSubHeading {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            box-sizing: border-box;
            width: 150%;
            margin-top: 10px;
            margin-left: -42px;
            text-align: center;
            z-index: 100;
            outline: 0
        }

        @-webkit-keyframes rotation {
            from {
                -webkit-transform: rotate(0)
            }
            to {
                -webkit-transform: rotate(359deg)
            }
        }

        @-moz-keyframes rotation {
            from {
                -moz-transform: rotate(0)
            }
            to {
                -moz-transform: rotate(359deg)
            }
        }

        @-o-keyframes rotation {
            from {
                -o-transform: rotate(0)
            }
            to {
                -o-transform: rotate(359deg)
            }
        }

        @keyframes rotation {
            from {
                transform: rotate(0)
            }
            to {
                transform: rotate(359deg)
            }
        }

        .spinner.preloader {
            background-color: #fff;
            left: 0;
            position: fixed;
            top: 0;
            z-index: 1000;
        }


    </style>

@endsection


@section('content')

    <div class="preloader spinner" id="preloaderSpinner" style="display: none; opacity: 0.7;">
        <div class="spinWrap">
            <p class="spinnerImage"></p>
            <p class="loader"></p>
            <p id="spinnerMessage" class="loadingMessage"><b>Redirecting to Paypal.</b></p>
            <p id="spinnerSubHeading" class="loadingSubHeading"><b>Please wait..</b></p>
        </div>
    </div>

    <!--Collection-->
    <div class="container">
        <div class="row margin-bottom-40">
            <!-- BEGIN CONTENT -->
            <div class="col-md-12 col-sm-12">
                <div class="product-page">
                    <div class="row">
                        <form class="form-horizontal" method="post" id="certificate">
                            <div class="col-md-12 col-sm-12">

                                <div class="product-info">
                                    <h2>Select Theme <span class="pull-right"
                                                style="font-size: 16px; text-transform: none;">Select theme Preview from
                                            bottom</span>
                                    </h2>
                                </div>
                                <div id="owl-demo" class="owl-carousel">

                                    <?php
                                    if (isset($giftcertificate)) {
                                    foreach ($giftcertificate as $key => $value) {
                                    ?>
                                    <div class="radio-inline col-md-12 col-sm-12">
                                        <label>
                                            <input class="radiocase" type="radio" name="optionsRadios"
                                                    value="{{$value['gift_id']}}"
                                                    data-id="{{$value['gift_id']}}" data-img="{{$value['gc_img_src']}}"
                                                    data-amount="{{$value['gift_amount']}}"
                                                    data-name="{{$value['gift_name']}}"
                                                    data-code="{{$value['gift_code']}}" id="optionsRadios"
                                                    style="position: absolute; left: 57%; bottom: 0px; background: rgb(51, 51, 51) none repeat scroll 0% 0%;"
                                                    value="option2">
                                            <div class="item"><img src="{{$value['gc_img_src']}}" alt="Owl Image">
                                                <h3>{{$value['gift_name']}}</h3> <h5
                                                        class="fa fa-rupee">{{$value['gift_amount']}}</h5></div>
                                            {{--<a class="fancybox-button" rel="photos-lib"><img alt="" src="{{$value['gc_img_src']}}" style='height: 175px; width: 200px;'></a>--}}
                                        </label>
                                    </div>

                                    <?php
                                    }
                                    }
                                    ?>


                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 m-top-md">
                                <h1>Enter Gift Certificate Details</h1>

                                <div class="product-page-content"
                                        style="padding: 10px; border: 1px solid rgb(229, 229, 229); background: rgb(229, 229, 229) none repeat scroll 0% 0%;">

                                    <!-- BEGIN FORM-->


                                    <div class="form-body">
                                        {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3 control-label">Amount</label>--}}
                                        {{--<div class="col-md-3">--}}
                                        {{--<select class="form-control" name="amount">--}}
                                        {{--<option>set my own</option>--}}
                                        {{--<option>25.00</option>--}}
                                        {{--<option>50.00</option>--}}
                                        {{--<option>75.00</option>--}}
                                        {{--<option>100.00</option>--}}
                                        {{--<option>150.00</option>--}}
                                        {{--<option>250.00</option>--}}
                                        {{--<option>500.00</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-3">--}}
                                        {{--<input type="number" min="0"  name="ramount" class="form-control" placeholder="amount" value="$">--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3 control-label">Quantity</label>--}}
                                        {{--<div class="col-md-9">--}}
                                        {{--<p class="form-control-static">--}}
                                        {{--1 will be sent to each recipient--}}
                                        {{--</p>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Receiver's E-mail</label>
                                            <div class="col-md-9">
                                                <input type="email" name="email" class="form-control"
                                                        placeholder="Email" id="emailID" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Retype E-mail</label>
                                            <div class="col-md-9">
                                                <input type="email" name="remail" class="form-control"
                                                        placeholder="Retype-Email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Message</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" name="message" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label"></label>
                                            <div class="col-md-9">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="check" value="" checked>
                                                        Personalized Details
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--                            </form>-->
                                    <!-- END FORM-->

                                    <div class="" id="suggestagift">
                                        <div class="padding-top-20">
                                            <h1></h1>
                                            <!--                                    <form class="form-horizontal" role="form">-->

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Receiver's Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="receivername"
                                                                placeholder="Name" id="rname">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Sender's Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="sendername"
                                                                placeholder="Sender's">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Message</label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" name="mess" rows="3"
                                                                id="rmessage"></textarea>
                                                    </div>
                                                </div>


                                            </div>
                                            <!--                                    </form>-->
                                            <button type="submit" id="savegift" class="btn btn-success pull-right">
                                                Send
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                            </div>


                            <hr>
                            <div class="col-md-8 col-sm-8 col-md-offset-2 ">
                                <div class="certify-bg certifys" id="datacert"
                                        style="background-repeat: no-repeat; background-size: 100% 100%; width: 100%; height: 100%"
                                        data-image="">

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <form id="paypalform" name="_xclick" action="https://sandbox.paypal.com/cgi-bin/webscr"
                            method="post" class="hidden">
                    <!--                                                                    <input type="hidden" name="cmd" value="_xclick">
                                                              <input type="hidden" name="upload" value="1">
                                                              <input type="hidden" name="business"  value="ankitsingh@globussoft.com">
                                                              <input type="hidden" name="currency_code" value="USD">
                                                              <input type="hidden" name="item_name"  id="items" value="">
                                                              <input type="hidden" name="amount"  id="prices" value="">-->
                        <input style="display:none;" type="image" class="" src="/images/buynowpaypal.png" border="0"
                                alt="Make payments with PayPal - it's fast, free and secure!">
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('pagejavascripts')
    <script src="/assets/js/owl.carousel.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#owl-demo").owlCarousel({
                navigation: true,
                afterInit: function (elem) {
                    var that = this
                    that.owlControls.prependTo(elem)
                }
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {

            $(document.body).on("click", ".radiocase", function () {
                var obj = $(this);
                var amount = obj.attr('data-amount');
                var name = obj.attr('data-name');
                var code = obj.attr('data-code');
                var img = obj.attr('data-img');

                $(".gift-data").remove();

                $(".form-body").append(
                        '<input class="gift-data" type="hidden" name="amount" value="' + amount + '">' +
                        '<input class="gift-data" type="hidden" name="name" value="' + name + '">' +
                        '<input class="gift-data" type="hidden" name="code" value="' + code + '">' +
                        '<input class="gift-data" type="hidden" name="img" value="' + img + '">'
                );

            });

            $('#certificate').validate({
                rules: {
                    email: {
                        required: true,
                        remote: {
                            url: "/giftcertificate-ajax-handler",
                            type: 'GET',
                            datatype: 'json',
                            data: {
                                method: 'checkForGiftCertificate'
                            }
                        }
                    },
                    remail: {
                        required: true
                    },
                    message: {
                        required: true
                    },
                    receivername: {
                        required: true
                    },
                    sendername: {
                        required: true
                    },
                    mess: {
                        required: true
                    },
                    optionsRadios: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter email.",
                        remote: "Gift certificate not valid for this email as this user is not a member to our site"
                    },
                    remail: {
                        required: "Please confirm email."
                    },
                    message: {
                        required: "Please enter Message."
                    },
                    receivername: {
                        required: "please enter Receiver Name."
                    },
                    sendername: {
                        required: "please enter Sender Name."
                    },
                    mess: {
                        required: "please enter Message."
                    },
                    optionsRadios: {
                        required: "please select certificate."
                    }
                },
                submitHandler: function () {
                    $('#preloaderSpinner').css('display', 'block');
                    var formData = new FormData($('#certificate')[0]);
                    formData.append("method", 'giftCertificateUserInfo');
                    var siteUrl = "<?php echo env("WEB_URL")?>";
                    var sitePaypal = siteUrl + '/payment-response';
                    var toAppend = '';
                    $.ajax({
                        url: '/giftcertificate-ajax-handler',
                        type: 'POST',
                        dataType: 'json',
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function (response) {
                            console.log(response);
                            if (response['code'] == 200) {
//                            var receiverName = response['name'];
                                var receiverId = response['gift_for'];
                                var senderId = response['gift_by'];
                                var amount = response['gift_amount'];
                                var invoice = response['gift_id'];
                                var giftcode = response['gift_code'];
                                var giftcertiId = response['gift_id'];
//                            var receiverEmail = response['receiver_email'];
                                {{--toAppend += '<input type="hidden" name="cmd" value="_xclick">';--}}
                                        {{--toAppend += '<input type="hidden" name="upload" value="1">';--}}
                                        {{--toAppend += '<input type="hidden" name="business" value="vinidubey@globussoft.com">';--}}
                                        {{--toAppend += '<input type="hidden" name="amount" value="' + amount + '">';--}}
                                        {{--toAppend += '<input type="hidden" name="item_name" value="' + giftcode + '">';--}}
                                        {{--toAppend += '<input type="hidden" name="item_number" value="' + giftcertiId + '">';--}}
                                        {{--toAppend += '<input type="hidden" name="invoice" value="' + invoice + '">';--}}
                                        {{--toAppend += '<input type="hidden" name="return" value="'+sitePaypal+'">';--}}
                                        {{--toAppend += '<input type="hidden" name="notify_url" value="http://localhost.wishclone.com/paypal-ipnlistener">';--}}

                                        {{--$('#paypalform').html(toAppend);--}}
                                        {{--$('#paypalform').submit();--}}
                                        window.location = response['data']['link'];
                            } else {

                            }
                        }
                    });
                }
            });
            $(document.body).on('input', '#rname', function () {
                var inputname = $(this).val();
                $('#displayname').html(inputname);

            });
            $(document.body).on('input', '#rmessage', function () {
                var inputmessage = $(this).val();
                $('#displaymessage').html(inputmessage);

            });
        });

    </script>

@endsection

