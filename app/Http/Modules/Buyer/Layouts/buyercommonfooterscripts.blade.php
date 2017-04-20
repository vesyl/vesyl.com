<!-- modals start-->
<div class="modal fade cart-bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog">

        <div class="modal-content _check_pop">
            <div class="modal-header">
                <button type="button" class="close fa fa-2x fa-close" data-dismiss="modal" aria-label="Close">
                </button>
                <h4 class="modal-title" id="gridSystemModalLabel">My Cart</h4>
            </div>
            <div class="modal-body">
                {{--<div class="container _ccp">--}}
                <div class="row pop_2nd_row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="col-md-8 vat_included">
                            Sub Total: <p class="sub_total fa fa-rupee"><span id="sub_total"></span></p>
                            <!--todo get currency unit here-->
                            {{--<p>VAT included</p>--}}
                        </div>
                        <div class="col-md-4 pop_button">
                            <a href="javascript:;" class="btn btn-info" role="button" id="checkout">CHECK OUT NOW</a>
                        </div>
                    </div>
                </div>
                {{--</div>--}}
                <div class="clearfix"></div>
                <hr>
                <div class="row cartcontainer">
                    {{--<div class="row">--}}
                    {{--<div class="col-md-12 pop_image">--}}
                    {{--<div class="col-md-4">--}}
                    {{--<img src="" class="img-responsive pop-img" style="height: 100px; width:100px;">--}}

                    {{--<div class="pop_text">--}}
                    {{--<p class="prod_name">YX White T-SHIRT </p>--}}

                    {{--<p class="prod_price">£ 89.90--}}
                    {{--<del> £ 100.90</del>--}}
                    {{--</p>--}}
                    {{--<b class="offer_percent">15% off</b>--}}

                    {{--<p class="pop_estimate">ESTIMATED DELIVERY:--}}
                    {{--<span>SUN 03/20/2016- WED 04/06/2016</span>--}}
                    {{--</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<!--<div class="col-md-2 3rd_rowtext">-->--}}
                    {{--<!--<span class="pop_text">YX White T-SHIRT<br> £ 89.90 <del> £ 100.90</del> <br> <i class="offer_percent">15% off</i></span><br>-->--}}
                    {{--<!--<span class="pop_estimate">ESTIMATED DELIVERY<br>-->--}}
                    {{--<!--<span class="SUN">SUN 03/20/2016-</span>-->--}}
                    {{--<!--<span class="real-price">04/06/2016</span>-->--}}
                    {{--<!--</span>-->--}}
                    {{--<!--</div>-->--}}
                    {{--<div class="col-md-1 select_quantity">--}}
                    {{--<a class="btn btn-default btn-select btn-select-light _cop3">--}}
                    {{--<input type="hidden" class="btn-select-input" id="" name="" value=""/>--}}
                    {{--<span class="btn-select-value cop">1</span>--}}
                    {{--<span><img class="arrow1 _cop1" src="/assets/images/arrow.png"></span>--}}
                    {{--<ul>--}}
                    {{--<li>1</li>--}}
                    {{--<li>2</li>--}}
                    {{--<li>3</li>--}}
                    {{--<li>4</li>--}}
                    {{--<li>5</li>--}}
                    {{--</ul>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2 pop_rate">--}}
                    {{--<p>&pound89.99</p>--}}
                    {{--<a href="#">--}}
                    {{--<p>Remove</p>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
        {{--==================================================================--}}

    </div>
</div>


<!--modals end-->

<!-- Javascripts -->
<script src="/assets/plugins/jquery/jquery-2.1.3.min.js"></script>
<script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/assets/plugins/pace-master/pace.min.js"></script>
<script src="/assets/plugins/jquery-blockui/jquery.blockui.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/assets/plugins/switchery/switchery.min.js"></script>
<script src="/assets/plugins/uniform/jquery.uniform.min.js"></script>
<script src="/assets/plugins/offcanvasmenueffects/js/classie.js"></script>
{{--<script src="/assets/plugins/offcanvasmenueffects/js/main.js"></script>--}}
<script src="/assets/plugins/waves/waves.min.js"></script>
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
{{--FOR UI-NOTIFICATIONS--}}
<script src="/assets/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/pages/notifications.js"></script>
<script src="/assets/js/custom.js"></script>

<script>
    $(document).ready(function () {
        //FOR UI-NOTIFICATIONS
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "slideDown",
            "hideMethod": "slideUp"
        };

        @if(session('msg')!='')
                toastr["{{session('status')}}"]("{{session('msg')}}");
        @endif

        $("#input-item-count").bind("change mouseleave", function () {
            var str_test = $("#input-item-count").val();
            if (/\D/.test(str_test)) {
                $("#input-item-count").val('1');
            }
        });

        $(document.body).on('click', ".increase-quan", function () {
            var tar_ele = $(this).siblings("input:text");
            if (isNaN(parseInt(tar_ele.val()))) {
                tar_ele.val('1');
            }
            value = parseInt(tar_ele.val()) + 1;
            tar_ele.val(value);
        });

        $(document.body).on('click', ".decrease-quan", function () {
            var tar_ele = $(this).siblings("input:text");
            if (isNaN(parseInt(tar_ele.val()))) {
                tar_ele.val('1');
            }
            value = parseInt(tar_ele.val()) - 1;
            tar_ele.val(value);
        });

        $(document.body).on('click', '.increase-quan,.decrease-quan', function () {
            var obj = $(this);//todo on change update in cart and in UI
            var inputObj = obj.siblings('#input-item-count');
            var qty = inputObj.val();
            if (qty < 1) {
                inputObj.val(1);
            }
            $("#hidden-qunatity-id").val(qty);
        });

        $(document.body).on('click', '#cart', function () {
            var cartDetails = '';
            var usersession = "{{Session::get('fs_buyer')['id']}}";
            if (typeof usersession != "undefined" && usersession) {
                $.ajax({
                    url: '/order-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'cartDetails'
                    },
                    success: function (response) {
                        mainresponse = $.parseJSON(response);
                        console.log(mainresponse);
                        $('.cartcontainer').empty();
                        var toAppend = '';
                        var res = [];
                        $.each(mainresponse, function (index, val) {
                            if (index == 0 && typeof val['subtotal'] != "undefined") {
                                $('#sub_total').html(val['subtotal']);
                            }
                            toAppend += '<div class="col-md-12 pop_image">';
                            toAppend += '<div class="col-md-7">';
                            toAppend += '<div class="col-md-5">';
                            toAppend += '<img src="' + val['image_url'] + '" class="img-responsive pop-img" style="height: 100px; width:100px;">';
                            toAppend += '</div>';
                            toAppend += '<div class="col-md-7 pop_text">';
                            toAppend += '<p class="prod_name">' + val['product_name'] + '</p>';
                            toAppend += ' <p class="prod_price">' + val['price_total'] + ' <del style="padding-left: 10px"> ' + Math.round((val['finalPrice'] / 6) * 100) / 100 + '</del></p>';//todo get currency unit
//                            toAppend += '<b class="offer_percent">15% off</b>';//todo show discount value here
//                            toAppend += '<p class="pop_estimate">ESTIMATED DELIVERY:<span>SUN 03/20/2016- WED 04/06/2016</span></p>';//todo show shipping estimation here
                            toAppend += '</div>';
                            toAppend += '</div>';
                            toAppend += '<div class="col-md-3 form-group width-100">';
//                            toAppend += '<label class="control-label decrease-quan" for="">';
//                            toAppend += ' <i class="fa fa-minus"></i>';
//                            toAppend += '</label>';
                            toAppend += '<input id="input-item-count" class="form-control quantity" type="text" style="padding:0; text-align:center; width:50px;margin-left: 6px;"  name="input-item-count" value="' + val['quantity'] + '">';
//                            toAppend += '<input type="hidden" class="btn-select-input" id="" name="" value=""/>';
//                            toAppend += '<label class="control-label increase-quan" for=""><i class="fa fa-plus"></i></label>';
                            toAppend += '</div>';
                            toAppend += '<div class="col-md-2">';// pop_rate class
                            toAppend += '<p class="fa fa-inr">' + val['finalPrice'] + '</p>';
                            toAppend += '<a href="javascript:;" class="remove-from-cart" data-id="' + val['order_id'] + '"><p>Remove</p></a>';
                            toAppend += '</div>';
                            toAppend += '</div>';
                            toAppend += '<div class="clearfix"></div>';
                        });
                        $('.cartcontainer').append(toAppend);
                    }
                });
                /*} else {
                 cartDetails = $.parseJSON(getCookie('cart_cookie_name'));
                 if (cartDetails.length != 0) {
                 $('.cartcontainer').empty();
                 var toAppend = '';
                 var res = [];
                 var sub_total = 0;
                 $.each(cartDetails, function (index, val) {
                 sub_total = Number(sub_total) + Number(val['cmbPrice']);
                 if (index == (cartDetails.length - 1) && sub_total != 0) {
                 $('#sub_total').html(sub_total);
                 }
                 toAppend += '<div class="row">';
                 toAppend += '<div class="col-md-12 pop_image">';
                 toAppend += '<div class="col-md-4">';
                 toAppend += '<img src="' + val['cmbImage'] + '" class="img-responsive pop-img" style="height: 100px; width:100px;">';
                 toAppend += '<div class="pop_text">';
                 toAppend += '<p class="prod_name">' + val['cmbPrice'] + '</p>';
                 toAppend += '<p class="prod_price">' + val['cmbPrice'] + ' <del> £ 100.90</del></p>';
                 toAppend += '<b class="offer_percent">15% off</b>';
                 toAppend += '<p class="pop_estimate">ESTIMATED DELIVERY:<span>SUN 03/20/2016- WED 04/06/2016</span></p>';
                 toAppend += '</div>';
                 toAppend += '</div>';
                 toAppend += '<div class="col-md-1 select_quantity">';
                 toAppend += '<a class="btn btn-default btn-select btn-select-light _cop3">';
                 toAppend += '<input type="hidden" class="btn-select-input" id="" name="" value=""/>';
                 toAppend += '<span class="btn-select-value cop">' + val['quantity'] + '</span>';
                 toAppend += '<span><img class="arrow1 _cop1" src="/assets/images/arrow.png"></span>';
                 toAppend += '<ul><li>' + val['quantity'] + '</li></ul>';
                 toAppend += '</a>';
                 toAppend += '</div>';
                 toAppend += '<div class="col-md-2 pop_rate">';
                 toAppend += '<p class="fa fa-inr">' + val['cmbPrice'] + '</p>';
                 toAppend += '<a href="javascript:;" class="remove-from-cart" data-id="' + val['cmbPrice'] + '"><p>Remove</p></a>';
                 toAppend += '</div>';
                 toAppend += '</div>';
                 toAppend += '</div>';
                 });
                 $('.cartcontainer').append(toAppend);
                 } */
            }

        });


        $(document.body).on('click', '.remove-from-cart', function () {
            var obj = $(this);
            var orderId = obj.attr('data-id');
            if (confirm("Do you want to Remove This Product From Your Cart!") == true) {
                $.ajax({
                    url: '/order-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'removerCartOrder',
                        orderId: orderId,
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['status']](response['msg']);
                        var effect = 'slide';
                        // Set the options for the effect type chosen
                        var options = {direction: 'right'};
                        // Set the duration (default: 400 milliseconds)
                        var duration = 500;
                        obj.parents('.col-md-12').toggle(effect, options, duration);
                        //todo change cart total value here
                    }
                });
            }


        });

    });

    $(document.body).on('click', '#checkout', function () {
        var usersession = "{{Session::get('fs_buyer')['id']}}"
        var siteUrl = "{{env("WEB_URL")}}";
        if (typeof usersession != "undefined" && usersession) {
            window.location = 'http://' + siteUrl + '/buyer/checkout';
        } else {
            toastr["error"]("You need to login for continue");
        }
    });


</script>