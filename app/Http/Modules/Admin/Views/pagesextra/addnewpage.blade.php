

@extends('Admin/Layouts/adminlayout')

@section('title', trans('message.add_new_pages')) {{--TITLE GOES HERE--}}

@section('headcontent')
    <link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert display-hide" id="addtagmsgdiv">
                <button class="close" data-close="alert"></button>
                                    <span id="addtagmsg">
                                        <!--ADD TAG RESPONSE GOES HERE-->
                                    </span>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        {{--<i class="fa fa-plus font-blue-hoki"></i>--}}
                        {{--<span class="caption-subject font-blue-hoki bold">Add new Coupon</span>--}}
                        <span class="caption-helper"></span>
                    </div>

                </div>

                <div class="portlet-body form">
                    <form method="post" id="addnewpages">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Page Title:</label>
                            <input type="text" class="form-control" name="subject" placeholder="Subject"><br>
                            {!!  $errors->first('subject', '<font color="red">:message</font>') !!}
                            <label class="control-label col-md-2 col-lg-1 col-sm-2 col-xs-12">Page Url:</label>
                            <mark class="large  col-md-3" style="height: 35px!important;font-size: 18px;">{{$weburl}}/</mark>
                            <input type="text" class="form-control  col-md-2 " name="url" placeholder="Url " style="width: 25%" onkeypress="return onlyAlphabets(event,this);" />
                            <div id="notification"></div>

                            {{--<input type="text" class="form-control  col-md-2 " name="url" placeholder="Url " style="width: 25%"><br>--}}
                            {!!  $errors->first('url', '<font color="red">:message</font>') !!}

                            <br><br><br>
                            <textarea class="ckeditor" name="description"></textarea>
                            {!!  $errors->first('description', '<font color="red">:message</font>') !!}
                            </br></br>
                            <select name="status" class="dropdown-toggle btn btn-primary" style="margin-left:4px;">
                                <option value="">select status</option>
                                <option value="A">Active</option>
                                <option value="I">Inactive </option>
                            </select>
                        </div>


                        <button class="btn btn-primary" type="submit" id="asd">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('pagejavascripts')
    <script src="/assets/global/plugins/ckeditor/ckeditor.js"></script>
    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#addnewpages').validate({// initialize the plugin
                rules: {
                    subject: {
                        required: true,
                        required: true

                    },
                    description: {
                        required: true
                    },
                    url:{
                        required:true

                    }
                }
            });
        });

        function onlyAlphabets(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else {
                    return true;
                }
                if (!(charCode > 47 && charCode < 58 || charCode > 64 && charCode < 91)) {
                    document.getElementById("notification").innerHTML = "";
                    return true;
                }
                else {
                    document.getElementById("notification").innerHTML = "Capital  letters and Numerics Not Allowed ";
                    return false;
                }
            }
            catch (err) {
//                alert(err.Description);
            }
        }
    </script>
@endsection