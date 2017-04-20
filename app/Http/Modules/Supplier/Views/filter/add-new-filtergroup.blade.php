<?php

//print_a(old());
?>

@extends('Supplier/Layouts/supplierlayout')

@section('title', trans('message.new_filter_group')) {{--TITLE GOES HERE--}}

@section('pageheadcontent')
    {{--<link href="/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css"/>--}}
    {{--<link href="/assets/plugins/summernote-master/summernote.css" rel="stylesheet" type="text/css"/>--}}
    <link href="/assets/plugins/jstree/themes/default/style.min.css" rel="stylesheet" type="text/css"/>
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
@endsection

@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">

                <div class="portlet-title">

                    <div class="actions">
                        <a class="btn btn-default" href="/supplier/manage-filtergroup">Back to list </a>
                    </div>

                </div>

                <div class="alert">
                    @if(Session::has('errmsg'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('errmsg') }}</p>
                    @endif
                </div>

                <form class="form form-horizontal" id="addnewfiltergroupform" method="post"
                      enctype="multipart/form-data">
                    <div class="form-body">
                        <div class="form-group">
                            <label for="productfiltergroupnamestatus" class="col-md-3 control-label">Filter
                                Type:</label>

                            <div class="col-md-2">
                                <select class="form-control" id="productfiltertype"
                                        name="productfiltertype">
                                    <option value="">Select filter type...</option>
                                    <option value="G">Filter Group</option>
                                    <option value="V">Filter Variant</option>
                                </select>
                                {!!  $errors->first('productfiltertype', '<font color="red">:message</font>') !!}
                            </div>
                            <div class="col-md-2">
                                <select class="form-control filtergroup hide" id="productFilterGroups"
                                        name="productFilterGroupType">
                                    <option value="">Select filter Group...</option>
                                    <?php if(isset($allGroupFilter)){ foreach($allGroupFilter as $key => $val) { ?>
                                    <option value="<?php echo $val->product_filter_option_id ?>"><?php echo $val->product_filter_option_name; ?></option>
                                    <?php }} ?>
                                </select>
                                {!!  $errors->first('productFilterGroupType', '<font color="red">:message</font>') !!}
                            </div>
                        </div>
                        <div id="filter-group" class="productgroup hide">
                            <div class="form-group">
                                <label for="productfiltergroupname" class="col-md-3 control-label">Filter
                                    name:</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="productfiltergroupname"
                                           placeholder="filter name" name="productfiltergroupname">
                                    {!!  $errors->first('productfiltergroupname', '<font color="red">:message</font>') !!}
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
                                <label for="filterdescription" class="col-md-3 control-label">Filter
                                    Description</label>

                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="filterdescription"
                                           placeholder="filter description" name="filterdescription">
                                    {!!  $errors->first('filterdescription', '<font color="red">:message</font>') !!}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="productfiltergroupnamestatus" class="col-md-3 control-label">Filter
                                    By:</label>

                                <div class="col-md-2">
                                    <select class="form-control" id="productfiltergroupfeature"
                                            name="productfiltergroupfeature">
                                        <option value=""></option>
                                        <optgroup label="Features">
                                            <?php foreach($features as $key => $val) { ?>
                                            <option value="<?php echo $val->feature_id . "-" . "0"?>"><?php echo $val->feature_name; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                        <optgroup label="Product Fields">
                                            <option value="0-1">Price</option>
                                            <option value="0-2">InStock</option>
                                            <option value="0-3">Free Shiping</option>
                                        </optgroup>
                                    </select>
                                    {!!  $errors->first('productfiltergroupfeature', '<font color="red">:message</font>') !!}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Type</label>

                                <div class="col-sm-2">
                                    <select name="filter_variant_type" class="form-control m-b-sm"
                                            id="filter_variant_type">
                                        <option value="1">Select box</option>
                                        <option value="2">Radio group</option>
                                        <option value="3">Check box</option>
                                    </select>
                                    {!!  $errors->first('filter_variant_type', '<font color="red">:message</font>') !!}
                                </div>
                            </div>
                            {{--<div class="form-group">--}}
                            {{--<label for="productfiltergroupnamestatus" class="col-md-3 control-label">Filter group--}}
                            {{--status:</label>--}}

                            {{--<div class="col-md-2">--}}
                            {{--<select class="form-control" id="productfiltergroupnamestatus"--}}
                            {{--name="productfiltergroupnamestatus">--}}
                            {{--<option value="">set status</option>--}}
                            {{--<option value="0">Inactive</option>--}}
                            {{--<option value="1">Active</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<h4 class="no-m m-b-sm m-t-lg">Multiple Selection</h4>--}}
                            {{--<select class="js-states form-control" multiple="multiple" tabindex="-1" style="display: none; width: 100%">--}}
                            {{--<optgroup label="Alaskan/Hawaiian Time Zone">--}}
                            {{--<option value="AK">Alaska</option>--}}
                            {{--<option value="HI">Hawaii</option>--}}
                            {{--</optgroup>--}}
                            {{--<optgroup label="Pacific Time Zone">--}}
                            {{--<option value="CA">California</option>--}}
                            {{--<option value="NV">Nevada</option>--}}
                            {{--<option value="OR">Oregon</option>--}}
                            {{--<option value="WA">Washington</option>--}}
                            {{--</optgroup>--}}
                            {{--</select>--}}


                            <div class="panel panel-white">
                                <div class="panel-heading clearfix">
                                    <h3 class="panel-title">Choose Categories</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="checkTree">
                                        <ul>
                                            <li data-jstree='{"opened":true}'>All Categories <span class="catinputdivs"
                                                                                                   data-id="0"></span>
                                                {{--<input type="checkbox" value="" class="catinput"--}}
                                                {{--name="productcategories[0]"--}}
                                                {{--hidden>--}}
                                                <?php
                                                function treeView($array, $id = 0)
                                                {
                                                for ($i = 0; $i < count($array); $i++) {
                                                if ($array[$i]->parent_category_id == $id) { ?>
                                                <ul>
                                                    <li class="catli"
                                                        data-jstree='{"opened":true}'>  <?php echo $array[$i]->category_name;
                                                        $catId = $array[$i]->category_id; ?>
                                                        <span class="catinputdivs"
                                                              data-id="<?php echo $array[$i]->category_id; ?>"
                                                              data-checked="@if(isset(old('productcategories')[$catId]))
                                                                      checked
                                                                      @endif">
                                            </span>


                                                        {{--<li data-jstree='{"type":"file"}'> --}}
                                                        <?php treeView($array, $array[$i]->category_id); ?>
                                                    </li>
                                                </ul>
                                                <?php
                                                }
                                                }
                                                } ?>
                                                {{--</li>--}}

                                                @if(isset($categories))
                                                    <?php echo treeView($categories);  ?>
                                                @endif

                                            </li>
                                        </ul>
                                    </div>
                                    {!!  $errors->first('productcategories', '<font color="red">:message</font>') !!}
                                </div>
                            </div>

                            {{--<div class="checkbox">--}}
                            {{--<label for="filtercheckproduct" class="col-md-3 control-label" >Display On Product Detail--}}
                            {{--<input type="checkbox" name="filtercheckproduct">--}}
                            {{--</label>--}}
                            {{--</div>--}}
                            {{--<div class="clearfix"></div>--}}
                            {{--<div class="checkbox">--}}
                            {{--<label for="filtercheckcatalog" class="col-md-3 control-label" >Display On Catalog--}}
                            {{--<input type="checkbox" name="filtercheckcatalog">--}}
                            {{--</label>--}}
                            {{--</div>--}}
                        </div>
                        <div id="filter-option" class="productvarinat hide">
                            <div class="form-group">
                                <div class="table-responsive col-md-offset-1 col-md-9">

                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 30%;">Name</th>
                                            <th style="width: 50%;">Description</th>
                                            <th style="width: 20%;">Extra</th>
                                        </tr>
                                        </thead>
                                        <tbody id="variantTBody">

                                        @if(!empty(old()))
                                            @foreach(old('filter_variant') as $keyFV => $valueFV)
                                                <tr>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control"
                                                                   name="filter_variant[{{$keyFV}}][name]"
                                                                   value="{{old('filter_variant')[$keyFV]['name']}}">
                                                            <span class="error">{!! $errors->first('filter_variant.'.$keyFV.'.name') !!}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control"
                                                                   name="filter_variant[{{$keyFV}}][description]"
                                                                   value="{{old('filter_variant')[$keyFV]['description']}}">
                                                            <span class="error">{!! $errors->first('filter_variant.'.$keyFV.'.description','<font color="red">:message</font>') !!}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class="col-sm-1 addvarianttr"><i class="fa fa-plus"></i></a>
                                                        <a class="col-sm-1 removevarianttr"><i
                                                                    class="fa fa-remove"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @else
                                            <tr>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                               name="filter_variant[0][name]">
                                                        <span class="error">{!! $errors->first('filter_variant.name','<font color="red">:message</font>') !!}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                               name="filter_variant[0][description]">
                                                        <span class="error">{!! $errors->first('filter_variant.description','<font color="red">:message</font>') !!}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a class="col-sm-1 addvarianttr"><i class="fa fa-plus"></i></a>
                                                    <a class="col-sm-1 removevarianttr"><i class="fa fa-remove"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-info btn-rounded hide" id="submitadd" style="text-align:center">Add filter group</button>
                    {{--<button class="btn-btn-success" type="submit" id="submitadd">Add filter group</button>--}}


                </form>

            </div>
        </div>
    </div>
@endsection


@section('pagejavascripts')
    {{--<script src="/assets/plugins/select2/js/select2.min.js"></script>--}}
    <script src="/assets/plugins/jstree/jstree.min.js"></script>
    <script src="/assets/js/pages/jstree.js"></script>
    {{--<script src="/assets/plugins/summernote-master/summernote.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
//            $("#select2_sample_modal_5").select2({
//                productcategories: ['2', '3', '5']

//            });

            $(document.body).on("change", 'input:checkbox[name="filtercheckproduct"]', function () {
                var checkedflagproduct = $(this).is(':checked');
//                alert(checkedflagproduct);
            });

            $(document.body).on("change", 'input:checkbox[name="filtercheckcatalog"]', function () {
                var checkedflagcatalog = $(this).is(':checked');
//                alert(checkedflagcatalog);
            });
//            $.validator.addMethod("nameregex", function (value, element) {
//                return this.optional(element) || /^[A-Za-z\-'\s]+$/.test(value);
//            }, "Name can contain only alphabets, white spaces and hyphens.");

            var count = 2;

//            $('#addnewfiltergroupform').validate({
////            ignore: [],
//                rules: {
//                    productfiltergroupname: {
//                        required: true,
//                        nameregex: true,
//                        remote: {
//                            url: "/admin/features-ajax-handler",
//                            type: 'POST',
//                            datatype: 'json',
//                            data: {
//                                method: 'checkForProductcategoryName'
//                            }
//                        }
//                    },
//                    productfiltergroupnamestatus: {
//                        required: true
//                    },
//                    "filter[]": {
//                        nameregex: true
//                    }
//                },
//                messages: {
//                    productfiltergroupname: {
//                        required: "Please enter a name for filter group.",
//                        remote: "Specific filter group already exists."
//                    },
//                    productfiltergroupnamestatus: {
//                        required: "Please set the status."
//                    }
//                }
//            });

            var count = 2;

            $(document.body).on('click', '#addanotherfiltergroupdiv', function (e) {
                e.preventDefault();
                var toAppend = '<div class="form-group">';
                toAppend += '<label for="tag[]" class="col-md-1 control-label">Name:</label>';
                toAppend += '<div class="col-md-2">';
                toAppend += '<input type="text" class="form-control" placeholder="name" name="tag[]" id="taginput' + count + '">';
                toAppend += '</div>';
                toAppend += '<label for="tagdescription[]" class="col-md-1 control-label">Description:</label>';
                toAppend += '<div class="col-md-4">';
                toAppend += '<textarea class="form-control" placeholder="description" name="tagdescription[]" id="tagdescinput' + count + '" style="max-width: 100%; max-height: 150px; min-height: 40px; min-width: 100%;"></textarea>';
                toAppend += '</div>';
                toAppend += '<label for="tagstatus[]" class="col-md-1 control-label">Status:</label>';
                toAppend += '<div class="col-md-2">';
                toAppend += '<select class="form-control" name="tagstatus[]">';
                toAppend += '<option value="0">Inactive</option>';
                toAppend += '<option value="1" selected>Active</option>';
                toAppend += '</select>';
                toAppend += '</div>';
                toAppend += '<div class="col-md-1" id="addanotherfiltergroupdiv">';
                toAppend += '<button class="btn btn-info fa fa-plus" id="addanotherfiltergroupdiv"></button>';
                toAppend += '</div>';
                toAppend += '</div>';
                toAppend += '<div class="clearfix"></div>';

                $('#addanotherfiltergroupdiv').remove();
                $(toAppend).insertBefore($('#appendbeforehere'));

                $("#filterinput1" + count).rules('add', {
                    nameregex: true
                });

                count++;
            });

            setTimeout(function () {
                var catinputdivs = $('.catinputdivs');
                $.each(catinputdivs, function (i, a) {
                    var catid = $(a).attr('data-id');
                    var checkedstring = '';
                    if ($(a).attr('data-checked') == "checked") {
                        checkedstring = "checked";
                    }
                    $(a).html('<input type="checkbox" name="productcategories[' + catid + ']" class="catinput" hidden ' + $(a).attr('data-checked') + '/>');
                });
                var catinputs = $('.catinput');
                $.each(catinputs, function (i, a) {
                    if ($(a).attr('checked') != undefined) {
                        $(a).parent().parent().click();
                    }
                });
            }, 1000);
            $(document.body).on("click", '.jstree-anchor', function () {
//                console.log($(this).find('.catinput'));
                if ($(this).parent().attr('aria-selected') == 'true') {
                    $(this).find('.catinput').attr('checked', true);
                    var catid = $(this).find('.catinput').attr('data-catid');
//                    $(this).find('.catinput').prop('checked', true);
                } else {
                    $(this).find('.catinput').attr('checked', false);
//                    $(this).find('.catinput').prop('checked', false);
                }
//                var checkedflagproduct = $(this).is(':checked');

            });

            var variantCounter = 1;
            $(document.body).on('click', '.addvarianttr', function () {
                toAppend = '<tr>';
                toAppend += '<td>';
                toAppend += '<div class="col-sm-12">';
                toAppend += '<input type="text" class="form-control" name="filter_variant[' + variantCounter + '][name]">';
                toAppend += '</div>';
                toAppend += '</td>';
                toAppend += '<td>';
                toAppend += '<div class="col-sm-12">';
                toAppend += '<input type="text" class="form-control" name="filter_variant[' + variantCounter + '][description]">';
                toAppend += '</div>';
                toAppend += '</td>';
                toAppend += '<td>';
                toAppend += '<a class="col-sm-1 addvarianttr"><i class="fa fa-plus"></i></a>';
                toAppend += '<a class="col-sm-1 removevarianttr"><i class="fa fa-remove"></i></a>';
                toAppend += '</td>';
                toAppend += '</tr>';
                $('#variantTBody').append(toAppend);
                variantCounter++;
            });

            $(document.body).on('click', '.removevarianttr', function () {
                var varianttrs = $('.addvarianttr');
                if (varianttrs.length > 1)
                    $(this).parent().parent().remove();
            });
            $(document.body).on('change', function (e) {
                e.preventDefault();
                var filterType = $('#productfiltertype').val();
                if (filterType !== '') {
                    if (filterType == 'G') {
                        $("#filter-group").removeClass('hide');
                        $("#filter-option").addClass('hide');
                        $("#productFilterGroups").addClass('hide');
//                    $('.productgroup').find('.productvarinat').addClass('hidden');
                    } else {
                        $("#filter-option").removeClass('hide');
                        $("#filter-group").addClass('hide');
                        $("#productFilterGroups").removeClass('hide');
                    }
                    $("#submitadd").removeClass('hide');
                }else {
                    $("#filter-option, #filter-group , #submitadd").addClass('hide');
//                    $("#filter-group").addClass('hide');
                }


            });


//            if ($(".productvarinat").find('.error').text()) {
//                $.each($(".productvarinat").find('.error'), function (index, value) {
//                    if ($(this).text()) {
//                        $(".productvarinat").children('.tab-pane').removeClass('active');
//                        $(this).closest('.tab-pane').addClass('active');
//
//                        $(".nav-tabs").children('li').removeClass('active');
//                        var id = $(this).closest('.tab-pane').attr('id');
//                        if (id == 'tab_1_1') {
//                            $(".nav-tabs").children('li').first().addClass('active');
//                        } else {
//                            $(".nav-tabs").children('li').last().addClass('active');
//                        }
//                        return false;
//                    }
//                });
//            }


        });
    </script>
@endsection