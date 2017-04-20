@extends('Buyer/Layouts/buyerlayout')

@section('title', 'Profile')

@section('pageheadcontent')
<link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="/assets/css/custom/profile.css" rel="stylesheet" type="text/css"/>

{{--<link rel="stylesheet" href="/assets/css/intlTelInput.css">--}}
<link href="/assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>


@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PROFILE SIDEBAR -->
            <div class="profile-sidebar" style="width:250px;">
                <!-- PORTLET MAIN -->
                <div class="portlet light profile-sidebar-portlet">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        {{--                        <img src="{{url('images'.Session::get('fs_supplier')['profilepic'])}}" class="img-responsive">--}}
                        {{--                        <img src="{{Storage::path(Session::get('fs_supplier')['profilepic'])}}" class="img-responsive">--}}
                        <img src="{{Session::get('fs_buyer')['profilepic']}}" class="img-responsive"
                             alt="{{Session::get('fs_buyer')['name'].' '.Session::get('fs_buyer')['last_name']}}">
                        {{--                        <img src="{{url('images/ '.Session::get('fs_supplier')['profilepic'].')}}" class="img-responsive">--}}
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{Session::get('fs_buyer')['name'].' '.Session::get('fs_buyer')['last_name']}}
                        </div>
                    </div>
                </div>
                <!-- END PORTLET MAIN -->
            </div>
            <!-- END BEGIN PROFILE SIDEBAR -->
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1_1" data-toggle="tab">Personal Info</a></li>
                                    <li><a href="#tab_1_2" data-toggle="tab">Change Avatar</a></li>
                                    <li><a href="#tab_1_3" data-toggle="tab">Change Password</a></li>
                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active" id="tab_1_1">
                                        <form method="post"  name="form1" enctype="multipart/form-data" id="profile-info">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input type="text" placeholder="First Name" class="form-control"
                                                           name="first_name" id="first_name"
                                                           @if($uesrDetails->name) value="{{$uesrDetails->name}}" @endif/>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group">
                                                    <label class="control-label">Email Id</label>
                                                    <input type="text" placeholder="Email Id" class="form-control"
                                                           disabled="" name="email_id" id="email_id"
                                                           @if($uesrDetails->email) value="{{$uesrDetails->email}}" @endif/>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group">
                                                    <label class="control-label">Address Line 1</label>
                                                    <input type="text" placeholder="Address Line 1" class="form-control"
                                                           name="address_line_1" id="address_line_1"
                                                           @if($uesrDetails->addressline1) value="{{$uesrDetails->addressline1}}" @endif/>
                                                </div>

                                                <div class="clearfix"></div>
                                                <div class="form-group" id="state">
                                                    <label class="control-label">State</label>
                                                    <input type="text" placeholder="State" class="form-control"
                                                           name="state"
                                                           @if($usernames->state_name) value="{{$usernames->state_name}}" @endif/>
                                                </div>
                                                <div class="form-group hide"  id="state1">
                                                    <label class="control-label">State</label>
                                                    <select name="state" class="form-control m-b-sm"  id="statelist">
                                                        <option value="">Select State</option>
                                                        <?php if(isset($state)){ foreach($state as $value){ ?>
                                                        <?php if($usernames->state==$value->location_id){?>
                                                        <option value="<?php echo $value->location_id; ?>" selected><?php echo $value->name; ?></option>
                                                        <?php }else{ ?>
                                                        <option value="<?php echo $value->location_id; ?>"><?php echo $value->name; ?></option>
                                                        <?php } ?>
                                                        <?php } } ?>
                                                    </select>
                                                    <span class="error">{!! $errors->first('state') !!}</span>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group" id="city">
                                                    <label class="control-label">City</label>
                                                    <input type="text" placeholder="City" class="form-control"
                                                           name="city"
                                                           @if($usernames->city_name) value="{{$usernames->city_name}}" @endif/>
                                                </div>
                                                <div class="form-group hide" id="city1">
                                                    <label class="control-label">City</label>
                                                    <select name="city" class="form-control m-b-sm"  id="citylist">
                                                        <option value="">Select city</option>
                                                        <?php if(isset($city)){ foreach($city as $value){?>


                                                        <?php if($usernames->city==$value->location_id){?>
                                                        <option value="<?php echo $value->location_id; ?>" selected><?php echo $value->name; ?></option>
                                                        <?php }else{ ?>
                                                        <option value="<?php echo $value->location_id; ?>"><?php echo $value->name; ?></option>
                                                        <?php } ?>



                                                        <?php } } ?>
                                                    </select>
                                                    <span class="error">{!! $errors->first('city') !!}</span>
                                                </div>
                                                <div class="clearfix"></div>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input type="text" placeholder="Last Name" class="form-control"
                                                           name="last_name" id="last_name"
                                                           @if($uesrDetails->last_name) value="{{$uesrDetails->last_name}}" @endif/>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group">
                                                    <label class="control-label">Contact Number</label>
                                                    <input type="tel" placeholder="e.g. +1 702 123 4567" class="form-control"
                                                           name="contact_number" id="mobile-number" onclick="phonenumber(document.form1.contact_number)"
                                                           @if($uesrDetails->phone) value="{{$uesrDetails->phone}}" @endif/>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group">
                                                    <label class="control-label">Address Line 2</label>
                                                    <input type="text" placeholder="Address Line 2" class="form-control"
                                                           name="address_line_2" id="address_line_2"
                                                           @if($uesrDetails->addressline2) value="{{$uesrDetails->addressline2}}" @endif/>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="form-group" id="country1">
                                                    <label class="control-label">Country</label>
                                                    <input type="text" placeholder="Country" class="form-control"
                                                           name="country"
                                                           @if($usernames->country_name) value="{{$usernames->country_name}}" @endif/>
                                                </div>
                                                <div class="form-group hide " id="country2">
                                                    <label class="control-label">Country</label>
                                                    <select name="country" class="form-control m-b-sm" id="country">
                                                        <option value="">Select Country</option>
                                                        <?php if(isset($country)){ foreach($country as $value){ ?>
                                                        <?php if($usernames->country==$value->location_id){?>
                                                        <option value="<?php echo $value->location_id; ?>" selected><?php echo $value->name; ?></option>
                                                        <?php }else{ ?>
                                                        <option value="<?php echo $value->location_id; ?>"><?php echo $value->name; ?></option>
                                                        <?php } ?>
                                                        <?php } } ?>
                                                    </select>
                                                    <span class="error">{!! $errors->first('country') !!}</span>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="form-group">
                                                    <label class="control-label">Zip Code</label>
                                                    <input maxlength="6" type="text" placeholder="Zip Code"
                                                           class="form-control" name="zipcode" id="zipcode"
                                                           @if($uesrDetails->zipcode) value="{{$uesrDetails->zipcode}}" @endif/>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12" align="center">
                                                    <div class="margiv-top-10">
                                                        <a class="btn btn-circle green-haze" id="edit-info"
                                                           style="display: none;" >Edit</a>
                                                        <button class="btn btn-circle green-haze hide"
                                                                id="save-info-changes">Save Changes
                                                        </button>
                                                        <a class="btn btn-circle default hide" id="cancel">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END PERSONAL INFO TAB -->
                                    <!-- CHANGE AVATAR TAB -->
                                    <div class="tab-pane" id="tab_1_2">
                                        <form method="post" enctype="multipart/form-data" id="change-avatar-form">
                                            <div class="form-group">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                         style="width: 200px; height: 150px;">
                                                        <img src="{{Session::get('fs_buyer')['profilepic']}}" class="img-responsive"
                                                             alt="{{Session::get('fs_buyer')['name'].' '.Session::get('fs_buyer')['last_name']}}">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                         style="max-width: 200px; max-height: 150px;">
                                                    </div>
                                                    <div>
                                                            <span class="btn btn-circle default btn-file">
                                                                <span class="fileinput-new">
                                                                    Select image </span>
                                                                <span class="fileinput-exists">
                                                                    Change </span>
                                                                <input type="file" name="..." accept="image/*">
                                                            </span>
                                                        <a href="#" class="btn btn-circle default fileinput-exists"
                                                           data-dismiss="fileinput">
                                                            Remove </a>
                                                    </div>
                                                </div>
                                                <div class="clearfix margin-top-10">
                                                    <span class="label label-danger">NOTE! </span>&nbsp;&nbsp;
                                                    <span>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                </div>
                                            </div>
                                            <div class="margin-top-10">
                                                <a class="btn btn-circle green-haze" id="avatar-submit">
                                                    Submit </a>
                                                <a class="btn btn-circle default">
                                                    Cancel </a>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END CHANGE AVATAR TAB -->
                                    <!-- CHANGE PASSWORD TAB -->
                                    <div class="tab-pane" id="tab_1_3">
                                        <form method="POST" enctype="multipart/form-data" id="password-change">
                                            <div class="form-group">
                                                <label class="control-label">Current Password</label>
                                                <input type="password" class="form-control" name="current_password"
                                                       id="current_password"/>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="control-label">New Password</label>
                                                <input type="password" class="form-control" name="new_password"
                                                       id="new_password"/>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="control-label">Confirm Password</label>
                                                <input type="password" class="form-control" name="confirm_password"
                                                       id="confirm_password"/>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="margin-top-10">
                                                <button class="btn btn-circle green"
                                                        id="submit-change-password"> Change Password
                                                </button>
                                                <button type="button" class="btn btn-circle default"
                                                        onclick="document.getElementById('password-change').reset();">
                                                    Reset
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END CHANGE PASSWORD TAB -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection

@section('pagejavascripts')

    <script>
        $(document).ready(function () {

            $("#profile-info").hover(function () {
                $('#edit-info').show(200);
            }, function () {
                $('#edit-info').hide(200);
            });

            $('#edit-info').click(function(e){
                $('#country1').addClass('hide');
                $('#state').addClass('hide');
                $('#city').addClass('hide');
                $('#country2').removeClass('hide');
                $('#state1').removeClass('hide');
                $('#city1').removeClass('hide');

            });

            $("#profile-info :input").attr("disabled", true);
            $("#profile-info :input").attr("style", "cursor: default");
            $("#edit-info").click(function (e) {
                $("#profile-info :input").removeAttr('disabled').attr("style", "cursor: auto");
                $("#email_id, #user_name").attr({disabled: "true", style: "cursor: default"});
                $("#edit-info").addClass("hide");
                $("#save-info-changes, #cancel").removeClass("hide").attr("style", "cursor: hand");
            });

//            $("#mobile-number").intlTelInput();

            $("#cancel").click(function (e) {
                $("#profile-info :input").attr({disabled: "true", style: "cursor: default"});
                $("#edit-info").removeClass("hide").attr("style", "cursor: hand");
                $("#save-info-changes, #cancel").addClass("hide");
            });


            $('#profile-info').validate({
                rules: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    address_line_1: {
                        required: true
                    },
                    mobile_number: {
                        required: true,
                        mobileUK:true,
                        remote: {
                            url: "/buyer/userAjaxHandler",
                            type: 'POST',
                            datatype: 'json',
                            data: {
                                method: 'checkContactNumber'
                            }
                        }
                    },
                    city: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    country:{
                        required:true
                    },
                    zipcode: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter first name"
                    },
                    last_name: {
                        required: "Please enter last name"
                    },
                    address_line_1: {
                        required: "Please enter an address"
                    },
                    city: {
                        required: "Please enter city"
                    },
                    state: {
                        required: "Please enter state"
                    },
                    country:{
                        required: "Please enter country"
                    },
                    zipcode: {
                        required: "Please enter zip code"
                    },
                    mobile_number: {
                        required: "Please enter your contact number",
                        remote: "This Contact Number is already in use."
                    }
                }
            });


            $("#save-info-changes").click(function (e) {
                e.preventDefault();
                if ($('#profile-info').valid()) {
                    var profileData = $('#profile-info').serializeArray();
                    profileData.push({name: 'method', value: 'updateProfileInfo'});
                    $(document).ajaxStart($.blockUI);
                    $.ajax({
                        type: "POST",
                        url: "/buyer/ajaxHandler",
                        dataType: "json",
                        data: profileData,
                        success: function (response) {
                            window.location.reload();
                            $(document).ajaxStop($.unblockUI);
                            var alertMsg = '';
                            if ($.isArray(response['message']) && response['error']) {
                                $.each(response['message'], function (index, value) {
                                    alertMsg += '\u2666\xA0\xA0' + value + '\n';
                                })
//                                toastr["error"](alertMsg);
                                toastr[ response['status'] ](alertMsg );
                            } else {

                                toastr[response['status']](response['message']);
//                                toastr["success"](response['message']);
                            }
                        },
                        error: function (response) {
                            $(document).ajaxStop($.unblockUI);
                        }
                    });
                }
            });

            $.validator.addMethod("password_regex", function (value, element) {
                return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/i.test(value);
            }, "Passwords are 8-16 characters with uppercase letters, lowercase letters,  at least one number and Symbol.");

            $('#password-change').validate({
                rules: {
                    current_password: {
                        required: true
                    },
                    new_password: {
                        required: true,
                        password_regex: true
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#new_password"
                    }
                },
                messages: {
//                    current_password: "Enter Current Password",
//                new_password: " Enter New Password",
                    confirm_password: " Enter Confirm Password Same as Password"
                }
            });


            $("#submit-change-password").click(function (e) {
                e.preventDefault();
                if ($('#password-change').valid()) {
                    var passwordData = $('#password-change').serializeArray();
                    passwordData.push({name: 'method', value: 'updatePassword'});
                    $(document).ajaxStart($.blockUI);
                    $.ajax({
                        type: "POST",
                        url: "/buyer/ajaxHandler",
                        dataType: "json",
                        data: passwordData,
                        success: function (response) {
                            $(document).ajaxStop($.unblockUI);
                            var alertMsg = '';
                            if ($.isArray(response['message']) && response['error']) {
                                $.each(response['message'], function (index, value) {
                                    alertMsg += '\u2666\xA0\xA0' + value + '\n';
                                })
                                toastr[ response['status'] ](alertMsg );
                            } else {
                                toastr[response['status']](response['message']);
                            }
                            $('#password-change').trigger("reset");
                        },
                        error: function (response) {
                            $(document).ajaxStop($.unblockUI);
                            $('#password-change').trigger("reset");
                        }
                    });
                }
            });


            $('#avatar-submit').click(function (e) {
                e.preventDefault();
                var formData = new FormData();
                formData.append('method', 'updateAvatar');

                formData.append('file', $('input[type=file]')[0].files[0]);
                $(document).ajaxStart($.blockUI);
                $.ajax({
                    type: "POST",
                    url: "/buyer/ajaxHandler",
                    contentType: false,
                    dataType: "json",
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function (response) {
                        $(document).ajaxStop($.unblockUI);
                        var alertMsg = '';
                        if ($.isArray(response['message']) && response['error']) {
                            $.each(response['message'], function (index, value) {
                                alertMsg += '\u2666\xA0\xA0' + value + '\n';
                            })
                            toastr[ response['status'] ](alertMsg );
                            location.reload();
                        } else {
//                            toastr(response['message']);
//                            toastr[ response['status'] ](alertMsg );
                            alert(response['message']);
                        }
                    },
                    error: function (response) {
                        $(document).ajaxStop($.unblockUI);
                    }
                });
            });

        });
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


    {{--<script src="/assets/plugins/flot/intlTelInput.js"></script>--}}
    {{--<script src="/assets/plugins/flot/phoneno-international-format.js"></script>--}}


    {{--<script src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>--}}
    {{--<script src="/assets/js/pages/dashboard.js"></script>--}}

    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>



@endsection
