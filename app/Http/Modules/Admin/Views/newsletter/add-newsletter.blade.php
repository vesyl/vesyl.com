    @extends('Admin/Layouts/adminlayout')

@section('title', trans('message.add_new_newsletter')) {{--TITLE GOES HERE--}}

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
                    <form method="post" id="addnewsletterform">
                        <div class="form-group">
                        <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Newsletter Subject:</label>
                        <input type="text" class="form-control" name="subject" placeholder="Subject"><br>
                            {!!  $errors->first('subject', '<font color="red">:message</font>') !!}
                        <textarea class="ckeditor" name="description"></textarea>
                            {!!  $errors->first('description', '<font color="red">:message</font>') !!}
                        </br></br>
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
        $('#addnewsletterform').validate({// initialize the plugin
            rules: {
                subject: {
                    required: true
                },
                description: {
                    required: true
                }
            }
        });
        });
    </script>
@endsection