<!DOCTYPE html>
<html>
<head>
    <!--<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />-->
    @include('Admin/Layouts/adminheadscripts')
    <script type="text/javascript">
        document.getElementById("myForm").reset();
    </script>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<body class="page-login" >
<main class="page-content">
    <div class="page-inner bg-color">
        <div id="main-wrapper">
            <div class="row" style="margin-top: 10%;">
                <div class="col-md-3 center">
                    <div class="login-box login">
                        <a href="/" class="logo-name text-lg text-center">Flash Sale</a>

                        <p class="text-center m-t-md">Please login into your account.</p>
                        <span class="error">{!! $errors->first('errMsg') !!}</span>
                        <form class="m-t-md" method="post"  >

                            <div class="form-group">
                                <input class="form-control" placeholder="Email" name="email" >
                                <span class="error">{!! $errors->first('email') !!}</span>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" >
                                <span class="error">{!! $errors->first('password') !!}</span>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Login</button>
                            <a href="javascript:;" class="display-block text-center m-t-md text-sm" id="forgotPasswordview">Forgot
                                Password?</a>
                        </form>
                    </div>

                    <div class="login-box forgot hide">
                        <a href="/" class="logo-name text-lg text-center">Flash Sale</a>
                        <p class="text-center m-t-md enter">Enter Your mail</p>
                        <p class="text-center m-t-md check">Check Your mail</p>
                        <form class="m-t-md " method="post" onsubmit="return false">

                            <div class="form-group" >
                                <input id="a1" class="form-control" placeholder="Email" name="email" >
                                <span class="error">{!! $errors->first('email') !!}</span>
                            </div>

                            <div class="form-group resetcode " >

                                <input id="a3" class="form-control" placeholder="Enter resetPassword" name="resetPassword" >
                                <span class="error">{!! $errors->first('Resetcode') !!}</span>
                            </div>

                            <button type="submit" class="btn btn-success btn-block reset" id="resetPassword">reset password</button>
                            <button type="submit"  class="btn btn-success btn-block submit" id="submit">submit</button>
                            {{--<a href="/admin/login" class="display-block text-center m-t-md text-sm" id="cancel">cancel</a>--}}
                        </form>

                    </div>
                    <div class="login-box newpassword hide">
                        <a href="/" class="logo-name text-lg text-center">Flash Sale</a>
                        <p class="text-center m-t-md enter" style="display: block!important;">Enter Your New password</p>
                        <form class="m-t-md " method="post" onsubmit="return false">

                            <div class="form-group" >
                                <input type="password" id="newpassword" class="form-control" placeholder="newpassword" name="newpassword" >
                                <span class="error">{!! $errors->first('email') !!}</span>
                            </div>

                            <div class="form-group newpassword " >

                                <input type="password" id="ConfirmPassword" class="form-control" placeholder="ConfirmPassword" name="ConfirmPassword" >
                                <span class="error">{!! $errors->first('Resetcode') !!}</span>
                            </div>

                            <button type="submit" class="btn btn-success btn-block reset" id="resetPassword">reset password</button>
                            <button type="submit"  class="btn btn-success btn-block submit" id="changepasswordbutton">submit</button>
                        </form>

                    </div>
                </div>
            </div>
            <!-- Row -->
            <div class="row">
                <div class="col-md-3 center">
                    <p class="text-center m-t-xs text-sm" style="color:#fff;" >2015 &copy;Flash Sale</p>
                </div>
            </div>
        </div>
        <!-- Main Wrapper -->
    </div>
    <!-- Page Inner -->
</main>
<!-- Page Content -->

@include('Admin/Layouts/admincommonfooterscripts')

</body>
</html>
<script>
    $(document).ready(function () {


        $.ajaxSetup(
                {
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
    $(document).ready(function(){
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
                    url: "/admin/resetPassword",
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
                });
                var data = jQuery.parseJSON(data);

                if (data == 1) {
                    console.log("admin/resetpassed  ");

                }
            });

    });

</script>
<script>


    $("#submit").on('click',function (e) {
        e.preventDefault();
        email = $("#a1").val();
       password= $("#a3").val();
        console.log("came");
        $.ajax({
            url: "/admin/submit",
            type: "post",
            datatype: "json",
            data: {
                email: email,
                password : password,
            },
            success: function (data) {
                if(data==1){
                    $('.newpassword').removeClass('hide');
                    $('.forgot').addClass('hide');
                    console.log("came to admain submit");
                }
                else{
                    console.log('came to admain submit');
                }

            }

        });
    });




    $("#changepasswordbutton").on('click',function (e) {
        e.preventDefault();
        email = $("#a1").val();
       password= $("#a3").val();
        newpassword= $("#newpassword").val();
        ConfirmPassword= $("#ConfirmPassword").val();
        console.log(email);
        console.log(password);
        console.log(newpassword);

        $.ajax({
            url: "/admin/changepassword",
            type: "post",
            data: {
                email: email,
                password : password,
                newpassword:newpassword,
                ConfirmPassword:ConfirmPassword
            },
            success: function (data) {
                if(data==1){
                    $('.newpassword').addClass('hide');
                    $('.login').removeClass('hide');
                    alert("successfully Updated ");

                }else{
                    alert("Invalid Password  ");
                }

            }

        });
    });
</script>

