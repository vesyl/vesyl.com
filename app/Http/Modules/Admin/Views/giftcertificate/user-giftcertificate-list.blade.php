@extends('Admin/Layouts/adminlayout')

@section('title', 'Manage Giftcertificate') {{--TITLE GOES HERE--}}

@section('headcontent')
<link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection


@section('content')
{{--PAGE CONTENT GOES HERE--}}
{{--DISPLAY ALL CATEGORIES, USING SERVER SIDE DATATABLES--}}
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">All User Giftcertificate</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover" style="width: 100%"
                       id='datatable_filter'>
                    <thead>
                    <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Gift By</th>
                        <th class="text-center">Gift For</th>
                        <th class="text-center">Gift Amount</th>
                        <th class="text-center">Gift Balance</th>
                        <th class="text-center">Gift Name</th>
                        <th class="text-center">Gift Message</th>
                        <th class="text-center">Gift Code</th>
                        <th class="text-center">Gift Status</th>
                        {{--<th class="text-center">Edit/Delete</th>--}}
                        <th class="text-center">Redeem Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rowCount = 1; ?>

                    @foreach($userGiftList as $key => $val)
                    <tr id="filter<?php echo $val->gift_certificate_id?>">
                        <td class="text-center"><?php echo $rowCount++; ?></td>
                        <td class="text-center">{{$val->gift_by_name}}</td>
                        <td class="text-center">{{$val->gift_for_name}}</td>
                        <td class="text-center">{{$val->gift_amount}}</td>
                        <td class="text-center">{{$val->gift_balance}}</td>
                        <td class="text-center">{{$val->gift_name}}</td>
                        <td class="text-center">{{$val->gift_message}}</td>
                        <td class="text-center">{{$val->gift_code}}</td>
                        <td class="center" style="text-align: center;">
                            @if($val->gift_status == 'S')
                            <button class="btn btn-success changestatus"
                                    data-id="<?php echo $val->gift_certificate_id ?>">
                                Active
                            </button>
                            @elseif($val->gift_status == 'P')
                            <button class="btn btn-primary changestatus"
                                    data-id="<?php echo $val->gift_certificate_id ?>">
                                Inactive
                            </button>
                            @else($val->gift_status == 'F')
                            <button class="btn btn-danger changestatus"
                                    data-id="<?php echo $val->gift_certificate_id ?>">
                                Failed
                            </button>
                            @endif
                        </td>
                        {{--<td style="text-align: center">--}}
                            {{--<div role="group" class="btn-group ">--}}
                                {{--<button aria-expanded="false" data-toggle="dropdown"--}}
                                        {{--class="btn btn-default dropdown-toggle" type="button">--}}
                                    {{--<i class="fa fa-cog"></i>&nbsp;--}}
                                    {{--<span class="caret"></span>--}}
                                {{--</button>--}}
                                {{--<ul role="menu" class="dropdown-menu">--}}
                                    {{--<li>--}}
                                        {{--<a href="/admin/edit-certificate/{{$val->gift_certificate_id}}"><i--}}
                                                    {{--class="fa fa-pencil"></i>&nbsp;Edit</a>--}}
                                    {{--</li>--}}
                                    {{--<li><a href="javascript:void(0);" class="delete-giftcertificate"--}}
                                           {{--data-cid="{{$val->gift_certificate_id}}"><i--}}
                                                    {{--class="fa fa-trash"></i>&nbsp;Delete</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</td>--}}
                        <td style="text-align: center;">
                            @if($val->redeem_status == 'N')
                            <button class="btn btn-success reedemchangestatus"
                                    data-id="<?php echo $val->gift_certificate_id ?>">
                                Active
                            </button>
                            @elseif($val->redeem_status == 'U')
                            <button class="btn btn-danger reedemchangestatus"
                                    data-id="<?php echo $val->gift_certificate_id ?>">
                                Inactive
                            </button>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejavascripts')
<script>
    {{--PAGE SCRIPTS GO HERE--}}

    $(document).ready(function () {
        var table = $('#datatable_filter');
        var oTable = table.dataTable({
            "lengthMenu": [
                [10, 20, 100, -1],
                [10, 20, 100, "All"]
            ], "bAutoWidth": false,
            "bSort": false
//                "searching":false
//                "info":     false
        });
//        $(document.body).on("click", ".changestatus", function () {
//            var giftId = $(this).attr('data-id');
//            var giftStatus = $(this).val();
//            var obj = $(this);
//            var giftId = $(this).attr('data-id');
//            var status = 'P';
//            if (obj.hasClass('btn-success')) {
//                status = 'P';
//            } else if (obj.hasClass('btn-danger')) {
//                status = 'A';
//            }
//            if (status == 'P' || status == 'A') {
//                $(document).ajaxStart($.blockUI);
//                $.ajax({
//                    url: '/admin/giftcertificate-ajax-handler',
//                    type: 'GET',
//                    datatype: 'json',
//                    data: {
//                        method: 'changeGiftStatus',
//                        giftId: giftId,
//                        giftStatus: status
//                    },
//                    success: function (response) {
//                        $(document).ajaxStop($.unblockUI);
//                        var res = $.parseJSON(response);
//                        toastr[res['status']](res['msg']);
//                        if (res['status'] == 'success') {
//                            if (obj.hasClass('btn-success')) {
//                                obj.removeClass('btn-success');
//                                obj.addClass('btn-danger');
//                                obj.text('Inactive');
//                            } else {
//                                obj.removeClass('btn-danger');
//                                obj.addClass('btn-success');
//                                obj.text('Active');
//                            }
//                        }
//                    }
//                });
//            }
//        });

//        $(document.body).on('click', '.delete-giftcertificate', function () {
//            var w = $(this);
//            var giftId = w.attr('data-cid');
//            var del = confirm("Are you sure you want to proceed?");
//            if (del) {
//                $(document).ajaxStart($.blockUI);
//                $.ajax({
//                    url: '/admin/giftcertificate-ajax-handler',
//                    type: 'GET',
//                    datatype: 'json',
//                    data: {
//                        method: 'deleteGiftCertificate',
//                        giftId: giftId,
//
//                    },
//                    success: function (response) {
//                        $(document).ajaxStop($.unblockUI);
//                        response = $.parseJSON(response);
//                        toastr[response['status']](response['msg']);
//                        if (response['status'] == 'success') {
//                            var oTable = $('#datatable_filter').dataTable();
//                            oTable.fnDeleteRow(document.getElementById('filter-' + giftId));
//                        }
//
//                    }
//
//                });
//            }
//        });

    });

</script>
<script src="/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
@endsection

