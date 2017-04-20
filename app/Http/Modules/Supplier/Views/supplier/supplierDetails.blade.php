{{--{{dd($countries['name']->name)}}--}}
        <!DOCTYPE html>

<head>
    @include('Supplier/Layouts/supplierheadscripts')
</head>

    {{--<title>FlashSale | Supplier Details</title>--}}
    {{--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>--}}
    {{--<link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="/assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="/assets/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="/assets/css/custom/profile.css" rel="stylesheet" type="text/css"/>--}}
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    {{--<link href="/assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>--}}
    <style>
        label.error {
            font-weight: normal;
            color: #FF0000 !important;
        }

        span.error {
            font-weight: normal;
            color: #FF0000 !important;
        }
    </style>
</head>

<body>

<body class="page-login">

<main class="page-content">
    <div class="page-inner bg-color">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-3 center">

                   <div style="color:white;font-size: 25px;text-align:center;" >Supplier Details</div>
        @if(session('error'))
            <span class="error"> {{session('error')}}</span>
        @endif
        <form class="m-t-md" method="post" action="">
            <span class="error">{!! $errors->first('registerErrMsg') !!}</span>

            <div class="form-group">
                <input name="addressline1" type="text" class="form-control" placeholder="Address Line 1"
                       value="{{ old('addressline1') }}">
                <span class="error">{!! $errors->first('addressline1') !!}</span>
            </div>
            <div class="form-group">
                <input name="addressline2" type="text" class="form-control" placeholder="Address Line 2"
                       value="{{ old('addressline2') }}">
                <span class="error">{!! $errors->first('addressline2') !!}</span>
            </div>

            <div class="form-group">
                <select name="country" class="form-control m-b-sm" id="country">
                    <option value="">Select Country</option>
                    <?php if(isset($countries)){ foreach($countries as $value){ ?>
                    <option value="<?php echo $value->location_id; ?>"><?php echo $value->name; ?></option>
                    <?php } } ?>
                </select>
                <span class="error">{!! $errors->first('country') !!}</span>
            </div>
            <div class="clearfix"></div>
                <div class="form-group hidden" id="state">
                        <label class="control-label"></label>
                        <select name="state" class="form-control m-b-sm " id="statelist">
                        </select>
                        <span class="error">{!! $errors->first('state') !!}</span>
                </div>
                    <div class="clearfix"></div>
                <div class="form-group hidden" id="city">
                    <label class="control-label"></label>
                    <select name="city" class="form-control m-b-sm " id="citylist">
                    </select>
                    <span class="error">{!! $errors->first('city') !!}</span>
            </div>

            <div class="form-group">
                <input name="zipcode" type="text" class="form-control" placeholder="Zipcode"
                       value="{{ old('zipcode') }}">
                <span class="error">{!! $errors->first('zipcode') !!}</span>
            </div>

            <div class="form-group">
                <input name="phone" type="text" class="form-control" placeholder="Phone (Ex.:+1234XXXXXX)"
                       value="{{ old('phone') }}">
                <span class="error">{!! $errors->first('phone') !!}</span>
            </div>
            <button type="submit" class="btn btn-success btn-block m-t-xs">Submit</button><br>
            <span style="color: green;"> @if(isset($success_msg)) {{ $success_msg }} @endif</span>
        </form>
    </div>
</div>
</div>
                </div>
            </main>

@include('Supplier/Layouts/suppliercommonfooterscripts')
{{--<script src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>--}}

    <script>
        $(document).ready(function () {
            $('#country').change(function (e) {
                var country_id = $('#country').val();
                $.ajax({
                    type: "POST",
                    url: "/supplier/userAjaxHandler",
                    dataType: "json",
                    data: {
                        method: 'getStates',
                        countryId: country_id
                    },
                    success: function (response) {
                        if (response) {
                            $('#state').removeClass('hidden');
                            var state = response;
                            var toappend = '<option value="">Select State</option>';
                            if (response != '') {
                                $.each(state, function (key, value) {
                                    toappend += ' <option value="' + value["location_id"] + '">' + value["name"] + '</option>';
                                });
                            }
                            $('#statelist').html(toappend);
                        }

                    },
                    error: function (response) {
                    }
                });
            });

            $('#statelist').change(function (e) {
                var state_id = $('#statelist').val();
                $.ajax({
                    type: "POST",
                    url: "/supplier/userAjaxHandler",
                    dataType: "json",
                    data: {
                        method: 'getCitys',
                        stateId: state_id
                    },
                    success: function (response) {
                        if (response) {
                            $('#city').removeClass('hidden');
                            var state = response;
                            var toappend = '<option value="">Select City</option>';
                            if (response != '') {
                                $.each(state, function (key, value) {
                                    toappend += ' <option value="' + value["location_id"] + '">' + value["name"] + '</option>';
                                });
                            }
                            $('#citylist').html(toappend);
                        }

                    },
                    error: function (response) {
                    }
                });
            });




        });

    </script>

    <script src="/assets/plugins/3d-bold-navigation/js/main.js"></script>
    <script src="/assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="/assets/plugins/jquery-counterup/jquery.counterup.min.js"></script>
    <script src="/assets/plugins/toastr/toastr.min.js"></script>

    <script src="/assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="/assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/plugins/curvedlines/curvedLines.js"></script>
    <script src="/assets/plugins/metrojs/MetroJs.min.js"></script>


    {{--<script src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>--}}
    {{--<script src="/assets/js/pages/dashboard.js"></script>--}}

    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

