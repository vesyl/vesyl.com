<?php $weightSymbol = getSetting('weight_symbol');
$weightSymbol = $weightSymbol ? $weightSymbol : 'lbs';
$priceSymbol = getSetting('price_symbol');
$priceSymbol = $priceSymbol ? $priceSymbol : '$';
?>
@extends('Admin/Layouts/adminlayout')

@section('title', 'Transaction History') {{--TITLE GOES HERE--}}

@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
    <link href="/assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>

@endsection

@section('content')
    {{--PAGE CONTENT GOES HERE--}}

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-white">
                <div class="panel-body">

                    <div class="portlet-title">
                       Transaction History
                    </div>

                    <table id="transactionstableadmin" class="display table table-striped table-bordered table-hover text-center" cellspacing="0" width="100%">
                        <thead>
                        <tr role="row" class="heading text-center">
                            {{--<th><input type="checkbox" class="group-checkable"></th>--}}
                            <th>Transaction Id</th>
                            <th>Transaction date</th>
                            <th>Product Details</th>
                            <th>Transaction amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr role="row" class="filter">
                            {{--<td></td>--}}
                            <td><input type="text" class="form-control form-filter input-sm" name="order_id"></td>
                            <td>
                                <div class="input-group date form_datetime input-small">
                                    <input id="fromdate" name="date_from" type="text"
                                            class="form-control form-filter input-sm"> <span class="input-group-btn">
                                        <button type="button" class="btn default date-reset"><i
                                                    class="fa fa-times"></i></button>
                                        <button type="button" class="btn default date-set"><i
                                                    class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                                <div class="input-group date form_datetime input-small">
                                    <input id="todate" name="date_to" type="text"
                                            class="form-control form-filter input-sm"> <span class="input-group-btn">
                                        <button type="button" class="btn default date-reset"><i
                                                    class="fa fa-times"></i></button>
                                        <button type="button" class="btn default date-set"><i
                                                    class="fa fa-calendar"></i></button>
                                    </span>
                                </div>

                            </td>
                            <td>
                            </td>
                            <td><input type="text" class="form-control form-filter input-sm"
                                        name="price_from" placeholder="Price From">
                                <input type="text" class="form-control form-filter input-sm"
                                        name="price_to" placeholder="Price To">
                            </td>
                            <td>
                                <select name="order_status" class="form-control form-filter input-small">
                                    <option value="">Select...</option>
                                    <option value="P">Transaction Pending</option>
                                    <option value="S">Success</option>
                                    <option value="F">Failed</option>
                                </select>
                            </td>
                            <td>
                                <div class="margin-bottom-5">
                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                class="fa fa-search"></i> Search
                                    </button>
                                </div>
                                <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Reset
                                </button>
                            </td>
                        </tr>
                        </thead>


                    </table>

                </div>
            </div>
        </div>

    </div>
@endsection


@section('pagejavascripts')

    <script src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="/assets/global/scripts/metronic.js" type="text/javascript"></script>
    {{--<script src="/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>--}}
    {{--<script src="/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>--}}
    {{--<script src="/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>--}}
    <script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
    {{--<script src="/assets/admin/pages/scripts/table-ajax.js"></script>--}}


    {{--<script src="/assets/global/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>--}}
    {{--<script type="text/javascript" src="/assets/global/scripts/datatable.js"></script>--}}

    <script type="text/javascript">
        var AdminOrders = function () {

            var initPickers = function () {
                //init date pickers
//                $('.date-picker').datepicker({
//                    rtl: Metronic.isRTL(),
//                    autoclose: true
//                });
            }

            var handleOrders = function () {
                var grid = new Datatable();

                grid.init({
                    src: $("#transactionstableadmin"),
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
                            "url": "/admin/transaction-datatables-handler?tablename=transactionstableadmin"
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
                 }); */

            }

            return {
                //main function to initiate the module
                init: function () {
//                    initPickers();
                    handleOrders();
//                    alert("Asd");
                }

            };

        }();
        $(document).ready(function () {

            Metronic.init(); // init metronic core components
//            Layout.init(); // init current layout
//            QuickSidebar.init(); // init quick sidebar
//            Demo.init(); // init demo features

            AdminOrders.init();

            $(document.body).on('click', '#change_status', function () {
                //alert("fg");
//                var obj = $(this);
//                $.ajax({
//                    url: '/admin/order-ajax-handler',
////                   type:
//                });

            });

        });

        //        P=pending [in cart],
        //        TS=tx success [if not cod then show cancel button],
        //        TP=tx in process[if COD show cancel button],
        //        TF=tx failed,
        //        S=shipping [show cancel button],
        //        UC=user cancel request [show dispute button],
        //        UCA=user cancel approved,
        //        MC=merchant cancel,
        //        D=delivered [show dispute button, show refund button],
        //        RR=refund request,
        //        RP=refund in process,
        //        RD=refund done [show dispute button]

    </script>
@endsection
