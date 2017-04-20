@extends('Admin/Layouts/adminlayout')

@section('title', 'Add New Language') {{--TITLE GOES HERE--}}

@section('headcontent')

    {{--PAGE STYLES OR SCRIPTS LINKS--}}
@endsection

@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">

                <div class="portlet-title">

                    <div class="actions">
                        <a class="btn btn-default" href="/admin/manage-language">Back to list </a>
                    </div>

                </div>

                {{--<div class="alert">--}}
                {{--@if(Session::has('errmsg'))--}}
                {{--<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('errmsg') }}</p>--}}
                {{--@endif--}}
                {{--</div>--}}
                <form class="form-horizontal" name="addlanguageform" method="post" enctype="multipart/form-data">

                    <div class="form-body">

                        <div class="form-group">
                            <label for="lang_code" class="col-md-3 control-label">Language
                                Code:</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" id="lang_code"
                                       placeholder="Language Code" name="lang_code">
                                {!!  $errors->first('lang_code', '<font color="red">:message</font>') !!}
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        {{--<div class="form-group">--}}
                        {{--<label class="col-sm-2 control-label">Filter Group Description</label>--}}
                        {{--<div class="col-sm-10">--}}
                        {{--<div class="summernote"></div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <label for="name" class="col-md-3 control-label">Name</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" id="name"
                                       placeholder="Language Name" name="name">
                                {!!  $errors->first('name', '<font color="red">:message</font>') !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="country_code" class="col-md-3 control-label">Country:</label>

                            <div class="col-md-4">
                                <select class="form-control" id="country_code"
                                        name="country_code">
                                    <option value=""></option>
                                    <optgroup label="Select">
                                        <?php foreach($countrydetail as $key => $val) { ?>
                                        <option value="<?php echo $val->location_id ?>"><?php echo $val->name; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                </select>
                                {!!  $errors->first('country_code', '<font color="red">:message</font>') !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        {{--<div class="form-group">--}}
                        {{--<label for="statact" class="col-md-3 control-label">Status</label>--}}

                        {{--<div class="col-md-4">--}}

                        {{--<input id="activeid" name="statact" type="checkbox" value="">Active--}}
                        {{--<input id="inactiveid" name="statact" type="checkbox" value="">Inactive--}}

                        {{--</div>--}}
                        {{--{!!  $errors->first('statact', '<font color="red">:message</font>') !!}--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="statact" class="col-md-3 control-label">Active</label>
                                <input type="checkbox" name="statact">

                                <div class="clearfix"></div>
                                <label for="statact" class="col-md-3 control-label">Inactive</label>
                                <input type="checkbox" name="statact">
                                {!!  $errors->first('statact', '<font color="red">:message</font>') !!}
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-rounded" id="submitadd">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
