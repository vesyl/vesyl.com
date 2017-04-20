@extends('Buyer/Layouts/buyerlayout')

@section('pageheadcontent')
    <style>
        .modal-content.checkout {
            background-color: #fff;
            color: white;
            outline: 0 none;
            position: relative;
            border: 0px solid white;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
    <link href="/assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/frontend/pages/css/style-shop.css" rel="stylesheet" type="text/css">
    {{--<link href="/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">--}}

@endsection

@section('title', 'My orders')

@section('content')
    <div class="panel info-box panel-white">
        <div class="panel-body">

            <!-- BEGIN SIDEBAR & CONTENT -->
            <div class="row margin-bottom-40">
                <!-- BEGIN CONTENT -->
                <div class="col-md-12 col-sm-12">
                    {{--<h1>My orders</h1>--}}
                    <div class="goods-page">
                        <div class="goods-data clearfix">
                            <div class="table-wrapper-responsive">
                                <table summary="Shopping cart" id="orderstablebuyer" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="goods-page-ref-no">Ref No</th>
                                        <th class="goods-page-image">Image</th>
                                        <th class="goods-page-description">Product details</th>
                                        <th class="goods-page-price">Order date</th>
                                        <th class="goods-page-total">Amount</th><!-- colspan="2"-->
                                        <th class="goods-page-quantity">Status</th>
                                        <th class="goods-page-quantity">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END SIDEBAR & CONTENT -->
        </div>
    </div>
@endsection
@section('pagejavascripts')
    <script type="text/javascript"
            src="/assets/scripts/jquery.mThumbnailScroller.js"></script>
    <script src="/assets/scripts/jquery-ui.js" type="text/javascript"></script>

    <script src="/assets/plugins/jquery-blockui/jquery.blockui.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>
    {{--<script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>--}}
    <script src="/assets/global/scripts/metronic.js" type="text/javascript"></script>
    {{--<script src="/assets/global/scripts/metronicmini.js" type="text/javascript"></script>--}}
    <script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>

    <script type="text/javascript">
        var UserOrders = function () {

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
                    src: $("#orderstablebuyer"),
                    onSuccess: function (grid) {
                        // execute some code after table records loaded
                        console.log("table loaded successfully");
                    },
                    onError: function (grid) {
                        // execute some code on network or other general error
                        console.log("error loading table");
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
                            "url": "/orders/orders-datatables-handler?tablename=orderstablebuyer"
                        },
                        "order": [
                            [0, "asc"]
                        ], // set first column as a default sort by asc
                        "columnDefs": [{// define columns sorting options(by default all columns are sortable extept the first checkbox column)
                            'orderable': false,
                            'targets': [0, 1, 2, 3, 4, 5, 6]
                        }]
                    }
                });

                // handle group actionsubmit button click
                /*
                 grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
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
                 });
                 */

            }

            return {
                //main function to initiate the module
                init: function () {
                    initPickers();
                    handleOrders();

                }

            };

        }();
        $(document).ready(function () {

            UserOrders.init();

            $(document.body).on('click', '#change_location', function () {
                var obj = $(this);
                var userId = "<?php echo Session::get('fs_user')['id'];?>"
//                $.ajax({
//                    url: '/profile-ajax-handler',
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