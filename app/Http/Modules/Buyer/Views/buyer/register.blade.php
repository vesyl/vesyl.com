<!DOCTYPE html>
<html>
<head>
    @include('Buyer/Layouts/buyerheadscripts')
</head>
<body class="page-login">
<main class="page-content">
    <div class="page-inner bg-color">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-3 center">
                    <div class="login-box">
                        {!! Html::link('/', 'Flash Sale', ['class' => 'logo-name text-lg text-center']) !!}
                        <p class="text-center m-t-md">Create a Buyer account</p>

                        <form class="m-t-md" method="post" action="" id="registerForm">
                            <span class="error">{!! $errors->first('registerErrMsg') !!}</span>

                            <div class="form-group">
                                {!! Form::text('first_name',old('first_name') , ['class' => 'form-control','placeholder'=>'First Name','required'=>'true']) !!}
                                <span class="error">{!! $errors->first('first_name') !!}</span>
                            </div>
                            <div class="form-group">
                                {!! Form::text('last_name',old('last_name') , ['class' => 'form-control','placeholder'=>'Last Name','required'=>'true']) !!}
                                <span class="error">{!! $errors->first('last_name') !!}</span>
                            </div>
                            <div class="form-group">
                                {!! Form::email('email',old('email')  , ['class' => 'form-control','placeholder'=>'Email','required'=>'true']) !!}
                                <span class="error">{!! $errors->first('email') !!}</span>
                            </div>
                            <div class="form-group">
                                {!! Form::text('username',old('username')  , ['class' => 'form-control','placeholder'=>'Username','required'=>'true']) !!}
                                <span class="error">{!! $errors->first('username') !!}</span>
                            </div>
                            <div class="form-group">
                                {!! Form::password('password', ['class' => 'form-control','placeholder'=>'Password','required'=>'true']) !!}
                                <span class="error">{!! $errors->first('password') !!}</span>
                            </div>
                            <div class="form-group">
                                {!! Form::password('password_confirm', [ 'id'=>'password', 'class' => 'form-control','placeholder'=>'Confirm Password','required'=>'true']) !!}
                                <span class="error">{!! $errors->first('password_confirm') !!}</span>
                            </div>
                            <label>
                                <input type="checkbox" name="terms_and_policy"> Agree the terms and policy
                                <div class="clearfix"></div>
                                <span class="error">{!! $errors->first('terms_and_policy') !!}</span>
                            </label>
                            {!! Form::submit('Submit', ['class' => 'btn btn-success btn-block m-t-xs']) !!}
                            <p class="text-center m-t-xs text-sm">Already have an account?</p>
                            {!! Html::link('/buyer/login', 'Login', ['class' => 'btn btn-default btn-block m-t-xs']) !!}
                        </form>
                        <p class="text-center m-t-xs text-sm">2015 &copy; Flash Sale</p>
                    </div>
                </div>
            </div>
            <!-- Row -->

        </div>
        <!-- Main Wrapper -->
    </div>
    <!-- Page Inner -->
</main>
<!-- Page Content -->

@include('Buyer/Layouts/buyercommonfooterscripts')
{{--<script src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>--}}
<script>
    $.validator.addMethod("password_regex", function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/i.test(value);
    }, "Passwords are 8-16 characters with uppercase letters, lowercase letters,  at least one number and Symbol.");

    $(document).ready(function () {
        $('#registerForm').validate({
            rules: {
                username: {
                    required: true,
                    remote: {
                        url: "/buyer/userAjaxHandler",
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'checkUserName'
                        }
                    }
                },
                email: {
                    required: true,
                    remote: {
                        url: "/buyer/userAjaxHandler",
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'checkEmail'
                        }
                    }
                },

                    password: {
                        required: true,
                        password_regex: true
                    },
                    password_confirm: {
                        required: true,
                        equalTo: "#password"
                    }
            },
            messages: {
                username: {
                    remote: "The username has already been taken."
                },
                email: {
                    remote: "The email has already been taken."
                }
            },
            submitHandler: function (form) {
                event.preventDefault()
            }
        });
    });
</script>
</body>
</html>