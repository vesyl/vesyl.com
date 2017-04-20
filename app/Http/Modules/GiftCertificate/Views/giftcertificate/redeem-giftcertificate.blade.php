@extends('Home/Layouts/home_layout')
@section('pageheadcontent')
@endsection
@section('content')
        <!--Collection-->
<div class="container">
    <div class="col-md-12 well">
        <h3>Redeem your gift certificate</h3>
        <div class="row">
            <form class="form-inline col-md-8" method="POST" id="redeemform">

                <div class="form-group">
                    <label for="exampleInputEmail2">Enter claim code:</label>
                    <input type="text" class="form-control" id="claimcode" name="redeemcode"
                           placeholder="Enter claim code:">
                    <button type="submit" class="btn btn-warning" name="apply">Apply to the account</button>

                </div>
                <div class="clearfix"></div>
                <div class="col-md-offset-2">
                    <span id="errorspan"></span>
                </div>

            </form>
            <div class="col-md-4" style="margin-top: -30px;">
                <span class="addition"> </span>

                <h3 class="balance"></h3>
                <button class="btn blue-dark" id="currentbalance">Reload Your Balance</button>
            </div>
        </div>
    </div>
</div>
</div>


@endsection
@section('pagejavascripts')

    <script type="text/javascript">
        $(document).ready(function () {

            var validator = $('#redeemform').validate({
                rules: {
                    redeemcode: {
                        required: true,
                        remote: {
                            url: "/giftcertificate-ajax-handler",
                            type: 'POST',
                            datatype: 'json',
                            data: {
                                method: 'checkForGiftCode'
                            }
                        }
                    }
                },
                messages: {
                    redeemcode: {
                        required: "Please enter a gift code",
                        remote: "Gift code not valid for you",
                    }
                },
                errorPlacement: function (error, element) {
                    $("#errorspan").html(error);

                },
                submitHandler: function () {
                    var claimcode = $('#claimcode').val();
                    $.ajax({
                        url: '/giftcertificate-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'checkForAlreadyexistingcode',
                            claimcode: claimcode
                        },
                        success: function (response) {
                            if (response == 0) {
                                // console.log(response);
                                $('#errorspan').show();
                                $('#errorspan').html('Code already used');
                                $('#errorspan').css('color', 'red');
//                            $('.errorspan').delay(4000).hide('slow');
                            } else if (response == 1) {
                                $('#errorspan').show();
                                $('#errorspan').html('Code redeemed');
                                $('#errorspan').css('color', 'green');

                            }
                        }
                    });
                }
            });

            $(document.body).on("click", "#currentbalance", function () {

                $.ajax({
                    url: '/giftcertificate-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'reloadBalance',
                    },
                    success: function (response) {
                        var walletbalance = response;
                        var parwallet = $.parseJSON(response);
                        $('.balance').empty();
                        $('.balance').append('<span class="pull-right">Your Current Balance: $' + parwallet + '</span>');
                    }
                });
            });
        });

    </script>

@endsection