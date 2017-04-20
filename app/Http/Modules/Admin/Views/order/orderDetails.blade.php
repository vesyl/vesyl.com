@extends('Admin/Layouts/adminlayout')

@section('title', 'Manage Orders') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($dataForView['code'] != 200)
                <div style="text-align: center">
                    <span class="">{{$dataForView['message']}}</span>
                    {{--<br> --}}
                    {{--<a href="/admin/add-option">--}}
                    {{--<button class="btn btn-xs btn-success">Add new option</button>--}}
                    {{--</a>--}}
                </div>
            @else
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h4 class="panel-title">{{$dataForView['message'] }}</h4>

                        <div class="panel-control">
                            <a href="/admin/manage-orders">
                                <button class="btn btn-xs btn-success">Go back</button>
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-basket font-green-sharp"></i>
                                    <span class="caption-subject font-green-sharp bold uppercase"> Order
                                        #{{$dataForView['data']['order_id']}}</span>
                                    <span class="caption-helper">{{date('M d, Y - h:i A', $dataForView['data']['tx_date'])}}</span>
                                </div>
                                <!--<div class="actions">
                                    <a href="/admin/manage-orders" class="btn btn-default btn-circle" id="back_button">
                                        <i class="fa fa-angle-left"></i> <span class="hidden-480">Back</span> </a>
                                </div>-->
                            </div>
                            <div class="portlet-body">
                                <div class="tabbable">
                                    <!--<ul class="nav nav-tabs nav-tabs-lg">
                                        <li class="active"><a href="#tab_1" data-toggle="tab">Details</a></li>
                                        <li><a href="#tab_2" data-toggle="tab">History </a></li>
                                    </ul>-->
                                    <?php
                                    $payment_mode_list = array(
                                            'COD' => 'COD',
                                            'CC' => 'Credit Card',
                                            'DC' => 'Debit Card',
                                            'NB' => 'Net Banking'
                                    );
                                    $userDetails = json_decode($dataForView['data']['user_details'], true);
                                    ?>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="portlet yellow-crusta box">
                                                        <div class="portlet-title">
                                                            <div class="caption"><i class="fa fa-cogs"></i>Order Details
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Order #:</div>
                                                                <div class="col-md-7 value"><?php echo $dataForView['data']['order_id']; ?></div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Order Date & Time:</div>
                                                                <div class="col-md-7 value"><?php echo date('M d, Y - h:i A', $dataForView['data']['tx_date']); ?></div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Order Status:</div>
                                                                <div class="col-md-7 value">
                                                                    <span class="label label-<?php echo "Asd";//$dataForView['data']['status_class']; ?>"><?php echo $dataForView['data']['status']; ?> </span>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Grand Total:</div>
                                                                <div class="col-md-7 value"><i class="fa fa-rupee"></i>&nbsp;<?php echo $dataForView['data']['final_price']; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Payment Information:</div>
                                                                <div class="col-md-7 value">
                                                                    <?php
                                                                    if ($dataForView['data']['payment_mode'] != '') {
                                                                        echo $dataForView['data']['payment_mode'];
                                                                    } else {
                                                                        echo 'COD';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="portlet red-sunglo box">
                                                        <div class="portlet-title">
                                                            <div class="caption"><i class="fa fa-cogs"></i>Shipping
                                                                Address
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="row static-info">
                                                                <div class="col-md-12 value">
                                                                    <?php
                                                                    echo $userDetails['firstname'] . " " . $userDetails['lastname'] . '<br>';
                                                                    echo json_decode($dataForView['data']['shipping_addr'], true)['addrline1'] . ',<br>';
                                                                    echo json_decode($dataForView['data']['shipping_addr'], true)['addrline2'] . ',<br>';
                                                                    echo json_decode($dataForView['data']['shipping_addr'], true)['city'] . ',<br>';
                                                                    echo json_decode($dataForView['data']['shipping_addr'], true)['state'] . ',<br>';
                                                                    echo json_decode($dataForView['data']['shipping_addr'], true)['country'] . '-' . json_decode($dataForView['data']['shipping_addr'], true)['zip'];
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="portlet blue-hoki box">
                                                        <div class="portlet-title">
                                                            <div class="caption"><i class="fa fa-cogs"></i>Product
                                                                Details
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                        <!--<div class="row static-info">
                                                                <div class="col-md-5 name">Product SKU-Id:</div>
                                                                <div class="col-md-7 value">{{$dataForView['data']['tx_id']}}</div>
                                                            </div>-->
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Product Name:</div>
                                                                <div class="col-md-7 value"><?php echo json_decode($dataForView['data']['product_details'], true)['p_name']; ?></div>
                                                            </div>
                                                            <!--<div class="row static-info">
                                                                <div class="col-md-5 name">Size:</div>
                                                                <div class="col-md-7 value">{{ $dataForView['data']['order_id']}}</div>
                                                            </div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="portlet green-meadow box">
                                                        <div class="portlet-title">
                                                            <div class="caption"><i class="fa fa-cogs"></i>Customer
                                                                Information
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">


                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Customer Name:</div>
                                                                <div class="col-md-7 value"><?php echo $userDetails['firstname'] . " " . $userDetails['lastname']; ?></div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Email:</div>
                                                                <div class="col-md-7 value"><?php echo $dataForView['data']['email']; ?></div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">Phone Number:</div>
                                                                <div class="col-md-7 value"><?php echo $dataForView['data']['email']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab_2">
                                            <div class="table-container">
                                                <table class="table table-striped table-bordered table-hover" style="width:50%;">
                                                    <thead>
                                                    <tr role="row" class="heading">
                                                        <th width="25%">Datetime</th>
                                                        <th width="55%">Description</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
//                                                    $orderDateAndStatus = explode(',', $dataForView['data']['updated_date']);
//                                                    $statusWithDate = array();
//                                                    foreach ($orderDateAndStatus as $value) {
//                                                        if (explode('-', $value)[0]) {
//                                                            $statusWithDate[explode('-', $value)[0]] = explode('-', $value)[1];
//                                                        }
//                                                    }
//                                                    $status_list = array(
//                                                            1 => array("primary" => "TXN Success"),
//                                                            2 => array("primary" => "Inprocess"),
//                                                            3 => array("warning" => "Cancel Request"),
//                                                            4 => array("danger" => "TXN Failed"),
//                                                            5 => array("danger" => "Merchant Cancel"),
//                                                            6 => array("success" => "Delivered"),
//                                                            7 => array("warning" => "Refund Request"),
//                                                            8 => array("info" => "Refund Inprocess"),
//                                                            9 => array("default" => "Refunded"),
//                                                            10 => array("primary" => "Shipping"),
//                                                            11 => array("warning" => "Cancel Inprocess"),
//                                                            12 => array("danger" => "Cancelled"),
//                                                    );
//
//                                                    foreach ($statusWithDate as $key => $value) {
                                                    ?>
                                                    <tr>
                                                        <td><?php //echo date('M d, Y - h:i A', $value); ?></td>
                                                        <td><?php //echo current($status_list[$key]); ?></td>
                                                    </tr>

                                                    <?php
//                                                    }
                                                     ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('pagejavascripts')
    <script>
        $(document).ready(function () {

        });

    </script>
@endsection
