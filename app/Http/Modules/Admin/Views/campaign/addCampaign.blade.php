@extends('Admin/Layouts/adminlayout')

@section('title', 'Add New Flashsale') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" media="screen" rel="stylesheet"
            type="text/css">

    <link href="/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css"/>
    {{--<link href="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" media="screen" rel="stylesheet"--}}
    {{--type="text/css">--}}
    {{--<link rel="stylesheet" type="text/css" href="/assets/plugins/bootstrap-datepicker/css/datepicker.css">--}}
    {{--<link rel="stylesheet" type="text/css"--}}
    {{--href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>--}}
    {{--<link rel="stylesheet" type="text/css"--}}
    {{--href="/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>--}}



    <link rel="stylesheet" type="text/css" href=" /assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
    <link rel="stylesheet" type="text/css"
            href=" /assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
    <link rel="stylesheet" type="text/css"
            href=" /assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>


    <link href=" /assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link type="text/css" rel="stylesheet" media="screen"
            href="/assets/plugins/input-autocomplete-without-addnew/dist/tokens.css">
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
            {{--<div class="portlet light bordered">--}}
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        {{--<i class="fa fa-plus font-blue-hoki"></i>--}}
                        {{--<span class="caption-subject font-blue-hoki bold">Add new Coupon</span>--}}
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a href="/admin/manage-campaign" class="btn btn-default btn-circle"><i
                                    class="fa fa-angle-left"></i> Back To List</a>
                    </div>

                </div>

                <div class="portlet-body form">
                    <!-- BEGIN ADD NEW COUPON FORM -->
                    <form class="form-horizontal form-bordered" id="addnewdailyspecial" enctype="multipart/form-data"
                            method="post">
                        <div class="form-body">

                            <div class="form-group">
                                <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Campaign Name:</label>

                                <div class="input-group">
                                    <div class=" form-horizontal">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="campaignname"
                                                    name="campaign_name" placeholder="campaign name">
                                        </div>
                                        {!!  $errors->first('campaign_name', '<font color="red">:message</font>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">Campaign Type:</label>

                                <div class="margin-bottom-16">
                                    <select class="bs-select form-control input-small" data-style="btn-info"
                                            id="campaign_type" name="campaign_type">
                                        <option value="">select</option>
                                        <option value="1">Dailyspecial</option>
                                        <option value="2">Flashsale</option>
                                    </select>
                                    {!!  $errors->first('campaign_type', '<font color="red">:message</font>') !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>


                            <!-- CHANGE AVATAR TAB -->
                            <div class="form-group col-md-6">
                                <label class="control-label col-md-4 col-lg-4 col-sm-4 col-xs-12">Upload
                                    Banner:</label>
                                <div class="input-group">
                                    <div class=" form-horizontal">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                    style="width: 200px; height: 150px;">
                                                <img src="/assets/images/no-image.png" alt=""/>
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
                                                    <input type="file" name="dailyspecial_image"
                                                            accept="image/*">
                                                </span>
                                                <a href="#" class="btn btn-circle default fileinput-exists"
                                                        data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                            {!!  $errors->first('flashsale_image', '<font color="red">:message</font>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- END CHANGE AVATAR TAB -->

                            <div class="form-group col-md-6">
                                <label class="control-label col-md-4 col-lg-4 col-sm-4 col-xs-12">Campaign Secondary
                                    banner:</label>

                                <div class="input-group">
                                    <div class=" form-horizontal">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                    style="width: 200px; height: 150px;">
                                                <img src="/assets/images/no-image.png" alt=""/>
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
                                                    <input type="file" name="campaign_sec_banner"
                                                            accept="image/*">
                                                </span>
                                                <a href="#" class="btn btn-circle default fileinput-exists"
                                                        data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                        {!!  $errors->first('campaign_sec_banner', '<font color="red">:message</font>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Secondary
                                    banner:</label>

                                <div class="input-group">
                                    <div class=" form-horizontal">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                    style="width: 200px; height: 150px;">
                                                <img src="/assets/images/no-image.png" alt=""/>
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
                                                    <input type="file" name="campaign_logo"
                                                            accept="image/*">
                                                </span>
                                                <a href="#" class="btn btn-circle default fileinput-exists"
                                                        data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                        {!!  $errors->first('campaign_logo', '<font color="red">:message</font>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Percentage Discount:</label>

                                {{--<div class="col-md-2">--}}
                                {{--<div class="margin-bottom-10">--}}
                                {{--<select class="bs-select form-control input-small" data-style="btn-info"--}}
                                {{--id="discounttype" name="discounttype">--}}
                                {{--<option value="">select</option>--}}
                                {{--<option value="2">Percentage</option>--}}
                                {{--<option value="1">Flat</option>--}}
                                {{--</select>--}}
                                {{--{!!  $errors->first('discounttype', '<font color="red">:message</font>') !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-3" id="flatdiscount">--}}
                                {{--<div class="input-inline input-medium input-icon right">--}}
                                {{--<i class="fa fa-rupee" id="iconrupee" style="display:none;"></i>--}}
                                {{--<input id="flatdiscountval" type="text" value="Choose discount type first."--}}
                                {{--name="flatdiscount" class="form-control integer-only" disabled--}}
                                {{--style="cursor: text">--}}
                                {{--{!!  $errors->first('flatdiscount', '<font color="red">:message</font>') !!}--}}
                                {{--</div>--}}

                                {{--</div>--}}
                                <div class="col-md-3" id="percentagediscount">
                                    <div class="input-inline input-medium input-icon right">
                                        <i class="icon-percent"></i>
                                        <input id="percentagediscountval" type="text" value="0"
                                                name="percentagediscount" class="form-control integer-only">
                                        {!!  $errors->first('percentagediscount', '<font color="red">:message</font>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Available from:</label>

                                <div class="col-md-5">
                                    <div data-date-start-date="+0d"
                                            class="input-group date form_meridian_datetime input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input id="availablefromdate" name="availablefromdate" type="text"
                                                class="form-control" size="16">
                                        <span class="input-group-btn">
                                            {{--<button type="button" class="btn default date-reset"><i--}}
                                            {{--class="fa fa-times"></i></button>--}}
                                            <button type="button" class="btn default date-set"><i
                                                        class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    {!!  $errors->first('availablefromdate', '<font color="red">:message</font>') !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Available upto:</label>

                                <div class="col-md-5">
                                    <div data-date-start-date="+0d"
                                            class="input-group date form_meridian_datetime input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input id="availableuptodate" name="availableuptodate" type="text"
                                                class="form-control" size="16">
                                        <span class="input-group-btn">
                                            {{--<button type="button" class="btn default date-reset"><i--}}
                                            {{--class="fa fa-times"></i></button>--}}
                                            <button type="button" class="btn default date-set"><i
                                                        class="fa fa-calendar"></i></button>
                                        </span>

                                    </div>
                                    {!!  $errors->first('availableuptodate', '<font color="red">:message</font>') !!}
                                </div>
                            </div>
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label col-md-2">Shop:</label>--}}

                            {{--<div class="margin-bottom-10">--}}
                            {{--<select class="bs-select form-control input-small" data-style="btn-info"--}}
                            {{--id="suppliershop" name="suppliershop">--}}
                            {{--<option value="">select</option>--}}
                            {{--<option value="0">Abevo</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>


                        <div class="form-group" id="validonvaluesdivcat">
                            <div class="form-group">
                                <label class="control-label col-md-2">Choose Categories:</label>

                                <div class="col-md-6">
                                    <select id="js-states" class="form-control select2" multiple
                                            data-placeholder="Choose Categories..." value="" name="productcategories[]">
                                    </select>
                                </div>
                                {!!  $errors->first('productcategories', '<font color="red">:message</font>') !!}
                                <a href="/admin/add-category" class="btn btn-success" value="Add Category"
                                        style="float:right">Add Your Own Category</a>

                            </div>
                        </div>
                        <div class="form-group" id="validonvaluesdivsubcat">
                            <div class="form-group">
                                <label class="control-label col-md-2">Choose Subcategories</label>
                                <div class="col-md-6">
                                    <select id="select2_sample2" class="form-control select2" multiple
                                            data-placeholder="Choose Subcategories.." value=""
                                            name="productsubcategories[]">
                                        {{--<optgroup label="NFC EAST">--}}
                                        {{--<option>Dallas Cowboys</option>--}}
                                        {{--<option>New York Giants</option>--}}
                                        {{--<option>Philadelphia Eagles</option>--}}
                                        {{--<option>Washington Redskins</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="NFC NORTH">--}}
                                        {{--<option>Chicago Bears</option>--}}
                                        {{--<option>Detroit Lions</option>--}}
                                        {{--<option>Green Bay Packers</option>--}}
                                        {{--<option>Minnesota Vikings</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="NFC SOUTH">--}}
                                        {{--<option>Atlanta Falcons</option>--}}
                                        {{--<option>Carolina Panthers</option>--}}
                                        {{--<option>New Orleans Saints</option>--}}
                                        {{--<option>Tampa Bay Buccaneers</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="NFC WEST">--}}
                                        {{--<option>Arizona Cardinals</option>--}}
                                        {{--<option>St. Louis Rams</option>--}}
                                        {{--<option>San Francisco 49ers</option>--}}
                                        {{--<option>Seattle Seahawks</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="AFC EAST">--}}
                                        {{--<option>Buffalo Bills</option>--}}
                                        {{--<option>Miami Dolphins</option>--}}
                                        {{--<option>New England Patriots</option>--}}
                                        {{--<option>New York Jets</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="AFC NORTH">--}}
                                        {{--<option>Baltimore Ravens</option>--}}
                                        {{--<option>Cincinnati Bengals</option>--}}
                                        {{--<option>Cleveland Browns</option>--}}
                                        {{--<option>Pittsburgh Steelers</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="AFC SOUTH">--}}
                                        {{--<option>Houston Texans</option>--}}
                                        {{--<option>Indianapolis Colts</option>--}}
                                        {{--<option>Jacksonville Jaguars</option>--}}
                                        {{--<option>Tennessee Titans</option>--}}
                                        {{--</optgroup>--}}
                                        {{--<optgroup label="AFC WEST">--}}
                                        {{--<option>Denver Broncos</option>--}}
                                        {{--<option>Kansas City Chiefs</option>--}}
                                        {{--<option>Oakland Raiders</option>--}}
                                        {{--<option>San Diego Chargers</option>--}}
                                        {{--</optgroup>--}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Choose Products:</label>
                            <span>If You Select More Than 10 Products. It will be Added as a Flashsale Campaign.</span>

                            <div class="col-md-6">
                                <select id="select2_tags" class="form-control select2" multiple
                                        data-placeholder="Choose Products..." name="product[]">
                                    @foreach($Products as $key => $val)
                                        <option value="<?php echo $val->product_id; ?>"
                                                id="daily"><?php echo $val->product_name; ?></option>
                                    @endforeach
                                </select>
                            </div>
                            {!!  $errors->first('product', '<font color="red">:message</font>') !!}
                        </div>

                    {{--</div>--}}
                    <!-- SUBMIT BUTTON-->
                        <div class="form-actions">
                            <div align="center">
                                <button type="submit" class="btn blue" type="submit" id="addsubmit">Add Camapign
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- END ADD NEW COUPON FORM -->
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->

@endsection

@section('pagejavascripts')
    <script src="/assets/plugins/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-select2.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

    <script src="/assets/js/pages/components-pickers.js"></script>
    {{--<script src="/assets/plugins/input-autocomplete-without-addnew/dist/tokens.js" type="text/javascript"></script>--}}
    {{--<script src="/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>--}}
    <script src="/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script type="text/javascript"
            src=" /assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript"
            src=" /assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript"
            src=" /assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

    <script>


        $(document).ready(function () {

            $('select').select2();

            $(".js-example-basic-multiple-limit").select2({
                maximumSelectionLength: 2
            });

            $(".js-example-tokenizer").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
//            $(document).on('change', '#discounttype', function (e) {
//                if ($('#discounttype').val() == "2") {
//                    $('#percentagediscount').removeClass("display-none");
//                    $('#flatdiscount').addClass("display-none");
//                } else if ($('#discounttype').val() == "1") {
//                    $('#flatdiscount').removeClass("display-none");
//                    $('#flatdiscountval').prop("disabled", false);
//                    $('#flatdiscountval').val('0');
//                    $('#percentagediscount').addClass("display-none");
//                    $('#iconrupee').show();
//                } else if ($('#discounttype').val() == "") {
//                    $('#availablefromdate').val('')
//                    $('#availableuptodate').val('')
//                    $('#percentagediscount').addClass("display-none");
//                    $('#flatdiscount').removeClass("display-none");
//                    $('#flatdiscountval').val('Choose discount type first.');
//                    $('#flatdiscountval').prop("disabled", true);
//                    $('#iconrupee').hide();
//                }
//            });

            var productData = new Array();
            var autocompleteList1 = new Array();
            var srcindex1 = new Array();
            var data = new Array();
            $.ajax({
                url: '/admin/campaign-ajax-handler',
                type: 'POST',
                datatype: 'json',
                data: {
                    method: 'getActiveCategories'
                },
                success: function (resposne) {
                    data1 = $.parseJSON(resposne);
                    $.each(data1, function (i, v) {
                        var tempArray = [];
                        tempArray['id'] = v.category_id;
                        tempArray['text'] = v.category_name;
                        data.push({id: tempArray['id'], text: tempArray['text']});
                    });

                    $("#js-states").select2({
                        data: data
                    });

                }
            });

            var productData = new Array();
            var autocompleteList1 = new Array();
            var srcindex1 = new Array();
            var subcategoryData = new Array();
            $.ajax({
                url: '/admin/campaign-ajax-handler',
                type: 'GET',
                datatype: 'json',
                data: {
                    method: 'getSubCategoriesForMainCategory'
                },
                success: function (resposne) {
                    data1 = $.parseJSON(resposne);

                    var toAppend = '';
                    $.each(data1, function (i, v) {
                        var tempArray = [];
//                        tempArray['id'] = v.category_id;
//                        tempArray['text'] = v.category_name;
//                        subcategoryData.push({id: tempArray['id'], text: tempArray['text']});
                        var categoryId = v['main_category_id'].split(",");
                        var categoryNames = v['main_category_name'].split(",");
                        toAppend += '<optgroup data-id="' + i + '" label="' + v['main_cat_name'] + '">';
                        $.each(categoryNames, function (catI, catV) {
                            toAppend += '<option value="' + i + '_' + categoryId[catI] + '">' + catV + '</option>';
                        });
                        toAppend += '</optgroup>';

                    });

//                    $("#select2_sample2").select2({
//                        data: toAppend
//                    });
                    $("#select2_sample2").append(toAppend);
                }
            });

            {{--<div class="form-group" id="validonvaluesdivsubcat">--}}
            {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2">Choose SubCategories:</label>--}}

            {{--<div class="col-md-6">--}}
            {{--<select id="js-state" class="form-control select2" multiple--}}
            {{--data-placeholder="Choose Subcategories..." value="" name="producsubtcategories[]">--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--{!!  $errors->first('productsubcategories', '<font color="red">:message</font>') !!}--}}
            {{--<a href="/admin/add-category" class="btn btn-success" value="Add Category"--}}
            {{--style="float:right">Add Your Own Category</a>--}}

            {{--</div>--}}
            {{--</div>--}}

            //            var maindata = new Array();
            //            var mainId = new Array();
            ////            $(document.body).on("change", "#select2_tags", function () {
            //
            //
            ////                var catId = $(this).val();
            //                $.ajax({
            //                    url: '/supplier/dailyspecial-ajax-handler',
            //                    type: 'POST',
            //                    datatype: 'json',
            //                    data: {
            //                        method: 'getSupplierProducts',
            ////                        catId: catId
            //                    },
            //                    success: function (response) {
            //                        var data = $.parseJSON(response);
            //                        var temparrayId = [];
            //                        var temparrayName = [];
            //                        $.each(data, function (pi, pv) {
            //                            temparrayId['id'] = pv['product_id'];
            //                            temparrayName['text'] = pv['product_name'];
            //                            data.push({id: temparrayId['id'], text: temparrayName['text']});
            //                        });
            //                        $("#select2_tags").select2({
            //                            data: data
            //                        });
            //
            ////                        $('#select2_tags').html('<option value="' + temparrayId + '">' + temparrayName + '</option>');
            //
            //                    }
            //                });

            // ============================================ //

//            var maindata = new Array();
//            var mainId = new Array();
//            var data = new Array();
//            $.ajax({
//                url: '/supplier/dailyspecial-ajax-handler',
//                type: 'POST',
//                datatype: 'json',
//                data: {
//                    method: 'getSupplierProducts',
//                },
//                success: function (response) {
//                    var datas = $.parseJSON(response);
//                    var temparrayId = [];
//                    var temparrayName = [];
////                    $('#addtagmsg').html(data['message']);
////                    $('#addtagmsgdiv').removeClass('alert-danger');
////                    $('#addtagmsgdiv').addClass('alert-success');
////                    $('#addtagmsgdiv').removeClass('display-hide');
//                    var toAppend = '';
//                    $.each(datas, function (pi, pv) {
//                        temparrayId['id'] = pv['product_id'];
//                        temparrayName['text'] = pv['product_name'];
//
//                    });
////                    if(data >= 10){
////                        $('#addtagmsg').html('Data Exceeded more than 10 added to flashsale');
////                    }
////                    $("#select2_tags").select2({
////                        data: data
////                    });
//                }
//
//            });
            // ============================================== //
            $('#addnewdailyspecial').submit(function () {
                var sub = $('#select2_tags').val();
                if (sub.length >= 10) {
                    if (confirm("Do you want to Make These Products As Flashsale!") == true) {
                        return true;
                    } else {
                        return false;
                    }
                }
            });
        });

        jQuery(document).ready(function () {
            Metronic.init();
            ComponentsPickers.init();
        });

    </script>
    {{--<script src="/assets/js/pages/dashboard.js"></script>--}}
@endsection

