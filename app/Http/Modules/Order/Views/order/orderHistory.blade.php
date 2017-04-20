@extends('Home/Layouts/home_layout')
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

@section('content')

    {{--<div class="container">--}}
    {{--<div class="row">--}}
    {{--<h3><b style="margin-left: 15px;">My orders</b></h3>--}}
    {{--<br/>--}}
    {{--</div>--}}

    {{--<br/><br/>--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-12">--}}

    {{--<table id="orderstable" class="display table table-striped table-bordered table-hover text-center" cellspacing="0" width="100%">--}}
    {{--<thead>--}}
    {{--<tr role="row" class="heading text-center">--}}
    {{--<th><input type="checkbox" class="group-checkable"></th>--}}
    {{--<th>Order Id</th>--}}
    {{--<th>Order date</th>--}}
    {{--<th>Product Details</th>--}}
    {{--<th>Order amount</th>--}}
    {{--<th>Status</th>--}}
    {{--<th>Action</th>--}}
    {{--</tr>--}}
    {{--<!--<tr role="row" class="filter">--}}
    {{--<td></td>--}}
    {{--<td><input type="text" class="form-control form-filter input-sm" name="product_id"></td>--}}
    {{--<td>--}}
    {{--<div class="input-group date form_datetime input-small">--}}
    {{--<input id="fromdate" name="date_from" type="text"--}}
    {{--class="form-control form-filter input-sm"> <span class="input-group-btn">--}}
    {{--<button type="button" class="btn default date-reset"><i--}}
    {{--class="fa fa-times"></i></button>--}}
    {{--<button type="button" class="btn default date-set"><i--}}
    {{--class="fa fa-calendar"></i></button>--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--<div class="input-group date form_datetime input-small">--}}
    {{--<input id="todate" name="date_to" type="text"--}}
    {{--class="form-control form-filter input-sm"> <span class="input-group-btn">--}}
    {{--<button type="button" class="btn default date-reset"><i--}}
    {{--class="fa fa-times"></i></button>--}}
    {{--<button type="button" class="btn default date-set"><i--}}
    {{--class="fa fa-calendar"></i></button>--}}
    {{--</span>--}}
    {{--</div>--}}

    {{--</td>--}}
    {{--<td>--}}
    {{--</td>--}}
    {{--<td><input type="text" class="form-control form-filter input-sm"--}}
    {{--name="price_from" placeholder="Price From">--}}
    {{--<input type="text" class="form-control form-filter input-sm"--}}
    {{--name="price_to" placeholder="Price To"></td>--}}
    {{--</td>--}}
    {{--<td>--}}
    {{--<select name="product_status" class="form-control form-filter input-small">--}}
    {{--<option value="">Select...</option>--}}
    {{--<option value="0">Pending</option>--}}
    {{--<option value="1">Success</option>--}}
    {{--<option value="2">Inactive</option>--}}
    {{--<option value="3">Rejected</option>--}}
    {{--<option value="4">Deleted</option>--}}
    {{--</select>--}}
    {{--</td>--}}
    {{--<td>--}}
    {{--<div class="margin-bottom-5">--}}
    {{--<button class="btn btn-sm yellow filter-submit margin-bottom"><i--}}
    {{--class="fa fa-search"></i> Search--}}
    {{--</button>--}}
    {{--</div>--}}
    {{--<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Reset--}}
    {{--</button>--}}
    {{--</td>--}}
    {{--</tr>-->--}}
    {{--</thead>--}}


    {{--</table>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div class="container">
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            <!-- BEGIN CONTENT -->
            <div class="col-md-12 col-sm-12">
                <h1>My orders</h1>
                <div class="goods-page">
                    <div class="goods-data clearfix">
                        <div class="table-wrapper-responsive">
                            <table summary="Shopping cart" id="orderstable">
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
                                <!--<tr>
                                    <td>
                                        <div class="goods-page-ref-no">
                                            javc2133
                                        </div>
                                    </td>
                                    <td>
                                        <div class="goods-page-image">
                                            <a href="#"><img src="../../assets/frontend/pages/img/products/model3.jpg" alt="Berry Lace Dress"></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="goods-page-description">
                                            <h3><a href="#">Cool green dress with red bell</a></h3>
                                            <p><strong>Item 1</strong> - Color: Green; Size: S</p>
                                            <em>More info is here</em></div>
                                    </td>
                                    <td>
                                        <div class="goods-page-price">
                                            <strong><span>$</span>47.00</strong></div>
                                    </td>
                                    <td>
                                        <div class="goods-page-total">
                                            <strong><span>$</span>47.00</strong></div>
                                    </td>
                                    <td>
                                        <div class="goods-page-quantity">
                                            <div class="product-quantity">
                                                <input id="product-quantity" type="text" value="1" readonly class="form-control input-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="del-goods-col">
                                            <a class="del-goods" href="#">&nbsp;</a></div>
                                    </td>
                                </tr>-->
                            </table>
                        </div>

                        <!--<div class="shopping-total">
                            <ul>
                                <li>
                                    <em>Sub total</em>
                                    <strong class="price"><span>$</span>47.00</strong>
                                </li>
                                <li>
                                    <em>Shipping cost</em>
                                    <strong class="price"><span>$</span>3.00</strong>
                                </li>
                                <li class="shopping-total-price">
                                    <em>Total</em>
                                    <strong class="price"><span>$</span>50.00</strong>
                                </li>
                            </ul>
                        </div>-->
                    </div>
                    <!--<button class="btn btn-default" type="submit">Continue shopping <i class="fa fa-shopping-cart"></i>
                    </button>
                    <button class="btn btn-primary" type="submit">Checkout <i class="fa fa-check"></i></button>-->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
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
                    src: $("#orderstable"),
                    onSuccess: function (grid) {
                        // execute some code after table records loaded
                        console.log("success");
                        console.log(grid);
                    },
                    onError: function (grid) {
                        // execute some code on network or other general error
                        console.log("error");
                        console.log(grid);
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
                            "url": "/orders/orders-datatables-handler?tablename=orderstableuser"
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