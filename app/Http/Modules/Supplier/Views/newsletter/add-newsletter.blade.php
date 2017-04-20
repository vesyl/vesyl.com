@extends('Supplier/Layouts/supplierlayout')

@section('title', trans('message.add_new_newsletter')) {{--TITLE GOES HERE--}}

@section('pageheadcontent')
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
                    <form method="post" id="addnewsletterform" onsubmit="return validate();">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Newsletter Subject:</label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"><br>
                            {!!  $errors->first('subject', '<font color="red">:message</font>') !!}
                            <textarea class="ckeditor" id="addnewsletterform123" name="description" required></textarea>
                            {!!  $errors->first('description', '<font color="red">:message</font>') !!}
                            </br></br>
                        </div>
                        <p id="errorMessage"></p>
                        <button class="btn btn-primary" type="submit" id="asd">Submit</button>
                    </form>

                    {{--<form id="myform">--}}
                        {{--<label for="field">Required, minimum length 3: </label>--}}
                        {{--<input type="text" class="left" id="field" name="field">--}}
                        {{--<br/>--}}
                        {{--<input type="submit" value="Validate!">--}}
                    {{--</form>--}}



                </div>
            </div>
        </div>
    </div>

@endsection
@section('pagejavascripts')
    <script src="/assets/global/plugins/ckeditor/ckeditor.js"></script>
    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery.validate.min.js" type="text/javascript"></script>


    <script type="text/javascript">
        $(document).ready(function () {
//            $( "#myform" ).validate({
//                rules: {
//                    field: {
//                        required: true,
//                        minlength: 3
//                    }
//                }
//            });



            $('#addnewsletterform').validate({// initialize the plugin
                rules: {
                    subject: {
                        required: true
                    },
                    description: {
                        required: true,
                        minlength: 30
                    }
                },
                messages: {
                    subject: {
                        required:"Enter the valid subject"
                    },
                    description: {
                        required:"Please enter your description",
                        minlength:"Your description must consist of at least 30 characters"
                    }
                },
                errorElement :'span'

            });
        });

    </script>
@endsection