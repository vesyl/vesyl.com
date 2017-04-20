@extends('Admin/Layouts/adminlayout')

{{--@section('title', trans('message.add_new_supplier')) --}}{{--TITLE GOES HERE--}}

@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    @if(Session::has('msg'))
                        {{Session::get('msg')}}
                    @endif
                    <div class="portlet-body form">
                        <form class="customer" method="post" enctype="multipart/form-data"  action="/admin/updatepassword"  id="password-change">
                            <div class="form-group">
                                <label for="currentpassword">Current Password</label>
                                <input type="password" class="form-control" id="password" name="oldpassword">
                                {!! $errors->first('currentpassword' ,'<font color="red">:message</font>') !!}
                            </div>
                            <div class="form-group">
                                <label for="newpassword">New Password</label>
                                <input type="password" class="form-control" id="newpassword" name="newpassword">
                                {!! $errors->first('newpassword' ,'<font color="red">:message</font>') !!}
                            </div>
                            <div class="form-group">
                                <label for="confirmpassword">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword">
                                {!! $errors->first('confirmpassword' ,'<font color="red">:message</font>') !!}
                            </div>

                            {{--<input type="submit" value="Save" class="btn btn-info text-uppercase">--}}
                            <button
                                    class="btn btn-info text-uppercase" id="save"> Save
                            </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="/admin/dashboard">Back</a>

                            {{--<div id="pw-suc-err"></div>--}}
                        </form>
                        {{--</div>--}}

                        {{--<div class="col-lg-6">--}}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('pagejavascripts')
<script>
    $.validator.addMethod("password_regex", function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/i.test(value);
    }, "Passwords are 8-16 characters with uppercase letters, lowercase letters,  at least one number and Symbol.");

    $('#password-change').validate({
        rules: {
            oldpassword: {
                required: true
            },
            newpassword: {
                required: true,
                password_regex: true
            },
            confirmpassword: {
                required: true,
                equalTo: "#newpassword"
            }
        },
        messages: {
//                    current_password: "Enter Current Password",
//                new_password: " Enter New Password",
            confirmpassword: " Enter Confirm Password Same as Password"
        }
    });

    $("#save").click(function (e) {
        var delay;
        e.preventDefault();
        if ($('#password-change').valid()) {
            var passwordData = $('#password-change').serializeArray();
            passwordData.push({name: 'method', value: 'updatePassword'});
            $(document).ajaxStart($.blockUI);
            $.ajax({
                type: "POST",
                url: "/admin/updatepassword",
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
                        toastr.options.timeOut = 5000;
//                                location.reload();
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

    </script>




{{--@section('pagejavascripts')--}}

    {{--<script src="/assets/global/scripts/metronic.js" type="text/javascript"></script>--}}
    {{--<script src="/assets/global/scripts/layout.js" type="text/javascript"></script>--}}
    {{--<script src="/assets/global/plugins/icheck/icheck.min.js"></script>--}}
    {{--<script src="/assets/admin/layout/scripts/form-icheck.js"></script>--}}

    {{--<script type="text/javascript">--}}

        {{--$(document).ready(function () {--}}
            {{--$(document.body).on('click', '#flashsale', function () {--}}

                {{--var checkId = $(this).val();--}}
                {{--alert(checkId);--}}
            {{--});--}}


        {{--});--}}

    {{--</script>--}}


@endsection