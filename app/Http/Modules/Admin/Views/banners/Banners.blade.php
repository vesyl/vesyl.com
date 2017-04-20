@extends('Admin/Layouts/adminlayout')

@section('title', 'New Product') {{--TITLE GOES HERE--}}

@section('headcontent')

    <style>
        #type1 {
            display: none;
        }

        #type2 {
            display: none;
        }

        #type3 {
            display: none;
        }

        #addType {
            display: none;
        }
        #checkTree {
            display: none;
        }
        .homeclass {
            display: none;
        }
        .Campaignclass {
            display: none;
        }

    </style>
    <link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/jstree/themes/default/style.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-white">
                <div class="panel-body">


                    <form class="form-horizontal" method="post" enctype="multipart/form-data" autocomplete="on"
                            id="addProductForm">
                        <select id="selectType" name="selectType">
                            <option value="">select type</option>
                            <option value="type1">type1</option>
                            <option value="type2">type2</option>
                            <option value="type3">type3</option>
                        </select>
                        <span class="error"
                                value="{!! $errors->first('selectType') !!}">{!! $errors->first('selectType') !!}</span>


                        <div id="type1" class="common">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Banner header: <input type="text" class="form-control"
                                            name="banner_data[type1][banner_headertype1]"
                                            value="">
                                </div>
                                <span class="error errort11"
                                        value="{!! $errors->first('banner_headertype1') !!}">{!! $errors->first('banner_headertype1') !!}</span>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Banner caption: <input type="text" class="form-control"
                                            name="banner_data[type1][banner_captiontype1]"
                                            value="">
                                </div>
                                <span class="error errort12"
                                        value="{!! $errors->first('banner_captiontype1') !!}">{!! $errors->first('banner_captiontype1') !!}</span>
                            </div>

                            <div class="form-group last">
                                {{--<h4 class="note note-info note-bordered"--}}
                                {{--style="text-align: center">--}}
                                {{--Other Images</h4>--}}
                                <div class="fileinput fileinput-new"
                                        data-provides="fileinput">
                                    <div class="fileinput-new thumbnail"
                                            style="width: 200px; height: 150px;">
                                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                            style="max-width: 200px; max-height: 150px;">
                                    </div>
                                    <div>
                                        <span class="btn default btn-info btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input id="main-image" type="file"
                                                    name="banner_data[type1][mainimagetype1]"> </span>
                                        <a href="#"
                                                class="btn default btn-danger fileinput-exists"
                                                data-dismiss="fileinput"> Remove </a>
                                    </div>
                                    <span class="error errort13"
                                            value="{!! $errors->first('mainimagetype1') !!}">{!! $errors->first('mainimagetype1') !!}</span>
                                </div>
                            </div>
                        </div>

                        <div id="type2" class="common">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Banner header: <input type="text" class="form-control"
                                            name="banner_data[type2][banner_headertype2]"
                                            value="">
                                </div>
                                <span class="error errort21"
                                        value="{!! $errors->first('banner_headertype2') !!}">{!! $errors->first('banner_headertype2') !!}</span>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Banner caption: <input type="text" class="form-control"
                                            name="banner_data[type2][banner_captiontype2]"
                                            value="">
                                </div>
                                <span class="error errort22"
                                        value="{!! $errors->first('banner_captiontype2') !!}">{!! $errors->first('banner_captiontype2') !!}</span>
                            </div>

                            <div class="fileinput fileinput-new"
                                    data-provides="fileinput">
                                <div class="fileinput-new thumbnail"
                                        style="width: 200px; height: 150px;">
                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                            alt=""/>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                        style="max-width: 200px; max-height: 150px;">
                                </div>
                                <div>
                                    <span class="btn default btn-info btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input id="main-image" type="file"
                                                name="banner_data[type2][mainimagetype2]"> </span>
                                    <a href="#"
                                            class="btn default btn-danger fileinput-exists"
                                            data-dismiss="fileinput"> Remove </a>
                                </div>
                                <span class="error errort23"
                                        value="{!! $errors->first('mainimagetype2') !!}">{!! $errors->first('mainimagetype2') !!}</span>

                                <div class="form-group homeclass">
                                    <input type="checkbox" name="banner_data[type2][for_home]" id="home">home</input>
                                    <span class="error errort24"
                                            value="{!! $errors->first('for_home') !!}">{!! $errors->first('for_home') !!}</span>
                                </div>

                                <div class="form-group Campaignclass">
                                    <input type="checkbox" id="Campaign">Category</input>
                                    <span class="error errort25"
                                            value="{!! $errors->first('for_categories') !!}">{!! $errors->first('for_categories') !!}</span>

                                </div>

                                <div class="form-group">
                                    {{--<label class="col-sm-2 control-label">Campaign</label>--}}
                                    {{--<div class="col-sm-4">--}}

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
                                    {{--<span class="error">{!! $errors->first('for_categories') !!}</span>--}}
                                </div>

                            </div>
                            {{--<div class="form-group last">--}}
                            {{--<h4 class="note note-info note-bordered"--}}
                            {{--style="text-align: center">--}}
                            {{--Other Images</h4>--}}
                            {{--<div class="col-md-12">--}}
                            {{--<div class="fileinput fileinput-new"--}}
                            {{--data-provides="fileinput"--}}
                            {{--id="otherimagesdiv">--}}
                            {{--<div class="fileinput-new thumbnail"--}}
                            {{--style="width: 200px; height: 150px;">--}}
                            {{--<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"--}}
                            {{--alt=""/>--}}
                            {{--</div>--}}

                            {{--</div>--}}
                            {{--<div>--}}
                            {{--<span class="btn default btn-file btn-info">--}}
                            {{--<span class="fileinput-new" id="otherimageselect"> Select images </span>--}}
                            {{--<span class="fileinput-exists hidden" id="otherimagechange"> Change </span>--}}
                            {{--<input type="file" class="commonpics" name="product_data[otherimages][]" accept="image/*"--}}
                            {{--id="otherimages2">--}}
                            {{--</span>--}}
                            {{--<a class="btn default fileinput-exists btn-danger hidden"--}}
                            {{--data-dismiss="fileinput"--}}
                            {{--id="otherimagesremove"> Remove All</a>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                        </div>

                        <div id="type3" class="common">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Banner header: <input type="text" class="form-control"
                                            name="banner_data[type3][banner_headertype3]"
                                            value="">
                                </div>
                                <span class="error errort31"
                                        value="{!! $errors->first('banner_headertype3') !!}">{!! $errors->first('banner_headertype3') !!}</span>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Banner caption: <input type="text" class="form-control"
                                            name="banner_data[type3][banner_captiontype3]"
                                            value="">
                                </div>
                                <span class="error errort32"
                                        value="{!! $errors->first('banner_captiontype3') !!}">{!! $errors->first('banner_captiontype3') !!}</span>
                            </div>

                            <div class="form-group last">
                                {{--<h4 class="note note-info note-bordered"--}}
                                {{--style="text-align: center">--}}
                                {{--Other Images</h4>--}}
                                <div class="col-md-12">
                                    <div class="fileinput fileinput-new"
                                            data-provides="fileinput"
                                            id="otherimagesdiv">
                                        <div class="fileinput-new thumbnail"
                                                style="width: 200px; height: 150px;">
                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                    alt=""/>
                                        </div>

                                    </div>
                                    <div>
                                        <span class="btn default btn-file btn-info">
                                            <span class="fileinput-new" id="otherimageselect"> Select images </span>
                                            <span class="fileinput-exists hidden" id="otherimagechange"> Change </span>
                                            <input type="file" name="banner_data[type3][mainimagetype3]" multiple=""
                                                    accept="image/*"
                                                    id="otherimages">
                                        </span>
                                        <a class="btn default fileinput-exists btn-danger hidden"
                                                data-dismiss="fileinput"
                                                id="otherimagesremove"> Remove All</a>
                                    </div>
                                    <span class="error errort33"
                                            value="{!! $errors->first('mainimagetype3') !!}">{!! $errors->first('mainimagetype3') !!}</span>
                                </div>
                            </div>



                        </div>
                        <div>
                            <input type="hidden"  id="activeBanner"/>
                        </div>


                        <button type="submit" id="addType">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("pagejavascripts")
    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
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
                    $(a).html('<input type="checkbox" name="banner_data[type2][for_categories][' + catid + ']" class="catinput" hidden ' + $(a).attr('data-checked') + '/>');
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
                toAppend += '<input type="text" class="form-control" name="feature_variant[name][]">';
                toAppend += '</div>';
                toAppend += '</td>';
                toAppend += '<td>';
                toAppend += '<div class="col-sm-12">';
                toAppend += '<input type="text" class="form-control" name="feature_variant[description][]">';
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

            $(document.body).on('change', '#featuretype', function () {
                if ($(this).val() == 0) {
                    $('.nav-tabs').find('a[href="#tab_addfeature_variants"]').addClass('hidden');
                } else {
                    $('.nav-tabs').find('a[href="#tab_addfeature_variants"]').removeClass('hidden');
                }
            });

        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {

            $(document.body).on('change', '#otherimages', function (e) {//NEED TO ADD VALIDATION FOR LIMIT OF FILES TO BE ALLOWED FOR UPLOADING
                e.preventDefault();
                var obj = $(this);
                var imagecount = 0;
                var files1 = e.target.files === undefined ? (e.target && e.target.value ? [{name: e.target.value.replace(/^.+\\/, '')}] : []) : e.target.files;
                if (files1.length > 0) {
                    if (files1.length <= 5) {
                        var el = '';
                        var flag = false;
                        $('#otherimagesdiv').html('');
                        $.each(files1, function (i, a) {
                            var file = a;
                            el = '<div class="fileinput-preview fileinput-exists thumbnail otherimagesdivs" style="width: 200px; height: 150px;" id="otherimagepreviewdiv' + imagecount + '">';
                            el += '</div>';
                            var img = document.createElement("img");
                            if ((typeof file.type !== "undefined" ? file.type.match(/^image\/(gif|png|jpeg)$/) : file.name.match(/\.(gif|png|jpe?g)$/i)) && typeof FileReader !== "undefined") {
                                if (i == 0) {
                                    flag = true;
                                }
                                var reader = new FileReader();
                                $('#otherimagesdiv').append(el);
                                reader.onload = function (re) {
//                                console.log('1' + re.target.result);
                                    img.src = re.target.result;
                                }
                                reader.readAsDataURL(file);
                                $('#otherimagepreviewdiv' + imagecount).html(img);
                                flag = flag && true;
                            }
                            imagecount++;
                        });
                        if (flag) {
                            $('#otherimagesdiv').removeClass('fileinput-new');
                            $('#otherimagesdiv').addClass('fileinput-exists');
                            $('#otherimagesremove').removeClass('hidden');
                            $('#otherimagechange').removeClass('hidden');
                            $('#otherimageselect').addClass('hidden');
                        } else {
                            //CODE TO SHOW ERROR MESSAGE
                        }
                    } else {
                        toastr['error']("You can't upload more than 5 images.");
                    }

                } else {
                    var toAppend = '<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">';
                    toAppend += '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>';
                    toAppend += '</div>';
                    $('#otherimagesdiv').html(toAppend);
                    $('#otherimagechange').addClass('hidden');
                    $('#otherimageselect').removeClass('hidden');
                    $('#otherimagesdiv').removeClass('fileinput-exists');
                    $('#otherimagesdiv').addClass('fileinput-new');
                    $('#otherimages').val('');
                }
            });

            $(document.body).on('click', '#otherimagesremove', function (e) {
                var toAppend = '<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">';
                toAppend += '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>';
                toAppend += '</div>';
                $('#otherimagesdiv').html(toAppend);
                $('#otherimagesdiv').removeClass('fileinput-exists');
                $('#otherimagesdiv').addClass('fileinput-new');
                $('#otherimagechange').addClass('hidden');
                $('#otherimageselect').removeClass('hidden');
                $('#otherimagesremove').addClass('hidden');
                $('#otherimages').val('');
            });

        });
    </script>



    <script>
        $(document).ready(function () {

            $('#selectType').on('change', function () {

                $("#addType").css('display', 'block');
//                $("#selectType").css('display', 'none');

                if (this.value == "type1") {
                    $(".common").css('display', 'none');
                    $("#type1").css('display', 'block');



                }
                else if (this.value == "type2") {
                    $(".common").css('display', 'none');
                    $("#type2").css('display', 'block');
                    $(".homeclass").css('display', 'block');
                    $(".Campaignclass").css('display', 'block');


                }
                else if (this.value == "type3") {
                    $(".common").css('display', 'none');
                    $("#type3").css('display', 'block');


                }
                else {
                    console.log("nothing is selected");
                }
            })

        })

    </script>

    <script>

        $(document).ready(function () {

            $('#Campaign').click(function() {
                if ($(this).is(':checked')) {
                    $("#checkTree").css('display', 'block');

                }
                else
                {
                    $("#checkTree").css('display', 'none');
                }
            });
        })

        </script>

    <script>

        $(document).ready(function () {
            $("#home").click(function () {
                if ($(this).is(':checked')) {

                    window.confirm("home banner is already set,do you want to deactivate previous banner");
//                    $("#activeBanner").html('<input type="text" name="banner_data[type2][for_home]" value="active"/>');


                }

            })

        })

        </script>

    <script>

        $(document).ready(function () {

            var flag ="";
            var items = {
             t11 : $(".errort11").attr("value"),
             t12 : $(".errort12").attr("value"),
             t13 : $(".errort13").attr("value"),
             t21 : $(".errort21").attr("value"),
             t22 : $(".errort22").attr("value"),
             t23 : $(".errort23").attr("value"),
             t24 : $(".errort24").attr("value"),
             t25 : $(".errort25").attr("value"),
             t31 : $(".errort31").attr("value"),
             t32 : $(".errort32").attr("value"),
             t33 : $(".errort33").attr("value")
        }



            $.each(items, function( index, value ) {

                if(value.length > 0 )
                {
                    flag = index;

                }
            });


            if(flag !="") {
                var type = flag.substring(0, 2);


                if(type == "t1"){
                    $(".common").css('display', 'none');
                    $("#type1").css('display', 'block');
                }
                if(type == "t2"){

                    $(".common").css('display', 'none');
                    $("#type2").css('display', 'block');
                    $(".homeclass").css('display', 'block');
                    $(".Campaignclass").css('display', 'block');

                }
                if(type == "t3")
                {
                    $(".common").css('display', 'none');
                    $("#type3").css('display', 'block');

                }
            }

        })


        </script>


    {{--<script>--}}

        {{--$(document).ready(function () {--}}

            {{--var a = $(".error").attr("value");--}}
            {{--alert(a);--}}

        {{--})--}}


    {{--</script>--}}

@endsection