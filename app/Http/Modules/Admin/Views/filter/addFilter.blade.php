@extends('Admin/Layouts/adminlayout')

@section('title', 'Filters') {{--TITLE GOES HERE--}}

@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link href="/assets/plugins/jstree/themes/default/style.min.css" rel="stylesheet" type="text/css"/>

@endsection

@section('content')
    {{--PAGE CONTENT GOES HERE--}}

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="panel info-box panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title">Add new Filter</h4>
                </div>
                <div class="panel-body">
                    {{--@if($errors)--}}
                    {{--{!! print_r($errors->all()) !!} @endif--}}
                    {{--{{var_dump(old('filter_type'))}}--}}
                    <div class="alert
                    @if(session('code')) @if(session('code') == 400) alert-danger @elseif(session('code') == 200) alert-success @else display-hide @endif
                    @else display-hide @endif">
                        <button class="close" data-close="alert"></button>
                        <span>
                            @if(session('code') == 400 || session('code') == 200)
                                <?php echo session('message'); ?>
                            @endif
                        </span>
                    </div>

                    <div class="portlet-title tabbable-line">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_addfilter_general" data-toggle="tab">General</a></li>
                            <li><a href="#tab_addfilter_variants" data-toggle="tab">Variants</a></li>
                        </ul>
                    </div>

                    <form class="form-horizontal" method="post">
                        <div class="form-body">
                            <div class="tab-content">
                                {{--GENERAL DETAILS TAB--}}
                                <div class="tab-pane active" id="tab_addfilter_general">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="filter_name" value="{{old('filter_name')}}">
                                            <span class="error">{!! $errors->first('filter_name') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="full_description" value="{{old('full_description')}}">
                                            <span class="error">{!! $errors->first('full_description') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Filter group</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="parent_id" value="{{old('parent_id')}}">
                                                <option value="">--None--</option>
                                                @foreach($filterGroups['data'] as $keyFG => $valueFG)
                                                    <option value="{{$valueFG['filter_id']}}" @if(old('parent_id') == $valueFG['filter_id']) selected @endif>{{$valueFG['filter_name']}}</option>
                                                @endforeach
                                            </select>
                                            <span class="error">{!! $errors->first('parent_id') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Filter type</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="filter_type" value="{{old('product_filter_type')}}" id="filtertype">
                                                <option value="">--Select--</option>
                                                <optgroup label="Checkbox">
                                                    <option value="0" @if(old('filter_type') != null && (int)old('filter_type') === 0) selected @endif>
                                                        Single
                                                    </option>
                                                    <option value="1" @if(old('filter_type') == 1) selected @endif>
                                                        Multiple
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Select box">
                                                    <option value="2" @if(old('filter_type') == 2) selected @endif>
                                                        Text
                                                    </option>
                                                    <option value="3" @if(old('filter_type') == 3) selected @endif>
                                                        Number
                                                    </option>
                                                    <option value="4" @if(old('filter_type') == 4) selected @endif>
                                                        Brands/Manufacturer
                                                    </option>
                                                </optgroup>
                                            </select>
                                            <span class="error">{!! $errors->first('filter_type') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">For categories</label>
                                        <div class="col-sm-4">

                                            <div id="checkTree">
                                                <ul>
                                                    <li data-jstree='{"opened":true}'>All categories
                                                        <span class="catinputdivs" data-id="0"></span>
                                                        @if(isset($allCategories))
                                                            <?php treeView($allCategories); ?>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>

                                            <?php
                                            function treeView($array, $id = 0)
                                            {
                                            foreach ($array as $keyArray => $valueArray) {
                                            if ($array[$keyArray]->parent_category_id == $id) { ?>
                                            <ul>
                                                <li data-jstree='{"opened":true}'>
                                                    <?php echo $array[$keyArray]->category_name;
                                                    $catId = $array[$keyArray]->category_id; ?>
                                                    <span class="catinputdivs" data-id="<?php echo $array[$keyArray]->category_id; ?>" data-checked="@if(isset(old('for_categories')[$catId]))
                                                            checked
                                                            @endif">
                                            </span>
                                                    <?php treeView($array, $array[$keyArray]->category_id); ?>
                                                </li>
                                            </ul>
                                            <?php }
                                            }
                                            }
                                            ?>

                                            <br>
                                            <span class="error">{!! $errors->first('for_categories') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Display on filters tab</label>
                                        <div class="col-sm-4">
                                            <input type="checkbox" class="form-control" name="display_on_product" @if(old('display_on_product') == "on") checked @endif/>
                                            <span class="error">{!! $errors->first('display_on_product') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Display on catalog</label>
                                        <div class="col-sm-4">
                                            <input type="checkbox" class="form-control" name="display_on_catalog" @if(old('display_on_catalog') == "on") checked @endif/>
                                            <span class="error">{!! $errors->first('display_on_catalog') !!}</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                </div>

                                <div class="tab-pane" id="tab_addfilter_variants">
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

                                            @if(isset(old('filter_variant')['name']))
                                                @foreach(old('filter_variant')['name'] as $keyFV => $valueFV)
                                                    @if($valueFV != '')
                                                        <tr>
                                                            <td>
                                                                <div class="col-sm-12">
                                                                    <input type="text" class="form-control" name="filter_variant[name][{{$keyFV}}]" value="{{$valueFV}}">
                                                                    <span class="error">{!! $errors->first('filter_variant.name.'.$keyFV) !!}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-sm-12">
                                                                    <input type="text" class="form-control" name="filter_variant[description][{{$keyFV}}]" value="{{old('filter_variant')['description'][$keyFV]}}">
                                                                    <span class="error">{!! $errors->first('filter_variant.description.'.$keyFV) !!}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a class="col-sm-1 addvarianttr"><i class="fa fa-plus"></i></a>
                                                                <a class="col-sm-1 removevarianttr"><i class="fa fa-remove"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            @endif
                                            <tr>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" name="filter_variant[name][]">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" name="filter_variant[description][]">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a class="col-sm-1 addvarianttr"><i class="fa fa-plus"></i></a>
                                                    <a class="col-sm-1 removevarianttr"><i class="fa fa-remove"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn blue btn-info" type="submit">Add filter</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejavascripts')
    <script src="/assets/plugins/jstree/jstree.min.js"></script>

    <script>
        {{--PAGE SCRIPTS GO HERE--}}
        $(document).ready(function () {

            $('#checkTree').jstree({
                'core': {
                    'themes': {
                        'responsive': false
                    }
                },
                'types': {
                    'default': {
                        'icon': 'fa fa-folder icon-state-info icon-md'
                    },
                    'file': {
                        'icon': 'fa fa-file icon-state-default icon-md'
                    }
                },
                'plugins': ['types', 'checkbox']
            });

            setTimeout(function () {
                var catinputdivs = $('.catinputdivs');
                $.each(catinputdivs, function (i, a) {
                    var catid = $(a).attr('data-id');
                    var checkedstring = '';
                    if ($(a).attr('data-checked') == "checked") {
                        checkedstring = "checked";
                    }
                    $(a).html('<input type="checkbox" name="for_categories[' + catid + ']" class="catinput" hidden ' + $(a).attr('data-checked') + '/>');
                });
                var catinputs = $('.catinput');
                $.each(catinputs, function (i, a) {
                    if ($(a).attr('checked') != undefined) {
                        $(a).parent().parent().click();
                    }
                });
            }, 1000);


            $(document.body).on("click", '.jstree-anchor', function () {
                var thisObj = $(this);
                console.log($(thisObj).find('.catinput'));
                if ($(thisObj).parent().attr('aria-selected') == 'true') {
                    $(thisObj).find('.catinput').attr('checked', true);
                    //                    $(this).find('.catinput').prop('checked', true);
                } else {
                    $(thisObj).find('.catinput').attr('checked', false);
                    //                    $(this).find('.catinput').prop('checked', false);
                }
            });

            $(document.body).on('click', '.addvarianttr', function () {
                toAppend = '<tr>';
                toAppend += '<td>';
                toAppend += '<div class="col-sm-12">';
                toAppend += '<input type="text" class="form-control" name="filter_variant[name][]">';
                toAppend += '</div>';
                toAppend += '</td>';
                toAppend += '<td>';
                toAppend += '<div class="col-sm-12">';
                toAppend += '<input type="text" class="form-control" name="filter_variant[description][]">';
                toAppend += '</div>';
                toAppend += '</td>';
                toAppend += '<td>';
                toAppend += '<a class="col-sm-1 addvarianttr"><i class="fa fa-plus"></i></a>';
                toAppend += '<a class="col-sm-1 removevarianttr"><i class="fa fa-remove"></i></a>';
                toAppend += '</td>';
                toAppend += '</tr>';
                $('#variantTBody').append(toAppend);
            });

            $(document.body).on('click', '.removevarianttr', function () {
                var varianttrs = $('.addvarianttr');
                if (varianttrs.length > 1)
                    $(this).parent().parent().remove();
            });

            $(document.body).on('change', '#filtertype', function () {
                if ($(this).val() == 0) {
                    $('.nav-tabs').find('a[href="#tab_addfilter_variants"]').addClass('hidden');
                } else {
                    $('.nav-tabs').find('a[href="#tab_addfilter_variants"]').removeClass('hidden');
                }
            });

        });
    </script>
@endsection
