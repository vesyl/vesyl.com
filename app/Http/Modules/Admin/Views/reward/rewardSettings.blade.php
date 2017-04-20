@extends('Admin/Layouts/adminlayout')

@section('title', 'Reward Settings')

@section('headcontent')
    {{--<link rel="stylesheet" type="text/css" href="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>--}}
    <link href="/assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <style>
        /* todo Uncomment this block for batch operations style
        .selected {
            background-color: #14B9D6;
        }*/
        th, td:not(:nth-child(2)) {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">

                <div class="panel-heading clearfix">
                    <h2 class="panel-title"><span class="lead bold">Settings</span></h2>
                </div>
                <div class="panel-body">

                    @if(Session::get('code'))
                        <div class="alert {{Session::get('code') != 200 ? 'alert-danger' : 'alert-success'}}">
                            {{Session::get('message')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form class="form-horizontal form-bordered" id="rsform" enctype="multipart/form-data" method="post">
                            <div class="col-md-offset-2 col-lg-offset-2 col-sm-offset-2 col-md-2 col-lg-2 col-sm-3 col-xs-12">
                                <!--<span class="">Status</span>-->
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12">
                                {{--<span class=""></br> for--}}
                                {{--@if ($currencies[0] != '')--}}
                                {{--{{$currencies[0]['currency_code']}}--}}
                                {{--@else--}}
                                {{--USD--}}
                                {{--@endif --}}
                                {{--users--}}
                                {{--</span>--}}
                            </div>
                            {{--@if ($currencies[1] != '')--}}
                            {{--<div class="col-md-1 col-lg-1 col-sm-2 col-xs-12">--}}
                            {{--<span class=""> for {{$currencies[1]['currency_code']}}users</span>--}}
                            {{--</div>--}}
                            {{--@endif--}}
                            {{--@if ($currencies[2] != '')--}}
                            {{--<div class="col-md-1 col-lg-1 col-sm-2 col-xs-12">--}}
                            {{--<span class=""> for {{$currencies[2]['currency_code']}} users</span>--}}
                            {{--</div>--}}
                            {{--@endif--}}
                            <div class="clearfix"></div>
                            </br>
                            @foreach($rewardSettings as $value)
                                <?php $label = explode(', ', $value['rs_name']); ?>
                                <div class="form-group" @if($label[0] == "Currency") style="border-bottom: solid; border-bottom-width: 1px; border-bottom-color: rgba(0,0,0,0.2); padding-bottom: 10px;" @endif>
                                    <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">{{$label[0]}}
                                        :</label>
                                    @if($label[0] != "Currency")
                                        <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12" style="display: inline-block;">
                                            <input type="checkbox" class="form-control checkboxes" name="status[{{$value['rs_id']}}]" @if($value['rs_status'] == 'A') checked @endif data-on-label="On" data-off-label="Off" data-id="{{$value['rs_id']}}"/>
                                        </div>
                                    @else
                                        <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12" style="display: inline-block">
                                            <h4> 1 USD equals to</h4>
                                        </div>
                                    @endif
                                    @if($label[0] != "Cart total")
                                        <div class="col-md-2 col-lg-2">
                                            <input class="form-control pointsbox" type="tel" autocomplete="off" placeholder="{{$label[0]}} points" value='{{$value['rs_points']}}' name="value[{{$value['rs_id']}}]" @if($value['rs_status'] == 'I') disabled @endif id="{{$value['rs_id']}}"/>
                                        </div>
                                        {{--@if($currencies[1] != '')--}}
                                        {{--<div class="col-md-1 col-lg-1">--}}
                                        {{--<label>{{$value['rs_points'] * $currencies[1]['value']}}</label>--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--@if($currencies[2] != '')--}}
                                        {{--<div class="col-md-1 col-lg-1">--}}
                                        {{--<label>{{$value['rs_points'] * $currencies[2]['value']}}</label>--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        <div class="col-md-1 col-lg-1">
                                            {{--<label>{{$label[1]}}</label>--}}
                                        </div>
                                    @else
                                        <div class="col-md-5 col-lg-5">
                                        </div>
                                    @endif
                                    <div class="col-md-3 col-lg-3">
                                        <span class="tooltips" data-placement="right" title="{{$value['rs_tooltip']}}" style="color: blue; margin-right: 20%;"><i class="fa fa-question-circle"></i></span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            @endforeach
                            <br>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-2 col-lg-2">
                                        <button type="submit" class="btn green"><i class="fa fa-check"></i>Save settings
                                        </button>
                                    </div>
                                    <div class="col-md-5 col-lg-5">
                                        @if(isset($updatedMsg)) {{$updatedMsg}} @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="panel panel-white">

                <div class="panel-heading clearfix">
                    <h2 class="panel-title lead"><span class="lead bold"> Product reward settings</span></h2>
                </div>
                <div class="panel-body">

                    <div class="col-md-12">

                        <div>

                        </div>
                        <div class="popover fade top" id="mypopover">
                            <div class="arrow"></div>
                            <h3 class="popover-title" style="color: red">Please enter a valid number..</h3>
                        </div>

                        <table class="dynamicTable table table-bordered" id="productrewards">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product</th>
                                {{--<th>Price</th>--}}
                                {{--<th>Quantity</th>--}}
                                <th>Reward points</th>
                                <th>Edit</th>
                                <th>Product Status</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('pagejavascripts')

    <script src="/assets/plugins/bootstrap-checkbox/js/bootstrap-checkbox.js" type="text/javascript"></script>

    <script src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>

    <script src="/assets/global/plugins/datatables/tabletools/js/dataTables.tableTools.min.js" type="text/javascript"></script>


    <script>
        $(document).ready(function () {
            $('.checkboxes').checkboxpicker();

            var table = $('#productrewards');

            /* DATATABLES NEW START
             var AdminProductsForRewards = function () {

             var handleOrders = function () {
             var grid = new Datatable();

             oTable = grid.init({
             src: $("#productrewards"),
             onSuccess: function (grid) {
             console.log("success");
             // execute some code after table records loaded
             },
             onError: function (grid) {
             console.log("error");
             // execute some code on network or other general error
             },
             loadingMessage: 'Loading...',
             dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options
             // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
             // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
             // So when dropdowns used the scrollable div should be removed.
             //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

             "lengthMenu": [
             [10, 20, 50, 100, 150, -1],
             [10, 20, 50, 100, 150, "All"] // change per page values here
             ],
             "pageLength": 10, // default record count per page
             "ajax": {
             "url": "/admin/product-datatables-handler?tablename=productstableadminrewards"
             },
             "order": [
             [0, "asc"]
             ], // set first column as a default sort by asc
             "columnDefs": [{// define columns sorting options(by default all columns are sortable extept the first checkbox column)
             'orderable': false,
             //                            'targets': [0, 6, 7]
             }]
             }
             });
             // handle group actionsubmit button click
             /* grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
             e.preventDefault();
             var action = $(".table-group-action-input", grid.getTableWrapper());
             if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
             grid.setAjaxParam("customActionType", "group_action");
             grid.setAjaxParam("customActionValue", action.val());
             grid.setAjaxParam("productId", grid.getSelectedRows());
             grid.getDataTable().ajax.reload();
             grid.clearAjaxParams();
             } else if (action.val() == "") {
             Metronic.alert({
             type: 'danger',
             icon: 'warning',
             message: 'Please select an action',
             container: grid.getTableWrapper(),
             place: 'prepend'
             });
             alert("Asd");
             } else if (grid.getSelectedRowsCount() === 0) {
             Metronic.alert({
             type: 'danger',
             icon: 'warning',
             message: 'No record selected',
             container: grid.getTableWrapper(),
             place: 'prepend'
             });
             alert("Asd");

             }
             }); *

             }

             return {
             //main function to initiate the module
             init: function () {
             //                    initPickers();
             handleOrders();
             }

             };

             }();
             //            AdminProductsForRewards.init();
             DATATABLES NEW END */

            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
//                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
//                    oTable.fnUpdate(aData[i], nRow, i, false);
//                }
//                oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[0].innerHTML = aData[0];
                jqTds[1].innerHTML = aData[1];
                jqTds[2].innerHTML = '<input type="text" class="input-small form-control" value="' + aData[2] + '" autofocus style="background-color: ThreeDFace;">';
                jqTds[3].innerHTML = '<a class="btn btn-sm btn-success edit" style="margin-left: 5%;"><i class="fa fa-check"></i></a>' + '<a class="btn btn-sm btn-warning cancel" href="" style="margin-left: 10%;"><i class="fa fa-reply"></i></a>';
                jqTds[4].innerHTML = aData[4];
                $('.input-small').focus();
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                var productId = $(nRow).find('.sorting_1').html();//(oTable.fnGetData(nRow))[4];
                var rewardPoints = jqInputs[0].value;
                $.ajax({
                    url: '/admin/rewardsettings-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'updateRewardPoints',
                        productId: productId,
                        rewardPoints: rewardPoints
                    },
                    success: function (response) {
                        oTable.fnUpdate(jqInputs[0].value, nRow, 2, false);
                        oTable.fnUpdate('<a class="btn btn-default btn-sm edit" style="margin-left: 5%;"><i class="fa fa-pencil"></i></a>', nRow, 3, false);
                        oTable.fnDraw();
                        nEditing = null;
                        var responseMsg = "Something went wrong. Please try again";
                        var responseType = 'danger';
                        try {
                            response = $.parseJSON(response);
                            responseType = response['code'] == 200 ? 'success' : 'warning';
                            responseMsg = response['message'];
                        } catch (e) {
                            //just to avoid stopping of execution due to exception
                        }
                        Metronic.alert({
                            type: responseType,
                            message: responseMsg,
                            container: $('#productrewards_wrapper'),
                            place: 'prepend'
                        });
                    },
                    error: function (response) {
                        oTable.fnDraw();
                        Metronic.alert({
                            type: 'danger',
                            message: "Something went wrong. Please try again",
                            container: $('#productrewards_wrapper'),
                            place: 'prepend'
                        });
                    }
                });
            }

            var tableWrapper = $('.dataTables_wrapper');
            var tableContainer = $(".table-container");
            var countSelectedRecords = function () {
                var selected = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).size();
                var text = "Asd";//tableOptions.dataTable.language.metronicGroupActions;
                if (selected > 0) {
                    $('.table-group-actions > span', tableWrapper).text(text.replace("_TOTAL_", selected));
                } else {
                    $('.table-group-actions > span', tableWrapper).text("");
                }
            };

            var selectedproducts = [];
            var tableInitialized = false;

            var oTable = table.dataTable({
                "lengthMenu": [
                    [10, 20, 100, -1],
                    [10, 20, 100, "All"]
                ],
                "pageLength": 10,
                "language": {
                    "lengthMenu": " _MENU_ records"
                },
                "columnDefs": [{
                    "targets": [3, 4],
                    "orderable": false
                }],
                "bAutoWidth": false,
                "rowCallback": function (row, data) {
                    if ($.inArray(data.DT_RowId, selectedproducts) !== -1) {
                        $(row).addClass('selected');
                    }
                },
                tableTools: {
                    "sRowSelect": "multi",
                    "aButtons": ["select_all", "select_none"]
                },
                "serverSide": true, // enable/disable server side ajax loading

                "ajax": { // define ajax settings
                    "url": "/admin/product-datatables-handler?tablename=productstableadminrewards", // ajax URL
                    "type": "POST", // request type
                    "timeout": 20000,
                    "data": function (data) { // add request parameters before submit
                        Metronic.blockUI({
                            message: "Please wait. Loading... ",
                            target: tableContainer,
                            overlayColor: 'none',
                            cenrerY: true,
                            boxed: true
                        });
                        return data;
                    },
                    "dataSrc": function (res) { // Manipulate the data returned from the server
//                        if ($('.group-checkable', table).size() === 1) {
//                            $('.group-checkable', table).attr("checked", false);
//                            $.uniform.update($('.group-checkable', table));
//                        }todo batch update reward points
                        return res.data;
                    },
                    "error": function () { // handle general connection errors
                        Metronic.alert({
                            type: 'danger',
                            icon: 'warning',
                            message: "Could not load products. Please reload the page.",
                            container: tableWrapper,
                            place: 'prepend'
                        });
                        Metronic.unblockUI(tableContainer);
                    }

                },
                "drawCallback": function (oSettings) { // run some code on table redraw
                    if (tableInitialized === false) { // check if table has been initialized
                        tableInitialized = true; // set table initialized
                        table.show(); // display table
                    }
                    Metronic.initUniform($('input[type="checkbox"]', table)); // reinitialize uniform checkboxes on each table reload
                    countSelectedRecords(); // reset selected records indicator
                }
            });

            $('#productrewards tbody').on('click', 'tr', function () {
                var id = this.id;
                var index = $.inArray(id, selectedproducts);
                if (index === -1) {
                    selectedproducts.push(id);
                } else {
                    selectedproducts.splice(index, 1);
                }
                $(this).toggleClass('selected');
                if (selectedproducts == '') {
                    $('#deselectallproductsB').prop("disabled", true);
                } else {
                    $('#deselectallproductsB').prop("disabled", false);
                }
                $('#selectallproductsB').prop("disabled", false);
            });

            $(document).on('click', '#deselectallproductsB', function (e) {
                e.preventDefault();
                $('#productrewards tbody tr').removeClass('selected');
                while (selectedproducts.length > 0) {
                    selectedproducts.pop();
                }
                $('#deselectallproductsB').prop("disabled", true);
                $('#selectallproductsB').prop("disabled", false);
            });

            $(document).on('click', '#selectallproductsB', function (e) {
                e.preventDefault();
                var obj = $('#productrewards tbody tr');
                while (selectedproducts.length > 0) {
                    selectedproducts.pop();
                }
                obj.addClass('selected');
                obj.each(function () {
                    selectedproducts.push(this.id);
                });
                $('#deselectallproductsB').prop("disabled", false);
                $('#selectallproductsB').prop("disabled", true);
            });

            $(document).on('click', '#batchsetrewardsB', function (e) {
                e.preventDefault();
                var batchRewardPtsVal = $('#batchrewardsI').val();
                var numregex = /^[0-9]+$/;
                var valid1 = false;
                var valid2 = false;
                if (batchRewardPtsVal != '' && numregex.test(batchRewardPtsVal)) {
                    valid1 = true;
                } else {
                    $('.popover-title').html('Please enter a valid number.');
                    $('#mypopover').addClass('in');
                    $('#mypopover').css('display', 'block');
                    $('#mypopover').css('top', '-40px');
                    $('#mypopover').css('left', '628px');
                }
                if (selectedproducts != '') {
                    valid2 = true;
                } else {
                    $('.popover-title').html('Please select atleast one product.');
                    $('#mypopover').addClass('in');
                    $('#mypopover').css('display', 'block');
                    $('#mypopover').css('top', '-40px');
                    $('#mypopover').css('left', '628px');
                }
                if (valid1 && valid2) {
                    $('#mypopover').removeClass('in');
                    $('#mypopover').removeAttr('style');
                    $.ajax({
                        url: '/admin/rewards-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'setBatchProductsRewardPoints',
                            selectedproducts: selectedproducts,
                            batchRewardPtsVal: batchRewardPtsVal
                        },
                        success: function (response) {
                            window.location.reload();
                        }
                    });
                }
            });


            var nEditing = null;
            table.on('click', '.cancel', function (e) {
                e.preventDefault();
                $('#productrewards tbody tr').removeClass('selected');
                while (selectedproducts.length > 0) {
                    selectedproducts.pop();
                }
                $('#deselectallproductsB').prop("disabled", true);
                $('#selectallproductsB').prop("disabled", false);
                restoreRow(oTable, nEditing);
                oTable.fnDraw();
                nEditing = null;
            });

            table.on('click', '.edit', function (e) {
                e.preventDefault();
                $('#productrewards tbody tr').removeClass('selected');
                while (selectedproducts.length > 0) {
                    selectedproducts.pop();
                }
                $('#deselectallproductsB').prop("disabled", true);
                $('#selectallproductsB').prop("disabled", false);
                var nRow = $(this).parents('tr')[0];
                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == '<i class="fa fa-check"></i>') {
                    /* Editing this row and want to save it */
                    var jqInputs = $('input', nRow);
                    var rewardPoints = jqInputs[0].value;
                    var numregex = /^[0-9\s]+$/;
                    if (numregex.test(rewardPoints)) {
                        saveRow(oTable, nEditing);
                    }
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });

//            oTable.fnSetColumnVis(6, false);

            $(document).on('change', '.checkboxes', function (e) {
                var thisobj = $(this).attr('data-id');
                if ($(this).is(":checked")) {
                    $('#' + thisobj).prop('disabled', false);
                } else {
                    $('#' + thisobj).prop('disabled', true);
                }
            });

        });


    </script>
@endsection
