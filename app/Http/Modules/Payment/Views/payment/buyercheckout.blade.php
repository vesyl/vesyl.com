@extends('Buyer/Layouts/buyerlayout')
@section('pageheadcontent')
    <link href="/assets/buyer/css/style.css" rel="stylesheet" type="text/css"/>

    <style>
        .modal-content.checkout {
            background-color: #fff;
            color: white;
            outline: 0 none;
            position: relative;
            border: 0px solid white;
        }

        .flash_pan h3 {
            margin-top: 0px;
        }

        .flash_pan {
            padding: 10px !important;
        }
    </style>
@endsection

@section('title', 'Checkout')

@section('content')
    <div class="panel info-box panel-white">
        <div class="panel-body">
            <div class="alert
                    @if(session('code'))
            @if(session('code') == 400) alert-danger
            @elseif(session('code') == 200) alert-success
            @else display-hide @endif
            @else display-hide @endif">
                <button class="close" data-close="alert"></button>
                <span>
                    @if(session('code') == 400 || session('code') == 200)
                        {{session('message')}}
                    @endif
                </span>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body text-center flash_pan">
                            <h3 style="color:#EF1053;">Enjoy Flat-Rate Global Shipping</h3>


                            <p><i><b>$9.95</b> for all orders over <b>$100</b> and <b>$19.95</b> for those under
                                    <b>$100</b></i>
                            </p>
                            <a href="#termsNconds" class="text-center" style="font-size:10px; color:#8E7863;">T&amp;C's apply</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <p style="color:#585858;"><i>There will never be any additional charges due open delivery</i></p>
                </div>
                <div class="col-md-4">
                    <p class="text-center">International Questions?&nbsp;<a href="" style="color:#EF1053;">Contact
                            Us</a>
                    </p>
                </div>

            </div>
            <br/><br/>
            <div class="row">
                <form method="post">
                    <div class="col-md-3">
                        <div class="panel panel-default f_pan1" style="padding: 15px; background-color: rgb(223, 223, 223);">
                            <h3><i><b>Ship to</b></i></h3>
                            <h5 style="line-height:23px;">
                                <span id="s_addr_span">
                                    {{$paymentDetails[0]['addressline1']}}<br/>
                                    {{$paymentDetails[0]['addressline2']}}({{$paymentDetails[0]['ln_country']}})<br/>
                                    {{$paymentDetails[0]['ls_state']}}( {{$paymentDetails[0]['lc_city']}}
                                    )</br>{{$paymentDetails[0]['zipcode']}}
                                    <p>{{$paymentDetails[0]['phone']}}<br/>
                                </span>
                            </h5>
                            {{--<a href="javascript:;" data-target="#modalShippingChange" style="margin-left: 78%;color:#ef1053;"--}}
                            {{--id="change_location" data-toggle="modal" data-target=".bd-example-modal-lg">Change</a>--}}
                            <a href="#modal_change_addr_s" data-target="#modal_change_addr_s" data-toggle="modal" style="margin-left: 78%;color:#ef1053;">Change</a>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default f_pan2" style="padding: 15px; background-color: rgb(223, 223, 223);">
                            <h3><i><b>Billing address</b></i></h3>
                            <!--<input type="checkbox"/>Same as shipping address-->
                            <h5 style="line-height:23px;">
                                <span id="b_addr_span">
                                    {{$paymentDetails[0]['addressline1']}}<br/>
                                    {{$paymentDetails[0]['addressline2']}}({{$paymentDetails[0]['ln_country']}})<br/>
                                    {{$paymentDetails[0]['ls_state']}}( {{$paymentDetails[0]['lc_city']}}
                                    )</br>{{$paymentDetails[0]['zipcode']}}
                                    <p>{{$paymentDetails[0]['phone']}}<br/>
                                </span>
                            </h5>
                            <a href="#modal_change_addr_b" data-target="#modal_change_addr_b" data-toggle="modal" style="margin-left: 78%;color:#ef1053;">Change</a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="panel panel-default f_pan3" style="padding: 15px; background-color: rgb(223, 223, 223);">
                            <h3><i><b>Payment</b></i></h3>
                            <label for="paymentMethod">How would you like to pay?</label>
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="checkbox" name="paymentMethod[]" value="paypal" class="p_method_cb"/>
                                    <img src="//www.paypalobjects.com/en_US/i/btn/btn_paynow_LG.gif"/> </label>
                            </div>
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="checkbox" name="paymentMethod[]" value="wallet" class="p_method_cb"/>
                                    My
                                    Wallet </label>
                            </div>
                            <div class="form-group"><label class="radio-inline">
                                    <input type="checkbox" name="paymentMethod[]" value="rp" class="p_method_cb"/>
                                    Reward
                                    points </label>
                            </div>

                            <div class="">
                                <div id="paypal_method_div" class="p_method_res_div hidden">

                                </div>

                                <div id="rp_method_div" class="p_method_res_div hidden">
                                    <hr style="border-color: #909090;">
                                    <div class="form-group">
                                        <label class="radio-inline"><input type="checkbox" name="useallrp" class="input-inline input-medium pmethodcb"/>
                                            Use all reward points</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="rpvalue" min="1" class="form-control input-inline input-medium pmethodinput" placeholder="Enter reward points to be used"/>
                                    </div>
                                </div>

                                <div id="wallet_method_div" class="p_method_res_div hidden">
                                    <hr style="border-color: #909090;">
                                    <div class="form-group">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="useallwallet" class="input-inline input-medium pmethodcb"/>
                                            Use all wallet money</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="walletvalue" min="1" class="form-control input-inline input-medium pmethodinput" placeholder="Enter wallet amount to be used"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">

                        {{--<form class="payment btn btn-success" method="POST" id="pay-payment" action="/paypal-payment">--}}
                        {{--<input type="hidden" id="hidden-product-id" name="productId" value="{{$productdetails[0]['product_id']}}">--}}
                        {{--<input type="hidden" id="hidden-option-id" name="variantId" value="">--}}
                        {{--<input type="hidden" id="hidden-qunatity-id" name="quantityId" value="">--}}
                        {{--<a href="javascript:;" data-paypal-button="true">--}}
                        {{--<img src="//www.paypalobjects.com/en_US/i/btn/btn_paynow_LG.gif" alt="Pay Now" id="buy-now"/>--}}
                        {{--</a>--}}
                        {{--</form>--}}
                        <br/>
                        <p style="margin-top:15px;">Order subtotal includes applicable Duties and VAT.</p>
                        <p style="margin-left: 12%;">
                            Subtotal <i class="fa fa-question-circle fa fa-rupee" aria-hidden="true"></i>
                            {{$paymentDetails[0]['subtotal']}}
                        </p>
                        <p style="margin-left: 12%;">
                            Shipping <i class="fa fa-question-circle fa fa-rupee" aria-hidden="true"></i>
                            {{$paymentDetails[0]['totalShippingPrice']}}
                        </p>
                        <hr>
                        <p style="margin-left: 12%;"><b>Total:</b>
                        </p>
                        <b class="fa fa-rupee"> {{$paymentDetails[0]['finalCheckoutPrice']}}</b></span><br/><br/><br/>
                        {{--<a href="" style="color:#ef1053;" class="text-center">Have a Promo Code? Apply it now</a>TODO IF COUPONS IS USED--}}
                        <button type="submit" class="btn btn-success">Confirm Order</button>

                    </div>

                    <div id="modal_change_addr_s" class="modal fade bd-example-modal-lg" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content checkout">

                                <div class="modal-header">
                                    <button class="close cls_btn" data-dismiss="modal" type="button">×</button>
                                    <h4 style="color:#ef1053; float:left;"><b>Change shipping address for this order</b>
                                    </h4>
                                </div>
                                <div class="modal-body" style="padding:10px 30px 30px;">
                                    <div class="row" style="text-align:center;">

                                        <div class="col-md-4 cntr">
                                            <div style="padding:15px 10px;" class="boxshadow col-md-12">
                                                {{--<button class="btn btn-chng2 pull-right clsdiv_btn" type="button">X</button>--}}
                                                <div class="form-group wrapper">
                                                    <input id="s_addressline1" class="form-control mrgn_btm" type="text" required placeholder="Address line 1" name="s_addressline1" value="{{$paymentDetails[0]['addressline1']}}">
                                                    <input id="s_addressline2" class="form-control mrgn_btm" type="text" placeholder="Address line 2" name="s_addressline2" value="{{$paymentDetails[0]['addressline2']}}">
                                                    <input id="s_city" class="form-control mrgn_btm" type="city" required placeholder="City" name="s_city" value="{{$paymentDetails[0]['lc_city']}}">
                                                    <input id="s_state" class="form-control mrgn_btm" type="state" placeholder="State" name="s_state" value="{{$paymentDetails[0]['ls_state']}}">
                                                    <input id="s_country" class="form-control mrgn_btm" type="country" required placeholder="Country" name="s_country" value="{{$paymentDetails[0]['ln_country']}}">
                                                    <input id="s_phone" class="form-control mrgn_btm" type="phone" required placeholder="Phone Number" name="s_phone" value="{{$paymentDetails[0]['phone']}}">
                                                    <input id="s_zip" class="form-control mrgn_btm" type="zip" required placeholder="Zip Code" name="s_zip" value="{{$paymentDetails[0]['zipcode']}}">
                                                </div>

                                                <div class="text-center">
                                                    <button class="btn btn-chng1 change_addr" type="button" data-addrtype="s">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="modal_change_addr_b" class="modal fade bd-example-modal-lg" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content checkout">

                                <div class="modal-header">
                                    <button class="close cls_btn" data-dismiss="modal" type="button">×</button>
                                    <h4 style="color:#ef1053; float:left;"><b>Change billing address for this order</b>
                                    </h4>
                                </div>
                                <div class="modal-body" style="padding:10px 30px 30px;">
                                    <div class="row" style="text-align:center;">

                                        <div class="col-md-4 cntr">
                                            <div style="padding:15px 10px;" class="boxshadow col-md-12">
                                                <div class="form-group wrapper">
                                                    <input id="b_addressline1" class="form-control mrgn_btm" type="text" required placeholder="Address line 1" name="b_addressline1" value="{{$paymentDetails[0]['addressline1']}}">
                                                    <input id="b_addressline2" class="form-control mrgn_btm" type="text" placeholder="Address line 2" name="b_addressline2" value="{{$paymentDetails[0]['addressline2']}}">
                                                    <input id="b_city" class="form-control mrgn_btm" type="city" required placeholder="City" name="b_city" value="{{$paymentDetails[0]['lc_city']}}">
                                                    <input id="b_state" class="form-control mrgn_btm" type="state" placeholder="State" name="b_state" value="{{$paymentDetails[0]['ls_state']}}">
                                                    <input id="b_country" class="form-control mrgn_btm" type="country" required placeholder="Country" name="b_country" value="{{$paymentDetails[0]['ln_country']}}">
                                                    <input id="b_phone" class="form-control mrgn_btm" type="phone" required placeholder="Phone Number" name="b_phone" value="{{$paymentDetails[0]['phone']}}">
                                                    <input id="b_zip" class="form-control mrgn_btm" type="zip" required placeholder="Zip Code" name="b_zip" value="{{$paymentDetails[0]['zipcode']}}">
                                                </div>

                                                <div class="text-center">
                                                    <button class="btn btn-chng1 change_addr" type="button" data-addrtype="b">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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

            $(document.body).on('click', '.change_addr', function () {
                var addr_type = $(this).data('addrtype');
                $('#' + addr_type + '_addr_span').html($('#' + addr_type + "_addressline1").val() + "</br>" + $('#' + addr_type + "_addressline2").val() + " ( " + $('#' + addr_type + "_country").val() + " ) </br>" + $('#' + addr_type + "_state").val() + " ( " + $('#' + addr_type + "_city").val() + " ) </br>" + $('#' + addr_type + "_zip").val() + "</br><p>" + $('#' + addr_type + "_phone").val() + "</p>");
                $('#modal_change_addr_' + addr_type + ' .cls_btn').click().click();
            });

            $(document.body).on('click', '.p_method_cb', function () {
                var pMethod = $(this).val();
                $('#' + pMethod + '_method_div').toggleClass('hidden');
            });

            $(document.body).on('change', '.pmethodcb', function () {
                $(this).parent().parent().parent().find('div > .pmethodinput').prop('disabled', false);
                if ($(this).prop("checked") == true) {
                    $(this).parent().parent().parent().find('div > .pmethodinput').prop('disabled', true);
                }
            });

        });

    </script>

@endsection