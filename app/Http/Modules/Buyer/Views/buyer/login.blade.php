<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    @include('Buyer/Layouts/buyerheadscripts')

</head>
<body class="page-login">
<main class="page-content">
    <div class="page-inner bg-color">
        <div id="main-wrapper">
            <div class="row" style="margin-top: 10%;">
                <div class="col-md-3 center">
                    <div class="login-box login">
                        <a href="/" class="logo-name text-lg text-center">Flash Sale</a>

                        <p class="text-center m-t-md">Please login into your account.</p>

                        <form class="m-t-md" method="post" action="login">
                            <div class="form-group">
                                <input class="form-control" placeholder="Email or Username" name="emailOrUsername"
                                       value="{{old('emailOrUsername')}}" required>
                                <span class="error">{!! $errors->first('emailOrUsername') !!}</span>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                       required>
                            </div>
                            <span class="error">{!! $errors->first('errMsg') !!}</span>

                            <div class="form-group">
                                <input type="checkbox" name="remember"> Remember me
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Login</button>
                            <a href="javascript:;" class="display-block text-center m-t-md text-sm"
                               id="forgotPasswordview">Forgot
                                Password?</a>
                            <p class="text-center m-t-xs text-sm">Do not have an account?</p>
                            <a href="register" class="btn btn-default btn-block m-t-md">Create an account</a>
                        </form>
                        <p class="text-center m-t-xs text-sm">2015 &copy; Flash Sale</p>
                    </div>
                    <div class="login-box forgot hide">
                        <a href="/" class="logo-name text-lg text-center">Flash Sale</a>
                        <p class="text-center m-t-md enter">Enter Your mail</p>
                        <p class="text-center m-t-md check">Check Your mail</p>
                        <form class="m-t-md " method="post" onsubmit="return false">

                            <div class="form-group">
                                <input id="a1" class="form-control" placeholder="Email" name="email">
                                <span class="error">{!! $errors->first('email') !!}</span>
                            </div>

                            <div class="form-group resetcode ">

                                <input id="a3" class="form-control" placeholder="Enter resetPassword"
                                       name="resetPassword">
                                <span class="error">{!! $errors->first('Resetcode') !!}</span>
                            </div>

                            <button type="submit" class="btn btn-success btn-block reset" id="resetPassword">reset
                                password
                            </button>
                            <button type="submit" class="btn btn-success btn-block submit" id="submit">submit</button>
                        </form>
                    </div>
                    <div class="login-box newpassword hide">
                        <a href="/" class="logo-name text-lg text-center">Flash Sale</a>
                        <p class="text-center m-t-md enter" style="display: block!important;">Enter Your New
                            password</p>
                        <form class="m-t-md " method="post" onsubmit="return false" id="password-change">

                            <div class="form-group">
                                <input type="password" id="newpassword1" class="form-control" placeholder="newpassword"
                                       name="newpassword">
                                <span class="error">{!! $errors->first('email') !!}</span>
                            </div>

                            <div class="form-group newpassword ">
                                <input type="password" id="ConfirmPassword1" class="form-control" placeholder="ConfirmPassword"
                                       name="ConfirmPassword">
                                <span class="error">{!! $errors->first('Resetcode') !!}</span>
                            </div>

                            <button type="submit" class="btn btn-success btn-block reset" id="resetPassword">reset
                                password
                            </button>
                            <button type="submit" class="btn btn-success btn-block submit" id="changepasswordbutton">
                                submit
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
        <!-- Main Wrapper -->
    </div>
    <!-- Page Inner -->
</main>
<!-- Page Content -->

@include('Buyer/Layouts/buyercommonfooterscripts')

</body>
</html>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
        });

        $("#forgotPasswordview").click(function (e) {
            $('.login').addClass('hide');
            $('.forgot').removeClass('hide');
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".resetcode").hide();
        $(".submit").hide();
        $(".resetPassword").hide();
        $(".check").hide();

        $("#resetPassword").click(function (e) {
            console.log("came");
            e.preventDefault();
            email = $("#a1").val();

            console.log("came");
            $.ajax({
                url: "/buyer/resetPassword",
                type: "post",
                datatype: "json",
                data: {
                    email: email,
                },
                success: function (data) {
                    $(".resetcode").show();
                    $(".submit").show();
                    $(".reset").hide();
                    $(".enter").hide();
                    $(".check").show();


                }
            })
            var data = jQuery.parseJSON(data);

            if (data == 1) {
                console.log("buyer/resetpassed  ");

            }
        });

        $("#submit").on('click', function (e) {
            e.preventDefault();
            email = $("#a1").val();
            password = $("#a3").val();
            console.log("came");
            $.ajax({
                url: "/buyer/submit",
                type: "post",
                datatype: "json",
                data: {
                    email: email,
                    password: password,
                },
                success: function (data) {
                    if (data == 1) {
                        $('.newpassword').removeClass('hide');
                        $('.forgot').addClass('hide');
                        console.log("successfully Updated ");

                    } else {
                        alert("We can not find an account with that email address ");
                    }

                }

            });
        });

        $("#changepasswordbutton").on('click', function (e) {
            e.preventDefault();
            email = $("#a1").val();
            password = $("#a3").val();
            newpassword = $("#newpassword").val();
            ConfirmPassword = $("#ConfirmPassword").val();
            console.log(email);
            console.log(password);
            console.log(newpassword);

            $.ajax({
                url: "/buyer/changepassword",
                type: "post",
                data: {
                    email: email,
                    password: password,
                    newpassword: newpassword,
                    ConfirmPassword: ConfirmPassword
                },
                success: function (data) {
                    if (data == 1) {
                        $('.newpassword').addClass('hide');
                        $('.login').removeClass('hide');
                        alert("successfully Updated ");

                    } else {
                        alert("Invalid Password  ");
                    }

                }

            });
        });
    });
    $.validator.addMethod("password_regex", function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/i.test(value);
    }, "Passwords are 8-16 characters with uppercase letters, lowercase letters,  at least one number and Symbol.");

    $('#password-change').validate({
        rules: {
            newpassword: {
                required: true,
                password_regex: true
            },
            ConfirmPassword: {
                required: true,
                equalTo: "#newpassword"
            }
        },
        messages: {
//                    current_password: "Enter Current Password",
//                new_password: " Enter New Password",
            ConfirmPassword: " Enter Confirm Password Same as Password"
        }
    });

</script>

